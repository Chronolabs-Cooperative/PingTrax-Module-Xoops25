<?php
/**
 * PingTrax Constructor for Plugin's
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
 * Class PingtraxPlugins
 */
class PingtraxPlugins extends XoopsObject
{
  
	/**
	 * @var array
	 */
	var $_configs = array();
	
	/**
	 *
	 */
	function __construct()
	{
		$this->XoopsObject();
		
		// Load Module Config's into object array
		$moduleHandler = xoops_gethandler('module');
		$module = $moduleHandler->getByDirname('pingtrax');
		if (is_a($module, 'XoopsModule'))
		{
			$configHandler = xoops_gethandler('config');
			$this->_configs = $configHandler->getConfigList($module->getVar('mid'));
		}
	}
 
	/**
	 * 
	 */
	function getModuleDirname()
	{
		if (is_a($GLOBALS['xoopsModule'], 'XoopsModule'))
		{
			return $GLOBALS['xoopsModule']->getVar('dirname');
		}
	}

	/**
	 *
	 */
	function getModuleClass()
	{
		switch ($this->getModulePHPSelf())
		{
			default:
				
				foreach(get_declared_classes() as $class)
				{ 
					if ($this->getModuleDirname() != '' && substr(strtolower($class), 0, strlen($this->getModuleDirname()))==strtolower($this->getModuleDirname()) && (!strpos(strtolower($class), 'categor') && !strpos(strtolower($this->getModulePHPSelf()), 'categor')))
					{
						@$obj = new $class();
						if (is_a($obj, "XoopsPersistableObjectHandler"))
							return strtolower(str_replace(array(ucfirst($this->getModuleDirname()), $this->getModuleDirname(), 'handler', 'Handler'), '', $class));
					}
				}
				
				break;
		}
	}

	/**
	 *
	 */
	function getModuleItemID()
	{
		$id = 0;
		switch ($this->getModulePHPSelf())
		{
			default:
				
				$idnaming = explode(PHP_EOL, file_get_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'item-id-names.txt'));
				foreach($_GET as $key => $value)
				{
					if (!is_array($value))
					{
						foreach($idnaming as $idname)
						{
							if (strpos($key, $idname) && is_numeric($_GET[$key]))
								$id = $_GET[$key];
							elseif (is_numeric($_GET[$key]) && !in_array($key, explode(PHP_EOL, file_get_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'exclude-names.txt'))))
								$id = $_GET[$key];
						}
					}
				}
		}
		return $id;
	}
	
	/**
	 *
	 */
	function getModulePHPSelf()
	{
		$parts = explode(DIRECTORY_SEPARATOR, $this->getItemPHPSelf());
		$found = false;
		foreach($parts as $id => $value)
		{
			if ($found == false)
				unset($parts[$id]);
			if ($value == 'modules')
				$found = true;
		}
		return implode(DIRECTORY_SEPARATOR, $parts);
	}

	/**
	 *
	 */
	function getModuleGet()
	{
		return $_GET;
	}


	/**
	 *
	 */
	function getItemCategoryID()
	{
		$id = 0;
		switch ($this->getModulePHPSelf())
		{
			default:
		
				$idnaming = explode(PHP_EOL, file_get_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'category-id-names.txt'));
				foreach($_GET as $key => $value)
				{
					if (!is_array($value))
					{
						foreach($idnaming as $idname)
						{
							if (strpos($key, $idname) && is_numeric($_GET[$key]))
								$id = $_GET[$key];
							elseif ($id = 0 && is_numeric($_GET[$key]) && !in_array($key, explode(PHP_EOL, file_get_contents(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'include' . DIRECTORY_SEPARATOR . 'exclude-names.txt'))))
								$id = $_GET[$key];
						}
					}
				}
		}
		return $id;
	}
	
	/**
	 *
	 */
	function getItemProtocol()
	{
		return strtolower(XOOPS_PROT);
	}

	/**
	 *
	 */
	function getItemDomain()
	{
		return parse_url(strtolower(XOOPS_URL), PHP_URL_HOST);
	}

	/**
	 *
	 */
	function getItemRefererURI()
	{
		return $_SERVER["REQUEST_URI"];
	}

	/**
	 *
	 */
	function getItemPHPSelf()
	{
		if (XOOPS_ROOT_PATH == substr(strtolower($_SERVER["REQUEST_URI"]), 0, strlen(XOOPS_ROOT_PATH)))
			return substr($_SERVER["PHP_SELF"], strlen(XOOPS_ROOT_PATH)-1);
		return $_SERVER["PHP_SELF"];
	}


	/**
	 *
	 */
	function getItemTitle()
	{
		switch ($this->getModulePHPSelf())
		{
			default:
				
				if (is_object($GLOBALS['xoopsTpl']))
					return $GLOBALS['xoopsTpl']->_tpl_vars['xoops_pagetitle'];
				break;
		}
		return $GLOBALS["xoopsConfig"]['sitename'];
	}
	

	/**
	 *
	 */
	function getItemDescription()
	{
		switch ($this->getModulePHPSelf())
		{
			default:
				
				if (is_object($GLOBALS['xoopsTpl']))
					return $GLOBALS['xoopsTpl']->_tpl_vars['xoops_meta_description'];
				break;
		}
		return $GLOBALS["xoopsConfigMetaFooter"]['meta_description'];
	}
	
	/**
	 *
	 */
	function getItemAuthorUID()
	{
		static $uid = 0;
		if ($uid = 0)
			switch ($this->getModulePHPSelf())
			{
				default:
					foreach($GLOBALS['xoopsTpl']->_tpl_vars as $key => $values)
					{
						if ($key = 'uid' && is_numeric($values))
							$uid = $values;
						elseif(is_array($values))
							$uid = explore_array($values, 'uid', 'uid=([0-9]+)');
						elseif(is_string($values))
						{
							preg_match('uid=([0-9])+', $values, $matches);
							if (!empty($matches))
							{
								foreach($matches as $match)
								{
									if (is_array($match))
									{
										foreach($match as $value)
											if (is_numeric($value))
											{
												$uid = $value;
												continue;
												continue;
												continue;
												continue;
											}
									} else {
											$uid = $match;
											continue;
											continue;
											continue;
									}
								}
							}
						}
						if ($uid>0)
							continue;
					}
			}
		return $uid;
	}
	
	function explore_array($array = array(), $key = 'uid', $pattern = 'uid=([0-9]+)')
	{
		foreach($array as $key => $values)
		{
			if ($key = 'uid' && is_numeric($values))
					return $values;
				elseif(is_array($values))
					return explore_array($values, 'uid', 'uid=([0-9]+)');
				elseif(is_string($values))
				{
					preg_match('uid=([0-9])+', $values, $matches);
					if (!empty($matches))
						foreach($matches as $match)
							if (is_array($match))
								foreach($match as $value)
									if (is_numeric($value))
									{
										return $value;
									}
							else 
								return $match;
				}
		}
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
		return parse_url(strtolower(str_replace("%xoops_url%", XOOPS_URL, $this->_configs['default_feed_url'])), PHP_URL_SCHEME);
	}
	
	/**
	 *
	 */
	function getFeedDomain()
	{
		return parse_url(strtolower(str_replace("%xoops_url%", XOOPS_URL, $this->_configs['default_feed_url'])), PHP_URL_HOST);
	}
	
	/**
	 *
	 */
	function getFeedRefererURI()
	{
		return parse_url(strtolower(str_replace("%xoops_url%", XOOPS_URL, $this->_configs['default_feed_url'])), PHP_URL_PATH) . "?" .parse_url(strtolower($this->_configs['default_feed_url']), PHP_URL_QUERY); 
	}
	
}


