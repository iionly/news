<?php
/**
 * News CSS
 *
 * @package News
*/
?>

/* force tinymce input height for a more useful editing / news creation area */
form#news-post-edit #description_parent #description_ifr {
	height:400px !important;
}

.widget_news_in_groups_navigator {
	border-top: 1px dotted #CCCCCC;
	padding-top: 5px;
	text-align: center;
}

.widget_news_in_groups_navigator > span {
	border: 1px solid #CCCCCC;
	cursor: pointer;
	padding: 2px 4px;
	margin: 0 2px;
}

.widget_news_in_groups_navigator > span.active,
.widget_news_in_groups_navigator > span:hover {
	background: #CCCCCC;
}

.widget_news_in_groups_navigator > span.active {
	cursor: auto;
}
