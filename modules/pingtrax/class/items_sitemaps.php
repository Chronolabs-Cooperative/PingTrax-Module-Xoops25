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

require_once __DIR__ . DIRECTORY_SEPARATOR . 'simple_html_dom.php';

/**
 * Class PingtraxItems_sitemaps
 *
 * @subpackage      itemtrax
 *
 * Database MySQL Table:-
 * 
 * CREATE TABLE `pingtrax_items_sitemaps` (
 *   `id` mediumint(32) NOT NULL AUTO_INCREMENT,
 *   `map-referer` varchar(44) NOT NULL DEFAULT '',
 *   `item-referer` varchar(44) NOT NULL DEFAULT '',
 *   `frequency` enum('monthly','fortnightly','weekly','daily') NOT NULL DEFAULT 'monthly',
 *   `priority` float(2,1) NOT NULL DEFAULT '0.9',
 *   `checking` int(12) NOT NULL DEFAULT '0',
 *   `changed` int(12) NOT NULL DEFAULT '0',
 *   `changes` int(12) NOT NULL DEFAULT '0',
 *   `when` int(12) NOT NULL DEFAULT '0',
 *   `header-md5` varchar(32) NOT NULL DEFAULT '0',
 *   `header-changes` int(12) NOT NULL DEFAULT '0',
 *   `header-changed` int(12) NOT NULL DEFAULT '0',
 *   `header-bytes` int(12) NOT NULL DEFAULT '0',
 *   `body-md5` varchar(32) NOT NULL DEFAULT '0',
 *   `body-changes` int(12) NOT NULL DEFAULT '0',
 *   `body-changed` int(12) NOT NULL DEFAULT '0',
 *   `body-bytes` int(12) NOT NULL DEFAULT '0',
 *   `tabled-md5` varchar(32) NOT NULL DEFAULT '0',
 *   `tabled-changes` int(12) NOT NULL DEFAULT '0',
 *   `tabled-changed` int(12) NOT NULL DEFAULT '0',
 *   `tabled-bytes` int(12) NOT NULL DEFAULT '0',
 *   `dived-md5` varchar(32) NOT NULL DEFAULT '0',
 *   `dived-changes` int(12) NOT NULL DEFAULT '0',
 *   `dived-changed` int(12) NOT NULL DEFAULT '0',
 *   `dived-bytes` int(12) NOT NULL DEFAULT '0',
 *   `header-md5-last` varchar(32) NOT NULL DEFAULT '0',
 *   `header-changes-last` int(12) NOT NULL DEFAULT '0',
 *   `header-changed-last` int(12) NOT NULL DEFAULT '0',
 *   `header-bytes-last` int(12) NOT NULL DEFAULT '0',
 *   `body-md5-last` varchar(32) NOT NULL DEFAULT '0',
 *   `body-changes-last` int(12) NOT NULL DEFAULT '0',
 *   `body-changed-last` int(12) NOT NULL DEFAULT '0',
 *   `body-bytes-last` int(12) NOT NULL DEFAULT '0',
 *   `tabled-md5-last` varchar(32) NOT NULL DEFAULT '0',
 *   `tabled-changes-last` int(12) NOT NULL DEFAULT '0',
 *   `tabled-changed-last` int(12) NOT NULL DEFAULT '0',
 *   `tabled-bytes-last` int(12) NOT NULL DEFAULT '0',
 *   `dived-md5-last` varchar(32) NOT NULL DEFAULT '0',
 *   `dived-changes-last` int(12) NOT NULL DEFAULT '0',
 *   `dived-changed-last` int(12) NOT NULL DEFAULT '0',
 *   `dived-bytes-last` int(12) NOT NULL DEFAULT '0',
 *   PRIMARY KEY (`id`),
 *   KEY `SEARCH` (`id`,`map-referer`,`item-referer`) USING BTREE,
 *   KEY `CHRONOLOGISTICS` (`map-referer`,`item-referer`) USING BTREE KEY_BLOCK_SIZE=64
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8 PACK_KEYS=1 CHECKSUM=1 DELAY_KEY_WRITE=1 ROW_FORMAT=DYNAMIC KEY_BLOCK_SIZE=8;
 *
 */
