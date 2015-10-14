<?php
/**
 * View for news objects
 *
 * @package News
 */

$full = elgg_extract('full_view', $vars, false);
$news = elgg_extract('entity', $vars, false);

if (!$news) {
	return true;
}

$owner = $news->getOwnerEntity();
$container = $news->getContainerEntity();
$categories = elgg_view('output/categories', $vars);
$excerpt = $news->excerpt;
if (!$excerpt) {
	$excerpt = elgg_get_excerpt($news->description);
}

if (elgg_instanceof($container, 'group')) {
	$owner_icon = '<a href="'.elgg_get_site_url().'news/group/'.$container->guid.'/all"><img src="' . elgg_get_simplecache_url('news/news.gif') . '"></a>';
} else {
	$owner_icon = '<a href="'.elgg_get_site_url().'news/all"><img src="' . elgg_get_simplecache_url('news/news.gif') . '"></a>';
}

$vars['owner_url'] = "news/owner/$owner->username";
$by_line = elgg_view('page/elements/by_line', $vars);

// The "on" status changes for comments, so best to check for !Off
if ($news->comments_on != 'Off') {
	$comments_count = $news->countComments();
	//only display if there are commments
	if ($comments_count != 0) {
		$text = elgg_echo("comments") . " ($comments_count)";
		$comments_link = elgg_view('output/url', array(
			'href' => $news->getURL() . '#comments',
			'text' => $text,
			'is_trusted' => true,
		));
	} else {
		$comments_link = '';
	}
} else {
	$comments_link = '';
}

$subtitle = "$by_line $comments_link $categories";

$metadata = '';
if (!elgg_in_context('widgets')) {
	// only show entity menu outside of widgets
	$metadata = elgg_view_menu('entity', array(
		'entity' => $vars['entity'],
		'handler' => 'news',
		'sort_by' => 'priority',
		'class' => 'elgg-menu-hz',
	));
}

if ($full) {

	$body = elgg_view('output/longtext', array(
		'value' => $news->description,
		'class' => 'news-post',
	));

	$params = array(
		'entity' => $news,
		'title' => false,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
	);
	$params = $params + $vars;
	$summary = elgg_view('object/elements/summary', $params);

	echo elgg_view('object/elements/full', array(
		'summary' => $summary,
		'icon' => $owner_icon,
		'body' => $body,
	));

} else {
	// brief view

	$params = array(
		'entity' => $news,
		'metadata' => $metadata,
		'subtitle' => $subtitle,
		'content' => $excerpt,
	);
	$params = $params + $vars;
	$list_body = elgg_view('object/elements/summary', $params);

	echo elgg_view_image_block($owner_icon, $list_body);
}
