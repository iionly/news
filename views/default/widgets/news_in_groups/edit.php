<?php
/**
 * settings for the news in groups widget
 *
 * originally from the Group Tools plugin and modified for the News plugin
 *
 */

$widget = $vars["entity"];

$news_count = sanitise_int($widget->news_count);
if ($news_count < 1) {
	$news_count = 5;
}

$options_values = array("" => elgg_echo("news:news_in_groups:settings:no_project"));
$options = array(
	"type" => "group",
	"limit" => false,
	"joins" => array("JOIN " . elgg_get_config("dbprefix") . "groups_entity ge ON e.guid = ge.guid"),
	"order_by" => "ge.name ASC"
);

$batch = new ElggBatch("elgg_get_entities", $options);
foreach ($batch as $group) {
	$options_values[$group->getGUID()] = $group->name;
}

for ($i = 1; $i < 6; $i++) {
	$metadata_name = "project_" . $i;
	
	echo "<div>";
	echo elgg_echo("news:news_in_groups:settings:project") . " ";
	echo elgg_view("input/dropdown", array("options_values" => $options_values, "name" => "params[" . $metadata_name . "]", "value" => $widget->$metadata_name));
	echo "</div>";
}

echo "<div>";
echo elgg_echo("news:news_in_groups:settings:news_count") . " ";
echo elgg_view("input/dropdown", array("options" => array(1,2,3,4,5,10,15,20), "name" => "params[news_count]", "value" => $news_count));
echo "</div>";

echo "<div>";
echo elgg_echo("news:news_in_groups:settings:group_icon_size") . " ";
echo elgg_view("input/dropdown", array("options_values" => array("medium" => elgg_echo("news:news_in_groups:settings:group_icon_size:medium"), "small" => elgg_echo("news:news_in_groups:settings:group_icon_size:small")), "name" => "params[group_icon_size]", "value" => $widget->group_icon_size));
echo "</div>";
