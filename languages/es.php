<?php
/**
 * News Spanish language file
 *
 */

return array(
	'news' => 'Noticias',
	'news:news' => 'Noticias',
	'news:revisions' => 'Revisiones',
	'news:archives' => 'Archivo de noticias',
	'item:object:news' => 'Noticias',

	'news:title:user_news' => 'Noticias de %s',
	'news:title:all_news' => 'Noticias del sitio',

	'news:group' => 'Noticias del grupo',
	'news:enablenews' => 'Permitir noticias de grupo',
	'news:write' => 'Escribir una noticia',

	// Editing
	'news:add' => 'Añadir una noticia',
	'news:edit' => 'Editar noticia',
	'news:excerpt' => 'Extracto',
	'news:body' => 'Cuerpo',
	'news:save_status' => 'Último guardado',
	'news:never' => 'Nunca',

	// Statuses
	'news:status' => 'Estado',
	'news:status:draft' => 'Borrador',
	'news:status:published' => 'Publicada',
	'news:status:unsaved_draft' => 'Borrador no guardado',

	'news:revision' => 'Revisión',
	'news:auto_saved_revision' => 'Revisión autoguardada',

	// messages
	'news:message:saved' => 'Noticia guardada.',
	'news:error:cannot_save' => 'No se ha podido guardar la noticia',
	'news:error:cannot_auto_save' => 'No se puede guardar automáticamente.',
	'news:error:cannot_write_to_container' => 'Permisos insuficientes para guardar las noticias del grupo',
	'news:messages:warning:draft' => 'Hay un borrador no guardado de esta noticia!',
	'news:edit_revision_notice' => '(Versión antigua)',
	'news:message:deleted_post' => 'Noticia borrada.',
	'news:error:cannot_delete_post' => 'No se ha podido borrar la noticia.',
	'news:none' => 'No hay noticias publicadas.',
	'news:error:missing:title' => 'Por favor introduzca el título de la noticia!',
	'news:error:missing:description' => 'Por favor añada el cuerpo de la noticia.',
	'news:error:cannot_edit_post' => 'Puede que este envío no exista o usted no tenga permisos para editarlo.',
	'news:error:post_not_found' => 'No se ha encontrado la noticia especificada.',
	'news:error:revision_not_found' => 'No se ha podido encontrar esta revisión.',

	// river
	'river:create:object:news' => '%s ha publicado una noticia %s',
	'river:comment:object:news' => '%s ha comentado en la noticia %s',

	// notifications
	'news:notify:summary' => 'Nueva noticia con título %s',
	'news:notify:subject' => 'Nueva noticia: %s',
	'news:notify:body' =>
'
%s ha creado una nueva noticia: %s

%s

Ver y comentar la noticia:
%s
',

	// widgets
	'news:widget:description' => 'Este widget muestra las noticias más recientes.',
	'news:numbertodisplay' => 'Número de noticias a mostrar',

	"news:news_in_groups:title" => "Noticias en grupos",
	"news:news_in_groups:description" => "Mostrar las últimas noticias de varios grupos.",
	"news:news_in_groups:no_projects" => "Aún no se han configurado grupos.",
	"news:news_in_groups:no_news" => "No hay noticias en este grupo.",
	"news:news_in_groups:settings:project" => "Grupo",
	"news:news_in_groups:settings:no_project" => "Selecciona un grupo",
	"news:news_in_groups:settings:news_count" => "Máximo número de noticias",
	"news:news_in_groups:settings:group_icon_size" => "Tamaño del icono de grupo",
	"news:news_in_groups:settings:group_icon_size:small" => "Pequeño",
	"news:news_in_groups:settings:group_icon_size:medium" => "Mediano"
);