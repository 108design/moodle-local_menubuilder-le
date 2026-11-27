<?php
// This file is part of Moodle - https://moodle.org/
/**
 * lang/de/local_menubuilder.php
 *
 * @package local_menubuilder
 * @copyright 2026 Andreas Giesen <andreas@108design.com>
 * @license 108design source-available software license; see LICENSE.md
 */

$string['managedmenuname_help'] = 'Wähle einen Namen, unter dem du das Menü in der Liste und in der Menüblockauswahl wiederfinden.';
$string['managedmenudeleteconfirm'] = '„{$a}“ löschen? Prüfe vorher deine Inhalte auf Shortcodes: Die Nutzungsliste durchsucht nicht alle Moodle-Texte. Shortcodes eines gelöschten Menüs bleiben leer.';
$string['managedmenudeleteused'] = 'Entferne vor dem Löschen die Hauptnavigationszuweisung und die konfigurierten Menüblöcke. Shortcodes in Moodle-Texten musst du gesondert prüfen.';
$string['managedmenuversionconflict'] = 'Jemand hat das Menü nach dem Öffnen gespeichert. Deine Änderungen wurden nicht gespeichert. Kopiere dein JSON vor dem Neuladen, prüfe den aktuellen Stand und übertrage deine Änderungen erneut.';
$string['managedmenusintro'] = 'Lege ein zentrales Menü an und verwende es in der Hauptnavigation, in Menüblöcken und in Moodle-Inhalten.';
$string['menunoknownusage'] = 'Keine Hauptnavigationszuweisung und kein konfigurierter Menüblock gefunden. Prüfe Text-Shortcodes gesondert.';
$string['menublockuse'] = 'Wähle dieses Menü in einem Frontpage-Menüblock oder kopiere seinen Shortcode in Inhalte, für die der Menü-Builder-Shortcode-Filter aktiviert ist.';
$string['menujsoninvalid'] = 'Prüfe das Menü-JSON: {$a}';
$string['pagehelp_menubasics_help'] = 'Lege zentrale Definitionen an, bearbeite und dupliziere sie. Speichern wirkt an jeder Verwendungsstelle.';
$string['pagehelp_menubasics_article'] = '<p>Mit <strong>Menü anlegen</strong> erfasst du Name, Beschreibung und JSON-Definition. Speichern wirkt an allen Ausgabestellen. <strong>Duplizieren</strong> erzeugt einen neuen Schlüssel und lässt die Kopie ohne Hauptnavigationszuweisung.</p><p>Der Schlüssel identifiziert das Menü in Blöcken und Shortcodes. Er bleibt beim Umbenennen und Bearbeiten erhalten. Ein veraltetes Formular kann keinen neueren Stand überschreiben. Kopiere dein JSON vor dem Neuladen, um einen Schreibkonflikt aufzulösen.</p>';
$string['pagehelp_menujson_help'] = 'Verwende das kanonische Schema, stabile Eintrags-IDs und die Tiefen 0 bis 2. Im Hilfepanel findest du ein vollständiges Beispiel.';
$string['pagehelp_menujson_article'] = '<p>Der JSON-Editor hebt die Syntax hervor und zeigt Fehlerstellen. Wähle in der lesenden Strukturvorschau einen Eintrag, um dessen JSON einzeln zu bearbeiten. Eintrag-JSON ins Formular übernehmen aktualisiert das Gesamtdokument im Formular; Änderungen speichern speichert das Menü. Gültige offene Eintragsänderungen werden beim Speichern mit übernommen. Eintragsänderungen verwerfen stellt den Eintrag aus dem Gesamtdokument wieder her. Beschriftungen, source.url, icon und visibility bearbeitest du im JSON; IDs bleiben stabil. Einträge ergänzen, entfernen oder sortieren du im vollständigen JSON-Dokument.</p><p>Kopiere das Beispiel in <strong>Menü-JSON</strong> und passe Beschriftungen und Links an. Das Dokument enthält eine <code>items</code>-Liste mit bis zu 100 Einträgen. Jede ID muss eindeutig sein und aus 8 bis 64 Buchstaben, Ziffern, Unterstrichen oder Bindestrichen bestehen. Behalte IDs beim Bearbeiten bei. Die Tiefe beginnt bei 0 und darf jeweils nur um eine Ebene bis höchstens 2 steigen.</p><pre>{
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
$string['pagehelp_menutopnav_article'] = '<p><strong>Hauptnavigation ergänzen</strong> weist genau ein Menü der Hauptnavigation zu. Die Auswahl eines anderen Menüs wechselt diese Zuweisung, ohne eine Definition zu löschen. Entferne das Häkchen, um die Zuweisung aufzuheben.</p><p>Moodles native Einträge behalten Reihenfolge und Ziele. Deine Einträge nutzen Dropdowns, das Mehr-Menü und die mobile Navigation. Verlinkte Eltern führen mit ihrer Beschriftung zum Ziel; der getrennte Pfeil öffnet die Kinder. Bei Containern öffnet die ganze Zeile das Untermenü.</p>';
$string['pagehelp_menuuse_help'] = 'Verwende denselben stabilen Schlüssel in mehreren Menüblöcken oder gefilterten Texten. Deine Verweise bleiben von der Hauptnavigationszuweisung unabhängig.';
$string['pagehelp_menuuse_article'] = '<p>Die beiden Plugins <strong>Frontpage-Menü</strong> (<code>block_frontpage_menu</code>) und <strong>Menü-Builder-Shortcode</strong> (<code>filter_frontpage_menu</code>) findest du kostenlos auf GitHub und im Moodle Marketplace.</p><p>Wähle das zentrale Menü in jedem <strong>Frontpage-Menü</strong>-Block aus. Kompakt ist vertikal, Breit ist horizontal. Wähle das Profil nach dem tatsächlich verfügbaren Platz deines Themes.</p><p>Kopiere <code>[[frontpage-menu:dein-menueschluessel]]</code> aus <strong>Menü verwenden</strong> in Moodle-Inhalte. Aktiviere dort den <strong>Menü-Builder-Shortcode</strong>-Filter. Der Filter läuft im System-, Kategorie-, Kurs-, Aktivitäts- und Blockkontext. Moodle und dein Theme bestimmen die verfügbaren Blockregionen und gefilterten Textbereiche.</p><p>Alle Verweise laden die aktuelle Definition. Eine fehlende oder gelöschte Definition bleibt in der Ausgabe leer.</p>';
$string['pagehelp_menudelete_help'] = 'Bekannte Hauptnavigations- und Blockverwendungen verhindern das Löschen. Prüfe Shortcodes in deinen Inhalten gesondert.';
$string['pagehelp_menudelete_article'] = '<p><strong>Bekannte Verwendungen</strong> zeigt die aktive Hauptnavigationszuweisung und konfigurierte Menüblöcke. Die Liste durchsucht nicht alle Moodle-Textfelder nach Shortcodes. Prüfe vor dem Löschen die Inhalte, in denen du den Schlüssel verwendet hast.</p><p>Entferne das Hauptnavigationshäkchen und lösche oder ändere die aufgeführten Blöcke. Nutze anschließend <strong>Löschen</strong> und bestätige den Vorgang. Der Schlüssel wird mit der Definition entfernt; verbliebene Shortcodes bleiben leer.</p><p>Bewahre bei Bedarf JSON-Kopien auf oder nutze deine regulären Moodle-Datenbanksicherungen. Das Speichern eines Menüs legt kein eigenes Revisionsarchiv an.</p>';
$string['unsupportedupgrade'] = 'Dieses Paket kann die installierte Version nicht aktualisieren. Stelle die vorherigen Plugin-Dateien wieder her.';
$string['updateguard_hint'] = 'Verwende ein freigegebenes Paket für die installierte Ausgabe. Du kannst den Vorgang erneut prüfen.';
$string['updateguard_dependency'] = 'Eine benötigte Plugin-Version fehlt. Aktualisiere die Abhängigkeit gemeinsam mit diesem Plugin oder vorher.';
$string['updateguard_approval'] = 'Die Pakete oder der installierte Stand haben sich seit der Vorschau geändert. Prüfe den Vorgang erneut.';
$string['updateguard_busy'] = 'Eine andere Installation läuft. Versuche es nach deren Abschluss erneut.';
$string['updateguard_installed'] = 'Der installierte Updateschutz ist unvollständig. Stelle die Dateien der zuletzt installierten freigegebenen Version wieder her.';
$string['updateguard_operation'] = 'Diese Kombination von Installationsaktionen wird nicht unterstützt. Starte einen einzelnen Updatevorgang erneut.';
$string['uinoresults'] = 'Keine passenden Ergebnisse. Setze die Suche zurück, um alle Menüs zu sehen.';
$string['uinomenus'] = 'Noch keine Menüs. Lege über Menü anlegen deine erste JSON-Definition an.';
$string['jsoneditorintro'] = 'Bearbeite das vollständige JSON oder wähle in der Strukturvorschau einen vorhandenen Eintrag aus. Erst Änderungen speichern übernimmt das Menü.';
$string['jsoneditorguidance'] = 'Wichtige Schlüssel: title ist die Beschriftung, source.url das Linkziel, icon die Icon-Kennung und visibility die Sichtbarkeit. Lass die übrigen Felder stehen, wenn du sie nicht gezielt ändern möchtest.';
$string['jsoneditorpending'] = 'Eintrag-JSON geändert. Übernimm es ins Formular oder verwirf diese Änderungen, bevor du einen anderen Eintrag wählst.';
$string['jsoneditorresolve'] = 'Übernimm oder verwirf zuerst die aktuellen Eintragsänderungen. Dein Text bleibt im Editor erhalten.';
$string['jsoneditorentrynotice'] = 'Hier bearbeitest du einen vorhandenen Eintrag als JSON. Änderungen speichern prüft und speichert das vollständige Menü.';
$string['jsoneditorempty'] = 'Noch keine Einträge. Ergänze Einträge im vollständigen JSON; ein Beispiel findest du im Hilfepanel.';
$string['jsoneditorinvalid'] = 'Die Vorschau benötigt ein JSON-Objekt mit einer items-Liste. Korrigiere das vollständige JSON, um sie zu aktualisieren.';
$string['jsoneditorconflict'] = 'Das vollständige JSON wurde während dieser Eintragsbearbeitung geändert. Dein Text bleibt erhalten. Kopiere ihn, bevor du zum vollständigen JSON zurückkehrst.';
$string['jsoneditorentryinvalid'] = 'Gib für diesen Eintrag ein gültiges JSON-Objekt ein.';
$string['updateinfo_choice'] = 'Kontrolliere vor dem Fortfahren die Paketquelle und Moodles Prüfergebnisse. Mit „{$a}“ unten kannst du abbrechen.';
