<?php
/**
 * lang/de/local_menubuilder.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */

$string['pluginname'] = 'Menu Builder';
$string['managedmenus'] = 'Menüs';
$string['managedmenuname'] = 'Name';
$string['managedmenuname_help'] = 'Wähle einen Namen, unter dem Du das Menü in der Liste und in der Menüblockauswahl wiederfindest.';
$string['menudescription'] = 'Beschreibung';
$string['menudescription_help'] = 'Optionale Notizen für die Verwaltung. Die Beschreibung erscheint nicht im ausgegebenen Menü.';
$string['managedmenuadd'] = 'Menü anlegen';
$string['managedmenuedit'] = 'Menü bearbeiten';
$string['managedmenuduplicate'] = 'Duplizieren';
$string['managedmenucopyname'] = 'Kopie von {$a}';
$string['managedmenuduplicated'] = 'Das Menü wurde mit einem neuen Schlüssel dupliziert. Es ist nicht der Hauptnavigation zugewiesen.';
$string['managedmenudelete'] = 'Menü löschen';
$string['managedmenudeleteconfirm'] = ('„{$a}“ löschen? Prüfe vorher Deine Inhalte auf Shortcodes: Die '
    . 'Nutzungsliste durchsucht nicht alle Moodle-Texte. Shortcodes eines '
    . 'gelöschten Menüs bleiben leer.');
$string['managedmenudeleteused'] = ('Entferne vor dem Löschen die Hauptnavigationszuweisung und die '
    . 'konfigurierten Menüblöcke. Shortcodes in Moodle-Texten musst Du gesondert '
    . 'prüfen.');
$string['managedmenudeleted'] = 'Das Menü wurde gelöscht.';
$string['managedmenutopnav'] = 'Hauptnavigation ergänzen';
$string['managedmenuversionconflict'] = ('Jemand hat das Menü nach dem Öffnen gespeichert. Deine Änderungen wurden '
    . 'nicht gespeichert. Kopiere Dein JSON vor dem Neuladen, prüfe den aktuellen '
    . 'Stand und übertrage Deine Änderungen erneut.');
$string['managedmenusintro'] = ('Lege ein zentrales Menü an und verwende es in der Hauptnavigation, in '
    . 'Menüblöcken und in Moodle-Inhalten.');
$string['managedmenuusageprimary'] = 'Hauptnavigation (aktiv)';
$string['managedmenuusageblockinstance'] = 'Menüblock #{$a}';
$string['menukey'] = 'Schlüssel';
$string['menuusage'] = 'Bekannte Verwendungen';
$string['menunoknownusage'] = ('Keine Hauptnavigationszuweisung und kein konfigurierter Menüblock gefunden. '
    . 'Prüfe Text-Shortcodes gesondert.');
$string['menuuse'] = 'Menü verwenden';
$string['menublockuse'] = ('Wähle dieses Menü in einem Frontpage-Menüblock oder kopiere seinen '
    . 'Shortcode in Inhalte, für die der Menü-Builder-Shortcode-Filter aktiviert '
    . 'ist.');
$string['menusourcecentralgroup'] = 'Zentrale Menüs';
$string['menujson'] = 'Menü-JSON';
$string['menujsoninvalid'] = 'Prüfe das Menü-JSON: {$a}';
$string['menusubmenutoggle'] = 'Untermenü öffnen: {$a}';
$string['moduleinactive'] = ('Die Menüverwaltung gehört einem anderen Host oder benötigt einen Abgleich '
    . 'der Eigentümerschaft. Die vorhandene Zuordnung wurde beibehalten.');
$string['unsupportedmigration'] = ('Dieser installierte Build lässt sich mit diesem Paket nicht migrieren. '
    . 'Stelle das passende installierte Paket wieder her und prüfe die '
    . 'Installationsquelle.');
$string['privacy'] = 'Datenschutz';
$string['privacy:metadata'] = ('Menüdefinitionen und Platzierungen speichern die Kennungen der anlegenden '
    . 'und zuletzt bearbeitenden Administratoren. Definitionen können '
    . 'Rollen-Kurznamen und Verweise auf globale Gruppen im Systemkontext enthalten.');
