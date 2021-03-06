<?php
/**
 * WoWRoster.net WoWRoster
 *
 * Roster ajax functions list
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @package    WoWRoster
 * @subpackage Ajax
*/

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

$ajaxfuncs['menu_button_add'] = array(
	'file'=>ROSTER_AJAX . 'menu.php',
);
$ajaxfuncs['menu_button_del'] = array(
	'file'=>ROSTER_AJAX . 'menu.php',
);
$ajaxfuncs['item'] = array(
	'file'=>ROSTER_AJAX . 'tooltip.php',
);
$ajaxfuncs['text'] = array(
	'file'=>ROSTER_AJAX . 'tooltip.php',
);
$ajaxfuncs['spell'] = array(
	'file'=>ROSTER_AJAX . 'tooltip.php',
);
$ajaxfuncs['talent'] = array(
	'file'=>ROSTER_AJAX . 'tooltip.php',
);
$ajaxfuncs['load'] = array(
	'file'=>ROSTER_AJAX . 'media.php',
);
$ajaxfuncs['delete'] = array(
	'file'=>ROSTER_AJAX . 'media.php',
);
$ajaxfuncs['upload'] = array(
	'file'=>ROSTER_AJAX . 'media.php',
);