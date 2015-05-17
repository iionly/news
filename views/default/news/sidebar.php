<?php
/**
 * News sidebar
 *
 * @package News
 */

// fetch & display latest comments
if ($vars['page'] != 'friends') {
	echo elgg_view('page/elements/comments_block', array(
		'subtypes' => 'news',
		'container_guid' => elgg_get_page_owner_guid(),
	));
}

// only users can have archives at present
if ($vars['page'] == 'owner' || $vars['page'] == 'group') {
	echo elgg_view('news/sidebar/archives', $vars);
}

if ($vars['page'] != 'friends') {
	echo elgg_view('page/elements/tagcloud_block', array(
		'subtypes' => 'news',
		'container_guid' => elgg_get_page_owner_guid(),
	));
}