$string['menubuilder:manage'] = 'Zentrale Menüs verwalten';
$string['pagehelp_menus_title'] = 'Menu-Builder-Hilfe';
$string['pagehelp_more'] = 'Mehr dazu im Hilfepanel';
$string['contexthelptopics'] = 'Hilfethemen';
$string['pagehelp_menubasics'] = 'Menüs verwalten';
$string['pagehelp_menubasics_help'] = ('Lege zentrale Definitionen an, bearbeite und dupliziere sie. Speichern wirkt '
    . 'an jeder Verwendungsstelle.');
$string['pagehelp_menubasics_article'] = ('<p>Mit <strong>Menü anlegen</strong> erfasst Du Name, Beschreibung und '
    . 'JSON-Definition. Speichern wirkt an allen Ausgabestellen. '
    . '<strong>Duplizieren</strong> erzeugt einen neuen Schlüssel und lässt die '
    . 'Kopie ohne Hauptnavigationszuweisung.</p><p>Der Schlüssel identifiziert das '
    . 'Menü in Blöcken und Shortcodes. Er bleibt beim Umbenennen und Bearbeiten '
    . 'erhalten. Ein veraltetes Formular kann keinen neueren Stand überschreiben. '
    . 'Kopiere Dein JSON vor dem Neuladen, um einen Schreibkonflikt aufzulösen.</p>');
$string['pagehelp_menujson'] = 'JSON-Format und Beispiele';
$string['pagehelp_menujson_help'] = ('Verwende das kanonische Schema, stabile Eintrags-IDs und die Tiefen 0 bis 2. '
    . 'Im Hilfepanel findest Du ein vollständiges Beispiel.');
$string['pagehelp_menujson_article'] = '<p>Kopiere das Beispiel in <strong>Menü-JSON</strong> und passe Beschriftungen und Links an. Das Dokument enthält eine <code>items</code>-Liste mit bis zu 100 Einträgen. Jede ID muss eindeutig sein und aus 8 bis 64 Buchstaben, Ziffern, Unterstrichen oder Bindestrichen bestehen. Behalte IDs beim Bearbeiten bei. Die Tiefe beginnt bei 0 und darf jeweils nur um eine Ebene bis höchstens 2 steigen.</p><pre>{
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
}</pre><p>Verwende <code>internal</code> für einen lokalen <code>/pfad</code> oder <code>#anker</code>, <code>external</code> für HTTP(S)- oder mailto-Links und <code>container</code> ohne URL für einen aufklappbaren Elternpunkt. Optional sind <code>icon</code> (etwa <code>i/course</code>, <code>fa:solid:book</code> oder <code>fa:brand:github</code>), <code>target</code> (<code>self</code> oder <code>blank</code>), <code>showLabel</code> (Boolean) und <code>visibility</code>. Verfügbare Moodle-pix-Icons: i/home, i/dashboard, i/course, i/users, i/user, i/portfolio, i/news, i/files, i/folder, i/contentbank, i/email, i/notifications, i/link, i/location, i/language, i/search, i/settings, i/stats, i/report, i/payment, i/star, i/tip, i/info, i/questions, i/rss, i/calendar.</p><p>Ausgelassene optionale Felder nutzen ihre Vorgaben. <code>presentation.submenu</code> erlaubt <code>standard</code>; Erweiterungsobjekte bleiben leer. Die Validierung benennt ungültige Felder oder Einträge. JSON ist auf 256 KiB begrenzt. Markiere den JSON-Text zum Kopieren.</p>';
$string['pagehelp_menutopnav'] = 'Hauptnavigation';
$string['pagehelp_menutopnav_help'] = ('Ergänzt das Menü nach Moodles nativen Einträgen. Die Auswahl eines anderen '
    . 'Menüs verschiebt diese globale Zuweisung; Blöcke und Shortcodes behalten '
    . 'ihre Verweise.');
$string['pagehelp_menutopnav_article'] = ('<p><strong>Hauptnavigation ergänzen</strong> weist genau ein Menü der '
    . 'Hauptnavigation zu. Die Auswahl eines anderen Menüs wechselt diese '
    . 'Zuweisung, ohne eine Definition zu löschen. Entferne das Häkchen, um die '
    . 'Zuweisung aufzuheben.</p><p>Moodles native Einträge behalten Reihenfolge und '
    . 'Ziele. Deine Einträge nutzen Dropdowns, das Mehr-Menü und die mobile '
    . 'Navigation. Verlinkte Eltern führen mit ihrer Beschriftung zum Ziel; der '
    . 'getrennte Pfeil öffnet die Kinder. Bei Containern öffnet die ganze Zeile '
    . 'das Untermenü.</p>');
