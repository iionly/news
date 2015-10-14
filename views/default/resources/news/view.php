<?php

$page_type = elgg_extract('page_type', $vars);
$guid = elgg_extract('guid', $vars);

elgg_entity_gatekeeper($guid, 'object', 'news');
elgg_group_gatekeeper();

$news = get_entity($guid);

elgg_set_page_owner_guid($news->container_guid);

// no header or tabs for viewing an individual news post
$params = [
	'filter' => '',
	'title' => $news->title
];

$container = $news->getContainerEntity();
$crumbs_title = $container->name;

if (elgg_instanceof($container, 'group')) {
	elgg_push_breadcrumb($crumbs_title, "news/group/$container->guid/all");
} else {
	elgg_push_breadcrumb($crumbs_title, "news/owner/$container->username");
}

elgg_push_breadcrumb($news->title);

$params['content'] = elgg_view_entity($news, array('full_view' => true));

// check to see if we should allow comments
if ($news->comments_on != 'Off' && $news->status == 'published') {
	$params['content'] .= elgg_view_comments($news);
}

$params['sidebar'] = elgg_view('news/sidebar', array('page' => $page_type));

$body = elgg_view_layout('content', $params);

echo elgg_view_page($params['title'], $body);
