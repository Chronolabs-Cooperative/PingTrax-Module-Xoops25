<?php
/**
 * Extended User Profile
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         profile
 * @since           2.3.0
 * @author          Jan Pedersen
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: install.php 12360 2014-03-08 09:46:59Z beckmi $
 */

function xoops_module_install_pingtrax($module)
{
    global $module_id;
    $module_id = $module->getVar('mid');
    xoops_loadLanguage('user');

    $pingsHandler = xoops_getmodulehandler('pings', 'pingtrax');
	$data = json_decode(file_get_contents($pingsHandler->_resource), true);
    foreach($data as $referer => $values)
    {
    	if (!$pingsHandler->getCount(new Criteria('uri', $values['uri'])))
    	{
	        $obj = $pingsHandler->create(true);
	        $obj->setVar('referer', $referer);
	        $obj->setVar('type', $values['type']);
	        $obj->setVar('uri', $values['uri']);
	        $pingsHandler->insert($obj);
    	}
    }

    return true;
}

