<?php
/**
 * Delete news entity
 *
 * @package News
 */

$news_guid = get_input('guid');
$news = get_entity($news_guid);

if (elgg_instanceof($news, 'object', 'news') && $news->canEdit()) {
	$container = get_entity($news->container_guid);
	if ($news->delete()) {
		system_message(elgg_echo('news:message:deleted_post'));
		if (elgg_instanceof($container, 'group')) {
			forward("news/group/$container->guid/all");
		} else {
			forward("news/owner/$container->username");
		}
	} else {
		register_error(elgg_echo('news:error:cannot_delete_post'));
	}
} else {
	register_error(elgg_echo('news:error:post_not_found'));
}

forward(REFERER);