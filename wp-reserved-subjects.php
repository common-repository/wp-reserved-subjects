<?php



### Load WP-Config File If This File Is Called Directly
if (!function_exists('add_action')) {
	$wp_root = '../../..';
	if (file_exists($wp_root.'/wp-load.php')) {
		require_once($wp_root.'/wp-load.php');
	} else {
		require_once($wp_root.'/wp-config.php');
	}
}


### Use WordPress 2.6 Constants
if (!defined('WP_CONTENT_DIR')) {
	define( 'WP_CONTENT_DIR', ABSPATH.'wp-content');
}
if (!defined('WP_CONTENT_URL')) {
	define('WP_CONTENT_URL', get_option('siteurl').'/wp-content');
}
if (!defined('WP_PLUGIN_DIR')) {
	define('WP_PLUGIN_DIR', WP_CONTENT_DIR.'/plugins');
}
if (!defined('WP_PLUGIN_URL')) {
	define('WP_PLUGIN_URL', WP_CONTENT_URL.'/plugins');
}
### Create Text Domain For Translations
add_action('init', 'reserved_subjects_textdomain');
function reserved_subjects_textdomain() {
	if (!function_exists('wp_print_styles')) {
		load_plugin_textdomain('wp-reserved-subjects', 'wp-content/plugins/wp-reserved-subjects');
	} else {
		load_plugin_textdomain('wp-reserved-subjects', false, 'wp-reserved-subjects');
	}
}

/*
Plugin Name: WP Reserved subjects
Plugin URI: http://varhol.sk/
Description: Spracovávanie námetov na články vo viacužívateľských blogoch.
Author: Ján Varhol
Author URI: http://varhol.sk/
*/
$subjects_to_do_db_version = "1.0";
register_activation_hook(__FILE__,'subjects_to_do_install');

function subjects_to_do_install () { //install routines, create database table and register plugin version
	global $wpdb;
	global $subjects_to_do_db_version;
	global $table_name;
	global $current_user;
	get_currentuserinfo();
	$admin_email = get_option("admin_email");
	$table_name = $wpdb->prefix . "subjects_to_do";
	if($wpdb->get_var("show tables like '$table_name'") != $table_name) {
		$sql = "CREATE TABLE " . $table_name . " (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		time bigint(11) DEFAULT '0' NOT NULL,
		expire bigint(11) DEFAULT '0' NOT NULL,
		user bigint(11) DEFAULT '0' NOT NULL,
		subjectname tinytext NOT NULL,
		text text NOT NULL,
		UNIQUE KEY id (id)
		);";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	} //end if
	$table_name2 = $wpdb->prefix . "subjects_to_do_users";
	if($wpdb->get_var("show tables like '$table_name2'") != $table_name2) {
		$sql = "CREATE TABLE " . $table_name2 . " (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		user bigint(11) DEFAULT '0' NOT NULL,
		admin bigint(11) DEFAULT '0' NOT NULL,
		mail_new bigint(11) DEFAULT '0' NOT NULL,
		mail_changes bigint(11) DEFAULT '0' NOT NULL,
		UNIQUE KEY id (id)
		);";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
	} //end if
} //end subjects_to_do_install

add_action('admin_menu', 'mt_add_pages'); //add menu into admin

function mt_add_pages() { // action function for above hook
	add_menu_page(__('Reserved subjects', 'wp-reserved-subjects'), __('Reserved subjects', 'wp-reserved-subjects'), 2, __FILE__, 'mt_toplevel_page');
	//add_submenu_page(__FILE__, 'Editácia námetov', 'Editácia námetov', 8, 'edit-subjects.php', 'mt_edit_subjects_page');
	add_submenu_page(__FILE__, __('Add subject', 'wp-reserved-subjects'), __('Add subject', 'wp-reserved-subjects'), 2, 'add-subject.php', 'mt_add_subject_page');
	add_submenu_page(__FILE__, __('Expired subjects', 'wp-reserved-subjects'), __('Expired subjects', 'wp-reserved-subjects'), 2, 'expired-subjects.php', 'mt_expired_page');
	add_submenu_page(__FILE__, __('Options', 'wp-reserved-subjects'), __('Options', 'wp-reserved-subjects'), 2, 'options_page.php', 'mt_subjects_to_do_options_page');
} //end mt_add_pages



function mt_toplevel_page() { //content of top level
	include_once('functions.php');
	if (user_setup()) {
		include_once('top_level_routines.php'); //routines for reserving and unreserving subjects
		include_once('noreserve.php'); //displaying non reserved subjects
		include_once('reserve.php'); //displaying reserved subjects
	}
} //end mt_toplevel_page

function mt_expired_page() { //content of expired subjects page
	include_once('functions.php');
	include_once('top_level_routines.php'); //routines for reserving and unreserving subjects
	include_once('expired.php');
} //end mt_expired_page

/*function mt_edit_subjects_page() {  //content of edit subjects page
	include_once('edit-subjects.php');
} //end mt_edit_subjects_page*/

function mt_add_subject_page() {  //content of add subject page
	include_once('functions.php');
	include_once('add_subject.php');
} //end mt_add_subject_page

function mt_subjects_to_do_options_page() {  //content of options page
	?><div class="wrap">
	<h2><?php _e('Options', 'wp-reserved-subjects') ?></h2>
</div><?php
	include_once('functions.php');
	include_once('options_page.php');
} //end mt_add_subject_page

?>

