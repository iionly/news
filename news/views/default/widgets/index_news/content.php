<?php
/**
 * News Plugin
 *
 * Index page News widget for Widget Manager plugin
 *
 */

// get widget settings
$count = sanitise_int($vars["entity"]->news_count, false);
if(empty($count)){
	$count = 4;
}

elgg_push_context('widgets');
$options = array(
	'type' => 'object',
	'subtype' => 'news',
	'limit' => $count,
	'full_view' => false,
	'pagination' => false,
);
$content = elgg_list_entities($options);
elgg_pop_context();

if ($content) {
	echo $content;
} else {
	echo '<p>' . elgg_echo('news:none') . '</p>';
}
