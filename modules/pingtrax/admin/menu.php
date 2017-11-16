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

// defined("XOOPS_ROOT_PATH") || die("XOOPS root path not defined");

$path = dirname(dirname(dirname(dirname(__FILE__))));
include_once $path . '/mainfile.php';

$dirname         = basename(dirname(dirname(__FILE__)));
$module_handler  = xoops_gethandler('module');
$module          = $module_handler->getByDirname($dirname);
$pathIcon32      = $module->getInfo('icons32');
$pathModuleAdmin = $module->getInfo('dirmoduleadmin');
$pathLanguage    = $path . $pathModuleAdmin;

if (!file_exists($fileinc = $pathLanguage . '/language/' . $GLOBALS['xoopsConfig']['language'] . '/' . 'main.php')) {
    $fileinc = $pathLanguage . '/language/english/main.php';
}

include_once $fileinc;

$adminmenu = array();

$i = 1;
$adminmenu[$i]['title'] = _MI_PINGTRAX_DASHBOARD;
$adminmenu[$i]['link'] = "admin/admin.php";
$adminmenu[$i]['icon']  = $pathIcon32.'/home.png' ;
++$i;
$adminmenu[$i]['title'] = _MI_PINGTRAX_PINGS;
$adminmenu[$i]['link']  = 'admin/pings.php';
$adminmenu[$i]['icon']  = $pathIcon32.'/globe.png';
++$i;
$adminmenu[$i]['title'] = _MI_PINGTRAX_ITEMS;
$adminmenu[$i]['link']  = 'admin/items.php';
$adminmenu[$i]['icon']  = $pathIcon32.'/playlist.png';
++$i;
$adminmenu[$i]['title'] = _MI_PINGTRAX_ABOUT;
$adminmenu[$i]['link']  = 'admin/about.php';
$adminmenu[$i]['icon']  = $pathIcon32.'/about.png';