class PingtraxItems_sitemaps extends XoopsObject
{
    /**
     *
     */
    function __construct()
    {
        $this->XoopsObject();
        $this->initVar('id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('map-referer', XOBJ_DTYPE_TXTBOX, null, true, 44);
        $this->initVar('item-referer', XOBJ_DTYPE_TXTBOX, null, true, 44);
        $this->initVar('frequency', XOBJ_DTYPE_ENUM, 'daily', false, false, false, false, false, array('monthly','fortnightly','weekly','daily'));
        $this->initVar('priority', XOBJ_DTYPE_FLOAT, 0.9, false);
        $this->initVar('checking', XOBJ_DTYPE_INT, time(), false);
        $this->initVar('changed', XOBJ_DTYPE_INT, time(), false);
        $this->initVar('changes', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('when', XOBJ_DTYPE_INT, time(), false);
        $this->initVar('header-md5', XOBJ_DTYPE_OTHER, md5(NULL), false, 32);
        $this->initVar('header-changes', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('header-changed', XOBJ_DTYPE_INT, time(), false);
        $this->initVar('header-bytes', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('body-md5', XOBJ_DTYPE_OTHER, md5(NULL), false, 32);
        $this->initVar('body-changes', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('body-changed', XOBJ_DTYPE_INT, time(), false);
        $this->initVar('body-bytes', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('tabled-md5', XOBJ_DTYPE_OTHER, md5(NULL), false, 32);
        $this->initVar('tabled-changes', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('tabled-changed', XOBJ_DTYPE_INT, time(), false);
        $this->initVar('tabled-bytes', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('dived-md5', XOBJ_DTYPE_OTHER, md5(NULL), false, 32);
        $this->initVar('dived-changes', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('dived-changed', XOBJ_DTYPE_INT, time(), false);
        $this->initVar('dived-bytes', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('header-md5-last', XOBJ_DTYPE_OTHER, md5(NULL), false, 32);
        $this->initVar('header-changes-last', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('header-changed-last', XOBJ_DTYPE_INT, time(), false);
        $this->initVar('header-bytes-last', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('body-md5-last', XOBJ_DTYPE_OTHER, md5(NULL), false, 32);
        $this->initVar('body-changes-last', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('body-changed-last', XOBJ_DTYPE_INT, time(), false);
        $this->initVar('body-bytes-last', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('tabled-md5-last', XOBJ_DTYPE_OTHER, md5(NULL), false, 32);
        $this->initVar('tabled-changes-last', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('tabled-changed-last', XOBJ_DTYPE_INT, time(), false);
        $this->initVar('tabled-bytes-last', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('dived-md5-last', XOBJ_DTYPE_OTHER, md5(NULL), false, 32);
        $this->initVar('dived-changes-last', XOBJ_DTYPE_INT, 0, false);
        $this->initVar('dived-changed-last', XOBJ_DTYPE_INT, time(), false);
        $this->initVar('dived-bytes-last', XOBJ_DTYPE_INT, 0, false);
    }

    /**
     * 
     */
    function checkForChanges()
    {
    	$changes = 0;
    	$itemsHandler = xoops_getmodulehandler('items', 'pingtrax');
    	$item = $itemsHandler->getByReferer($this->getVar('item-referer'));
    	if (is_a($item, "PingtraxItems"))
    	{
    		$html = $this->getURIData($item->getVar('item-protocol').$item->getVar('item-domain').$item->getVar('item-referer-uri'), 65, 65, $item->getVar('module-get'));
    		if (strlen($html)==0)
    		{
    			foreach($item->getVar('module-get') as $item => $value)
    				$get[$item] = $value;
    			$html = $this->getURIData($item->getVar('item-protocol').$item->getVar('item-domain').$item->getVar('item-php-self')."?".http_build_query($get), 65, 65, $get);
    		}
    		if (strlen($html)!=0)
    		{
    			$dom = str_get_html($html);
    			// Does headers
    			$headbytes = 0;
    			$headmd5 = '';
    			foreach($dom->find("head") as $head)
    			{
    				$headmd5 = md5($headmd5 . sha1($head->innertext));
    				$headbytes = $headbytes + strlen($head->innertext);
    			}
    			$changes .+ $this->setVars(array('header-md5' => $headmd5, 'header-bytes' => $headbytes));
    			// Does Full Body

    			$bodybytes = 0;
    			$bodymd5 = '';
    			foreach($dom->find('body') as $body)
    			{
    				$bodymd5 = md5($bodymd5 . sha1($body->plaintext));
    				$bodybytes = $bodybytes + strlen($body->plaintext);
    			}
    			$changes .+ $this->setVars(array('body-md5' => $bodymd5, 'body-bytes' => $bodybytes));
    			// Does Tables
    			$tablesbytes = 0;
    			$tablesmd5 = '';
    			foreach($dom->find('table') as $table)
    			{
    				$tablesmd5 = md5($tablesmd5 . sha1($table->plaintext));
    				$tablesbytes = $tablesbytes + strlen($table->plaintext);
    			}
    			$changes .+ $this->setVars(array('tabled-md5' => $tablesmd5, 'tabled-bytes' => $tablesbytes));
    			// Does Div's
    			$divsbytes = 0;
    			$divsmd5 = '';
    			foreach($dom->find('div') as $div)
    			{
    				$divsmd5 = md5($divsmd5 . sha1($div->plaintext));
    				$divsbytes = $divsbytes + strlen($div->plaintext);
    			}
    			$changes .+ $this->setVars(array('dived-md5' => $divsmd5, 'dived-bytes' => $divsbytes));
    		}
    	}
    	return $changes;
    }
    
    /**
     * 
     * {@inheritDoc}
     * @see XoopsObject::setVars()
     */
    function setVars($var_arr = array())
    {
    	$changes = 0;
    	$lasting = array('header' => array('-md5', '-bytes'), 'body' => array('-md5', '-bytes'), 'tabled' => array('-md5', '-bytes'), 'dived' => array('-md5', '-bytes'));
    	foreach($lasting as $key=> $values)
    	{
    		$found = false;
    		foreach($values as $value)
	   			if (in_array($key.$value, array_keys($var_arr)))
	   			{
	   				if ($this->getVar($key.$value)!=$var_arr[$key.$value])
	   					foreach($values as $val)
	   						$this->setVar($key.$val.'-last', $this->getVar($key.$val));
	   				$found = true;
	   			}
    		if ($found == true)
    		{
    			$changes .+ 1;
    			$this->setVar($key.'-changes', $this->getVar($key.'-changes')+1);
    			$this->setVar($key.'-changed', time());
    			if ($this->getVar('changed')>0)
    			{
    				if (time() - $this->getVar('changed') < (3600 * 24))
    				{
    					$this->setvar('frequency', 'daily');
    				} elseif (time() - $this->getVar('changed') < (3600 * 24 * 7))
    				{
    					$this->setvar('frequency', 'weekly');
    				} elseif (time() - $this->getVar('changed') < (3600 * 24 * 14))
    				{
    					$this->setvar('frequency', 'fortnightly');
    				} else {
    					$this->setvar('frequency', 'monthly');
    				}
    			} else 
    				$this->setvar('frequency', 'daily');
    			$this->setVar('changed', time());
    			$this->setVar('changes', $this->getVar('changes')+1);
    			if ($this->getvar('priority') < 0.9)
    				$this->setVar('priority', $this->getVar('priority') + 0.1);
    		} else {
    			if ($this->getVar('changed')>0)
    			{
    				if (time() - $this->getVar('changed') < (3600 * 24))
    				{
    					$this->setvar('frequency', 'daily');
    				} elseif (time() - $this->getVar('changed') < (3600 * 24 * 7))
    				{
    					$this->setvar('frequency', 'weekly');
    				} elseif (time() - $this->getVar('changed') < (3600 * 24 * 14))
    				{
    					$this->setvar('frequency', 'fortnightly');
    				} else {
    					$this->setvar('frequency', 'monthly');
    				}
    			} else {
    				$this->setvar('frequency', 'daily');
    				$this->setVar('changed', time());
    			}
    			if ($this->getvar('frequency')=='')
    				$this->setvar('frequency', 'daily');
    			if ($this->getvar('priority') > 0.1)
    				$this->setVar('priority', $this->getVar('priority') - 0.1);
    		}
    	}
    	
	   	switch($this->getVar('frequency'))
	   	{
	   		case 'daily':
	   		default:
	   			$this->setvar('frequency', 'daily');
	   			$this->setVar('checking', time() + (3600 * 24 - 900));
	   			break;
	   		case 'weekly':
	   			$this->setVar('checking', time() + (3600 * 24 * 7 - 900));
	   			break;
	   		case 'fortnightly':
	   			$this->setVar('checking', time() + (3600 * 24 * 14 - 900));
	   			break;
	   		case 'monthly':
	   			$this->setVar('checking', time() + (3600 * 24 * 7 * 4 - 900));
	   			break;
	   	}
	   	parent::setVars($var_arr);
	   	return ($changes>0?$changes:false);
    }

    
    /* function getURIData()
     *
     * 	cURL Routine
     * @author 		Simon Roberts (Chronolabs) simon@labs.coop
     *
     * @return 		float()
     */
    private function getURIData($uri = '', $timeout = 65, $connectout = 65, $post_data = array())
    {
    	if (!function_exists("curl_init"))
    	{
    		return file_get_contents($uri);
    	}
    	if (!$btt = curl_init($uri)) {
    		return false;
    	}
    	curl_setopt($btt, CURLOPT_HEADER, 0);
    	curl_setopt($btt, CURLOPT_POST, (count($posts)==0?false:true));
    	if (count($posts)!=0)
    		curl_setopt($btt, CURLOPT_POSTFIELDS, http_build_query($post_data));
    	curl_setopt($btt, CURLOPT_CONNECTTIMEOUT, $connectout);
    	curl_setopt($btt, CURLOPT_TIMEOUT, $timeout);
    	curl_setopt($btt, CURLOPT_RETURNTRANSFER, true);
    	curl_setopt($btt, CURLOPT_VERBOSE, false);
    	curl_setopt($btt, CURLOPT_SSL_VERIFYHOST, false);
    	curl_setopt($btt, CURLOPT_SSL_VERIFYPEER, false);
    	$data = curl_exec($btt);
    	curl_close($btt);
    	return $data;
    }
  
    
}

/**
 * Class PingtraxItems_sitemapsHandler
 */
class PingtraxItems_sitemapsHandler extends XoopsPersistableObjectHandler
{

    /**
     * @param null|object $db
     */
    function __construct(&$db)
    {
        parent::__construct($db, "pingtrax_items_sitemaps", 'PingtraxItems_sitemaps', 'id', 'map-referer');
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
    
    /**
     * 
     * @param string $mapreferer
     */
    function checkForChanges($mapreferer = '')
    {
    	$changes = 0;
    	$this->addTimeLimit(120);
    	$criteria = new CriteriaCompo(new Criteria('checking', time(), "<="));
    	if (!empty($mapreferer))
    		$criteria->add(new Criteria('`map-referer`', $mapreferer));
    	foreach($this->getObjects($criteria, true) as $id => $item)
    	{
    		$start = microtime(true);
    		if (is_a($item, "PingtraxItems_sitemaps"))
    			$changes = $changes + $item->checkForChanges();
    		$this->addTimeLimit(microtime(true)-$start+5);
    	}
    	return $changes;
    }
}
