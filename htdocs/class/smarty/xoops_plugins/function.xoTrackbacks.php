<?php
/**
 * PingTrax Smarty Trackback Function
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

function smarty_function_xoTrackbacks($tag_args, &$comp)
{
	$attrs = $comp->_parse_attrs($tag_args);
    foreach ($attrs as $arg_name => $arg_value) {
        if ($arg_name == 'dirname') {
            $dirname = $arg_value;
            continue;
        } 
    }
    global $pingtraxitem;
    if (is_a($pingtraxitem, "PingtraxItems"))
    {
	    $GLOBALS['xoTheme']->addStylesheet(XOOPS_URL . "/modules/pingtrax/pingtrax.css");
	    $trackbacksTpl = new XoopsTpl();
	    $trackbacksTpl->assign('permlink', $pingtraxitem->getVar('item-protocol').$pingtraxitem->getVar('item-domain').$pingtraxitem->getVar('item-referer-uri'));
	    $commentHandler = xoops_gethandler('comment');
	    $moduleHandler = xoops_gethandler('module');
	    $criteria = new CriteriaCompo(new Criteria('com_modid', $moduleHandler->getByDirname('pingtrax')->getVar('mid')));
	    $criteria->add(new Criteria('com_itemid', $pingtraxitem->getVar('id')));
	    $criteria->setOrder('com_created');
	    $criteria->setSort('DESC');
	    foreach($commentHandler->getObjects($criteria) as $comid => $comment)
	    	$trackbacksTpl->append('trackbacks', array('subject'=>$comment->getVar('com_title'), 'comment'=>$comment->getVar('com_text')));
	    ob_start();
	    $trackbacksTpl->display($GLOBALS['xoops']->path('/modules/pingtrax/templates/xoTrackback.html'));
    	return ob_get_clean();
    }
    return '';
}
