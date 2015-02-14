News plugin for Elgg 1.10
Latest Version: 1.10.3
Released: 2015-02-14
Contact: iionly@gmx.de
License: GNU General Public License version 2
Copyright: (c) iionly


The News plugin allows to make news postings or announcements either site-wide or group-specific. This News plugin is very similar to the Blog plugin bundled with Elgg core (actually it's based on it). Therefore, the posting of news works still quite in the same way as posting a blog but the news will be displayed separately from the blogs postings to indicate that they are kind of different (meant as news or site announcement and not as a blog posting only). Additionally, the difference is that only admins and group owners can make news postings. Group owners can only make news postings within a group they own while admins can post a news both within groups and side-wide.

The News plugin also contains an index page News widget and a News widget for group pages, if you (optionally) use the Widget Manager plugin.

This News plugin might also be able to replace other former News plugins as long as they were also based on the Blog plugin, use the subtype "news" for the news objects (and have not been modified regarding the data structure of the news objects).


Installation:

(0. If you have a previous version of the news plugin installed, disable the plugin on your site and remove the news plugin folder from your mod directory completely before installing the new version,)
1. Copy the news plugin folder into you mod folder,
2. Enable the News plugin in the admin section of your site.


Changelog:

1.10.3:

- Updated for Elgg 1.10,
- Fixing of deprecation issues,
- Register JS as AMD module.

1.9.2:

- Same as in version 1.8.2 to work on Elgg 1.9,
- Fixed a deprecation issue (with the hopefully soon to be released Widget Manager plugin for Elgg 1.9) with widgets urls (index page and group pages).

1.8.2:

- Group pages of public groups showing up without error again if visitor is not logged in and news widget is included in page (either with or without Widget Manager plugin used).

1.9.1:

- Updated version 1.8.1 for Elgg 1.9.

1.8.1:

- Allow group admins (as defined by the use of the group_tools plugin) to add/edit group news,
- New widget for profile pages, dashboard and index page that displays the latest news in groups using a slider (originally from the Group Tools plugin).

1.9.0:

- Updated version 1.8.0 for Elgg 1.9.

1.8.0:

- Initial release.
