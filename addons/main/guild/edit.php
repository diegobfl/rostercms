<?php
/**
 * WoWRoster.net WoWRoster
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3. * @package    News
 */

if( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

$roster->auth->setAction('&amp;id=' . $_GET['id']);

if( !$roster->auth->getAuthorized('news_can_edit_post') )
{
	echo $roster->auth->getLoginForm();
	return; //To the addon framework
}

// Display news
$query = "SELECT * "
	. "FROM `" . $roster->db->table('news',$addon['basename']) . "` news "
	. "WHERE `news_id` = '" . $_GET['id'] . "';";

$result = $roster->db->query($query);

if( $roster->db->num_rows($result) == 0 )
{
	echo messagebox($roster->locale->act['bad_news_id'], '', 'sred');
	return;
}

$news = $roster->db->fetch($result);

// Assign template vars
$roster->tpl->assign_vars(array(
	'S_ADD_NEWS'       => false,

	'S_HTML_ENABLE'    => false,
	'S_NEWS_HTML'   => (bool)$news['html'],

	'U_EDIT_FORMACTION'  => makelink('guild-main'),
	'U_NEWS_ID'          => $news['news_id'],

	'CONTENT'       => $news['text'],
	'AUTHOR'        => $news['poster'],
	'TITLE'         => $news['title'],
	'S_POSTER'		=> $roster->auth->user['user_display'],
	'S_POSTER_ID'	=> $roster->auth->user['id'],
	)
);

if($addon['config']['news_html'] >= 0)
{
	$roster->tpl->assign_var('S_HTML_ENABLE', true);

	if($addon['config']['news_nicedit'] > 0)
	{
		roster_add_js('js/ckeditor/ckeditor.js','module', 'header', FALSE, FALSE, FALSE);
	}
}

$roster->tpl->set_filenames(array(
	'head' => $addon['basename'] . '/news_head.html',
	'body' => $addon['basename'] . '/edit.html',
	'foot' => $addon['basename'] . '/news_foot.html'
	)
);
$roster->tpl->display('head');
$roster->tpl->display('body');
$roster->tpl->display('foot');