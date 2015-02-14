<?php
/**
 * News Plugin
 *
 * Groups page News widget for Widget Manager plugin
 *
 */

// get widget settings
$count = sanitise_int($vars["entity"]->news_count, false);
if(empty($count)){
	$count = 4;
}

$container_guid =  elgg_get_page_owner_guid();

elgg_push_context('widgets');
$options = array(
	'type' => 'object',
	'subtype' => 'news',
	'container_guid' => $container_guid,
	'limit' => $count,
	'full_view' => false,
	'pagination' => false,
	'no_results' => elgg_echo('news:none'),
	'distinct' => false,
);
$content = elgg_list_entities($options);
elgg_pop_context();

$group = elgg_get_page_owner_entity();
$current_user = elgg_get_logged_in_user_entity();

if (elgg_is_logged_in() && (($current_user->isAdmin()) || (($current_user->guid == $group->owner_guid) || (check_entity_relationship($current_user->guid, "group_admin", $group->guid))))) {
	$content .= elgg_view('output/url', array(
		'href' => "news/add/$container_guid",
		'text' => elgg_echo('news:write'),
		'is_trusted' => true,
	));
}

echo $content;
