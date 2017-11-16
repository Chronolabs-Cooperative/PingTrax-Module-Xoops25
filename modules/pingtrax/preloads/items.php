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

/**
 * Class PingtraxPingPreload
 */
class PingtraxItemsPreload extends XoopsPreloadItem
{
	
    /**
     * @param $args
    */
    function eventCoreIncludeCommonEnd($args)
    {
     	global $pingtraxitem;
     	$pluginHandler = xoops_getmodulehandler('plugins', 'pingtrax');
     	$pingtraxitem = $pluginHandler->getItemObject();
    }
  
    /**
     * @param $args
     */
    function eventCoreFooterEnd($args)
    {
    	global $pingtraxitem;
    	if (is_a($pingtraxitem, "PingtraxItems"))
    	{
    		$pluginHandler = xoops_getmodulehandler('plugins', 'pingtrax');
    		if ($pingtraxitem->getVar('discover-hook')=='unknown')
    			$pingtraxitem->setVar('discover-hook', 'preloader');
    		$pingtraxitem = $pluginHandler->setFooterItem($pingtraxitem);
    	}
    }
}
