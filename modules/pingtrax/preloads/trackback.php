<?php
/**
 * PingTrax Preloads
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

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

require_once dirname(__DIR__) . DIRECTORY_SEPARATOR . 'class' . DIRECTORY_SEPARATOR . 'trackback.php';

/**
 * Class PingtraxTrackbackPreload
 */
class PingtraxTrackbackPreload extends XoopsPreloadItem
{
    
    /**
     * @param $args
     */
    function eventCoreFooterEnd($args)
    {
    	global $pingtraxitem;
    	if (is_a($pingtraxitem, "PingtraxItems"))
    	{
    		$trackback = new PingtraxTrackback($pingtraxitem->getVar('item-title'), $pingtraxitem->getVar('item-author-name'), 'UTF-8');
    		echo $trackback->rdf_autodiscover($trackback->RFC822_from_datetime($pingtraxitem->getVar('created')), $pingtraxitem->getVar('item-title'), $pingtraxitem->getVar('item-description'), $pingtraxitem->getVar('item-protocol').$pingtraxitem->getVar('item-domain').$pingtraxitem->getVar('item-referer-uri'), XOOPS_URL . '/modules/pingtrax/api/' . $pingtraxitem->getVar('referer'), $pingtraxitem->getVar('item-author-name'));
    	}
    }
   
}
