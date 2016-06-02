<?php
/**
 * Pingtrax Database Class Handler module
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


/**
 * Class PingtraxPings
 *
 * @subpackage      pingtrax
 *
 * Database MySQL Table:-
 * 
 * CREATE TABLE `pingtrax_pings` (
 *   `id` int(14) NOT NULL AUTO_INCREMENT,
 *   `referer` varchar(44) NOT NULL DEFAULT '',
 *   `type` enum('XML-RPC','SITEMAPS') NOT NULL DEFAULT 'XML-RPC',
 *   `uri` varchar(250) NOT NULL DEFAULT '',
 *   `last-item-referer` varchar(44) NOT NULL DEFAULT '',
 *   `successful-pings` int(18) NOT NULL DEFAULT '0',
 *   `failed-pings` int(18) NOT NULL DEFAULT '0',
 *   `sleep-till` int(12) NOT NULL DEFAULT '0',
 *   `success-time` int(12) NOT NULL DEFAULT '0',
 *   `failure-time` int(12) NOT NULL DEFAULT '0',
 *   `created` int(12) NOT NULL DEFAULT '0',
 *   `updated` int(12) NOT NULL DEFAULT '0',
 *   `offlined` int(12) NOT NULL DEFAULT '0',
 *   PRIMARY KEY (`id`,`referer`,`type`,`uri`),
 *   KEY `SEARCH` (`referer`,`type`,`uri`,`last-item-referer`,`successful-pings`,`failed-pings`,`id`) USING BTREE,
 *   KEY `CHRONOLOGISTICS` (`id`,`referer`,`created`,`updated`,`offlined`,`failure-time`,`success-time`,`sleep-till`) USING BTREE KEY_BLOCK_SIZE=128
 * ) ENGINE=InnoDB AUTO_INCREMENT=150 DEFAULT CHARSET=utf8 PACK_KEYS=1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC KEY_BLOCK_SIZE=16;
 *
 */
