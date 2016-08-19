<?php
/**
 * PingTrax Admin
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright   Chronolabs Cooperative http://sourceforge.net/projects/chronolabs/
 * @license     GNU GPL 3 (http://labs.coop/briefs/legal/general-public-licence/13,3.html)
 * @author      Simon Antony Roberts <wishcraft@users.sourceforge.net>
 * @see			http://sourceforge.net/projects/xoops/
 * @see			http://sourceforge.net/projects/chronolabs/
 * @see			http://sourceforge.net/projects/chronolabsapi/
 * @see			http://labs.coop
 * @version     1.0.1
 * @since		1.0.1
 */

include_once dirname(__FILE__) . '/admin_header.php';
xoops_cp_header();

xoops_load('PageNav');
xoops_load('XoopsFormLoader');

$indexAdmin = new ModuleAdmin();
echo $indexAdmin->addNavigation('pings.php');

$start = !isset($_REQUEST['start'])?0:(integer)$_REQUEST['start'];
$num = !isset($_REQUEST['num'])?30:(integer)$_REQUEST['num'];
$domain = !isset($_REQUEST['domain'])?'':(string)$_REQUEST['domain'];
$protocol = !isset($_REQUEST['protocol'])?'':(string)$_REQUEST['protocol'];

$sitemapsHandler = xoops_getmodulehandler('sitemaps', 'pingtrax');
$itemsHandler = xoops_getmodulehandler('items', 'pingtrax');
$itemsSitemapsHandler = xoops_getmodulehandler('items_sitemaps', 'pingtrax');

$criteria = new Criteria('offlined', 0);
foreach($sitemapsHandler->getObjects($criteria) as $id => $sitemap)
	$GLOBALS['xoopsTpl']->append('filter', array('protocol'=>$sitemap->getVar('protocol'), 'domain'=>$sitemap->getVar('domain'),'filename'=>$sitemap->getVar('filename')));

	
$criteria = new CriteriaCompo(new Criteria('`offlined`', 0));
if (!empty($protocol))
	$criteria->add(new Criteria('`item-protocol`', mysqli_escape_string($protocol)));
if (!empty($domain))
	$criteria->add(new Criteria('`item-domain`', $domain));
$criteria->add(new Criteria('`type`', 'local'));
$total = $itemsHandler->getCount($criteria);
$criteria->setStart($start);
$criteria->setLimit($num);

foreach($itemsHandler->getObjects($criteria, true) as $id => $item)
{
	$local = array();
	$local['id'] = $item->getVar('id');
	$local['dirname'] = $item->getVar('module-dirname');
	$local['class'] = $item->getVar('module-class');
	$local['itemid'] = $item->getVar('module-item-id');
	$local['catid'] = $item->getVar('item-category-id');
	$local['author']['uid'] = $item->getVar('item-author-uid');
	$local['author']['name'] = $item->getVar('item-author-name');
	$local['uri'] = $item->getVar('item-protocol').$item->getVar('item-domain').$item->getVar('item-referer-uri');
	$local['title'] = $item->getVar('item-title');
	$local['refereruri'] = $item->getVar('item-referer-uri');
	$local['discovery'] = $item->getVar('discovery-hook');
	$local['session'] = $item->getVar('user-session');
	$criteria = new CriteriaCompo(new Criteria('type', 'remote'));
	$criteria->add(new Criteria('parent-id', $local['id']));
	$local['children'] = $itemsHandler->getCount($criteria);
	$criteria = new CriteriaCompo(new Criteria('item-referer', $item->getVar('referer')));
	if ($itemsSitemapsHandler->getCount($criteria)==0)
	{
		$local['changed'] = $local['checking'] = $local['priority'] = $local['frequency'] = '---';
	} else {
		$itemsitemaps = $itemsSitemapsHandler->getObjects($criteria, false);
		if (isset($itemsitemaps[0]) && is_object($itemsitemaps[0]))
		{
			$local['frequency'] = $itemsitemaps[0]->getVar('frequency');
			$local['priority'] = $itemsitemaps[0]->getVar('priority');
			$local['changed'] = ($itemsitemaps[0]->getVar('changed')==0?"---":date('Y-m-d H:i:s', $itemsitemaps[0]->getVar('changed')));
			$local['checking'] = ($itemsitemaps[0]->getVar('checking')==0?"---":date('Y-m-d H:i:s', $itemsitemaps[0]->getVar('checking')));
		} else 
			$local['changed'] = $local['checking'] = $local['priority'] = $local['frequency'] = '---';
	}
	$GLOBALS['xoopsTpl']->append('locals', $local);
}

$pagenav = new XoopsPageNav($total, $num, $start, 'start', "&num=$num&protocol=$protocol&domain=$domain");
$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(5));
$GLOBALS['xoopsTpl']->assign('start', $start);
$GLOBALS['xoopsTpl']->assign('num', $num);
$GLOBALS['xoopsTpl']->assign('protocol', $protocol);
$GLOBALS['xoopsTpl']->assign('domain', $domain);
$GLOBALS['xoopsTpl']->assign('phpself', XOOPS_URL . $_SERVER["PHP_SELF"]);
$GLOBALS['xoopsTpl']->display($GLOBALS['xoops']->path('/modules/pingtrax/templates/admin/items.html'));


include_once dirname(__FILE__) . '/admin_footer.php';
//xoops_cp_footer();
