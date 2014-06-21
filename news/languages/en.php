<?php
/**
 * News English language file
 *
 */

return array(
	'news' => 'News',
	'news:news' => 'News',
	'news:revisions' => 'Revisions',
	'news:archives' => 'News archives',
	'item:object:news' => 'News',

	'news:title:user_news' => '%s\'s news',
	'news:title:all_news' => 'All site news',

	'news:group' => 'Group news',
	'news:enablenews' => 'Enable group news',
	'news:write' => 'Write a news post',

	// Editing
	'news:add' => 'Add news post',
	'news:edit' => 'Edit news post',
	'news:excerpt' => 'Excerpt',
	'news:body' => 'Body',
	'news:save_status' => 'Last saved: ',
	'news:never' => 'Never',

	// Statuses
	'news:status' => 'Status',
	'news:status:draft' => 'Draft',
	'news:status:published' => 'Published',
	'news:status:unsaved_draft' => 'Unsaved Draft',

	'news:revision' => 'Revision',
	'news:auto_saved_revision' => 'Auto Saved Revision',

	// messages
	'news:message:saved' => 'News post saved.',
	'news:error:cannot_save' => 'Cannot save news post.',
	'news:error:cannot_auto_save' => 'Cannot automatically save news post.',
	'news:error:cannot_write_to_container' => 'Insufficient access to save news to group.',
	'news:messages:warning:draft' => 'There is an unsaved draft of this post!',
	'news:edit_revision_notice' => '(Old version)',
	'news:message:deleted_post' => 'News post deleted.',
	'news:error:cannot_delete_post' => 'Cannot delete news post.',
	'news:none' => 'There haven\'t any news been posted yet.',
	'news:error:missing:title' => 'Please enter a news title!',
	'news:error:missing:description' => 'Please enter the body of your news!',
	'news:error:cannot_edit_post' => 'This post may not exist or you may not have permissions to edit it.',
	'news:error:post_not_found' => 'Cannot find specified news post.',
	'news:error:revision_not_found' => 'Cannot find this revision.',

	// river
	'river:create:object:news' => '%s published a news post %s',
	'river:comment:object:news' => '%s commented on the news %s',

	// notifications
	'news:notify:summary' => 'New news post called %s',
	'news:notify:subject' => 'New news post: %s',
	'news:notify:body' =>
'
%s made a new news post: %s

%s

View and comment on the news post:
%s
',

	// widgets
	'news:widget:description' => 'This widget lists the most recent news.',
	'news:numbertodisplay' => 'Number of news to display',

	"news:news_in_groups:title" => "News in Groups",
	"news:news_in_groups:description" => "Shows latest news from various groups.",
	"news:news_in_groups:no_projects" => "No groups configured yet.",
	"news:news_in_groups:no_news" => "No news for this group.",
	"news:news_in_groups:settings:project" => "Group",
	"news:news_in_groups:settings:no_project" => "Select a group",
	"news:news_in_groups:settings:news_count" => "Max number of news",
	"news:news_in_groups:settings:group_icon_size" => "Group icon size",
	"news:news_in_groups:settings:group_icon_size:small" => "Small",
	"news:news_in_groups:settings:group_icon_size:medium" => "Medium"
);