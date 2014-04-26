<?php
/**
 * News Plugin
 *
 * Index page News widget for Widget Manager plugin
 *
 */

$count = sanitise_int($vars["entity"]->news_count, false);
if(empty($count)){
	$count = 4;
}

?>
<div>
	<?php echo elgg_echo("news:numbertodisplay"); ?><br />
	<?php echo elgg_view("input/text", array("name" => "params[news_count]", "value" => $count, "size" => "4", "maxlength" => "4")); ?>
</div>