/**
 * Class PingtraxPluginsHandler
 */
class PingtraxPluginsHandler extends XoopsPersistableObjectHandler
{

	/**
	 * @var string
	 */
	var $_default = 'default';

	/**
	 * @var array
	 */
	var $_plugins = array();
		
	/**
	 * @param null|object $db
	 */
	function __construct(&$db)
	{
		parent::__construct($db);
	}


	function getRemoteObject(PingtraxItems $item, $url = '', $name = '', $subject = '', $comment = '')
	{
		$ret = array();
		
		if (file_exists($file = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . ($dirname = $item->getVar('module-dirname')) . '.php'))
		{
			require_once $file;
		} elseif (file_exists($file = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . ($dirname = $this->_default) . '.php'))
		{
			require_once $file;
		}
	
		if (class_exists($class = "PingtraxPlugins".ucfirst(strtolower($dirname))) && empty($this->_plugins[$dirname]))
		{
			$this->_plugins[$dirname] = new $class();
		}
		if (is_object($this->_plugins[$dirname]))
		{
			$ret['type'] = 'remote';
			$ret['module-dirname'] = 'pingtrax';
			$ret['module-class'] = 'items';
			$ret['module-item-id'] = $item->getVar('id');
			$ret['parent-id'] = $item->getVar('id');
			$ret['item-author-name'] = $name;
			$ret['item-title'] = $subject;
			$ret['item-description'] = $comment;
			$ret['module-php-self'] = $this->_plugins[$dirname]->getModulePHPSelf();
			$ret['module-get'] = $this->_plugins[$dirname]->getModuleGet();
			$ret['item-category-id'] = $this->_plugins[$dirname]->getItemCategoryID();
			$ret['item-protocol'] = $this->_plugins[$dirname]->getItemProtocol();
			$ret['item-domain'] = $this->_plugins[$dirname]->getItemDomain();
			$ret['item-referer-uri'] = $this->_plugins[$dirname]->getItemRefererURI();
			$ret['feed-protocol'] = $this->_plugins[$dirname]->getFeedProtocol();
			$ret['feed-domain'] = $this->_plugins[$dirname]->getFeedDomain();
			$ret['feed-referer-uri'] = $this->_plugins[$dirname]->getFeedRefererURI();
			$ret['item-php-self'] = $this->_plugins[$dirname]->getItemPHPSelf();
			$ret['referer'] = $this->getReferer($ret);
		}
		if (!empty($ret))
		{
			$itemsHandler = xoops_getmodulehandler('items', 'pingtrax');
			$item = $itemsHandler->create(true);
			$item->setVars($ret);
			$ret = $itemsHandler->get($itemsHandler->insert($item));
		}
		return $ret;
	}
	
