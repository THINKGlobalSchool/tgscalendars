<?php
$cal = $vars['entity'];

switch ($cal->access_id) {
	case -1: 
		$access_level = 'Default';
		break;
	case 0: 
		$access_level = 'Private';
		break;
	case 1: 
		$access_level = 'Logged In Users';
		break;
	case 2:
		$access_level = 'Public';
		break;
	case -2 :
		$access_level = 'Friends Only';
		break;
	default:
		$acl = get_access_collection($cal->access_id);
		$access_level = $acl->name;
		break;
}

//build delete link
$delete_url = "{$vars['url']}action/tgscalendar/delete?guid={$cal->guid}";
$delete_link = elgg_view('output/confirmlink', array(
	'href' => $delete_url,
	'text' => elgg_echo('delete')
));

//build edit link
$edit_url = strtok(current_page_url(),'?').'?guid='.$cal->guid;
$edit_link = "<a href='{$edit_url}'>Edit</a>";


?>
<li>
	<a class="admin_calendar_link" href="<?= $cal->google_cal_feed ?>" style="color: #<?= $cal->text_color ?>; background-color: #<?= $cal->background_color ?>;"><?= $cal->title ?></a> - <?= $access_level ?> <span class="admin_calendar_controls"><?= $edit_link ?> | <?= $delete_link ?></span>
</li>
