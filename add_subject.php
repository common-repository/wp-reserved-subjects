<?php
	global $current_user;
	global $wpdb;
	global $user_ID;
	global $table_name;
	$table_name = $wpdb->prefix . "subjects_to_do";
	get_currentuserinfo();
	if ($_POST[ 'hidden_add_subject_form' ] == 'Y') {
		$name_of_subject = $_POST[ 'name_of_subject' ];
		$text_of_subject = $_POST[ 'text_of_subject' ];
		$expire = $_POST[ 'expire' ];
		$expire = time() + ($expire * 43200);
		$insert = "INSERT INTO " 
			. $table_name  
			. " VALUES ('','"
			. time() 
			. "','" 
			. $wpdb->escape($expire)
			. "','','" 
			. $wpdb->escape($name_of_subject) 
			. "','" 
			. $wpdb->escape($text_of_subject) 
			. "');";
		//$wpdb->show_errors();
		$wpdb->query( $insert );
		$message = __('Subject was succesfully added.', 'wp-reserved-subjects') . "<a href=\"admin.php?page=wp-reserved-subjects/wp-reserved-subjects.php\">" . __('View subjects listing.', 'wp-reserved-subjects') . "</a>";
		$query = "SELECT id FROM "
		. $table_name
		. " WHERE subjectname='"
		. $name_of_subject
		. "' and text='"
		. $text_of_subject
		. "';";
		$result = $wpdb->get_results($query, OBJECT);
		foreach ($result as $sub) { $add_id = $sub->id; } //end foreach
		send_emails(get_user_name($user_ID), __('add subject', 'wp-reserved-subjects'), $add_id);
		if (!empty($message)) {
			?><div id="message" class="updated fade"><p><?php echo $message; ?></p></div><?php
		} //end if
	} ?>
<div class="wrap">
	<h2 id="add-subject"><?php _e('Add subject', 'wp-reserved-subjects') ?></h2>
	<form action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>" method="post" name="add_subject_form" id="add_subject_form" class="validate">
	<table class="form-table">
		<tr class="form-field">
			<th scope="row"><label for="name_of_subject"><?php _e('Subject name', 'wp-reserved-subjects') ?></label><input type="hidden" name="hidden_add_subject_form" id="hidden_add_subject_form" value="Y" /></th>
			<td ><input name="name_of_subject" type="text" id="name_of_subject" /></td>
		</tr>
		<tr class="form-field">
			<th scope="row"><label for="text_of_subject"><?php _e('Subject text', 'wp-reserved-subjects') ?></label></th>
			<td><textarea name="text_of_subject" id="text_of_subject" rows="6" cols="40" ></textarea></td>
		</tr>
		<tr class="form-field">
			<th scope="row"><label for="expire"><?php _e('Expire in', 'wp-reserved-subjects') ?></label></th>
			<td><select name="expire" id="expire">
				<option value="1">1 <?php _e('day', 'wp-reserved-subjects') ?></option>
				<option value="2">2 <?php _e('days&nbsp;', 'wp-reserved-subjects') ?></option>
				<option value="3">3 <?php _e('days&nbsp;', 'wp-reserved-subjects') ?></option>
				<option value="5">5 <?php _e('days', 'wp-reserved-subjects') ?></option>
				<option value="7" selected="selected">7 <?php _e('days', 'wp-reserved-subjects') ?></option>
				<option value="10">10 <?php _e('days', 'wp-reserved-subjects') ?></option>
				<option value="14">14 <?php _e('days', 'wp-reserved-subjects') ?></option>
			</select></td>
		</tr>
	</table>
	<p class="submit"><input name="addsubjectsub" type="submit" id="addsubjectsub" value="<?php _e('Add subject', 'wp-reserved-subjects') ?>" /></p>
	</form>
</div>
