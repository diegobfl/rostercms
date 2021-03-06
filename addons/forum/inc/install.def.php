<?php
/**
 * WoWRoster.net WoWRoster
 *
 * @copyright  2002-2011 WoWRoster.net
 * @license    http://www.gnu.org/licenses/gpl.html   Licensed under the GNU General Public License v3. * @package    News
 * @subpackage Forum
*/

if ( !defined('IN_ROSTER') )
{
	exit('Detected invalid access to this file!');
}

/**
 * forum Addon Installer
 * @package Forum
 */
class forumInstall
{
	var $active = true;
	var $icon = 'inv_misc_note_02';

	var $version = '0.3.4';
	var $wrnet_id = '0';

	var $fullname = 'WoWRoster Forum';
	var $description = 'A basic forum for the WoWRoster';
	var $credits = array(
		array(	"name"=>	"Ulminia",
				"info"=>	"Original author")
	);


	/**
	 * Install function
	 *
	 * @return bool
	 */
	function install()
	{
		global $installer;

		// Master and menu entries
		$installer->add_config("'100','startpage','forun_conf','display','master'");
		$installer->add_config("'110','forum_conf',NULL,'blockframe','menu'");	// forum
		$installer->add_config("'120','forum_topic',NULL,'blockframe','menu'");	// topic
		$installer->add_config("'130','forum_post',NULL,'blockframe','menu'");	// post
		$installer->add_config("'140','forum_mod_t',NULL,'blockframe','menu'"); // topic
		$installer->add_config("'150','forum_mod_p',NULL,'blockframe','menu'"); // posts
		$installer->add_config("'160','forum_edit','rostercp-addon-forum-forum','makelink','menu'");
		$installer->add_config("'170','topic_edit','rostercp-addon-forum-topic','makelink','menu'");
		
		//forum_conf
		$installer->add_config("'114','forum_html_posts','-1','radio{enabled^1|disabled^0|forbidden^-1','forum_conf'");
		$installer->add_config("'115','forum_cke','1','radio{enabled^1|disabled^0', 'forum_conf'");
		
		//forum_topic
		$installer->add_config("'124','topic_html','-1','radio{enabled^1|disabled^0|forbidden^-1','forum_topic'");
		$installer->add_config("'125','topic_cke','1','radio{enabled^1|disabled^0', 'forum_topic'");
		
		//forum_post
		$installer->add_config("'134','post_html','-1','radio{enabled^1|disabled^0|forbidden^-1','forum_post'");
		$installer->add_config("'135','post_cke','1','radio{enabled^1|disabled^0', 'forum_post'");

		
		$installer->create_table($installer->table('forums'),"
			`forum_id` int(10) NOT NULL AUTO_INCREMENT,
			`parent_id` int(5) DEFAULT NULL,
			`title` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
			`access` varchar(20) DEFAULT NULL,
			`access_post` varchar(20) DEFAULT NULL,
			`order_id` int(5) DEFAULT NULL,
			`desc` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
			`misc` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
			`active` int(2) DEFAULT NULL,
			`locked` int(1) NOT NULL DEFAULT '0',
			`icon` varchar(255) DEFAULT NULL,
			PRIMARY KEY (`forum_id`)");

		$installer->create_table($installer->table('topics'),"
			`topic_id` int(10) NOT NULL AUTO_INCREMENT,
			`forum_id` int(10) NOT NULL,
			`title` varchar(120) NOT NULL,
			`access` varchar(30) NOT NULL,
			`date_create` int(11) DEFAULT NULL,
			`date_update` int(11) DEFAULT NULL,
			`user` varchar(45) NOT NULL,
			`user_id` int(10) DEFAULT NULL,
			`last_user` varchar(45) DEFAULT NULL,
			`mics` varchar(100) NOT NULL,
			`anounc` varchar(10) NOT NULL DEFAULT '0',
			`sticky` varchar(10) NOT NULL DEFAULT '0',
			`active` int(2) DEFAULT NULL,
			`locked` int(1) NOT NULL DEFAULT '0',
			UNIQUE KEY `topic_id` (`topic_id`)");

		$installer->create_table($installer->table('posts'),"
			`post_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
			`topic_id` mediumint(9) NOT NULL DEFAULT '0',
			`forum_id` smallint(6) NOT NULL DEFAULT '0',
			`user` varchar(45) NOT NULL,
			`user_id` mediumint(9) NOT NULL DEFAULT '0',
			`post_time` int(11) NOT NULL DEFAULT '0',
			`enable_html` tinyint(1) NOT NULL DEFAULT '0',
			`post_edit_time` int(11) DEFAULT '0',
			`post_edit_count` smallint(6) NOT NULL DEFAULT '0',
			`post_subject` varchar(255) DEFAULT '',
			`post_text` text,
			`locked` int(1) NOT NULL DEFAULT '0',
			`flaged` int(1) NOT NULL DEFAULT '0',

			PRIMARY KEY (`post_id`),
			KEY `forum_id` (`forum_id`),
			KEY `topic_id` (`topic_id`),
			KEY `user_id` (`user_id`),
			KEY `post_time` (`post_time`),
			KEY `topic_n_id` (`post_id`,`topic_id`)");

		$installer->create_table($installer->table('topics_track'),"
			`user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			`topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			`forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
			`mark_time` int(11) unsigned NOT NULL DEFAULT '0',
			PRIMARY KEY (`user_id`,`topic_id`),
			KEY `forum_id` (`forum_id`),
			KEY `topic_id` (`topic_id`)");

		$permissions = array(
			array('catagory'=> 'forum_forum','name'=> 'forum_lock','info'=> 'forum_lock_desc','cfg_name'=> 'forum_lock'),
			array('catagory'=> 'forum_topics', 'name'=> 'forum_topics_start', 'info'=> 'forum_topics_start_desc','cfg_name'=> 'topic_start'),
			array('catagory'=> 'forum_topics','name'=> 'forum_topics_lock','info'=> 'forum_topics_lock_desc','cfg_name'=> 'topic_lock'),
			array('catagory'=> 'forum_topics','name'=> 'forum_topics_delete','info'=> 'forum_topics_delete_desc','cfg_name'=> 'topic_delete'),
			array('catagory'=> 'forum_topics','name'=> 'forum_topics_move','info'=> 'forum_topics_move_desc','cfg_name'=> 'topic_move'),
			array('catagory'=> 'forum_posts','name'=> 'forum_posts_start','info'=> 'forum_posts_start_desc','cfg_name'=> 'post_start'),
			array('catagory'=> 'forum_posts','name'=> 'forum_posts_editany','info'=> 'forum_posts_editany_desc','cfg_name'=> 'post_editany'),
			array('catagory'=> 'forum_posts','name'=> 'forum_posts_editown','info'=> 'forum_posts_editown_desc','cfg_name'=> 'post_editown'),
			array('catagory'=> 'forum_posts','name'=> 'forum_posts_deleteany','info'=> 'forum_posts_deleteany_desc','cfg_name'=> 'post_deleteany'),
			array('catagory'=> 'forum_posts','name'=> 'forum_posts_deleteown','info'=> 'forum_posts_deleteown_desc','cfg_name'=> 'post_deleteown'),
			array('catagory'=> 'forum_forum','name'=> 'forum_postin_lock','info'=> 'forum_postin_lock_desc','cfg_name'=> 'forum_postin_lock'),
			array('catagory'=> 'forum_topics','name'=> 'forum_topics_postin_lock','info'=> 'forum_topics_postin_lock_desc','cfg_name'=> 'topics_postin_lock'),
			array('catagory'=> 'forum_posts','name'=> 'forum_posts_postin_lock','info'=> 'forum_posts_postin_lock_desc','cfg_name'=> 'posts_postin_lock'),
			
			array('catagory'=> 'forum_topics','name'=> 'forum_topics_sticky','info'=> 'forum_topics_sticky_desc','cfg_name'=> 'topic_sticky'),
			array('catagory'=> 'forum_topics','name'=> 'forum_topics_anounc','info'=> 'forum_topics_anounc_desc','cfg_name'=> 'topic_anounc'),
			//array('catagory'=> 'forum_topics','name'=> 'forum_topics_move','info'=> 'forum_topics_move_desc','cfg_name'=> 'topic_move'),
		);
		$installer->add_permissions($permissions);
		
		$installer->add_menu_button('forum_button','guild');
		return true;
	}

	/**
	 * Upgrade functoin
	 *
	 * @param string $oldversion
	 * @return bool
	 */
	function upgrade($oldversion)
	{
		global $installer;

		if( version_compare('0.3.4', $oldversion, '>') == true )
		{
			$permissions = array(
				array('catagory'=> 'forum_forum','name'=> 'forum_postin_lock','info'=> 'forum_postin_lock_desc','cfg_name'=> 'forum_postin_lock'),
				array('catagory'=> 'forum_topics','name'=> 'forum_topics_postin_lock','info'=> 'forum_topics_postin_lock_desc','cfg_name'=> 'topics_postin_lock'),
				array('catagory'=> 'forum_posts','name'=> 'forum_posts_postin_lock','info'=> 'forum_posts_postin_lock_desc','cfg_name'=> 'posts_postin_lock'),
				array('catagory'=> 'forum_topics','name'=> 'forum_topics_sticky','info'=> 'forum_topics_sticky_desc','cfg_name'=> 'topic_sticky'),
				array('catagory'=> 'forum_topics','name'=> 'forum_topics_anounc','info'=> 'forum_topics_anounc_desc','cfg_name'=> 'topic_anounc'),
			);
			$installer->add_permissions($permissions);
		}
		if( version_compare('0.3.2', $oldversion, '>') == true )
		{
			$permissions = array(
				array('catagory'=> 'forum_topics', 'name'=> 'forum_topics_start', 'info'=> 'forum_topics_start_desc','cfg_name'=> 'topic_start'),
				array('catagory'=> 'forum_topics','name'=> 'forum_topics_lock','info'=> 'forum_topics_lock_desc','cfg_name'=> 'topic_lock'),
				array('catagory'=> 'forum_topics','name'=> 'forum_topics_delete','info'=> 'forum_topics_delete_desc','cfg_name'=> 'topic_delete'),
				array('catagory'=> 'forum_topics','name'=> 'forum_topics_move','info'=> 'forum_topics_move_desc','cfg_name'=> 'topic_move'),
				array('catagory'=> 'forum_posts','name'=> 'forum_posts_start','info'=> 'forum_posts_start_desc','cfg_name'=> 'post_start'),
				array('catagory'=> 'forum_posts','name'=> 'forum_posts_editany','info'=> 'forum_posts_editany_desc','cfg_name'=> 'post_editany'),
				array('catagory'=> 'forum_posts','name'=> 'forum_posts_editown','info'=> 'forum_posts_editown_desc','cfg_name'=> 'post_editown'),
				array('catagory'=> 'forum_posts','name'=> 'forum_posts_deleteany','info'=> 'forum_posts_deleteany_desc','cfg_name'=> 'post_deleteany'),
				array('catagory'=> 'forum_posts','name'=> 'forum_posts_deleteown','info'=> 'forum_posts_deleteown_desc','cfg_name'=> 'post_deleteown'),
			);
			$installer->add_permissions($permissions);
		}
		if( version_compare('0.3.3', $oldversion, '>') == true )
		{
			$permissions = array(
				array('catagory'=> 'forum_forum','name'=> 'forum_lock','info'=> 'forum_lock_desc','cfg_name'=> 'forum_lock'),
			);
			$installer->add_permissions($permissions);
		}
		if( version_compare('0.2.1', $oldversion, '>') == true )
		{
			$installer->drop_table($installer->table('forums'));
			$installer->drop_table($installer->table('topics'));
			$installer->drop_table($installer->table('posts'));
					$installer->create_table($installer->table('forums'),"
					`forum_id` int(10) NOT NULL AUTO_INCREMENT,
					`parent_id`  int(5) DEFAULT NULL,
					`title` varchar(100) CHARACTER SET latin1 DEFAULT NULL,
					`access` varchar(20) DEFAULT NULL,
					`order_id` int(5) DEFAULT NULL,
					`desc` varchar(200) CHARACTER SET latin1 DEFAULT NULL,
					`misc` varchar(50) CHARACTER SET latin1 DEFAULT NULL,
					`active` int(2) DEFAULT NULL,
					`locked` int(1) NOT NULL DEFAULT '0',
					`icon` varchar(255) DEFAULT NULL,
					PRIMARY KEY (`forum_id`)
				");

		$installer->create_table($installer->table('topics'),"
					`topic_id` int(10) NOT NULL AUTO_INCREMENT,
					`forum_id` int(10) NOT NULL,
					`title` varchar(120) NOT NULL,
					`access` varchar(30) NOT NULL,
					`date_update` int(11) DEFAULT NULL,
					`date_create` int(11) DEFAULT NULL,
					`user` varchar(45) NOT NULL,
					`user_id` int(10) DEFAULT NULL,
					`last_user` varchar(45) DEFAULT NULL,
					`mics` varchar(100) NOT NULL,
					`anounc` varchar(10) NOT NULL DEFAULT '0',
					`sticky` varchar(10) NOT NULL DEFAULT '0',
					`active` int(2) DEFAULT NULL,
					`locked` int(1) NOT NULL DEFAULT '0',
					UNIQUE KEY `topic_id` (`topic_id`)
				");

		$installer->create_table($installer->table('posts'),"
					`post_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
					`topic_id` mediumint(9) NOT NULL DEFAULT '0',
					`forum_id` smallint(6) NOT NULL DEFAULT '0',
					`user` varchar(45) NOT NULL,
					`user_id` mediumint(9) NOT NULL DEFAULT '0',
					`post_time` int(11) NOT NULL DEFAULT '0',
					`enable_html` tinyint(1) NOT NULL DEFAULT '0',
					`post_edit_time` int(11) DEFAULT '0',
					`post_edit_count` smallint(6) NOT NULL DEFAULT '0',
					`post_subject` varchar(255) DEFAULT '',
					`post_text` text,
					`locked` int(1) NOT NULL DEFAULT '0',
					PRIMARY KEY (`post_id`),
					KEY `forum_id` (`forum_id`),
					KEY `topic_id` (`topic_id`),
					KEY `user_id` (`user_id`),
					KEY `post_time` (`post_time`),
					KEY `topic_n_id` (`post_id`,`topic_id`)
				");

		$installer->create_table($installer->table('topics_track'),"
					`user_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
					`topic_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
					`forum_id` mediumint(8) unsigned NOT NULL DEFAULT '0',
					`mark_time` int(11) unsigned NOT NULL DEFAULT '0',
					PRIMARY KEY (`user_id`,`topic_id`),
					KEY `forum_id` (`forum_id`),
					KEY `topic_id` (`topic_id`)
				");
		}
		return true;
	}

	/**
	 * Un-Install function
	 *
	 * @return bool
	 */
	function uninstall()
	{
		global $installer;

		$installer->drop_table($installer->table('forums'));
		$installer->drop_table($installer->table('topics'));
		$installer->drop_table($installer->table('posts'));
		$installer->remove_all_config();
		$installer->remove_all_menu_button();
		$installer->remove_all_permissions();
		
		return true;
	}
}
