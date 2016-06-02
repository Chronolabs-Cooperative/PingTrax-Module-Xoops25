<?php
/**
 * PingTrax Module Global Constants
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

define('_MI_PINGTRAX_NAME',"PingTrax");
define('_MI_PINGTRAX_DESC',"Module for Automated PingList's and Trackbacks as well as sitemaps in XML!");
define('_MI_PINGTRAX_DEFAULT_FEED_URL',"Default URL for the RSS Feed Backend");
define('_MI_PINGTRAX_DEFAULT_FEED_URL_DESC',"This will provide the default URL for the feed, <em>%xoops_url%</em> will be replaced with <em>".XOOPS_URL."</em>");
define('_MI_PINGTRAX_PINGS_SLEEP_TILL', 'Ping Sleeps for this period until next batch');
define('_MI_PINGTRAX_PINGS_SLEEP_TILL_DESC', 'This is how long a ping will sleep for until called in a batch with the footer preloader!');
define('_MI_PINGTRAX_SITEMAPS_SLEEP_TILL', 'Sitemap re-write will sleep for this period!');
define('_MI_PINGTRAX_SITEMAPS_SLEEP_TILL_DESC', 'This is how long a sitemap re-write will sleep for until called in a batch with the footer preloader!');
define('_MI_PINGTRAX_TIME_RANDOM', 'Random (15m ~ 24Hrs)');
define('_MI_PINGTRAX_TIME_15M', '15 Minutes');
define('_MI_PINGTRAX_TIME_30M', '30 Minutes');
define('_MI_PINGTRAX_TIME_1HR', '1 Hour');
define('_MI_PINGTRAX_TIME_2HR', '2 Hours');
define('_MI_PINGTRAX_TIME_3HR', '3 Hours');
define('_MI_PINGTRAX_TIME_4HR', '4 Hours');
define('_MI_PINGTRAX_TIME_5HR', '5 Hours');
define('_MI_PINGTRAX_TIME_6HR', '6 Hours');
define('_MI_PINGTRAX_TIME_7HR', '7 Hours');
define('_MI_PINGTRAX_TIME_8HR', '8 Hours');
define('_MI_PINGTRAX_TIME_9HR', '9 Hours');
define('_MI_PINGTRAX_TIME_10HR', '10 Hours');
define('_MI_PINGTRAX_TIME_11HR', '11 Hours');
define('_MI_PINGTRAX_TIME_12HR', '12 Hours');
define('_MI_PINGTRAX_TIME_14HR', '14 Hours');
define('_MI_PINGTRAX_TIME_16HR', '16 Hours');
define('_MI_PINGTRAX_TIME_24HR', '24 Hours');
		
// Admin Menus
define('_MI_PINGTRAX_DASHBOARD', 'PingTrax Dashboard');
define('_MI_PINGTRAX_PINGS', 'Pinglist\'s/Sitemap\'s');
define('_MI_PINGTRAX_ITEMS', 'Discovered Item\'s');
define('_MI_PINGTRAX_ABOUT', 'About PingTrax');