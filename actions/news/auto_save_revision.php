<?php
/**
 * Action called by AJAX periodic auto saving when editing.
 *
 * @package News
 */

$guid = get_input('guid');
$user = elgg_get_logged_in_user_entity();
$title = htmlspecialchars(get_input('title', '', false), ENT_QUOTES, 'UTF-8');
$description = get_input('description');
$excerpt = get_input('excerpt');

// because get_input() doesn't use the default if the input is ''
if (empty($excerpt)) {
	$excerpt = $description;
}

// store errors to pass along
$error = false;

if ($title && $description) {

	if ($guid) {
		$entity = get_entity($guid);
		if (elgg_instanceof($entity, 'object', 'news') && $entity->canEdit()) {
			$news = $entity;
		} else {
			$error = elgg_echo('news:error:post_not_found');
		}
	} else {
		$news = new ElggNews();
		$news->subtype = 'news';

		// force draft and private for autosaves.
		$news->status = 'unsaved_draft';
		$news->access_id = ACCESS_PRIVATE;
		$news->title = $title;
		$news->description = $description;
		$news->excerpt = elgg_get_excerpt($excerpt);

		// mark this as a brand new post so we can work out the
		// river / revision logic in the real save action.
		$news->new_post = true;

		if (!$news->save()) {
			$error = elgg_echo('news:error:cannot_save');
		}
	}

	// create draft annotation
	if (!$error) {
		// annotations don't have a "time_updated" so
		// we have to delete everything or the times are wrong.

		// don't save if nothing changed
		$auto_save_annotations = $news->getAnnotations(array(
			'annotation_name' => 'news_auto_save',
			'limit' => 1,
		));
		if ($auto_save_annotations) {
			$auto_save = $auto_save_annotations[0];
		} else {
			$auto_save = false;
		}

		if (!$auto_save) {
			$annotation_id = $news->annotate('news_auto_save', $description);
		} elseif ($auto_save instanceof ElggAnnotation && $auto_save->value != $description) {
			$news->deleteAnnotations('news_auto_save');
			$annotation_id = $news->annotate('news_auto_save', $description);
		} elseif ($auto_save instanceof ElggAnnotation && $auto_save->value == $description) {
			// this isn't an error because we have an up to date annotation.
			$annotation_id = $auto_save->id;
		}

		if (!$annotation_id) {
			$error = elgg_echo('news:error:cannot_auto_save');
		}
	}
} else {
	$error = elgg_echo('news:error:missing:description');
}

if ($error) {
	$json = array('success' => false, 'message' => $error);
	echo json_encode($json);
} else {
	$msg = elgg_echo('news:message:saved');
	$json = array('success' => true, 'message' => $msg, 'guid' => $news->getGUID());
	echo json_encode($json);
}
exit;
