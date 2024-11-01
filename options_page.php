<?php
	global $current_user;
	global $user_ID;
	global $wpdb;
	global $table_name;
	$table_name = $wpdb->prefix . "subjects_to_do_users";
	get_currentuserinfo();
	if ($_POST["hidden_options_form"]=="Y") {
		$query = "UPDATE "
		. $table_name
		. " SET mail_new='"
		. $_POST["send_mail_new"]
		. "', mail_changes='"
		. $_POST["send_mail_changes"]
		. "' WHERE user='"
		. $user_ID
		. "';";
		$wpdb->show_errors();
		$wpdb->query( $query );
		$message = __('Your changes were saved.', 'wp-reserved-subjects') ;
	} //end if
?>

<div class="wrap">
<?php
	if (!empty($message)) {
		?><div id="message" class="updated fade"><p><?php echo $message; ?></p></div><?php
	} //end if
	$query = "SELECT * FROM "
	. $table_name
	. " WHERE id='"
	. $user_ID
	. "';";
	$result = $wpdb->get_results($query, OBJECT);
	if (count($result)==0) {
		$insert = "INSERT INTO " 
		. $table_name 
		. " VALUES ('','"
		. $user_ID 
		. "','0','1','0');";
		//$wpdb->show_errors();
		$wpdb->query( $insert );
		$result = $wpdb->get_results($query, OBJECT);
	}
	foreach ($result as $res) {
?>
<form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post" name="options_form" id="options_form" class="validate">
<table class="form-table">
	<tr class="form-field">
		<th scope="row"><legend><?php _e('Send e-mail when subject is', 'wp-reserved-subjects') ?> <?php _e('added', 'wp-reserved-subjects') ?> </legend><input type="hidden" name="hidden_options_form" id="hidden_options_form" value="Y" /></th>
		<td>
			<input name="send_mail_new" type="radio" value="0"<?php if ($res->mail_new==0) { ?> checked="checked"<?php } ?> /><label for="send_mail_new"><?php _e('no', 'wp-reserved-subjects') ?></label><br />
			<input name="send_mail_new" type="radio" value="1"<?php if ($res->mail_new==1) { ?> checked="checked"<?php } ?> /><label for="send_mail_new"><?php _e('yes to', 'wp-reserved-subjects') ?> <?php echo $current_user->user_email; ?> (predvolené)</label><br />
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row"><legend><?php _e('Send e-mail when subject is', 'wp-reserved-subjects') ?> <?php _e('changed', 'wp-reserved-subjects') ?> </legend></th>
		<td>
			<input name="send_mail_changes" type="radio" value="0"<?php if ($res->mail_changes==0) { ?> checked="checked"<?php } ?> /><label for="send_mail_changes"><?php _e('no', 'wp-reserved-subjects') ?> (<?php _e('default', 'wp-reserved-subjects') ?>)</label><br />
			<input name="send_mail_changes" type="radio" value="1"<?php if ($res->mail_changes==1) { ?> checked="checked"<?php } ?> /><label for="send_mail_changes"><?php _e('yes to', 'wp-reserved-subjects') ?> <?php echo $current_user->user_email; ?></label><br />
		</td>
	</tr>
	
</table>
<p class="submit">
	<input name="optionssub" type="submit" id="optionssub" value="<?php _e('Save settings"', 'wp-reserved-subjects') ?>" />
</p>
</form>
<?php
	} //end foreach
?>
</div>
