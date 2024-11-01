<?php
function is_super_user() { //return true if the logged user is the right level to do the admin stuff
	global $user_ID;
	global $current_user;
	global $wpdb;
	global $table_name;
	$table_name = $wpdb->prefix . "subjects_to_do";
	get_currentuserinfo();
	//echo "|",$user_ID,"|";
	if (($current_user->user_level >= 8) or ($current_user->user_login == 'admin')) { 
		return true; 
	} else { 
		return false; 
	} //end if
}

function is_owned($id, $user) { //return false if the user is not admin and trying to mess with something that doesn't belong to him/her
	global $wpdb;
	global $table_name;
	global $current_user;
	$table_name = $wpdb->prefix . "subjects_to_do";
	get_currentuserinfo();
	$now = time();
	$query = "SELECT * FROM " . $table_name . " WHERE id='" . $id . "' AND user='" . $user . "';";
	$result = $wpdb->get_results($query, OBJECT);
	if (count($result) == 0) {
		return false;
	} else {
		return true;
	} //end if
}

function get_user_name($user) {
	global $wpdb;
	global $table_name;
	$table_name = $wpdb->prefix . "users";
	$query = "SELECT display_name FROM "
		. $table_name
		. " WHERE ID='"
		. $user
		. "';";
	$result = $wpdb->get_results($query, OBJECT);
	foreach ($result as $sub) { return $sub->display_name; }
}
function send_emails($who, $what, $which) { //I know, weird name, this is for sending info mails to administrator and other users
	global $wpdb;
	global $table_name;
	$table_name = $wpdb->prefix . "subjects_to_do";
	$admin_email = get_option('subjects_to_do_admin_email');
	$query = "SELECT subjectname, text FROM "
	. $table_name
	. " WHERE id='"
	. $which
	. "';";
	$result = $wpdb->get_results($query, OBJECT);
	foreach ($result as $sub) {
		$message = $who . " - " . $what . ".\n\n" . $sub->subjectname . "\n- " . $sub->text;
	} //end foreach
	send_more_emails($message, $what);
}
function send_more_emails ($message, $what) { // and this send an e-mail to anybody who checked it in their options page
	global $wpdb;
	global $table_name;
	$table_name = $wpdb->prefix . "subjects_to_do_users";
	if ($what=="pridanie námetu") {
		$query = "SELECT * FROM "
		. $table_name
		. " WHERE mail_new='1';";
	} else {
		$query = "SELECT * FROM "
		. $table_name
		. " WHERE mail_changes='1';";
	} //end if
	$result = $wpdb->get_results($query, OBJECT);
	$table_name = $wpdb->prefix . "users";
	foreach ($result as $sub) {
		$query = "SELECT user_email FROM "
		. $table_name
		. " WHERE ID='"
		. $sub->user
		. "';";
		$result2 = $wpdb->get_results($query, OBJECT);
		foreach ($result2 as $res2) {
			mail($res2->user_email, __('Reserved subjects', 'wp-reserved-subjects'), $message );
		} //end foreach
	} //end foreach
}
function user_setup() {
	global $current_user;
	global $user_ID;
	global $wpdb;
	global $table_name;
	$table_name = $wpdb->prefix . "subjects_to_do_users";
	get_currentuserinfo();
	$query = "SELECT * FROM "
	. $table_name
	. " WHERE id='"
	. $user_ID
	. "';";
	$result = $wpdb->get_results($query, OBJECT);
	if (count($result)==0) {
		?><div class="wrap">
	<h2><?php _e('Initial options', 'wp-reserved-subjects') ?></h2>
</div><?php
		require_once('options_page.php');
		return false;
	} else return true;
}
?>
