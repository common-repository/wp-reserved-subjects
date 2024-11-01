<div class="wrap">
	<h2><?php _e('Non reserved', 'wp-reserved-subjects') ?> <?php _e('subjects', 'wp-reserved-subjects') ?></h2>
	<table class="widefat">
	<thead>
		<tr>
			<th scope="col"><?php _e('Subjects', 'wp-reserved-subjects') ?></th>
			<th scope="col" style="width:10em"><?php _e('Expire in', 'wp-reserved-subjects') ?></th>
			<th scope="col" style="width:10em"><?php _e('Actions', 'wp-reserved-subjects') ?></th>
		</tr>
	</thead>
	<tbody id="the-comment-list" class="list:comment">
	<?php
		global $wpdb;
		global $subjects_to_do_db_version;
		global $table_name;
		global $current_user;
		$table_name = $wpdb->prefix . "subjects_to_do";
		get_currentuserinfo();
		$now = time();
		$query2 = "SELECT * FROM " . $table_name . " WHERE expire > " . $now . " AND user=0 ORDER BY expire ASC;";
		$subjects = $wpdb->get_results($query2, OBJECT);
		foreach ($subjects as $sub) { ?>
		<tr>
			<td><p><strong><?php echo $sub->subjectname; ?></strong></p><p><?php echo $sub->text; ?></p></td>
			<td><p><?php 
				$expire = ($sub->expire)-(time());
				$expire = (int)($expire/43200);
				echo $expire . " ";
				if ($expire==1) { _e('day', 'wp-reserved-subjects'); }
				else if (($expire>1) and ($expire<5)) { _e('days&nbsp;', 'wp-reserved-subjects'); }
				else { _e('days', 'wp-reserved-subjects'); }
				?></p></td>
			<td><p><a href="admin.php?page=wp-reserved-subjects/wp-reserved-subjects.php&#038;action=reserve&#038;user=
				<?php echo $current_user->display_name; ?>
				&#038;id=
				<?php echo ($sub->id); ?>
				&#038;_wpnonce=
				<?php echo (time()); ?>
				"><?php _e('Reserve', 'wp-reserved-subjects') ?></a><br />
				<?php if (is_super_user()) { ?>
					<a href="admin.php?page=wp-reserved-subjects/wp-reserved-subjects.php&#038;action=edit&#038;id=
					<?php echo ($sub->id); ?>
					&#038;_wpnonce=
					<?php echo (time()); ?>
					"><?php _e('Edit', 'wp-reserved-subjects') ?></a><br />
					<a href="admin.php?page=wp-reserved-subjects/wp-reserved-subjects.php&#038;action=end&#038;id=
					<?php echo ($sub->id); ?>
					&#038;_wpnonce=
					<?php echo (time()); ?>
					"><?php _e('End', 'wp-reserved-subjects') ?></a>
				<?php } //end if ?>
				</p></td>
		</tr>
		<?php
		} //end foreach ?>
	</tbody>
</table>
</div>
