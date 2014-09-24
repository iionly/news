<?php
/**
 * News
 *
 * @package News
 *
 */

elgg_register_event_handler('init', 'system', 'news_init');

/**
 * Init news plugin.
 */
function news_init() {

	elgg_register_library('elgg:news', elgg_get_plugins_path() . 'news/lib/news.php');

	// add a site navigation item
	$item = new ElggMenuItem('news', elgg_echo('news:news'), 'news/all');
	elgg_register_menu_item('site', $item);

	elgg_register_event_handler('upgrade', 'upgrade', 'news_run_upgrades');

	// add to the main css
	elgg_extend_view('css/elgg', 'news/css');

	// register the news's JavaScript
	$news_js = elgg_get_simplecache_url('js', 'news/save_draft');
	elgg_register_js('elgg.news', $news_js);

	// routing of urls
	elgg_register_page_handler('news', 'news_page_handler');

	// override the default url to view a news object
	elgg_register_plugin_hook_handler('entity:url', 'object', 'news_set_url');

	// notifications
	elgg_register_notification_event('object', 'news', array('publish'));
	elgg_register_plugin_hook_handler('prepare', 'notification:publish:object:news', 'news_prepare_notification');

	// add news link to
	elgg_register_plugin_hook_handler('register', 'menu:owner_block', 'news_owner_block_menu');

	// Register for search.
	elgg_register_entity_type('object', 'news');

	// Add group option
	add_group_tool_option('news', elgg_echo('news:enablenews'), true);
	elgg_extend_view('groups/tool_latest', 'news/group_module');

	// add news in groups widget
	elgg_register_widget_type("news_in_groups", elgg_echo("news:news_in_groups:title"), elgg_echo("news:news_in_groups:description"), array("profile","index","dashboard"), true);
	elgg_extend_view("css/elgg", "widgets/news_in_groups/css");
	elgg_extend_view("js/elgg", "widgets/news_in_groups/js");

	if (elgg_is_active_plugin('widget_manager')) {
		//add index widget for Widget Manager plugin
		elgg_register_widget_type('index_news', elgg_echo("news:news"), elgg_echo('news:widget:description'), array("index"));

		//add groups widget for Widget Manager plugin
		elgg_register_widget_type('groups_news', elgg_echo("news:news"), elgg_echo('news:widget:description'), array("groups"));

		//register title urls for widgets
		elgg_register_plugin_hook_handler("entity:url", "object", "news_widget_urls");
	}

	// register actions
	$action_path = elgg_get_plugins_path() . 'news/actions/news';
	elgg_register_action('news/save', "$action_path/save.php");
	elgg_register_action('news/auto_save_revision', "$action_path/auto_save_revision.php");
	elgg_register_action('news/delete', "$action_path/delete.php");

	// entity menu
	elgg_register_plugin_hook_handler('register', 'menu:entity', 'news_entity_menu_setup');

	// ecml
	elgg_register_plugin_hook_handler('get_views', 'ecml', 'news_ecml_views_hook');
}

/**
 * Dispatches news pages.
 * URLs take the form of
 *  All news:          news/all
 *  User's news:       news/owner/<username>
 *  User's archives:   news/archives/<username>/<time_start>/<time_stop>
 *  A news post:       news/view/<guid>/<title>
 *  New news post:     news/add/<guid>
 *  Edit news post:    news/edit/<guid>/<revision>
 *  Preview news post: news/preview/<guid>
 *  Group news:        news/group/<guid>/all
 *
 * Title is ignored
 *
 * @param array $page
 * @return bool
 */
function news_page_handler($page) {

	elgg_load_library('elgg:news');

	// forward to correct URL for news pages pre-1.8
	news_url_forwarder($page);

	// push all news breadcrumb
	elgg_push_breadcrumb(elgg_echo('news:news'), "news/all");

	if (!isset($page[0])) {
		$page[0] = 'all';
	}

	$page_type = $page[0];
	switch ($page_type) {
		case 'owner':
			$user = get_user_by_username($page[1]);
			if (!$user) {
				forward('', '404');
			}
			$params = news_get_page_content_list($user->guid);
			break;
		case 'archive':
			$user = get_user_by_username($page[1]);
			if (!$user) {
				forward('', '404');
			}
			$params = news_get_page_content_archive($user->guid, $page[2], $page[3]);
			break;
		case 'view':
			$params = news_get_page_content_read($page[1]);
			break;
		case 'read': // Elgg 1.7 compatibility
			register_error(elgg_echo("changebookmark"));
			forward("news/view/{$page[1]}");
			break;
		case 'add':
			elgg_gatekeeper();
			$current_user_guid = elgg_get_logged_in_user_guid();
			$container = get_entity($page[1]);
			if (((elgg_instanceof($container, 'group')) && (($current_user_guid == $container->owner_guid) || (check_entity_relationship($current_user_guid, "group_admin", $container->guid)))) || elgg_is_admin_logged_in()) {
				$params = news_get_page_content_edit($page_type, $page[1]);
			} else {
				forward(REFERER);
			}
			break;
		case 'edit':
			elgg_gatekeeper();
			$current_user = elgg_get_logged_in_user_entity();
			$news = get_entity($page[1]);
			if (((elgg_instanceof($news, 'object', 'news')) && ($current_user->canEdit())) || elgg_is_admin_logged_in()) {
				$params = news_get_page_content_edit($page_type, $page[1], $page[2]);
			} else {
				forward(REFERER);
			}
			break;
		case 'group':
			$group = get_entity($page[1]);
			if (!elgg_instanceof($group, 'group')) {
				forward('', '404');
			}
			if (!isset($page[2]) || $page[2] == 'all') {
				$params = news_get_page_content_list($page[1]);
			} else {
				$params = news_get_page_content_archive($page[1], $page[3], $page[4]);
			}
			break;
		case 'all':
			$params = news_get_page_content_list();
			break;
		default:
			return false;
	}

	if (isset($params['sidebar'])) {
		$params['sidebar'] .= elgg_view('news/sidebar', array('page' => $page_type));
	} else {
		$params['sidebar'] = elgg_view('news/sidebar', array('page' => $page_type));
	}

	$body = elgg_view_layout('content', $params);

	echo elgg_view_page($params['title'], $body);
	return true;
}

