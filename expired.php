<div class="wrap">
	<h2><?php _e('Expired subjects', 'wp-reserved-subjects') ?></h2>
	<table class="widefat">
	<thead>
		<tr>
			<th scope="col"><?php _e('Subjects', 'wp-reserved-subjects') ?></th>
			<th scope="col"><?php _e('Expired before', 'wp-reserved-subjects') ?></th>
			<th scope="col"><?php _e('Reserved for', 'wp-reserved-subjects') ?></th>
			<?php if (is_super_user()) { ?>
			<th scope="col"><?php _e('Actions', 'wp-reserved-subjects') ?></th>
			<?php } ?>
		</tr>
	</thead>
	<tbody id="the-comment-list" class="list:comment">
	<?php
	global $wpdb;
	global $subjects_to_do_db_version;
	global $table_name;
	global $current_user;
	$table_name = $wpdb->prefix . "subjects_to_do";
	$now = time();
	$wpdb->show_errors();
	$query2 = "SELECT * FROM " . $table_name . " WHERE expire < " . $now . " ORDER BY expire DESC;";
	$subjects = $wpdb->get_results($query2, OBJECT);
	foreach ($subjects as $sub) { ?>
		<tr>
			<td><p><strong><?php echo $sub->subjectname; ?></strong></p><p><?php echo $sub->text; ?></p></td>
			<td><p><?php 
					$expire = (time()-($sub->expire));
					$expire = (int)($expire/43200);
					echo $expire . " ";
					if ($expire==1) { __('day&nbsp;', 'wp-reserved-subjects'); }
					else { __('days&nbsp;&nbsp;', 'wp-reserved-subjects'); } ?></p></td>
			<td><p><?php echo ($sub->name); ?></p></td>
			<?php if (is_super_user()) { ?>
			<td><p><a href="admin.php?page=expired-subjects.php&#038;action=edit&#038;id=
					<?php echo ($sub->id); ?>
					&#038;_wpnonce=
					<?php echo (time()); ?>
					"><?php _e('Edit', 'wp-reserved-subjects') ?></a></p></td>
			<?php } ?>
		</tr>
		<?php
	} //end foreach ?>
</tbody>
</table>
</div>
