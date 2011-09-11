<?php
/**
 * Shows a single calendar entry. Used for the admin area.
 */
$cal = elgg_extract('entity', $vars);
$access = get_write_access_array();
$access_level = $access[$cal->access_id];

//build delete link
$delete_url = "action/tgscalendar/delete?guid={$cal->guid}";
$delete_link = elgg_view('output/confirmlink', array(
	'href' => $delete_url,
	'text' => elgg_echo('delete')
));

//build edit link
$edit_url = "admin/appearance/tgscalendar?guid=$cal->guid";
$edit_url = elgg_normalize_url($edit_url);
$edit_link = "<a rel=\"toggle\" href=\"#elgg-tgscalendar-edit-$cal->guid\">" . elgg_echo('edit') . '</a>';

?>
<li class="pam mam elgg-tgscalendar-feed elgg-tgscalendar-feed-<?php echo $cal->guid; ?>">
	<a href="<? echo $cal->google_cal_feed ?>">
	<? echo $cal->title ?></a> - <? echo $access_level; ?>
	<span class="right"><? echo $edit_link; ?> | <? echo $delete_link; ?></span>
	<div class="hidden" id="elgg-tgscalendar-edit-<?php echo $cal->guid; ?>">
		<?php
		$vars = tgscalendar_prepare_form_vars($cal);
		echo elgg_view_form('tgscalendar/save', array('class' => 'mtl'), $vars);
		?>
	</div>
</li>
<?php

return true;

// once the admin area has its own CSS for entity lists the below should be used.

$cal = elgg_extract('entity', $vars);
$owner = $cal->getOwnerEntity();
$owner_icon = elgg_view_entity_icon($owner, 'tiny');
$container = $cal->getContainerEntity();
$categories = elgg_view('output/categories', $vars);

$owner_link = elgg_view('output/url', array(
	'href' => "polls/owner/$owner->username",
	'text' => $owner->name,
));
$author_text = elgg_echo('byline', array($owner_link));

$tags = elgg_view('output/tags', array('tags' => $cal->tags));
$date = elgg_view_friendly_time($cal->time_created);

$comments_count = $cal->countComments();
//only display if there are commments
if ($comments_count != 0) {
	$text = elgg_echo("comments") . " ($comments_count)";
	$comments_link = elgg_view('output/url', array(
		'href' => $cal->getURL() . '#comments',
		'text' => $text,
	));
} else {
	$comments_link = '';
}

$metadata = elgg_view_menu('entity', array(
	'entity' => $vars['entity'],
	'handler' => 'polls',
	'sort_by' => 'priority',
	'class' => 'elgg-menu-hz',
));

$subtitle = "$author_text $date $categories $comments_link";

// do not show the metadata and controls in widget view
if (elgg_in_context('widgets')) {
	$metadata = '';
}

if ($full && !elgg_in_context('gallery')) {
	$header = elgg_view_title($cal->title);

	$params = array(
		'entity' => $cal,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);
	$list_body = elgg_view('page/components/summary', $params);
	$cal_info = elgg_view_image_block($owner_icon, $list_body);
	$cal = elgg_view('polls/poll_container', $vars);

	echo <<<HTML
$header
$cal_info
<div class="elgg-content mts">
	$cal
</div>
HTML;

} else {
	$params = array(
		'entity' => $cal,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'tags' => $tags,
	);

	$body .= elgg_view('page/components/summary', $params);
	echo elgg_view_image_block($owner_icon, $body);
}