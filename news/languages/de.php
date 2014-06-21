<?php
/**
 * News German language file
 *
 */

return array(
	'news' => 'Neuigkeiten',
	'news:news' => 'Neuigkeiten',
	'news:revisions' => 'Revisionen',
	'news:archives' => 'Ältere Neuigkeiten',
	'item:object:news' => 'Neuigkeiten',

	'news:title:user_news' => 'Neuigkeiten von %s',
	'news:title:all_news' => 'Alle Neuigkeiten der Community',

	'news:group' => 'Gruppen-Neuigkeiten',
	'news:enablenews' => 'Gruppen-Neuigkeiten aktivieren',
	'news:write' => 'Neuigkeiten-Eintrag verfassen',

	// Editing
	'news:add' => 'Neuigkeiten-Eintrag hinzufügen',
	'news:edit' => 'Neuigkeiten-Eintrag bearbeiten',
	'news:excerpt' => 'Auszug',
	'news:body' => 'Text der Neuigkeit',
	'news:save_status' => 'Zuletzt gespeichert: ',
	'news:never' => 'Nie',

	// Statuses
	'news:status' => 'Status',
	'news:status:draft' => 'Entwurf',
	'news:status:published' => 'Veröffentlicht',
	'news:status:unsaved_draft' => 'Nicht-gespeicherter Entwurf',

	'news:revision' => 'Revision',
	'news:auto_saved_revision' => 'Automatisch gespeicherte Revision',

	// messages
	'news:message:saved' => 'Die Neuigkeit wurde gespeichert.',
	'news:error:cannot_save' => 'Beim Speichern der Neuigkeit ist ein Fehler aufgetreten.',
	'news:error:cannot_auto_save' => 'Beim automatischen Speichern der Neuigkeit ist ein Fehler aufgetreten.',
	'news:error:cannot_write_to_container' => 'Keine ausreichenden Zugriffsrechte zum Speichern der Gruppen-Neuigkeit vorhanden.',
	'news:messages:warning:draft' => 'Die Entwurfsversion dieser Neuigkeit wurde nocht nicht gespeichert!',
	'news:edit_revision_notice' => '(Alte Version)',
	'news:message:deleted_post' => 'Die Neuigkeit wurde gelöscht.',
	'news:error:cannot_delete_post' => 'Beim Löschen der Neuigkeit ist ein Fehler aufgetreten.',
	'news:none' => 'Es wurden noch keine Neuigkeiten-Einträge erstellt.',
	'news:error:missing:title' => 'Bitte gebe einen Titel für die Neuigkeit ein!',
	'news:error:missing:description' => 'Bitte gebe den Text der Neuigkeit ein!',
	'news:error:cannot_edit_post' => 'Diese Neuigkeit scheint nicht vorhanden zu sein oder Du hast möglicherweise nicht die notwendigen Zugriffrechte, um sie zu editieren.',
	'news:error:post_not_found' => 'Dieser Neuigkeiten-Eintrag ist nicht verfügbar.',
	'news:error:revision_not_found' => 'Diese Revision ist nicht verfügbar.',

	// river
	'river:create:object:news' => '%s veröffentlichte die Neuigkeit %s',
	'river:comment:object:news' => '%s kommentierte die Neuigkeit %s',

	// notifications
	'news:notify:summary' => 'Ein neuer Neuigkeiten-Eintrag mit dem Titel %s wurde erstellt',
	'news:notify:subject' => 'Ein neuer Neuigkeiten-Eintrag: %s',
	'news:notify:body' =>
'
%s veröffentlichte einen Neuigkeiten-Eintrag: %s

%s

Schau Dir die Neuigkeit an und schreibe einen Kommentar:
%s
',

	// widgets
	'news:widget:description' => 'Dieses Widget zeigt die letzten Neuigkeiten an.',
	'news:numbertodisplay' => 'Anzahl der anzuzeigenden Neuigkeiten',

	"news:news_in_groups:title" => "Gruppen-Neugigkeiten",
	"news:news_in_groups:description" => "Zeige die letzten Neuigkeiten aus verschiedenen Gruppen an.",
	"news:news_in_groups:no_projects" => "Es wurden noch keine Gruppen konfiguriert.",
	"news:news_in_groups:no_news" => "In dieser Gruppe wurden noch keine Neuigkeiten veröffentlicht.",
	"news:news_in_groups:settings:project" => "Gruppe",
	"news:news_in_groups:settings:no_project" => "Wähle eine Gruppe",
	"news:news_in_groups:settings:news_count" => "Maximale Anzahl von Neuigkeiten",
	"news:news_in_groups:settings:group_icon_size" => "Größe des Gruppen-Icons",
	"news:news_in_groups:settings:group_icon_size:small" => "Klein",
	"news:news_in_groups:settings:group_icon_size:medium" => "Mittel"
);