$string['pagehelp_menuuse'] = 'Blöcke und Shortcodes';
$string['pagehelp_menuuse_help'] = ('Verwende denselben stabilen Schlüssel in mehreren Menüblöcken oder '
    . 'gefilterten Texten. Ihre Verweise bleiben von der Hauptnavigationszuweisung '
    . 'unabhängig.');
$string['pagehelp_menuuse_article'] = ('<p>Wähle das zentrale Menü in jedem <strong>Frontpage-Menü</strong>-Block '
    . 'aus. Kompakt ist vertikal, Breit ist horizontal. Wähle das Profil nach dem '
    . 'tatsächlich verfügbaren Platz Deines Themes.</p><p>Kopiere '
    . '<code>[[frontpage-menu:dein-menueschluessel]]</code> aus <strong>Menü '
    . 'verwenden</strong> in Moodle-Inhalte. Aktiviere dort den '
    . '<strong>Menü-Builder-Shortcode</strong>-Filter. Der Filter läuft im '
    . 'System-, Kategorie-, Kurs-, Aktivitäts- und Blockkontext. Moodle und Dein '
    . 'Theme bestimmen die verfügbaren Blockregionen und gefilterten '
    . 'Textbereiche.</p><p>Alle Verweise laden die aktuelle Definition. Eine '
    . 'fehlende oder gelöschte Definition bleibt in der Ausgabe leer.</p>');
$string['pagehelp_menuaccess'] = 'Sichtbarkeit und Zugriff';
$string['pagehelp_menuaccess_help'] = ('Die Sichtbarkeit nutzt Anmeldestatus, Website-Administratoren, Systemrollen '
    . 'und globale Gruppen im Systemkontext. Die Zielberechtigungen gelten weiterhin.');
$string['pagehelp_menuaccess_article'] = (
        '<p><code>visibility.auth</code> erlaubt <code>any</code>, <code>guest</code> '
    . 'oder <code>authenticated</code>. <code>admin: true</code> beschränkt einen '
    . 'Eintrag auf Website-Administratoren. <code>roles</code> enthält Kurznamen '
    . 'von Rollen, die im Systemkontext zugewiesen sind; <code>cohorts</code> '
    . 'enthält ID-Nummern globaler Gruppen im Systemkontext.</p><p>Innerhalb einer '
    . 'Liste genügt ein passender Verweis. Sind Rollen- und Gruppenlisten gefüllt, '
    . 'müssen beide passen. Website-Administratoren benötigen keine passende '
    . 'Rollen- oder Gruppenmitgliedschaft, beachten aber die Anmeldebedingung. Eine '
    . 'nur im Kurs zugewiesene Rolle zählt nicht als Systemrolle.</p><p>Ein '
    . 'ausgeblendeter Elternpunkt blendet seinen Zweig aus. Ein leerer Container '
    . 'verschwindet. Die Menüsichtbarkeit erteilt keine Zielberechtigung: Das '
    . 'prüft Moodle gesondert.</p>'
    );
$string['pagehelp_menudelete'] = 'Verweise und Löschen';
$string['pagehelp_menudelete_help'] = ('Bekannte Hauptnavigations- und Blockverwendungen verhindern das Löschen. '
    . 'Prüfe Shortcodes in Deinen Inhalten gesondert.');
$string['pagehelp_menudelete_article'] = ('<p><strong>Bekannte Verwendungen</strong> zeigt die aktive '
    . 'Hauptnavigationszuweisung und konfigurierte Menüblöcke. Die Liste '
    . 'durchsucht nicht alle Moodle-Textfelder nach Shortcodes. Prüfe vor dem '
    . 'Löschen die Inhalte, in denen Du den Schlüssel verwendet '
    . 'hast.</p><p>Entferne das Hauptnavigationshäkchen und lösche oder ändere '
    . 'die aufgeführten Blöcke. Nutze anschließend <strong>Löschen</strong> und '
    . 'bestätige den Vorgang. Der Schlüssel wird mit der Definition entfernt; '
    . 'verbliebene Shortcodes bleiben leer.</p><p>Bewahre bei Bedarf JSON-Kopien auf '
    . 'oder nutze Deine regulären Moodle-Datenbanksicherungen. Das Speichern eines '
    . 'Menüs legt kein eigenes Revisionsarchiv an.</p>');
