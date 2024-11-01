<div class="wrap">
	<h2><?php _e('Reserved subjects', 'wp-reserved-subjects') ?></h2>
	<table class="widefat">
	<thead>
		<tr>
			<th scope="col"><?php _e('Subjects', 'wp-reserved-subjects') ?></th>
			<th scope="col"><?php _e('Reserved for', 'wp-reserved-subjects') ?></th>
			<th scope="col" style="width:10em"><?php _e('Expire in', 'wp-reserved-subjects') ?></th>
			<th scope="col" style="width:10em"><?php _e('Actions', 'wp-reserved-subjects') ?></th>
		</tr>
	</thead>
	<tbody id="the-comment-list" class="list:comment">
	<?php
	$now = time();
	$wpdb->show_errors();
	global $user_ID;
	get_currentuserinfo();
	$query2 = "SELECT * FROM " . $table_name . " WHERE expire > " . $now . " AND user<>'0' ORDER BY expire ASC;";
	$subjects = $wpdb->get_results($query2, OBJECT);
	//echo $query2, " OOOO", $table_name;
	foreach ($subjects as $sub) { ?>
		<tr>
			<td><h3><?php echo $sub->subjectname; ?></h3><p><?php echo $sub->text; ?></p></td>
			<td><p><strong><?php echo get_user_name($sub->user); ?></strong></p></td>
			<td><p><?php 
					$expire = ($sub->expire)-(time());
					$expire = (int)($expire/43200);
					echo $expire . " ";
					if ($expire==1) { _e('day', 'wp-reserved-subjects'); }
					else if (($expire>1) and ($expire<5)) { _e('days&nbsp;', 'wp-reserved-subjects'); }
					else { _e('days', 'wp-reserved-subjects'); } ?></p></td>
			<td><p><?php if ((($sub->user)==($user_ID)) or (is_super_user())) { ?><a href="admin.php?page=wp-reserved-subjects/wp-reserved-subjects.php&#038;action=noreserve&#038;user=
				<?php echo ($user_ID); ?>
				&#038;id=
				<?php echo ($sub->id); ?>
				&#038;_wpnonce=
				<?php echo (time()); ?>
				"><?php _e('Cancel reservation', 'wp-reserved-subjects') ?></a><br />
				<?php if (is_super_user()) { ?>
					<a href="admin.php?page=wp-reserved-subjects/wp-reserved-subjects.php&#038;action=edit&#038;id=
					<?php echo ($sub->id); ?>
					&#038;_wpnonce=
					<?php echo (time()); ?>
					"><?php _e('Edit', 'wp-reserved-subjects') ?></a><br />
				<?php } //end if ?>
				<a href="admin.php?page=wp-reserved-subjects/wp-reserved-subjects.php&#038;action=end&#038;user=
				<?php echo ($user_ID); ?>
				&#038;id=
				<?php echo ($sub->id); ?>
				&#038;_wpnonce=
				<?php echo (time()); ?>
				"><?php _e('End', 'wp-reserved-subjects') ?></a><?php } ?></p></td>
		</tr>
		<?php
	} //end foreach ?>
</tbody>
</table>
</div>
