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

include_once XOOPS_ROOT_PATH . '/class/template.php';

/**
 * Class PingtraxSitemaps
 * 
 * Database MySQL Table:-
 * 
 * CREATE TABLE `pingtrax_sitemaps` (
 *   `id` int(10) NOT NULL AUTO_INCREMENT,
 *   `referer` varchar(44) NOT NULL DEFAULT '',
 *   `protocol` enum('https://','http://') NOT NULL DEFAULT 'http://',
 *   `domain` varchar(100) NOT NULL DEFAULT '',
 *   `baseurl` varchar(100) NOT NULL DEFAULT '',
 *   `filename` varchar(65) NOT NULL DEFAULT '',
 *   `items` int(18) NOT NULL DEFAULT '0',
 *   `bytes` int(18) NOT NULL DEFAULT '0',
 *   `successful-pings` int(18) NOT NULL DEFAULT '0',
 *   `failed-pings` int(18) NOT NULL DEFAULT '0',
 *   `sleep-till` int(12) NOT NULL DEFAULT '0',
 *   `success-time` int(12) NOT NULL DEFAULT '0',
 *   `failure-time` int(12) NOT NULL DEFAULT '0',
 *   `written` int(12) NOT NULL DEFAULT '0',
 *   `created` int(12) NOT NULL DEFAULT '0',
 *   `updated` int(12) NOT NULL DEFAULT '0',
 *   `offlined` int(12) NOT NULL DEFAULT '0',
 *   PRIMARY KEY (`id`),
 *   KEY `SEARCH` (`id`,`referer`,`protocol`,`filename`,`domain`,`baseurl`) USING BTREE,
 *   KEY `CHRONOLOGISTICS` (`id`,`written`,`created`,`updated`,`offlined`,`referer`) USING BTREE KEY_BLOCK_SIZE=64
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC KEY_BLOCK_SIZE=16;
 *
 * @subpackage      pingtrax
 */