/**
 * Format and return the URL for news.
 *
 * @param string $hook
 * @param string $type
 * @param string $url
 * @param array  $params
 * @return string URL of news.
 */
function news_set_url($hook, $type, $url, $params) {
	$entity = $params['entity'];
	if (elgg_instanceof($entity, 'object', 'news')) {
		$friendly_title = elgg_get_friendly_title($entity->title);
		return "news/view/{$entity->guid}/$friendly_title";
	}
}

/**
 * Add a menu item to an ownerblock
 */
function news_owner_block_menu($hook, $type, $return, $params) {
	if (elgg_instanceof($params['entity'], 'user') && elgg_is_admin_logged_in()) {
		$url = "news/owner/{$params['entity']->username}";
		$item = new ElggMenuItem('news', elgg_echo('news'), $url);
		$return[] = $item;
	} else {
		if (($params['entity']->news_enable != "no") && (((elgg_get_logged_in_user_guid() == $params['entity']->owner_guid) || (check_entity_relationship(elgg_get_logged_in_user_guid(), "group_admin", $params['entity']->guid))) || elgg_is_admin_logged_in)) {
			$url = "news/group/{$params['entity']->guid}/all";
			$item = new ElggMenuItem('news', elgg_echo('news:group'), $url);
			$return[] = $item;
		}
	}

	return $return;
}

/**
 * Add particular news links/info to entity menu
 */
function news_entity_menu_setup($hook, $type, $return, $params) {
	if (elgg_in_context('widgets')) {
		return $return;
	}

	$entity = $params['entity'];
	$handler = elgg_extract('handler', $params, false);
	if ($handler != 'news') {
		return $return;
	}

	if ($entity->status != 'published') {
		// draft status replaces access
		foreach ($return as $index => $item) {
			if ($item->getName() == 'access') {
				unset($return[$index]);
			}
		}

		$status_text = elgg_echo("news:status:{$entity->status}");
		$options = array(
			'name' => 'published_status',
			'text' => "<span>$status_text</span>",
			'href' => false,
			'priority' => 150,
		);
		$return[] = ElggMenuItem::factory($options);
	}

	return $return;
}

/**
 * Prepare a notification message about a published news
 *
 * @param string                          $hook         Hook name
 * @param string                          $type         Hook type
 * @param Elgg_Notifications_Notification $notification The notification to prepare
 * @param array                           $params       Hook parameters
 * @return Elgg_Notifications_Notification
 */
function news_prepare_notification($hook, $type, $notification, $params) {

	$entity = $params['event']->getObject();

	if ((elgg_instanceof($entity, 'object', 'news')) && ($entity->status == 'published')) {

		$owner = $params['event']->getActor();
		$recipient = $params['recipient'];
		$language = $params['language'];
		$method = $params['method'];

		$notification->subject = elgg_echo('news:notify:subject', array($entity->title), $language);
		$notification->body = elgg_echo('news:notify:body', array(
									$owner->name,
									$entity->title,
									$entity->getExcerpt(),
									$entity->getURL()
								), $language);
		$notification->summary = elgg_echo('news:notify:summary', array($entity->title), $language);

		return $notification;
	}
}

/**
 * Register news with ECML.
 */
function news_ecml_views_hook($hook, $entity_type, $return_value, $params) {
	$return_value['object/news'] = elgg_echo('news:news');

	return $return_value;
}

function news_widget_urls($hook_name, $entity_type, $return_value, $params){
	$result = $return_value;
	$widget = $params["entity"];

	if(empty($result) && ($widget instanceof ElggWidget)) {
		$owner = $widget->getOwnerEntity();
		switch($widget->handler) {
			case "index_news":
				$result = "/news/all";
				break;
			case "groups_news":
				if($owner instanceof ElggGroup){
					$result = "/news/group/{$owner->guid}/all";
				} else {
					$result = "/news/all";
				}
				break;
		}
	}
	return $result;
}

/**
 * Upgrade from 1.7 to 1.8.
 */
function news_run_upgrades($event, $type, $details) {
	$news_upgrade_version = elgg_get_plugin_setting('upgrade_version', 'news');

	if (!$news_upgrade_version) {
		 // When upgrading, check if the ElggNews class has been registered as this
		 // was added in Elgg 1.8
		if (!update_subtype('object', 'news', 'ElggNews')) {
			add_subtype('object', 'news', 'ElggNews');
		}

		// add the status metadata to all news entities and make them all published
		$options = array('type' => 'object', 'subtype' => 'news', 'limit' => false);
		$old_news = new ElggBatch('elgg_get_entities', $options);
		foreach($old_news as $news) {
			if (!$news->status) {
				$news->status = 'published';
				$news->save();
			}
		}

		elgg_set_plugin_setting('upgrade_version', 1, 'news');
	}
}
