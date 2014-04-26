<?php
/**
 * News sidebar menu showing revisions
 *
 * @package News
 */

//If editing a post, show the previous revisions and drafts.
$news = elgg_extract('entity', $vars, false);

if (elgg_instanceof($news, 'object', 'news') && $news->canEdit()) {
	$owner = $news->getOwnerEntity();
	$revisions = array();

	$auto_save_annotations = $news->getAnnotations(array(
		'annotation_name' => 'news_auto_save',
		'limit' => 1,
	));
	if ($auto_save_annotations) {
		$revisions[] = $auto_save_annotations[0];
	}

	// count(false) == 1!  AHHH!!!
	$saved_revisions = $news->getAnnotations(array(
		'annotation_name' => 'news_revision',
		'limit' => 10,
		'reverse_order_by' => true,
	));
	if ($saved_revisions) {
		$revision_count = count($saved_revisions);
	} else {
		$revision_count = 0;
	}

	$revisions = array_merge($revisions, $saved_revisions);

	if ($revisions) {
		$title = elgg_echo('news:revisions');

		$n = count($revisions);
		$body = '<ul class="news-revisions">';

		$load_base_url = "news/edit/{$news->getGUID()}";

		// show the "published revision"
		if ($news->status == 'published') {
			$load = elgg_view('output/url', array(
				'href' => $load_base_url,
				'text' => elgg_echo('news:status:published'),
				'is_trusted' => true,
			));

			$time = "<span class='elgg-subtext'>"
				. elgg_view_friendly_time($news->time_created) . "</span>";

			$body .= "<li>$load : $time</li>";
		}

		foreach ($revisions as $revision) {
			$time = "<span class='elgg-subtext'>"
				. elgg_view_friendly_time($revision->time_created) . "</span>";

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

			$text = "$load: $time";
			$class = 'class="auto-saved"';

			$n--;

			$body .= "<li $class>$text</li>";
		}

		$body .= '</ul>';

		echo elgg_view_module('aside', $title, $body);
	}
}