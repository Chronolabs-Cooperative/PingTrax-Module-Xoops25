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

include_once dirname(__FILE__) . '/admin_header.php';
xoops_cp_header();


$pingsHandler = xoops_getmodulehandler('pings', 'pingtrax');
$itemsHandler = xoops_getmodulehandler('items', 'pingtrax');

$indexAdmin = new ModuleAdmin();

$indexAdmin->addInfoBox(_AM_PINGTRAX_STATISTICS);

$indexAdmin->addInfoBoxLine(_AM_PINGTRAX_STATISTICS, "<label>"._AM_PINGTRAX_STATISTICS_PINGLISTS."</label>", $pingsHandler->getCountPinglists(), 'Green');
$indexAdmin->addInfoBoxLine(_AM_PINGTRAX_STATISTICS, "<label>"._AM_PINGTRAX_STATISTICS_SITEMAPS."</label>", $pingsHandler->getCountSitemaps(), 'Green');
$indexAdmin->addInfoBoxLine(_AM_PINGTRAX_STATISTICS, "<label>"._AM_PINGTRAX_STATISTICS_PINGSUCCESS."</label>", $pingsHandler->getSumSuccessful(), 'Green');
$indexAdmin->addInfoBoxLine(_AM_PINGTRAX_STATISTICS, "<label>"._AM_PINGTRAX_STATISTICS_PINGFAILURES."</label>", $pingsHandler->getSumFailures(), 'Green');
$indexAdmin->addInfoBoxLine(_AM_PINGTRAX_STATISTICS, "<label>"._AM_PINGTRAX_STATISTICS_PINGLASTSUCCESS."</label>", $pingsHandler->getLastSuccessDate('Y-m-d H:i:s'), 'Purple');
$indexAdmin->addInfoBoxLine(_AM_PINGTRAX_STATISTICS, "<label>"._AM_PINGTRAX_STATISTICS_PINGLASTFAILED."</label>", $pingsHandler->getLastFailedDate('Y-m-d H:i:s'), 'Red');
$indexAdmin->addInfoBoxLine(_AM_PINGTRAX_STATISTICS, "<label>"._AM_PINGTRAX_STATISTICS_URISADMIN."</label>", $itemsHandler->getCount(new Criteria('user-session', 'admin')), 'Blue');
$indexAdmin->addInfoBoxLine(_AM_PINGTRAX_STATISTICS, "<label>"._AM_PINGTRAX_STATISTICS_URISUSERS."</label>", $itemsHandler->getCount(new Criteria('user-session', 'user')), 'Blue');
$indexAdmin->addInfoBoxLine(_AM_PINGTRAX_STATISTICS, "<label>"._AM_PINGTRAX_STATISTICS_URISGUEST."</label>", $itemsHandler->getCount(new Criteria('user-session', 'guest')), 'Blue');

echo $indexAdmin->addNavigation('admin.php');
echo $indexAdmin->renderIndex();

include_once dirname(__FILE__) . '/admin_footer.php';
//xoops_cp_footer();
