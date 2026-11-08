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
$string['pagehelp_menujson_article'] = '<p>Der JSON-Editor hebt die Syntax hervor und zeigt Fehlerstellen. Wähle in der lesenden Strukturvorschau einen Eintrag, um dessen JSON einzeln zu bearbeiten. Eintrag-JSON ins Formular übernehmen aktualisiert das Gesamtdokument im Formular; Änderungen speichern speichert das Menü. Gültige offene Eintragsänderungen werden beim Speichern mit übernommen. Eintragsänderungen verwerfen stellt den Eintrag aus dem Gesamtdokument wieder her. Beschriftungen, source.url, icon und visibility bearbeitest Du im JSON; IDs bleiben stabil. Einträge ergänzt, entfernst oder sortierst Du im vollständigen JSON-Dokument.</p><p>Kopiere das Beispiel in <strong>Menü-JSON</strong> und passe Beschriftungen und Links an. Das Dokument enthält eine <code>items</code>-Liste mit bis zu 100 Einträgen. Jede ID muss eindeutig sein und aus 8 bis 64 Buchstaben, Ziffern, Unterstrichen oder Bindestrichen bestehen. Behalte IDs beim Bearbeiten bei. Die Tiefe beginnt bei 0 und darf jeweils nur um eine Ebene bis höchstens 2 steigen.</p><pre>{
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
$string['pagehelp_menuuse_article'] = ('<p>Die beiden Plugins <strong>Frontpage-Menü</strong> '
    . '(<code>block_frontpage_menu</code>) und <strong>Menü-Builder-Shortcode</strong> '
    . '(<code>filter_frontpage_menu</code>) findest Du kostenlos auf GitHub und im Moodle Marketplace.</p>'
    . '<p>Wähle das zentrale Menü in jedem <strong>Frontpage-Menü</strong>-Block '
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

// Shared independent signed update protection.
$string['updateguard_heading'] = 'Update angehalten';
$string['updateguard_component'] = 'Das Paket für {$a} wurde abgewiesen.';
$string['unsupportedupgrade'] = 'Dieses Paket kann die installierte Version nicht aktualisieren. Stelle die vorherigen Plugin-Dateien wieder her.';
$string['updateguard_hint'] = 'Verwende ein freigegebenes Paket für die installierte Ausgabe. Du kannst den Vorgang erneut prüfen.';
$string['updateguard_unchanged'] = 'Dieser Vorgang hat keine Plugin-Dateien ersetzt.';
$string['updateguard_help'] = 'Das installierte Plugin prüft vor dem Ersetzen die Herausgebersignatur, den Paketinhalt, den erlaubten Updatekanal und die Kompatibilität. Eine gültige Signatur erlaubt keinen Wechsel auf eine andere Ausgabe. Wird ein Paket abgewiesen, stoppt die gesamte Sammelaktion.';
$string['updateguard_signature'] = 'Die Herausgebersignatur oder der Paketinhalt konnte nicht bestätigt werden.';
$string['updateguard_policy'] = 'Das Paket passt nicht zur installierten Ausgabe oder zum erlaubten Paketprofil.';
$string['updateguard_channel'] = 'Updates über diesen Kanal sind für dieses Plugin nicht freigegeben.';
$string['updateguard_metadata'] = 'Die Versions- oder Abhängigkeitsangaben sind nicht konsistent.';
$string['updateguard_dependency'] = 'Eine benötigte Plugin-Version fehlt. Aktualisiere die Abhängigkeit gemeinsam mit diesem Plugin oder vorher.';
$string['updateguard_approval'] = 'Die Pakete oder der installierte Stand haben sich seit der Vorschau geändert. Prüfe den Vorgang erneut.';
$string['updateguard_busy'] = 'Eine andere Installation läuft. Versuche es nach deren Abschluss erneut.';
$string['updateguard_installed'] = 'Der installierte Updateschutz ist unvollständig. Stelle die Dateien der zuletzt installierten freigegebenen Version wieder her.';
$string['updateguard_operation'] = 'Diese Kombination von Installationsaktionen wird nicht unterstützt. Starte einen einzelnen Updatevorgang erneut.';
$string['updateguard_failed'] = 'Das Update konnte nicht geprüft werden. Es wurde keine Installation gestartet.';
$string['updateguard_back'] = 'Zur Pluginübersicht';
// End shared independent signed update protection.

$string['searchbarfilters'] = 'Filter';
$string['searchbardisplay'] = 'Anzeige';
$string['searchbarreset'] = 'Zurücksetzen';
$string['searchbarremove'] = '{$a} entfernen';
$string['searchbarerror'] = 'Die Ergebnisse konnten nicht aktualisiert werden. Die bisherigen Ergebnisse bleiben sichtbar.';
$string['managerdateformat'] = '%d. %b %Y, %H:%M';
$string['uinoresults'] = 'Keine passenden Ergebnisse. Setze die Suche zurück, um alle Menüs zu sehen.';
$string['uinomenus'] = 'Noch keine Menüs. Lege über Menü anlegen Deine erste JSON-Definition an.';
$string['searchbarmenus'] = 'Menünamen und Kennungen durchsuchen …';
$string['searchbarloading'] = 'Ergebnisse werden aktualisiert …';
$string['jsoneditorworkspace'] = 'Menü-JSON bearbeiten';
$string['jsoneditorintro'] = 'Bearbeite das vollständige JSON oder wähle in der Strukturvorschau einen vorhandenen Eintrag aus. Erst Änderungen speichern übernimmt das Menü.';
$string['jsoneditorpreview'] = 'Strukturvorschau';
$string['jsoneditorcomplete'] = 'Vollständiges Menü-JSON';
$string['jsoneditorentry'] = 'Eintrag-JSON';
$string['jsoneditorguidance'] = 'Wichtige Schlüssel: title ist die Beschriftung, source.url das Linkziel, icon die Icon-Kennung und visibility die Sichtbarkeit. Lass die übrigen Felder stehen, wenn Du sie nicht gezielt ändern möchtest.';
$string['jsoneditorapply'] = 'Eintrag-JSON ins Formular übernehmen';
$string['jsoneditordiscard'] = 'Eintragsänderungen verwerfen';
$string['jsoneditorpending'] = 'Eintrag-JSON geändert. Übernimm es ins Formular oder verwirf diese Änderungen, bevor Du einen anderen Eintrag wählst.';
$string['jsoneditorresolve'] = 'Übernimm oder verwirf zuerst die aktuellen Eintragsänderungen. Dein Text bleibt im Editor erhalten.';
$string['jsoneditorentrynotice'] = 'Hier bearbeitest Du einen vorhandenen Eintrag als JSON. Änderungen speichern prüft und speichert das vollständige Menü.';
$string['jsoneditorempty'] = 'Noch keine Einträge. Ergänze Einträge im vollständigen JSON; ein Beispiel findest Du im Hilfepanel.';
$string['jsoneditorinvalid'] = 'Die Vorschau benötigt ein JSON-Objekt mit einer items-Liste. Korrigiere das vollständige JSON, um sie zu aktualisieren.';
$string['jsoneditorcontainer'] = 'Untermenü-Überschrift';
$string['jsoneditorconflict'] = 'Das vollständige JSON wurde während dieser Eintragsbearbeitung geändert. Dein Text bleibt erhalten. Kopiere ihn, bevor Du zum vollständigen JSON zurückkehrst.';
$string['jsoneditorapplied'] = 'Eintrag ins Formular übernommen. Änderungen speichern aktualisiert das Menü.';
$string['jsoneditorentryinvalid'] = 'Gib für diesen Eintrag ein gültiges JSON-Objekt ein.';
