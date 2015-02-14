<?php
/**
 * Register the ElggNews class for the object/news subtype
 */

if (get_subtype_id('object', 'news')) {
	update_subtype('object', 'news', 'ElggNews');
} else {
	add_subtype('object', 'news', 'ElggNews');
}
