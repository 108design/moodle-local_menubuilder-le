<?php
/**
 * lang/en/local_menubuilder.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */

$string['pluginname'] = 'Menu Builder';
$string['managedmenus'] = 'Menus';
$string['managedmenuname'] = 'Name';
$string['managedmenuname_help'] = 'Choose a name that helps you find this menu in the list and the menu block selector.';
$string['menudescription'] = 'Description';
$string['menudescription_help'] = 'Optional notes for administrators. The description is not shown in the rendered menu.';
$string['managedmenuadd'] = 'Create Menu';
$string['managedmenuedit'] = 'Edit Menu';
$string['managedmenuduplicate'] = 'Duplicate';
$string['managedmenucopyname'] = 'Copy of {$a}';
$string['managedmenuduplicated'] = 'The menu has been duplicated with a new key. It is not assigned to the main navigation.';
$string['managedmenudelete'] = 'Delete Menu';
$string['managedmenudeleteconfirm'] = ('Delete “{$a}”? Check your content for shortcodes first: the usage list '
    . 'does not search all Moodle texts. Shortcodes referring to a deleted menu '
    . 'render empty.');
$string['managedmenudeleteused'] = ('Remove the main navigation assignment and configured menu blocks before '
    . 'deleting this menu. Shortcodes in Moodle texts require a separate check.');
$string['managedmenudeleted'] = 'The menu has been deleted.';
$string['managedmenutopnav'] = 'Add to Main Navigation';
$string['managedmenuversionconflict'] = ('Someone saved this menu after you opened it. Your changes were not saved. '
    . 'Copy your JSON before reloading, review the current version and apply your '
    . 'changes again.');
$string['managedmenusintro'] = 'Create a central menu and reuse it in the main navigation, menu blocks and Moodle content.';
$string['managedmenuusageprimary'] = 'Main navigation (active)';
$string['managedmenuusageblockinstance'] = 'Menu block #{$a}';
$string['menukey'] = 'Key';
$string['menuusage'] = 'Known Uses';
$string['menunoknownusage'] = 'No main navigation assignment or configured menu block found. Check text shortcodes separately.';
$string['menuuse'] = 'Use This Menu';
$string['menublockuse'] = ('Select this menu in a Frontpage menu block, or copy its shortcode into '
    . 'content where the Menu Builder shortcode filter is enabled.');
$string['menusourcecentralgroup'] = 'Central menus';
$string['menujson'] = 'Menu JSON';
$string['menujsoninvalid'] = 'Check the menu JSON: {$a}';
$string['menusubmenutoggle'] = 'Open submenu: {$a}';
$string['moduleinactive'] = ('Menu management belongs to another host or needs ownership reconciliation. '
    . 'Existing ownership has been preserved.');
$string['unsupportedmigration'] = ('This installed build cannot be migrated with this package. Restore the '
    . 'matching installed package and review the installation source.');
$string['privacy'] = 'Privacy';
$string['privacy:metadata'] = ('Menu definitions and placements retain the identifiers of administrators who '
    . 'created or last changed them. Definitions can contain role shortnames and '
    . 'system cohort references.');
$string['menubuilder:manage'] = 'Manage central menus';
$string['pagehelp_menus_title'] = 'Menu Builder Help';
$string['pagehelp_more'] = 'More in the help panel';
$string['contexthelptopics'] = 'Help Topics';
$string['pagehelp_menubasics'] = 'Manage Menus';
$string['pagehelp_menubasics_help'] = 'Create, edit and duplicate central definitions. Saving changes updates every use of the menu.';
$string['pagehelp_menubasics_article'] = ('<p>Use <strong>Create Menu</strong> to add a name, description and JSON '
    . 'definition. Saving takes effect at every output location. '
    . '<strong>Duplicate</strong> creates a new key and leaves the copy unassigned '
    . 'to the main navigation.</p><p>The key identifies the menu in blocks and '
    . 'shortcodes. It stays unchanged when you rename or edit the menu. A stale '
    . 'editing form cannot overwrite a newer saved version. Copy your JSON before '
    . 'reloading to resolve a conflict.</p>');
