<?php
	global $wpdb;
	global $subjects_to_do_db_version;
	global $table_name;
	global $user_ID;
	$table_name = $wpdb->prefix . "subjects_to_do";
	get_currentuserinfo();
if (!empty($_GET["action"])) {
	if ($_GET["action"] == "reserve") { //reserving
		$query = "SELECT user FROM "
			. $table_name
			. " WHERE id='"
			. $_GET["id"]
			. "';";
		$wpdb->show_errors();
		$result = $wpdb->get_results($query, OBJECT);
		if ($result->user == 0) { //making sure, that someone is not reserving already reserved subject
			$query = "UPDATE "
				. $table_name
				. " SET user='"
				. $user_ID
				. "' WHERE id='"
				. $_GET["id"]
				. "';";
			$wpdb->show_errors();
			$wpdb->query( $query );
			$message = __('Subject was succesfully reserved.', 'wp-reserved-subjects');
			send_emails(get_user_name($user_ID), __('reservation of subject', 'wp-reserved-subjects'), $_GET["id"]);
		} //end if
	}
	if ($_GET["action"] == "noreserve") { //unreserving
		if ((is_owned($_GET["id"], $user_ID)) or (is_super_user())) { //check if the user has rights to end subject
			$query = "UPDATE "
				. $table_name
				. " SET user='0' WHERE id='"
				. $_GET["id"]
				. "';";
			$wpdb->show_errors();
			$wpdb->query( $query );
			$message = __('Reservation of subject was canceled.', 'wp-reserved-subjects');
			send_emails(get_user_name($user_ID), __('cancelation of reservation of subject', 'wp-reserved-subjects'), $_GET["id"]);
		} else {
			$message = __('You don\'t have user rights to cancel reservation of this subject.', 'wp-reserved-subjects');
		} //end if
	}
	if ($_GET["action"] == "end") { //end subject
		if ((is_owned($reserve_id, $reserve_user)) or (is_super_user())) {
			$query = "UPDATE "
				. $table_name
				. " SET expire='"
				. time()
				. "' WHERE id="
				. $_GET["id"]
				. ";";
			$wpdb->show_errors();
			$wpdb->query( $query );
			$message = __('Subject was ended.', 'wp-reserved-subjects');
			send_emails(get_user_name($user_ID), __('end of subject', 'wp-reserved-subjects'), $_GET["id"]);
		} else {
			$message = __('You don\'t have user rights to delete this subject.', 'wp-reserved-subjects');
		} //end if
	}
	if (($_GET["action"] == "edit") and (is_super_user())) { //edit subject
		$query = "SELECT * FROM " . $table_name . " WHERE id='" . $_GET["id"] . "';";
		$result = $wpdb->get_results($query, OBJECT);
		foreach ($result as $sub) {
			?><div class="wrap">
			<h2 id="add-subject"><?php _e('Edit', 'wp-reserved-subjects') ?> <?php _e('subject', 'wp-reserved-subjects') ?></h2>
			<?php $form_action = "admin.php?page=" . $_GET["page"] . "&amp;action=edited&amp;id=" . $_GET["id"]; ?>
			<form action="<? echo $form_action; ?>" method="post" name="edit_subject_form" id="edit_subject_form" class="validate">
			<table class="form-table">
				<tr class="form-field">
					<th scope="row"><label for="name_of_subject"><?php _e('Subject name', 'wp-reserved-subjects') ?></label><input type="hidden" name="hidden_add_subject_form" id="hidden_add_subject_form" value="Y" /></th>
					<td><input name="name_of_subject" type="text" id="name_of_subject" value="<?php echo $sub->subjectname; ?>" /></td>
		</tr>
		<tr class="form-field">
			<th scope="row"><label for="text_of_subject"><?php _e('Subject text', 'wp-reserved-subjects') ?></label></th>
			<td><textarea name="text_of_subject" id="text_of_subject" rows="6" cols="40" ><?php echo $sub->text; ?></textarea></td>
		</tr>
		<tr class="form-field">
			<th scope="row"><label for="expire"><?php _e('Expire in', 'wp-reserved-subjects') ?></label></th>
			<td><select name="expire" id="expire">
				<option value="<?php echo $sub->expire; ?>"<?php if(time() > $sub->expire) { echo " selected=\"selected\""; } ?>><?php _e('Expired' , 'wp-reserved-subjects') ?></option>
				<option value="1"<?php if ( ((int)(($sub->expire-time())/43200)) == 1 ) { echo " selected=\"selected\""; } ?>>1 <?php _e('day', 'wp-reserved-subjects') ?></option>
				<option value="2"<?php if ( ((int)(($sub->expire-time())/43200)) == 2 ) { echo " selected=\"selected\""; } ?>>2 <?php _e('days&nbsp;', 'wp-reserved-subjects') ?></option>
				<option value="3"<?php if ( ((int)(($sub->expire-time())/43200)) == 3 ) { echo " selected=\"selected\""; } ?>>3 <?php _e('days&nbsp;', 'wp-reserved-subjects') ?></option>
				<option value="4"<?php if ( ((int)(($sub->expire-time())/43200)) == 4 ) { echo " selected=\"selected\""; } ?>>4 <?php _e('days&nbsp;', 'wp-reserved-subjects') ?></option>
				<option value="5"<?php if ( ((int)(($sub->expire-time())/43200)) == 5 ) { echo " selected=\"selected\""; } ?>>5 <?php _e('days', 'wp-reserved-subjects') ?></option>
				<option value="6"<?php if ( ((int)(($sub->expire-time())/43200)) == 6 ) { echo " selected=\"selected\""; } ?>>6 <?php _e('days', 'wp-reserved-subjects') ?></option>
				<option value="7"<?php if ( ((int)(($sub->expire-time())/43200)) == 7 ) { echo " selected=\"selected\""; } ?>>7 <?php _e('days', 'wp-reserved-subjects') ?></option>
				<option value="8"<?php if ( ((int)(($sub->expire-time())/43200)) == 8 ) { echo " selected=\"selected\""; } ?>>8 <?php _e('days', 'wp-reserved-subjects') ?></option>
				<option value="9"<?php if ( ((int)(($sub->expire-time())/43200)) == 9 ) { echo " selected=\"selected\""; } ?>>9 <?php _e('days', 'wp-reserved-subjects') ?></option>
				<option value="10"<?php if ( ((int)(($sub->expire-time())/43200)) == 10 ) { echo " selected=\"selected\""; } ?>>10 <?php _e('days', 'wp-reserved-subjects') ?></option>
				<option value="11"<?php if ( ((int)(($sub->expire-time())/43200)) == 11 ) { echo " selected=\"selected\""; } ?>>11 <?php _e('days', 'wp-reserved-subjects') ?></option>
				<option value="12"<?php if ( ((int)(($sub->expire-time())/43200)) == 12 ) { echo " selected=\"selected\""; } ?>>12 <?php _e('days', 'wp-reserved-subjects') ?></option>
				<option value="13"<?php if ( ((int)(($sub->expire-time())/43200)) == 13 ) { echo " selected=\"selected\""; } ?>>13 <?php _e('days', 'wp-reserved-subjects') ?></option>
				<option value="14"<?php if ( ((int)(($sub->expire-time())/43200)) == 14 ) { echo " selected=\"selected\""; } ?>>14 <?php _e('days', 'wp-reserved-subjects') ?></option>
			</select></td>
		</tr>
		<tr class="form-field">
			<th scope="row"><label for="user"><?php _e('Reserved for', 'wp-reserved-subjects') ?></label></th>
			<td><select name="user" id="user">
			<?php
			$users_table_name = $wpdb->prefix . "users";
			$subquery = "SELECT ID, display_name FROM "
			. $users_table_name
			. " ORDER BY display_name ASC;";
			$subresult = $wpdb->get_results($subquery, OBJECT);
			if ($sub->user == 0) {
			?>	<option value="0"><?php _e('nobody', 'wp-reserved-subjects') ?></option><?php
			} //end if
			foreach ($subresult as $subres) {
				?><option value="<?php echo $subres->ID ?>"<?php if ($sub->user == $subres->ID) { echo ?> selected="selected"<?php } ?>><?php echo $subres->display_name; ?></option><?php
			} //end foreach
			?>
			</select></td>
		</tr>
	</table>
	<p class="submit"><input name="editsubjectsub" type="submit" id="editsubjectsub" value="<?php _e('Edit', 'wp-reserved-subjects') ?> <?php _e('subject', 'wp-reserved-subjects') ?>" /></p>
	</form>
</div>

	<?php 	} //end foreach
	}
	if ($_GET["action"] == "edited") { //save edited content
		if (is_super_user()) {
			$expire = (time() + ($_POST["expire"] * 43200));
			$query = "UPDATE "
			. $table_name
			. " SET subjectname='"
			. $_POST["name_of_subject"]
			. "', text='"
			. $_POST["text_of_subject"]
			. "', expire='"
			. $expire
			. "', user='"
			. $_POST["user"]
			. "' WHERE id='"
			. $_GET["id"]
			. "';";
			$wpdb->show_errors();
			$wpdb->query( $query );
			$message = __('Subject was succesfully changed.', 'wp-reserved-subjects') ;
		} else {
			$message = __('You don\'t have user rights to change this subject.', 'wp-reserved-subjects') ;
		} //end if
	}
}
	if (!empty($message)) {
		?><div id="message" class="updated fade"><p><?php echo $message; ?></p></div><?php
	} //end if
?>
