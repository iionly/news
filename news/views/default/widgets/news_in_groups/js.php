<?php
/**
 * extra JS for the global site JS, to support news in groups widget
 *
 * originally from the Group Tools plugin and modified for the News plugin
 *
 */
?>
//<script>

elgg.provide("elgg.widgets.news_in_groups");

elgg.widgets.news_in_groups.init = function() {
	$(".widget_news_in_groups_navigator > span:first").each(function(index, elem) {
		elgg.widgets.news_in_groups.rotate_content(elem);
	});
	
	$(".widget_news_in_groups_navigator > span").live("click", function() {
		elgg.widgets.news_in_groups.rotate_content(this);
	});

	// rotate every 10 seconds
	setInterval (elgg.widgets.news_in_groups.rotate, 10 * 1000);
};

elgg.widgets.news_in_groups.rotate = function() {
	$(".widget_news_in_groups_navigator").each(function(index, elem) {
		
		if ($(this).find(".active").next().length === 0) {
			elgg.widgets.news_in_groups.rotate_content($(this).find("> span:first"));
		} else {
			elgg.widgets.news_in_groups.rotate_content($(this).find(".active").next());
		}
	});
}

elgg.widgets.news_in_groups.rotate_content = function(elem) {
	$(elem).parent().find("span.active").removeClass("active");
	$(elem).addClass("active");

	$(elem).parent().prev().find("> div").hide();
	var active = $(elem).attr("rel");
	$(elem).parent().parent().find("." + active).show();
}

elgg.register_hook_handler('init', 'system', elgg.widgets.news_in_groups.init);
