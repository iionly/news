<?php
/**
 * Save news entity
 *
 * Can be called by clicking save button or preview button. If preview button,
 * we automatically save as draft. The preview button is only available for
 * non-published drafts.
 *
 * Drafts are saved with the access set to private.
 *
 * @package News
 */

// start a new sticky form session in case of failure
elgg_make_sticky_form('news');

// save or preview
$save = (bool)get_input('save');

// store errors to pass along
$error = false;
$error_forward_url = REFERER;
$user = elgg_get_logged_in_user_entity();

// edit or create a new entity
$guid = get_input('guid');

if ($guid) {
	$entity = get_entity($guid);
	if (elgg_instanceof($entity, 'object', 'news') && $entity->canEdit()) {
		$news = $entity;
	} else {
		register_error(elgg_echo('news:error:post_not_found'));
		forward(get_input('forward', REFERER));
	}

	// save some data for revisions once we save the new edit
	$revision_text = $news->description;
	$new_post = $news->new_post;
} else {
	$news = new ElggNews();
	$news->subtype = 'news';
	$new_post = true;
}

// set the previous status for the hooks to update the time_created and river entries
$old_status = $news->status;

// set defaults and required values.
$values = array(
	'title' => '',
	'description' => '',
	'status' => 'draft',
	'access_id' => ACCESS_DEFAULT,
	'comments_on' => 'On',
	'excerpt' => '',
	'tags' => '',
	'container_guid' => (int)get_input('container_guid'),
);

// fail if a required entity isn't set
$required = array('title', 'description');

// load from POST and do sanity and access checking
foreach ($values as $name => $default) {
	if ($name === 'title') {
		$value = htmlspecialchars(get_input('title', $default, false), ENT_QUOTES, 'UTF-8');
	} else {
		$value = get_input($name, $default);
	}

	if (in_array($name, $required) && empty($value)) {
		$error = elgg_echo("news:error:missing:$name");
	}

	if ($error) {
		break;
	}

	switch ($name) {
		case 'tags':
			$values[$name] = string_to_tag_array($value);
			break;

		case 'excerpt':
			if ($value) {
				$values[$name] = elgg_get_excerpt($value);
			}
			break;

		case 'container_guid':
			// this can't be empty or saving the base entity fails
			if (!empty($value)) {
				$container = get_entity($value);
				if ($container && $container->canWriteToContainer(0, 'object', 'news')) {
					$values[$name] = $value;
				} else {
					$error = elgg_echo("news:error:cannot_write_to_container");
				}
			} else {
				unset($values[$name]);
			}
			break;

		default:
			$values[$name] = $value;
			break;
	}
}

// if preview, force status to be draft
if ($save == false) {
	$values['status'] = 'draft';
}

// if draft, set access to private and cache the future access
if ($values['status'] == 'draft') {
	$values['future_access'] = $values['access_id'];
	$values['access_id'] = ACCESS_PRIVATE;
}

// assign values to the entity, stopping on error.
if (!$error) {
	foreach ($values as $name => $value) {
		$news->$name = $value;
	}
}

// only try to save base entity if no errors
if (!$error) {
	if ($news->save()) {
		// remove sticky form entries
		elgg_clear_sticky_form('news');

		// remove autosave draft if exists
		$news->deleteAnnotations('news_auto_save');

		// no longer a brand new post.
		$news->deleteMetadata('new_post');

		// if this was an edit, create a revision annotation
		if (!$new_post && $revision_text) {
			$news->annotate('news_revision', $revision_text);
		}

		system_message(elgg_echo('news:message:saved'));

		$status = $news->status;

		// add to river if changing status or published, regardless of new post
		// because we remove it for drafts.
		if (($new_post || $old_status == 'draft') && $status == 'published') {
			elgg_create_river_item(array(
				'view' => 'river/object/news/create',
				'action_type' => 'create',
				'subject_guid' => $news->owner_guid,
				'object_guid' => $news->getGUID(),
			));

			elgg_trigger_event('publish', 'object', $news);

			// reset the creation time for news posts that move from draft to published
			if ($guid) {
				$news->time_created = time();
				$news->save();
			}
		} elseif ($old_status == 'published' && $status == 'draft') {
			$access = elgg_set_ignore_access(true);
			$access_status = access_get_show_hidden_status();
			access_show_hidden_entities(true);

			$river_items = new ElggBatch('elgg_get_river', array(
				'object_guid' => $news->guid,
				'action_type' => 'create',
				'limit' => false,
			));
			$river_items->setIncrementOffset(false);
			foreach ($river_items as $river_item) {
				$river_item->delete();
			}
			access_show_hidden_entities($access_status);
			elgg_set_ignore_access($access);
		}

		if ($news->status == 'published' || $save == false) {
			forward($news->getURL());
		} else {
			forward("news/edit/$news->guid");
		}
	} else {
		register_error(elgg_echo('news:error:cannot_save'));
		forward($error_forward_url);
	}
} else {
	register_error($error);
	forward($error_forward_url);
}