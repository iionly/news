<?php
/**
 * News archives
 */

$loggedin_user = elgg_get_logged_in_user_entity();
$page_owner = elgg_get_page_owner_entity();

if (elgg_instanceof($page_owner, 'user')) {
	$url_segment = 'news/archive/' . $page_owner->username;
} else {
	$url_segment = 'news/group/' . $page_owner->getGUID() . '/archive';
}

// This is a limitation of the URL schema.
if ($page_owner) {
	$dates = get_entity_dates('object', 'news', $page_owner->getGUID());

	if ($dates) {
		$dates = array_reverse($dates);
		$title = elgg_echo('news:archives');
		$content = '<ul class="news-archives">';
		foreach ($dates as $date) {
			$timestamplow = mktime(0, 0, 0, substr($date,4,2) , 1, substr($date, 0, 4));
			$timestamphigh = mktime(0, 0, 0, ((int) substr($date, 4, 2)) + 1, 1, substr($date, 0, 4));

			$link = elgg_get_site_url() . $url_segment . '/' . $timestamplow . '/' . $timestamphigh;
			$month = elgg_echo('date:month:' . substr($date, 4, 2), array(substr($date, 0, 4)));
			$content .= "<li><a href=\"$link\" title=\"$month\">$month</a></li>";
		}
		$content .= '</ul>';

		echo elgg_view_module('aside', $title, $content);
	}
}