class PingtraxPings extends XoopsObject
{
    /**
     * Constructor
     */
    function __construct()
    {
        $this->XoopsObject();
        $this->initVar('id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('referer', XOBJ_DTYPE_OTHER, sha1(NULL), false, 44);
        $this->initVar('type', XOBJ_DTYPE_ENUM, 'XML-RPC', true, false, false, false, array('XML-RPC','SITEMAPS'));
        $this->initVar('uri', XOBJ_DTYPE_TXTBOX, null, true, 250);
        $this->initVar('last-item-referer', XOBJ_DTYPE_OTHER, sha1(NULL), false, 44);
  		$this->initVar('successful-pings', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('failed-pings', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('sleep-till', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('success-time', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('failure-time', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('created', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('offlined', XOBJ_DTYPE_INT, 0, false);
    }

    /**
     * Gets Pinglist Item URL
     * 
     * @param PingtraxItems $item
     * @return string
     */
    function getPingURL(PingtraxItems $item)
    {
    	$uri = $this->getVar('uri');
    	$uri = str_replace(urlencode($item->getVar('item-title')), '%title', $uri);
    	$uri = str_replace(urlencode($item->getVar('item-decription')), '%description', $uri);
    	$uri = str_replace(urlencode($item->getVar('item-protocol').$item->getVar('item-domain').$item->getVar('item-referer-uri')), '%url', $uri);
    	$uri = str_replace(urlencode($item->getVar('feed-protocol').$item->getVar('feed-domain').$item->getVar('feed-referer-uri')), '%feed', $uri);
    	return $uri;
    }
    
    /**
     * Gets Pinglist Sitemap URL
     * 
     * @param PingtraxItems $item
     * @return string
     */
    function getSitemapURL(PingtraxSitemaps $sitemap)
    {
    	$uri = $this->getVar('uri');
    	$uri = str_replace(urlencode($sitemap->getVar('protocol').$sitemap->getVar('domain').(strlen($sitemap->getVar('baseurl'))>1?((substr($sitemap->getVar('baseurl'),0,1)!="/"?"/":"").$sitemap->getVar('baseurl').(substr($sitemap->getVar('baseurl'),strlen($sitemap->getVar('baseurl'))-1,1)!="/"?"/":"")):"/")).$sitemap->getVar('filename'), '%url', $uri);
    	return $uri;
    }
    
}

/**
 * Class PingtraxPingsHandler
 */
class PingtraxPingsHandler extends XoopsPersistableObjectHandler
{
	/**
	 *
	 * @var unknown
	 */
	var $_configs = array();
	
	/**
	 * var string		URL of JSON Resource for Install
	 */
	var $_resource 	=	"https://sourceforge.net/p/xoops/svn/HEAD/tree/XoopsModules/pingtrax/data/ping-resources.json?format=raw";
	
    /**
     * Constructor
     * 
     * @param null|object $db
     */
    function __construct(&$db)
    {
    	$moduleHandler = xoops_gethandler('module');
    	$configHandler = xoops_gethandler('config');
    	$this->_configs = $configHandler->getConfigList($moduleHandler->getByDirname(basename(dirname(__DIR__)))->getVar('mid'));
    	 
        parent::__construct($db, "pingtrax_pings", 'PingtraxPings', 'id', 'referer');
        
        $criteria = new Criteria('id',0,"<>");
        if ($this->getCount($criteria)==0)
        {
        	$data = json_decode(file_get_contents($this->_resource), true);
        	foreach($data as $referer => $values)
        	{
        		$obj = $this->create(true);
        		$obj->setVar('referer', $referer);
        		$obj->setVar('type', $values['type']);
        		$obj->setVar('uri', $values['uri']);
        		$this->insert($obj);
        	}
        }
    }
    
    /**
     * Set's Offline Tag to Delete Record
     * 
     * {@inheritDoc}
     * @see XoopsPersistableObjectHandler::delete()
     */
    function delete($object = NULL)
    {
    	$object->setVar('offlined', time());
    	return $this->insert($object, true)>0?true:false;
    }

    /**
     * Insert a Record
     * 
     * {@inheritDoc}
     * @see XoopsPersistableObjectHandler::insert()
     */
    function insert($object = NULL, $force = true)
    {
    	if ($object->isNew())
    	{
    		$object->setVar('created', time());
    	} else {
    		$object->setVar('updated', time());
    	}
    	return parent::insert($object, $force);
    }

    /**
     * Makes Pings for Pinglists
     * 
     * @param string $referer
     */
    function makePings($referer = '')
    {
    	$this->addTimeLimit(120);
    	$items_pingsHandler = xoops_getmodulehandler('items_pings', 'pingtrax');
    	$itemsHandler = xoops_getmodulehandler('items', 'pingtrax');
    	$criteria = new CriteriaCompo(new Criteria('`offlined`', 0));
    	if (!empty($referer))
    		$criteria->add(new Criteria('`referer`', $referer));
    	$sleepcriteria = new CriteriaCompo(new Criteria('`sleep-till`', 0), 'OR');
    	$sleepcriteria->add(new Criteria('`sleep-till`', time(), "<="), 'OR');
    	$criteria->add($sleepcriteria, 'AND');
    	$criteria->add(new Criteria('`type`', 'XML-RPC'), 'AND');
    	foreach($this->getObjects($criteria, true) as $id => $ping)
    	{
    		$start = microtime(true);
    		$criteria = new CriteriaCompo(new Criteria('when', 0));
    		$criteria->add(new Criteria('ping-referer', $ping->getVar('referer')));
    		foreach($items_pingsHandler->getObjects($criteria, true) as $piid => $itemping)
    		{
    			$item = $itemsHandler->getByReferer($itemping->getVar('item-referer'));
    			if (is_a($item, "PingtraxItems"))
    			{
    				$context = stream_context_create(array('http' => array(
    						'method' => "POST",
    						'header' => "Content-Type: text/xml\r\n",
    						'content' => $item->getPingXML()
    				)));
    				$file = @file_get_contents($ping->getPingURL($item), false, $context);
    				if ($file === false) { 
    					$ping->setVar('failed-pings', $this->getVar('failed-pings') + 1);
    					$ping->setVar('failure-time', time());
    				}
    				elseif ($file) {
    					$ping->setVar('successful-pings', $this->getVar('successful-pings') + 1);
    					$ping->setVar('success-time', time());
    					$itemping->setVar('when', time());
    					$items_pingsHandler->insert($itemping, true);
    				}
    			
    			}
    		}
    		switch($this->_config['pings_sleep_till'])
    		{
	    		case 0:
	    			$ping->setVar('sleep-till', time() + mt_rand(600, 3600*24));
	    			break;
	    		default:
	    			$ping->setVar('sleep-till', time() + $this->_config['pings_sleep_till']);
	    			break;
    		}
    		$this->insert($ping, true);
    	}
	}
	
	/**
	 * sends Sitemap to Pinglist supporting sitemap
	 * 
	 * @param PingtraxSitemaps $sitemap
	 */
	function sendSitemap(PingtraxSitemaps $sitemap)
	{
		$this->addTimeLimit(120);
		$items_pingsHandler = xoops_getmodulehandler('items_pings', 'pingtrax');
		$itemsHandler = xoops_getmodulehandler('items', 'pingtrax');
		$criteria = new CriteriaCompo(new Criteria('`offlined`', 0));
		if (!empty($referer))
			$criteria->add(new Criteria('`referer`', $referer));
		$sleepcriteria = new CriteriaCompo(new Criteria('`sleep-till`', 0), 'OR');
		$sleepcriteria->add(new Criteria('`sleep-till`', time(), "<="), 'OR');
		$criteria->add($sleepcriteria, 'AND');
		$criteria->add(new Criteria('`type`', 'SITEMAPS'), 'AND');
		foreach($this->getObjects($criteria, true) as $id => $ping)
		{
			$start = microtime(true);
			if (is_a($sitemap, "PingtraxSitemaps"))
			{
				$file = @file_get_contents($ping->getSitemapURL($sitemap), false);
				if ($file === false) {
					$ping->setVar('failed-pings', $this->getVar('failed-pings') + 1);
					$ping->setVar('failure-time', time());
				}
				elseif ($file) {
					$ping->setVar('successful-pings', $this->getVar('successful-pings') + 1);
					$ping->setVar('success-time', time());
					$itemping->setVar('when', time());
					$items_pingsHandler->insert($itemping, true);
				}
			}
			switch($this->_config['pings_sleep_till'])
    		{
	    		case 0:
	    			$ping->setVar('sleep-till', time() + mt_rand(600, 3600*24));
	    			break;
	    		default:
	    			$ping->setVar('sleep-till', time() + $this->_config['pings_sleep_till']);
	    			break;
    		}
			$this->insert($ping, true);
		}
	}
	
	/**
	 * Gets number of Pinglist's
	 */	
	function getCountPinglists()
	{
		$criteria = new CriteriaCompo(new Criteria('`offlined`', 0));
		$criteria->add(new Criteria('`type`', 'XML-RPC'));
		return $this->getCount($criteria);
	}
	
	/**
	 * Gets number of Sitemaps
	 */
	function getCountSitemaps()
	{
		$criteria = new CriteriaCompo(new Criteria('offlined', 0));
		$criteria->add(new Criteria('type', 'SITEMAPS'));
		return $this->getCount($criteria);
	}
	
	/**
	 * Gets Sum of Successes of Pinglist/Sitemaps
	 */
	function getSumSuccessful()
	{
		$sql = "SELECT sum(`successful-pings`) as `Successes` FROM `" . $this->db->prefix($this->table) . "` WHERE `offlined` = 0";
		list($sum) = $this->db->fetchRow($this->db->queryF($sql));
		return (empty($sum)?'0':$sum);
	}
	
	/**
	 * Gets Sum of Failures of Pinglist/Sitemaps
	 */
	function getSumFailures()
	{
		$sql = "SELECT sum(`failed-pings`) as `Failures` FROM `" . $this->db->prefix($this->table) . "` WHERE `offlined` = 0";
		list($sum) = $this->db->fetchRow($this->db->queryF($sql));
		return (empty($sum)?'0':$sum);
	}
	
	/**
	 * Gets Last Date Ping/Sitemap was Successful
	 * 
	 * @param string $format
	 * @return string
	 */
	function getLastSuccessDate($format = 'Y-m-d H:i:s')
	{
		$sql = "SELECT `success-time` FROM `" . $this->db->prefix($this->table) . "` WHERE `offlined` = 0 ORDER BY `success-time` DESC LIMIT 1";
		list($date) = $this->db->fetchRow($this->db->queryF($sql));
		return ($date!=0?date($format, $date):"---");
	}
	
	/**
	 * Gets Last Date Ping/Sitemap Failed
	 * 
	 * @param string $format
	 * @return string
	 */
	function getLastFailedDate($format = 'Y-m-d H:i:s')
	{
		$sql = "SELECT `failure-time` FROM `" . $this->db->prefix($this->table) . "` WHERE `offlined` = 0 ORDER BY `failure-time` DESC LIMIT 1";
		list($date) = $this->db->fetchRow($this->db->queryF($sql));
		return ($date!=0?date($format, $date):"---");
	}
}