class PingtraxSitemaps extends XoopsObject
{
    /**
     *
     */
    function __construct()
    {
        $this->XoopsObject();
        $this->initVar('id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('referer', XOBJ_DTYPE_OTHER, sha1(NULL), true, 44);
        $this->initVar('protocol', XOBJ_DTYPE_ENUM, 'http://', true, false, false, false, array('https://','http://'));
        $this->initVar('domain', XOBJ_DTYPE_TXTBOX, parse_url(XOOPS_URL, PHP_URL_HOST), true, 100);
        $this->initVar('baseurl', XOBJ_DTYPE_TXTBOX, parse_url(XOOPS_URL, PHP_URL_PATH), true, 100);
        $this->initVar('filename', XOBJ_DTYPE_TXTBOX, 'sitemap.'.parse_url(XOOPS_URL, PHP_URL_HOST).'.xml', true, 64);
        $this->initVar('items', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('bytes', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('successful-pings', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('failed-pings', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('sleep-till', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('success-time', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('failure-time', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('written', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('created', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('updated', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('offlined', XOBJ_DTYPE_INT, 0, false);
    }

}

/**
 * Class PingtraxSitemapsHandler
 */
class PingtraxSitemapsHandler extends XoopsPersistableObjectHandler
{
	/**
	 *
	 * @var unknown
	 */
	var $_configs = array();
	
    /**
     * @param null|object $db
     */
    function __construct(&$db)
    {
    	$moduleHandler = xoops_gethandler('module');
    	$configHandler = xoops_gethandler('config');
    	$this->_configs = $configHandler->getConfigList($moduleHandler->getByDirname(basename(dirname(__DIR__)))->getVar('mid'));
    	 
        parent::__construct($db, "pingtrax_sitemaps", 'PingtraxSitemaps', 'id', 'referer');
    }


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
     *
     * @param array $array
     */
    private function addTimeLimit($seconds = 30)
    {
    	global $timelimit;
    	$timelimit .+ $seconds;
    	set_time_limit($timelimit);
    }
    
    function writeSitemaps($referer = '')
    {
    	$this->addTimeLimit(120);
    	$items_sitemapsHandler = xoops_getmodulehandler('items_sitemaps', 'pingtrax');
    	$pingsHandler = xoops_getmodulehandler('pings', 'pingtrax');
    	$itemsHandler = xoops_getmodulehandler('items', 'pingtrax');
    	$criteria = new CriteriaCompo(new Criteria('`offlined`', 0));
    	if (!empty($referer))
    		$criteria->add(new Criteria('`referer`', $referer));
    	$sleepcriteria = new CriteriaCompo(new Criteria('`sleep-till`', 0), 'OR');
    	$sleepcriteria->add(new Criteria('`sleep-till`', time(), "<="), 'OR');
    	$criteria->add($sleepcriteria, 'AND');
    	foreach($this->getObjects($criteria, true) as $id => $sitemap)
    	{
    		$write = false;
    		$start = microtime(true);
    		$criteria = new CriteriaCompo(new Criteria('`map-referer`', $sitemap->getVar('referer')));
    		if ($items_sitemapsHandler->getCount($criteria)>$sitemap->getVar('items'))
    			$write = true;
    		$criteria = new Criteria('changed', $sitemap->getVar('written'), ">=");
    		if ($items_sitemapsHandler->getCount($criteria)>0)
    			$write = true;
    		if ($write==true)
    		{ 		
    			$sitemap->setVar('written', time());
    			$sitemapTpl = new XoopsTpl();
    			$criteria = new CriteriaCompo(new Criteria('`map-referer`', $sitemap->getVar('referer')));
    			$criteria->setOrder('`priority`, `chanaged`');
    			$criteria->setSort('ASC');
    			foreach($items_sitemapsHandler->getObjects($criteria, true) as $id => $item_sitemap)
    			{
    				$item = $itemsHandler->getByReferer($item_sitemap->getVar('item-referer'));
    				if (is_object($item))
    				{
    					$item_sitemap->setVar('when', $sitemap->getVar('written'));
    					$items_sitemapsHandler->insert($item_sitemap);
    					$sitemapTpl->append('urls', array(	'loc'=>$item->getVar('item-protocol').$item->getVar('item-domain').$item->getVar('item-referer-uri'),
    														'lastmod' => date('Y-m-d', $item_sitemap->getVar('changed')),
    														'changefreq' => $item_sitemap->getVar('frequency'),
    														'priority' => $item_sitemap->getVar('priority')));
    				}
    			}
    			ob_start();
    			$sitemapTpl->display(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'sitemaps.xml.html');
    			if (file_exists($flout = $GLOBALS['xoops']->path("/" . $sitemap->getVar('filename'))))
    				unlink($flout);
    			file_put_contents($flout, ob_get_clean());
    			if (file_exists($flout = $GLOBALS['xoops']->path("/robots.txt")))
    			{
    				$robots  = explode(PHP_EOL, file_get_contents($flout));
    				$found = false;
    				foreach($robots as $robot)
    				{
    					if ($robot == "Sitemap: /".$sitemap->getVar('filename'))
    						$found = true;
    				}
    				if ($found == false) 
    				{
    					$data = array();
    					$data[] = "Sitemap: /".$sitemap->getVar('filename');
    					foreach($robots as $robot)
    					{
    						$data[] = $robot;
    					}
    					unlink($flout);
    					file_put_contents($flout, implode(PHP_EOL, $data));
    				}
    			}
       		}
    		switch($this->_config['sitemaps_sleep_till'])
    		{
	    		case 0:
	    			$sitemap->setVar('sleep-till', time() + mt_rand(600, 3600*24));
	    			break;
	    		default:
	    			$sitemap->setVar('sleep-till', time() + $this->_config['sitemaps_sleep_till']);
	    			break;
    		}
       		$this->insert($sitemap, true);
       		$pingsHandler->sendSitemap($sitemap);
       		$this->addTimeLimit(microtime(true)-$start+10);
    	}
    }
}
