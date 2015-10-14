<?php
/**
 * News helper functions
 *
 * @package News
 */

/**
 * Get page components to list a user's or all news.
 *
 * @param int $container_guid The GUID of the page owner or null for all news
 * @return array
 */
function news_get_page_content_list($container_guid = null) {

	$return = array();

	$return['filter_context'] = $container_guid ? 'mine' : 'all';
	$return['filter'] = '';

	$options = array(
		'type' => 'object',
		'subtype' => 'news',
		'full_view' => false,
		'no_results' => elgg_echo('news:none'),
		'preload_owners' => true,
		'distinct' => false,
	);

	$current_user = elgg_get_logged_in_user_entity();

	if ($container_guid) {
		// access check for closed groups
		elgg_group_gatekeeper();

		$container = get_entity($container_guid);
		if ($container instanceof ElggGroup) {
		$options['container_guid'] = $container_guid;
		} else {
			$options['owner_guid'] = $container_guid;
		}
		$return['title'] = elgg_echo('news:title:user_news', array($container->name));

		$crumbs_title = $container->name;
		elgg_push_breadcrumb($crumbs_title);

		if ($current_user && ($container_guid == $current_user->guid)) {
			$return['filter_context'] = 'mine';
		} else if (elgg_instanceof($container, 'group')) {
			$return['filter'] = false;
		} else {
			// do not show button or select a tab when viewing someone else's posts
			$return['filter_context'] = 'none';
		}
	} else {
		$options['preload_containers'] = true;
		$return['filter_context'] = 'all';
		$return['title'] = elgg_echo('news:title:all_news');
		elgg_pop_breadcrumb();
		elgg_push_breadcrumb(elgg_echo('news:news'));
	}

	if (elgg_is_admin_logged_in() || ($container_guid && (elgg_instanceof($container, 'group')) && (($container->owner_guid == $current_user->guid) || (check_entity_relationship($current_user->guid, "group_admin", $container->guid))))) {
		elgg_register_title_button();
	}

	$return['content'] = elgg_list_entities($options);

	return $return;
}

/**
 * Get page components to show news with publish dates between $lower and $upper
 *
 * @param int $owner_guid The GUID of the owner of this page
 * @param int $lower      Unix timestamp
 * @param int $upper      Unix timestamp
 * @return array
 */
function news_get_page_content_archive($owner_guid, $lower = 0, $upper = 0) {

	$owner = get_entity($owner_guid);
	elgg_set_page_owner_guid($owner_guid);

	$crumbs_title = $owner->name;
	if (elgg_instanceof($owner, 'user')) {
		$url = "news/owner/{$owner->username}";
	} else {
		$url = "news/group/$owner->guid/all";
	}
	elgg_push_breadcrumb($crumbs_title, $url);
	elgg_push_breadcrumb(elgg_echo('news:archives'));

	if ($lower) {
		$lower = (int)$lower;
	}

	if ($upper) {
		$upper = (int)$upper;
	}

	$options = array(
		'type' => 'object',
		'subtype' => 'news',
		'full_view' => false,
		'no_results' => elgg_echo('news:none'),
		'preload_owners' => true,
		'distinct' => false,
	);

	if ($owner instanceof ElggGroup) {
		$options['container_guid'] = $owner_guid;
	} elseif ($owner instanceof ElggUser) {
		$options['owner_guid'] = $owner_guid;
	}

	if ($lower) {
		$options['created_time_lower'] = $lower;
	}

	if ($upper) {
		$options['created_time_upper'] = $upper;
	}

	$content = elgg_list_entities($options);

	$title = elgg_echo('date:month:' . date('m', $lower), array(date('Y', $lower)));

	return array(
		'content' => $content,
		'title' => $title,
		'filter' => '',
	);
}

/**
 * Get page components to edit/create a news post.
 *
 * @param string  $page     'edit' or 'new'
 * @param int     $guid     GUID of news post or container
 * @param int     $revision Annotation id for revision to edit (optional)
 * @return array
 */
