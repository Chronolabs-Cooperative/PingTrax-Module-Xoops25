<?php

require_once dirname(dirname(dirname(__DIR__))) . DIRECTORY_SEPARATOR . "mainfile.php";
require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . "class". DIRECTORY_SEPARATOR . "trackback.php";

$trackback = new PingtraxTrackback($GLOBALS['xoopsConfig']['sitename'], $GLOBALS['xoopsConfig']['sitename'], 'UTF-8');

if (!isset($_GET['referer']))
	die($trackback->recieve(false, "\$_GET['referer'] not specified and is required for trackback API!"));

xoops_load("XoopsUserUtility");
$moduleHandler = xoops_gethandler('module');
$commentHandler = xoops_gethandler('comment');
$itemsHandler = xoops_getmodulehandler('items', 'pingtrax');
$item = $itemsHandler->getByReferer($_GET['referer']);

if (!is_a($item, "PingtraxItems"))
	die($trackback->recieve(false, $_GET['referer'] . " does not match any recorded item on the trackback API!"));
	
$url = $_REQUEST["url"];
if ($url) {
	$excerpt = $_REQUEST["excerpt"];
	$name = ($_REQUEST["blog_name"]) ? $_REQUEST["blog_name"] : $url;
	$subject = ($_REQUEST["title"]) ? $_REQUEST["title"] : $url;
	$comment = "<strong> TrackBack from <a href=\"$url\">$name</a>:</strong><br />";
	$comment .= "<blockquote>$excerpt</blockquote>";
	
    $pluginHandler = xoops_getmodulehandler('plugins', 'pingtrax');
    $remoteitem = $pluginHandler->getRemoteObject($item, $url, $name, $subject, $comment);
    if (is_a($remoteitem, "PingtraxItems"))
    {
    	$comment = $commentHandler->create();
    	$comment->setVar('com_modid', $moduleHandler->getByDirname('pingtrax')->getVar('mid'));
    	$comment->setVar('com_title', $subject);
    	$comment->setVar('com_text', $comment);
    	$comment->setVar('com_created', time());
    	$comment->setVar('com_url', $url);
    	$comment->setVar('com_ip', XoopsUserUtility::getIP(true));
    	$comment->setVar('com_itemid', $item->getVar('id'));
    	$comment->setVar('dohtml', true);
    	$comment->setVar('dosmiley', true);
    	$comment->setVar('doxcode', true);
    	$comment->setVar('doimage', true);
    	$comment->setVar('dobr', true);
    	$commentHandler->insert($comment);
    }
} else {
	die($trackback->recieve(false, "Missing URL for trackback API!"));
}
die($trackback->recieve(true, ""));
