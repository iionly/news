<?php
/**
 * Group news module
 */

$group = elgg_get_page_owner_entity();
$current_user = elgg_get_logged_in_user_entity();

if ($group->news_enable == "no") {
	return true;
}

$all_link = elgg_view('output/url', array(
	'href' => "news/group/$group->guid/all",
	'text' => elgg_echo('link:view:all'),
	'is_trusted' => true,
));

elgg_push_context('widgets');
$options = array(
	'type' => 'object',
	'subtype' => 'news',
	'container_guid' => elgg_get_page_owner_guid(),
	'limit' => 4,
	'full_view' => false,
	'pagination' => false,
	'no_results' => elgg_echo('news:none'),
	'distinct' => false,
);
$content = elgg_list_entities($options);
elgg_pop_context();

if (elgg_is_logged_in() && (($current_user->isAdmin()) || (($current_user->guid == $group->owner_guid) || (check_entity_relationship($current_user->guid, "group_admin", $group->guid))))) {
	$new_link = elgg_view('output/url', array(
		'href' => "news/add/$group->guid",
		'text' => elgg_echo('news:write'),
		'is_trusted' => true,
    ));

    echo elgg_view('groups/profile/module', array(
		'title' => elgg_echo('news:group'),
		'content' => $content,
		'all_link' => $all_link,
		'add_link' => $new_link,
    ));

} else {
    echo elgg_view('groups/profile/module', array(
		'title' => elgg_echo('news:group'),
		'content' => $content,
		'all_link' => $all_link
    ));
}