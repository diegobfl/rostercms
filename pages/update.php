<?php
/**
 * WoWRoster.net WoWRoster
 *
 * LUA update interface
 *
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3.
 * @package    WoWRoster
 * @subpackage LuaUpdate
 */

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

if( !$roster->config['authenticated_user'] )
{
	print messagebox($roster->locale->act['update_disabled'], $roster->locale->act['update_errors'], 'sred');
	return;
}

// Include update lib
require_once (ROSTER_LIB . 'update.lib.php');
$update = new update();

// See if UU is requesting this page
if( preg_match('/uniuploader/i', $_SERVER['HTTP_USER_AGENT']) )
{
	$update->textmode = true;
}

// Set template vars
$roster->tpl->assign_vars(array(
	'S_DATA' 	=> false,
	'S_RESPONSE' 	=> false,
	'S_RESPONSE_ERROR' => false,
	'S_PASS' 	=> true,

	'U_UPDATE' 	=> makelink('update'),
	'S_UPDATE_INS' 	=> (bool)$roster->config['update_inst'],
	'PAGE_INFO'	=> $roster->locale->act['pagebar_update'],
	'L_UPLOAD_APP' 	=> $roster->config['uploadapp'],
	'L_PROFILER' 	=> $roster->config['profiler'],

	'L_PASSWORD_TIP' => makeOverlib($roster->locale->act['roster_upd_pw_help'], $roster->locale->act['password'], '', 2, '', ',WRAP,RIGHT'),

	'MESSAGES' 	=> ''
));

// Fetch addon data
$update->fetchAddonData();

// Has data been uploaded?
if( (isset($_POST['process']) && $_POST['process'] == 'process') || $update->textmode )
{
	$messages = $update->parseFiles();
	$messages .= $update->processFiles();

	$errors = $update->getErrors();

	// Normal upload results
	if( !$update->textmode )
	{
		$roster->tpl->assign_var('S_RESPONSE', true);

		// print the error messages
		if( !empty($errors) )
		{
			// We have errors
			$roster->tpl->assign_vars(array(
				'S_RESPONSE_ERROR' => true,
				'RESPONSE_ERROR' => $errors,
				'RESPONSE_ERROR_LOG' => htmlspecialchars(stripAllHtml($errors))
			));
		}

		$roster->tpl->assign_vars(array(
			'RESPONSE' => $messages,
			'RESPONSE_POST' => htmlspecialchars(stripAllHtml($messages))
		));

		$roster->tpl->set_handle('body', 'update.html');
		$roster->tpl->display('body');
	}
	else
	{
		// No-HTML result page for UU
		echo stripAllHtml($messages);

		$roster->output['show_header'] = false;
		$roster->output['show_menu'] = false;
		$roster->output['show_footer'] = false;
	}
}
else
{
	// No data uploaded, so return upload form
	$update->makeFileFields();

	if( $roster->auth->getAuthorized( 'gp_update' ) && $roster->auth->getAuthorized( 'cp_update' ) && $roster->auth->getAuthorized( 'lua_update' ) )
	{
		$roster->tpl->assign_var('S_PASS', false);
	}

	$messages = $update->getErrors();
	$roster->tpl->assign_var('MESSAGES', $messages);

	$roster->tpl->set_handle('body', 'update.html');
	$roster->tpl->display('body');
}
