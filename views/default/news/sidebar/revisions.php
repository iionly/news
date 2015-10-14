<?php
/**
 * News sidebar menu showing revisions
 *
 * @package News
 */

//If editing a news post, show the previous revisions and drafts.
$news = elgg_extract('entity', $vars, false);

if (!elgg_instanceof($news, 'object', 'news')) {
	return;
}

if (!$news->canEdit()) {
	return;
}

$owner = $news->getOwnerEntity();
$revisions = array();

$auto_save_annotations = $news->getAnnotations([
	'annotation_name' => 'news_auto_save',
	'limit' => 1,
]);
if ($auto_save_annotations) {
	$revisions[] = $auto_save_annotations[0];
}

$saved_revisions = $news->getAnnotations([
	'annotation_name' => 'news_revision',
	'reverse_order_by' => true,
	'limit' => false
]);

$revisions = array_merge($revisions, $saved_revisions);

if (empty($revisions)) {
	return;
}

$load_base_url = "news/edit/{$news->getGUID()}";

// show the "published revision"
$published_item = '';
if ($news->status == 'published') {
	$load = elgg_view('output/url', [
		'href' => $load_base_url,
		'text' => elgg_echo('status:published'),
		'is_trusted' => true,
	]);

	$time = elgg_format_element('span', ['class' => 'elgg-subtext'], elgg_view_friendly_time($news->time_created));
	$published_item = elgg_format_element('li', [], "$load: $time");
}

$n = count($revisions);
$revisions_list = '';
foreach ($revisions as $revision) {
	$time = elgg_format_element('span', ['class' => 'elgg-subtext'], elgg_view_friendly_time($revision->time_created));

	if ($revision->name == 'news_auto_save') {
		$revision_lang = elgg_echo('news:auto_saved_revision');
	} else {
		$revision_lang = elgg_echo('news:revision') . " $n";
	}
	
	$load = elgg_view('output/url', array(
		'href' => "$load_base_url/$revision->id",
		'text' => $revision_lang,
		'is_trusted' => true,
	));

	$revisions_list .= elgg_format_element('li', ['class' => 'auto-saved'], "$load: $time");
	
	$n--;
}

$body = elgg_format_element('ul', ['class' => 'news-revisions'], $published_item . $revisions_list);

echo elgg_view_module('aside', elgg_echo('news:revisions'), $body);