function news_get_page_content_edit($page, $guid = 0, $revision = null) {

	elgg_require_js('news/save_draft');

	$return = array(
		'filter' => '',
	);

	$vars = array();
	$vars['id'] = 'news-post-edit';
	$vars['class'] = 'elgg-form-alt';

	$sidebar = '';
	if ($page == 'edit') {
		$news = get_entity((int)$guid);

		$title = elgg_echo('news:edit');

		if (elgg_instanceof($news, 'object', 'news') && $news->canEdit()) {
			$vars['entity'] = $news;

			$title .= ": \"$news->title\"";

			if ($revision) {
				$revision = elgg_get_annotation_from_id((int)$revision);
				$vars['revision'] = $revision;
				$title .= ' ' . elgg_echo('news:edit_revision_notice');

				if (!$revision || !($revision->entity_guid == $guid)) {
					$content = elgg_echo('news:error:revision_not_found');
					$return['content'] = $content;
					$return['title'] = $title;
					return $return;
				}
			}

			$body_vars = news_prepare_form_vars($news, $revision);

			elgg_push_breadcrumb($news->title, $news->getURL());
			elgg_push_breadcrumb(elgg_echo('edit'));

			elgg_require_js('news/save_draft');

			$content = elgg_view_form('news/save', $vars, $body_vars);
			$sidebar = elgg_view('news/sidebar/revisions', $vars);
		} else {
			$content = elgg_echo('news:error:cannot_edit_post');
		}
	} else {
		elgg_push_breadcrumb(elgg_echo('news:add'));
		$body_vars = news_prepare_form_vars(null);

		$title = elgg_echo('news:add');
		$content = elgg_view_form('news/save', $vars, $body_vars);
	}

	$return['title'] = $title;
	$return['content'] = $content;
	$return['sidebar'] = $sidebar;
	return $return;
}

/**
 * Pull together news variables for the save form
 *
 * @param ElggNews       $post
 * @param ElggAnnotation $revision
 * @return array
 */
function news_prepare_form_vars($post = null, $revision = null) {

	// input names => defaults
	$values = array(
		'title' => null,
		'description' => null,
		'status' => 'published',
		'access_id' => ACCESS_DEFAULT,
		'comments_on' => 'On',
		'excerpt' => null,
		'tags' => null,
		'container_guid' => null,
		'guid' => null,
		'draft_warning' => '',
	);

	if ($post) {
		foreach (array_keys($values) as $field) {
			if (isset($post->$field)) {
				$values[$field] = $post->$field;
			}
		}

		if ($post->status == 'draft') {
			$values['access_id'] = $post->future_access;
		}
	}

	if (elgg_is_sticky_form('news')) {
		$sticky_values = elgg_get_sticky_values('news');
		foreach ($sticky_values as $key => $value) {
			$values[$key] = $value;
		}
	}

	elgg_clear_sticky_form('news');

	if (!$post) {
		return $values;
	}

	// load the revision annotation if requested
	if ($revision instanceof ElggAnnotation && $revision->entity_guid == $post->getGUID()) {
		$values['revision'] = $revision;
		$values['description'] = $revision->value;
	}

	// display a notice if there's an autosaved annotation
	// and we're not editing it.
	$auto_save_annotations = $post->getAnnotations(array(
		'annotation_name' => 'news_auto_save',
		'limit' => 1,
	));
	if ($auto_save_annotations) {
		$auto_save = $auto_save_annotations[0];
	} else {
		$auto_save = false;
	}

	/* @var ElggAnnotation|false $auto_save */
	if ($auto_save && $revision && $auto_save->id != $revision->id) {
		$values['draft_warning'] = elgg_echo('news:messages:warning:draft');
	}

	return $values;
}

/**
 * Forward to the new style of URLs
 *
 * Pre-1.7.5
 * Group news page: /news/group:<container_guid>/
 * Group news view:  /news/group:<container_guid>/read/<guid>/<title>
 * 1.7.5-1.8
 * Group news page: /news/owner/group:<container_guid>/
 * Group news view:  /news/read/<guid>
 *
 *
 * @param string $page
 */
function news_url_forwarder($page) {

	$viewtype = elgg_get_viewtype();
	$qs = ($viewtype === 'default') ? "" : "?view=$viewtype";

	$url = "news/all";

	// easier to work with & no notices
	$page = array_pad($page, 4, "");

	// group usernames
	if (preg_match('~/group\:([0-9]+)/~', "/{$page[0]}/{$page[1]}/", $matches)) {
		$guid = $matches[1];
		$entity = get_entity($guid);
		if (elgg_instanceof($entity, 'group')) {
			if (!empty($page[2])) {
				$url = "news/view/$page[2]/";
			} else {
				$url = "news/group/$guid/all";
			}
			register_error(elgg_echo("changebookmark"));
			forward($url . $qs);
		}
	}

	if (empty($page[0])) {
		return;
	}

	// user usernames
	$user = get_user_by_username($page[0]);
	if (!$user) {
		return;
	}

	if (empty($page[1])) {
		$page[1] = 'owner';
	}

	switch ($page[1]) {
		case "read":
			$url = "news/view/{$page[2]}/{$page[3]}";
			break;
		case "archive":
			$url = "news/archive/{$page[0]}/{$page[2]}/{$page[3]}";
			break;
		case "new":
			$url = "news/add/$user->guid";
			break;
		case "owner":
			$url = "news/owner/{$page[0]}";
			break;
	}

	register_error(elgg_echo("changebookmark"));
	forward($url . $qs);
}
