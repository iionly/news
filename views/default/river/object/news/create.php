<?php
/**
 * News river view.
 */

$item = $vars['item'];
/* @var ElggRiverItem $item */

$object = $item->getObjectEntity();

$excerpt = $object->excerpt ? $object->excerpt : $object->description;
$excerpt = strip_tags($excerpt);
$excerpt = elgg_get_excerpt($excerpt);

$vars['message'] = $excerpt;

echo elgg_view('page/components/image_block', array(
	'image' => '<a href="'.elgg_get_site_url().'news/all"><img src="'.elgg_get_site_url().'mod/news/graphics/news.gif"></a>',
	'body' => elgg_view('river/elements/body', $vars),
	'class' => 'elgg-river-item'
));