$string['pagehelp_menujson'] = 'JSON Format and Examples';
$string['pagehelp_menujson_help'] = 'Use the canonical schema, stable item IDs and depths 0 to 2. The panel contains a complete example.';
$string['pagehelp_menujson_article'] = '<p>Copy the example into <strong>Menu JSON</strong> and adapt the titles and links. The document has an <code>items</code> list with up to 100 entries. Each ID must be unique and contain 8 to 64 letters, numbers, underscores or hyphens. IDs stay stable when editing. Depth starts at 0 and may increase by one level at a time, up to 2.</p><pre>{
  "schema": "108design-menubuilder/menu/1",
  "kind": "menu",
  "items": [
    {
      "id": "learning_root",
      "depth": 0,
      "title": "Learning",
      "source": {
        "type": "container"
      }
    },
    {
      "id": "learning_courses",
      "depth": 1,
      "title": "Courses",
      "source": {
        "type": "internal",
        "url": "/course/index.php"
      },
      "icon": "i/course"
    },
    {
      "id": "contact_team",
      "depth": 0,
      "title": "Contact",
      "source": {
        "type": "external",
        "url": "mailto:team@example.invalid"
      },
      "target": "self"
    }
  ]
}</pre><p>Use <code>internal</code> for a local <code>/path</code> or <code>#anchor</code>, <code>external</code> for HTTP(S) or mailto links, and <code>container</code> with no URL for an expandable parent. Optional fields are <code>icon</code> (for example <code>i/course</code>, <code>fa:solid:book</code> or <code>fa:brand:github</code>), <code>target</code> (<code>self</code> or <code>blank</code>), <code>showLabel</code> (boolean), and <code>visibility</code>. Available Moodle pix icons: i/home, i/dashboard, i/course, i/users, i/user, i/portfolio, i/news, i/files, i/folder, i/contentbank, i/email, i/notifications, i/link, i/location, i/language, i/search, i/settings, i/stats, i/report, i/payment, i/star, i/tip, i/info, i/questions, i/rss, i/calendar.</p><p>Omitted optional fields use their defaults. <code>presentation.submenu</code> accepts <code>standard</code>; extension objects must stay empty. Validation identifies invalid fields or entries. JSON is limited to 256 KiB. Select the JSON text to copy it.</p>';
$string['pagehelp_menutopnav'] = 'Main Navigation';
$string['pagehelp_menutopnav_help'] = ('Add this menu after Moodle’s native entries. Selecting another menu moves '
    . 'this one global assignment; blocks and shortcodes keep their own references.');
$string['pagehelp_menutopnav_article'] = ('<p><strong>Add to Main Navigation</strong> assigns exactly one menu to the '
    . 'main navigation. Selecting another menu changes the assignment without '
    . 'deleting either definition. Clear the checkbox to remove this '
    . 'assignment.</p><p>Moodle’s native entries retain their order and '
    . 'destinations. Your entries use native dropdowns, the More menu and mobile '
    . 'navigation. Linked parent labels navigate; their separate arrow opens the '
    . 'children. Container entries use the whole row to open their children.</p>');
$string['pagehelp_menuuse'] = 'Blocks and Shortcodes';
$string['pagehelp_menuuse_help'] = ('Reuse the same stable key in multiple menu blocks or filter-enabled texts. '
    . 'Their references remain independent of the main navigation assignment.');
$string['pagehelp_menuuse_article'] = ('<p>Choose the central menu in each <strong>Frontpage menu</strong> block. '
    . 'Compact is vertical; Wide is horizontal. Choose the profile to fit the actual '
    . 'space offered by your theme.</p><p>Copy '
    . '<code>[[frontpage-menu:your-menu-key]]</code> from <strong>Use This '
    . 'Menu</strong> into Moodle content. Enable the <strong>Menu Builder '
    . 'shortcode</strong> filter where Moodle processes that content. The filter '
    . 'runs in system, category, course, activity and block contexts. Moodle and '
    . 'your theme determine the available block regions and filtered text '
    . 'areas.</p><p>All references resolve the current definition. A missing or '
    . 'deleted definition renders empty.</p>');
$string['pagehelp_menuaccess'] = 'Visibility and Access';
$string['pagehelp_menuaccess_help'] = ('Visibility uses login state, site administrators, system roles and system '
    . 'cohorts. Destination permissions still apply.');
$string['pagehelp_menuaccess_article'] = (
        '<p><code>visibility.auth</code> accepts <code>any</code>, <code>guest</code> '
    . 'or <code>authenticated</code>. <code>admin: true</code> restricts an entry to '
    . 'site administrators. <code>roles</code> contains role shortnames assigned at '
    . 'system level; <code>cohorts</code> contains ID numbers of system '
    . 'cohorts.</p><p>Within each list, one matching reference is sufficient. '
    . 'Populated role and cohort lists must both match. Site administrators bypass '
    . 'role and cohort membership checks, but still follow the login condition. A '
    . 'role assigned only in a course does not count as a system role.</p><p>A '
    . 'hidden parent hides its branch. An empty container disappears. Menu '
    . 'visibility does not grant access to the destination: Moodle checks that '
    . 'separately.</p>'
    );
$string['pagehelp_menudelete'] = 'References and Deletion';
$string['pagehelp_menudelete_help'] = 'Known main navigation and block uses prevent deletion. Check shortcodes in your content separately.';
$string['pagehelp_menudelete_article'] = ('<p><strong>Known Uses</strong> lists the active main navigation assignment '
    . 'and configured menu blocks. It does not search all Moodle text fields for '
    . 'shortcodes. Before deletion, check the content where you used this '
    . 'key.</p><p>Clear the main navigation checkbox and remove or reconfigure the '
    . 'listed blocks. Then use <strong>Delete</strong> and confirm. The key is '
    . 'removed with the definition; remaining shortcodes render empty.</p><p>Keep '
    . 'JSON copies or use your normal Moodle database backups when you need '
    . 'recovery. Saving a menu does not create a separate revision archive.</p>');
