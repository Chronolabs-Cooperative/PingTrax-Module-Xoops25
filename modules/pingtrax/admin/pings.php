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
$id = !isset($_REQUEST['id'])?0:(integer)$_REQUEST['id'];
$op = !isset($_REQUEST['op'])?'default':(string)$_REQUEST['op'];

$pingsHandler = xoops_getmodulehandler('pings', 'pingtrax');
$itemsHandler = xoops_getmodulehandler('items', 'pingtrax');

switch ($op)
{
	default:
		$criteria = new Criteria('offlined', 0);
		$total = $pingsHandler->getCount($criteria);
		$criteria->setStart($start);
		$criteria->setLimit($num);
		
		foreach($pingsHandler->getObjects($criteria, true) as $id => $ping)
		{
			if ($ping->getVar('last-item-referer')!='')
			{
				$item = $itemsHandler->getByReferer($ping->getVar('last-item-referer'));
				if (is_object($item))
					$last = array('uri'=>$item->getVar('item-protocol').$item->getVar('item-domain').$item->getVar('item-referer-uri'), 'title' => $item->getVar('item-title'));
					else
						$last = array();
			} else
				$last = array();
			$GLOBALS['xoopsTpl']->append('pings', array('id' => $ping->getVar('id'), 'type' => $ping->getVar('type'), 'uri' => $ping->getVar('uri'), 'last' => $last, "successes" => number_format($ping->getVar('successful-pings'), 0), "failures" => number_format($ping->getVar('failed-pings'), 0), 'success' => ($ping->getVar('success-time')!=0?date("Y-m-d H:i:s", $ping->getVar('success-time')):"---"), 'failed' => ($ping->getVar('failure-time')!=0?date("Y-m-d H:i:s", $ping->getVar('failure-time')):"---"), 'sleeptill' => ($ping->getVar('sleep-till')!=0?date("Y-m-d H:i:s", $ping->getVar('sleep-till')):"---")));
		}
		
		$pagenav = new XoopsPageNav($total, $num, $start, 'start', "&num=$num");
		$GLOBALS['xoopsTpl']->assign('pagenav', $pagenav->renderNav(5));
		$GLOBALS['xoopsTpl']->assign('start', $start);
		$GLOBALS['xoopsTpl']->assign('num', $num);
		
		$form = new XoopsThemeForm(_AM_PINGTRAX_PING_ADD, 'add-pinglist', $_SERVER['PHP_SELF']."?op=add&start=$start&num=&num");
		$form->addElement(new XoopsFormText(_AM_PINGTRAX_PING_URI, 'uri', 50, 250));
		$type = new XoopsFormSelect(_AM_PINGTRAX_PING_TYPE, 'type');
		$type->addOption('XML-RPC', 'XML-RPC');
		$type->addOption('SITEMAPS', 'SITEMAPS');
		$form->addElement($type);
		$form->addElement(new XoopsFormButton(_SUBMIT, 'submit', _SUBMIT));
		$GLOBALS['xoopsTpl']->assign('addform', $form->render());
		$GLOBALS['xoopsTpl']->display($GLOBALS['xoops']->path('/modules/pingtrax/templates/admin/pings.html'));
		break;
	case "add":
		if (!empty($_POST['uri']) && !empty($_POST['type']))
		{
			$ping = $pingsHandler->create();
			$ping->setVar('uri', $_POST['uri']);
			$ping->setVar('type', $_POST['type']);
			if($pingsHandler->insert($ping))
				redirect_header($_SERVER["PHP_SELF"]."?start=$start&num=$num", 7, _AM_PINGTRAX_PING_ADD_SUCCESSFUL);
		} 
		redirect_header($_SERVER["PHP_SELF"]."?start=$start&num=$num", 7, _AM_PINGTRAX_PING_ADD_FAILED);
		exit(0);
	case "edit":
		foreach($_POST['uri'] as $id=> $uri)
		{
			$ping = $pingsHandler->get($id);
			$ping->setVar('uri', $uri);
			$ping->setVar('type', $_POST['type'][$id]);
			$pingsHandler->insert($ping);
		}
		redirect_header($_SERVER["PHP_SELF"]."?start=$start&num=$num", 7, _AM_PINGTRAX_PING_EDIT_COMPLETE);
		exit(0);
	case "delete":
		$ping = $pingsHandler->get($id);
		if (is_a($ping, "PingtraxPings"))
			if ($pingsHandler->delete($ping))
				redirect_header($_SERVER["PHP_SELF"]."?start=$start&num=$num", 7, _AM_PINGTRAX_PING_DELETE_SUCCESS);
		redirect_header($_SERVER["PHP_SELF"]."?start=$start&num=$num", 7, _AM_PINGTRAX_PING_DELETE_FAILED);
		exit(0);
		break;
}

include_once dirname(__FILE__) . '/admin_footer.php';
//xoops_cp_footer();