	function getItemObject()
	{
		$ret = array();
		if (is_a($GLOBALS['xoopsModule'], 'XoopsModule'))
		{
			if (file_exists($file = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . ($dirname = $GLOBALS['xoopsModule']->getVar('dirname')) . '.php'))
			{
				require_once $file;
			} elseif (file_exists($file = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . ($dirname = $this->_default) . '.php'))
			{
				require_once $file;
			}
		} elseif (file_exists($file = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . ($dirname = $this->_default) . '.php'))
		{
			require_once $file;
		}
		if (class_exists($class = "PingtraxPlugins".ucfirst(strtolower($dirname))) && empty($this->_plugins[$dirname]))
		{
			$this->_plugins[$dirname] = new $class();
		}
		if (is_object($this->_plugins[$dirname]))
		{
			$ret['type'] = 'local';
			$ret['module-dirname'] = $this->_plugins[$dirname]->getModuleDirname();
			$ret['module-class'] = $this->_plugins[$dirname]->getModuleClass();
			$ret['module-item-id'] = $this->_plugins[$dirname]->getModuleItemID();
			$ret['module-php-self'] = $this->_plugins[$dirname]->getModulePHPSelf();
			$ret['module-get'] = $this->_plugins[$dirname]->getModuleGet();
			$ret['item-category-id'] = $this->_plugins[$dirname]->getItemCategoryID();
			$ret['item-protocol'] = $this->_plugins[$dirname]->getItemProtocol();
			$ret['item-domain'] = $this->_plugins[$dirname]->getItemDomain();
			$ret['item-referer-uri'] = $this->_plugins[$dirname]->getItemRefererURI();
			$ret['feed-protocol'] = $this->_plugins[$dirname]->getFeedProtocol();
			$ret['feed-domain'] = $this->_plugins[$dirname]->getFeedDomain();
			$ret['feed-referer-uri'] = $this->_plugins[$dirname]->getFeedRefererURI();
			$ret['item-php-self'] = $this->_plugins[$dirname]->getItemPHPSelf();
			$ret['referer'] = $this->getReferer($ret);
		}
		if (!empty($ret))
		{
			$itemsHandler = xoops_getmodulehandler('items', 'pingtrax');
			$item = $itemsHandler->create(true);
			$item->setVars($ret);
			$ret = $itemsHandler->get($itemsHandler->insert($item));
		}
		return $ret;
	}
	
	function getReferer($ret = array())
	{
		return sha1($ret['item-php-self'] . $ret['item-referer-uri'] . $ret['feed-protocol'] . $ret['feed-domain'] . $ret['feed-referer-uri'] . $ret['module-dirname'] . $ret['module-class'] . $ret['item-category-id'] . $ret['module-item-id'] . $ret['module-php-self'] . json_encode($ret['module-get'], true) . $ret['item-protocol'] . $ret['item-domain']);
	}
	
	function setFooterItem(PingtraxItems $item)
	{
		if (is_a($GLOBALS['xoopsModule'], 'XoopsModule'))
		{
			if (file_exists($file = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . ($dirname = $GLOBALS['xoopsModule']->getVar('dirname')) . '.php'))
			{
				require_once $file;
			} elseif (file_exists($file = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . ($dirname = $this->_default) . '.php'))
			{
				require_once $file;
			}
		} elseif (file_exists($file = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'plugins' . DIRECTORY_SEPARATOR . ($dirname = $this->_default) . '.php'))
		{
			require_once $file;
		}
		if (class_exists($class = "PingtraxPlugins".ucfirst(strtolower($dirname))) && empty($this->_plugins[$dirname]))
		{
			$this->_plugins[$dirname] = new $class();
		}
		if (is_object($this->_plugins[$dirname]))
		{
			$item->setVar('item-author-uid', $this->_plugins[$dirname]->getItemAuthorUID());
			$item->setVar('item-author-name', $this->_plugins[$dirname]->getItemAuthorName());
			$item->setVar('item-title', $this->_plugins[$dirname]->getItemTitle());
			$item->setVar('item-description', $this->_plugins[$dirname]->getItemTitle());
			$itemsHandler = xoops_getmodulehandler('items', 'pingtrax');
			return $itemsHandler->get($itemsHandler->insert($item));
		}
		return $item;
	}
}
