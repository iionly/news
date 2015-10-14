/**
 * news in groups widget
 *
 * originally from the Group Tools plugin and modified for the News plugin
 *
 */

define(function(require) {
	var $ = require('jquery');
	var elgg = require('elgg');

	function init() {
		$(".widget_news_in_groups_navigator > span:first").each(function(index, elem) {
			news_in_groups_rotate_content(elem);
		});
	
		$(".widget_news_in_groups_navigator > span").on("click", function() {
			news_in_groups_rotate_content(this);
		});

		// rotate every 10 seconds
		setInterval(news_in_groups_rotate, 10 * 1000);
	};

	function news_in_groups_rotate() {
		$(".widget_news_in_groups_navigator").each(function(index, elem) {

			if ($(this).find(".active").next().length === 0) {
				news_in_groups_rotate_content($(this).find("> span:first"));
			} else {
				news_in_groups_rotate_content($(this).find(".active").next());
			}
		});
	}

	function news_in_groups_rotate_content(elem) {
		$(elem).parent().find("span.active").removeClass("active");
		$(elem).addClass("active");

		$(elem).parent().prev().find("> div").hide();
		var active = $(elem).attr("rel");
		$(elem).parent().parent().find("." + active).show();
	}

	init();
});
