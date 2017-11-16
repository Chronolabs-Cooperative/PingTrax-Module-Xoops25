<?php
/**
 * PingTrax XCenter Plugin
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


/***
 * EXAMPLE PLUGIN FOR XCENTER FOR PINGTRAX
 * 
 * You can use this example to make other plugins for example newbb or Xforum!
 * 
 */

defined('XOOPS_ROOT_PATH') || die('XOOPS root path not defined');

/**
 * Class PingtraxPluginsXcenter
 */
class PingtraxPluginsXcenter extends PingtraxPlugins
{
	

	/**
	 *
	 */
	function getModuleDirname()
	{
		return 'xcenter';
	}
	
	/**
	 *
	 */
	function getModuleClass()
	{
		return 'xcenter';
	}
	
	/**
	 *
	 */
	function getModuleItemID()
	{
		return (isset($_REQUEST['storyid'])?(integer)$_REQUEST['storyid']:(isset($_REQUEST['id'])?$_REQUEST['id']:0));;
	}
	
	
	/**
	 *
	 */
	function getItemCategoryID()
	{
		return (isset($_REQUEST['catid'])?(integer)$_REQUEST['catid']:(isset($_REQUEST['catelogueid'])?$_REQUEST['catelogueid']:0));;
	}
	
	
	/**
	 *
	 */
	function getItemTitle()
	{
		$objectHandler = xoops_getmodulehandler($this->getModuleClass(), $this->getModuleDirname());
		$object = $objectHandler->getContent($this->getModuleItemID(), $GLOBALS['xoopsConfig']['language']);
		if (is_object($object['text']))
		{
			if ($object['text']->getVar('ptitle')!='')
				return $object['text']->getVar('ptitle');
			return $object['text']->getVar('title');
		}
		return parent::getItemTitle();
	}
	
	
	/**
	 *
	 */
	function getItemDescription()
	{
		$objectHandler = xoops_getmodulehandler($this->getModuleClass(), $this->getModuleDirname());
		$object = $objectHandler->getContent($this->getModuleItemID(), $GLOBALS['xoopsConfig']['language']);
		if (is_object($object['text']))
		{
			if ($object['text']->getVar('page_description')!='')
				return $object['text']->getVar('page_description');
		}
		return parent::getItemDescription();
	}
	
	/**
	 *
	 */
	function getItemAuthorUID()
	{
		$objectHandler = xoops_getmodulehandler($this->getModuleClass(), $this->getModuleDirname());
		$object = $objectHandler->getContent($this->getModuleItemID(), $GLOBALS['xoopsConfig']['language']);
		if (is_object($object['xcenter']))
		{
			if ($object['xcenter']->getVar('uid')!=0)
				return $object['xcenter']->getVar('uid');
		}
		return parent::getItemAuthorUID();
	}
	
	/**
	 *
	 */
	function getItemAuthorName()
	{
		switch ($this->getModulePHPSelf())
		{
			default:
				if ($this->getItemAuthorUID()>0)
				{
					$userHandler = xoops_gethandler('user');
					$user = $userHandler->get($this->getItemAuthorUID());
					if (is_a($user, "XoopsUser"))
					{
						if (trim($user->getVar('name'))!='')
							return trim($user->getVar('name'));
							else
								return trim($user->getVar('uname'));
					}
				}
		}
		return $GLOBALS["xoopsConfig"]['sitename'];
	}
	
	
	/**
	 *
	 */
	function getFeedProtocol()
	{
		return XOOPS_PROT;
	}
	
	/**
	 *
	 */
	function getFeedDomain()
	{
		return parse_url(strtolower(XOOPS_URL), PHP_URL_HOST);
	}
	
	/**
	 *
	 */
	function getFeedRefererURI()
	{
		return "/modules/xcenter/rss.php?catid=".$this->getItemCategoryID();
	}
}
