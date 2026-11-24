<?php
// This file is part of Moodle - https://moodle.org/
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
$string['managedmenuname_help'] = 'Wählen Sie einen Namen, unter dem Sie das Menü in der Liste und in der Menüblockauswahl wiederfinden.';
$string['menudescription'] = 'Beschreibung';
$string['menudescription_help'] = 'Optionale Notizen für die Verwaltung. Die Beschreibung erscheint nicht im ausgegebenen Menü.';
$string['managedmenuadd'] = 'Menü anlegen';
$string['managedmenuedit'] = 'Menü bearbeiten';
$string['managedmenuduplicate'] = 'Duplizieren';
$string['managedmenucopyname'] = 'Kopie von {$a}';
$string['managedmenuduplicated'] = 'Das Menü wurde mit einem neuen Schlüssel dupliziert. Es ist nicht der Hauptnavigation zugewiesen.';
$string['managedmenudelete'] = 'Menü löschen';
$string['managedmenudeleteconfirm'] = '„{$a}“ löschen? Prüfen Sie vorher Ihre Inhalte auf Shortcodes: Die Nutzungsliste durchsucht nicht alle Moodle-Texte. Shortcodes eines gelöschten Menüs bleiben leer.';
$string['managedmenudeleteused'] = 'Entfernen Sie vor dem Löschen die Hauptnavigationszuweisung und die konfigurierten Menüblöcke. Shortcodes in Moodle-Texten müssen Sie gesondert prüfen.';
$string['managedmenudeleted'] = 'Das Menü wurde gelöscht.';
$string['managedmenutopnav'] = 'Hauptnavigation ergänzen';
$string['managedmenuversionconflict'] = 'Jemand hat das Menü nach dem Öffnen gespeichert. Ihre Änderungen wurden nicht gespeichert. Kopieren Sie Ihr JSON vor dem Neuladen, prüfen Sie den aktuellen Stand und übertragen Sie Ihre Änderungen erneut.';
$string['managedmenusintro'] = 'Legen Sie ein zentrales Menü an und verwenden Sie es in der Hauptnavigation, in Menüblöcken und in Moodle-Inhalten.';
$string['managedmenuusageprimary'] = 'Hauptnavigation (aktiv)';
$string['managedmenuusageblockinstance'] = 'Menüblock #{$a}';
$string['menukey'] = 'Schlüssel';
$string['menuusage'] = 'Bekannte Verwendungen';
$string['menunoknownusage'] = 'Keine Hauptnavigationszuweisung und kein konfigurierter Menüblock gefunden. Prüfen Sie Text-Shortcodes gesondert.';
$string['menuuse'] = 'Menü verwenden';
$string['menublockuse'] = 'Wählen Sie dieses Menü in einem Frontpage-Menüblock oder kopieren Sie seinen Shortcode in Inhalte, für die der Menü-Builder-Shortcode-Filter aktiviert ist.';
$string['menusourcecentralgroup'] = 'Zentrale Menüs';
$string['menujson'] = 'Menü-JSON';
$string['menujsoninvalid'] = 'Prüfen Sie das Menü-JSON: {$a}';
$string['menusubmenutoggle'] = 'Untermenü öffnen: {$a}';
$string['moduleinactive'] = 'Die Menüverwaltung gehört einem anderen Host oder benötigt einen Abgleich der Eigentümerschaft. Die vorhandene Zuordnung wurde beibehalten.';
$string['privacy'] = 'Datenschutz';
$string['privacy:metadata'] = 'Menüdefinitionen und Platzierungen speichern die Kennungen der anlegenden und zuletzt bearbeitenden Administrator/innen. Definitionen können Rollen-Kurznamen und Verweise auf globale Gruppen im Systemkontext enthalten.';
$string['menubuilder:manage'] = 'Zentrale Menüs verwalten';
$string['pagehelp_menus_title'] = 'Menu-Builder-Hilfe';
$string['pagehelp_more'] = 'Mehr dazu im Hilfepanel';
$string['contexthelptopics'] = 'Hilfethemen';
$string['pagehelp_menubasics'] = 'Menüs verwalten';
$string['pagehelp_menubasics_help'] = 'Legen Sie zentrale Definitionen an, bearbeiten Sie und duplizieren Sie sie. Speichern wirkt an jeder Verwendungsstelle.';
$string['pagehelp_menubasics_article'] = '<p>Mit <strong>Menü anlegen</strong> erfassen Sie Name, Beschreibung und JSON-Definition. Speichern wirkt an allen Ausgabestellen. <strong>Duplizieren</strong> erzeugt einen neuen Schlüssel und lässt die Kopie ohne Hauptnavigationszuweisung.</p><p>Der Schlüssel identifiziert das Menü in Blöcken und Shortcodes. Er bleibt beim Umbenennen und Bearbeiten erhalten. Ein veraltetes Formular kann keinen neueren Stand überschreiben. Kopieren Sie Ihr JSON vor dem Neuladen, um einen Schreibkonflikt aufzulösen.</p>';
$string['pagehelp_menujson'] = 'JSON-Format und Beispiele';
$string['pagehelp_menujson_help'] = 'Verwenden Sie das kanonische Schema, stabile Eintrags-IDs und die Tiefen 0 bis 2. Im Hilfepanel finden Sie ein vollständiges Beispiel.';
$string['pagehelp_menujson_article'] = '<p>Der JSON-Editor hebt die Syntax hervor und zeigt Fehlerstellen. Wählen Sie in der lesenden Strukturvorschau einen Eintrag, um dessen JSON einzeln zu bearbeiten. Eintrag-JSON ins Formular übernehmen aktualisiert das Gesamtdokument im Formular; Änderungen speichern speichert das Menü. Gültige offene Eintragsänderungen werden beim Speichern mit übernommen. Eintragsänderungen verwerfen stellt den Eintrag aus dem Gesamtdokument wieder her. Beschriftungen, source.url, icon und visibility bearbeiten Sie im JSON; IDs bleiben stabil. Einträge ergänzen, entfernen oder sortieren Sie im vollständigen JSON-Dokument.</p><p>Kopieren Sie das Beispiel in <strong>Menü-JSON</strong> und passen Sie Beschriftungen und Links an. Das Dokument enthält eine <code>items</code>-Liste mit bis zu 100 Einträgen. Jede ID muss eindeutig sein und aus 8 bis 64 Buchstaben, Ziffern, Unterstrichen oder Bindestrichen bestehen. Behalten Sie IDs beim Bearbeiten bei. Die Tiefe beginnt bei 0 und darf jeweils nur um eine Ebene bis höchstens 2 steigen.</p><pre>{
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
}</pre><p>Verwenden Sie <code>internal</code> für einen lokalen <code>/pfad</code> oder <code>#anker</code>, <code>external</code> für HTTP(S)- oder mailto-Links und <code>container</code> ohne URL für einen aufklappbaren Elternpunkt. Optional sind <code>icon</code> (etwa <code>i/course</code>, <code>fa:solid:book</code> oder <code>fa:brand:github</code>), <code>target</code> (<code>self</code> oder <code>blank</code>), <code>showLabel</code> (Boolean) und <code>visibility</code>. Verfügbare Moodle-pix-Icons: i/home, i/dashboard, i/course, i/users, i/user, i/portfolio, i/news, i/files, i/folder, i/contentbank, i/email, i/notifications, i/link, i/location, i/language, i/search, i/settings, i/stats, i/report, i/payment, i/star, i/tip, i/info, i/questions, i/rss, i/calendar.</p><p>Ausgelassene optionale Felder nutzen ihre Vorgaben. <code>presentation.submenu</code> erlaubt <code>standard</code>; Erweiterungsobjekte bleiben leer. Die Validierung benennt ungültige Felder oder Einträge. JSON ist auf 256 KiB begrenzt. Markieren Sie den JSON-Text zum Kopieren.</p>';
$string['pagehelp_menutopnav'] = 'Hauptnavigation';
$string['pagehelp_menutopnav_help'] = 'Ergänzt das Menü nach Moodles nativen Einträgen. Die Auswahl eines anderen Menüs verschiebt diese globale Zuweisung; Blöcke und Shortcodes behalten ihre Verweise.';
$string['pagehelp_menutopnav_article'] = '<p><strong>Hauptnavigation ergänzen</strong> weist genau ein Menü der Hauptnavigation zu. Die Auswahl eines anderen Menüs wechselt diese Zuweisung, ohne eine Definition zu löschen. Entfernen Sie das Häkchen, um die Zuweisung aufzuheben.</p><p>Moodles native Einträge behalten Reihenfolge und Ziele. Ihre Einträge nutzen Dropdowns, das Mehr-Menü und die mobile Navigation. Verlinkte Eltern führen mit ihrer Beschriftung zum Ziel; der getrennte Pfeil öffnet die Kinder. Bei Containern öffnet die ganze Zeile das Untermenü.</p>';
$string['pagehelp_menuuse'] = 'Blöcke und Shortcodes';
$string['pagehelp_menuuse_help'] = 'Verwenden Sie denselben stabilen Schlüssel in mehreren Menüblöcken oder gefilterten Texten. Ihre Verweise bleiben von der Hauptnavigationszuweisung unabhängig.';
$string['pagehelp_menuuse_article'] = '<p>Die beiden Plugins <strong>Frontpage-Menü</strong> (<code>block_frontpage_menu</code>) und <strong>Menü-Builder-Shortcode</strong> (<code>filter_frontpage_menu</code>) finden Sie kostenlos auf GitHub und im Moodle Marketplace.</p><p>Wählen Sie das zentrale Menü in jedem <strong>Frontpage-Menü</strong>-Block aus. Kompakt ist vertikal, Breit ist horizontal. Wählen Sie das Profil nach dem tatsächlich verfügbaren Platz Ihres Themes.</p><p>Kopieren Sie <code>[[frontpage-menu:dein-menueschluessel]]</code> aus <strong>Menü verwenden</strong> in Moodle-Inhalte. Aktivieren Sie dort den <strong>Menü-Builder-Shortcode</strong>-Filter. Der Filter läuft im System-, Kategorie-, Kurs-, Aktivitäts- und Blockkontext. Moodle und Ihr Theme bestimmen die verfügbaren Blockregionen und gefilterten Textbereiche.</p><p>Alle Verweise laden die aktuelle Definition. Eine fehlende oder gelöschte Definition bleibt in der Ausgabe leer.</p>';
$string['pagehelp_menuaccess'] = 'Sichtbarkeit und Zugriff';
$string['pagehelp_menuaccess_help'] = 'Die Sichtbarkeit nutzt Anmeldestatus, Website-Administrator/innen, Systemrollen und globale Gruppen im Systemkontext. Die Zielberechtigungen gelten weiterhin.';
$string['pagehelp_menuaccess_article'] = '<p><code>visibility.auth</code> erlaubt <code>any</code>, <code>guest</code> oder <code>authenticated</code>. <code>admin: true</code> beschränkt einen Eintrag auf Website-Administrator/innen. <code>roles</code> enthält Kurznamen von Rollen, die im Systemkontext zugewiesen sind; <code>cohorts</code> enthält ID-Nummern globaler Gruppen im Systemkontext.</p><p>Innerhalb einer Liste genügt ein passender Verweis. Sind Rollen- und Gruppenlisten gefüllt, müssen beide passen. Website-Administrator/innen benötigen keine passende Rollen- oder Gruppenmitgliedschaft, beachten aber die Anmeldebedingung. Eine nur im Kurs zugewiesene Rolle zählt nicht als Systemrolle.</p><p>Ein ausgeblendeter Elternpunkt blendet seinen Zweig aus. Ein leerer Container verschwindet. Die Menüsichtbarkeit erteilt keine Zielberechtigung: Das prüft Moodle gesondert.</p>';
$string['pagehelp_menudelete'] = 'Verweise und Löschen';
$string['pagehelp_menudelete_help'] = 'Bekannte Hauptnavigations- und Blockverwendungen verhindern das Löschen. Prüfen Sie Shortcodes in Ihren Inhalten gesondert.';
$string['pagehelp_menudelete_article'] = '<p><strong>Bekannte Verwendungen</strong> zeigt die aktive Hauptnavigationszuweisung und konfigurierte Menüblöcke. Die Liste durchsucht nicht alle Moodle-Textfelder nach Shortcodes. Prüfen Sie vor dem Löschen die Inhalte, in denen Sie den Schlüssel verwendet haben.</p><p>Entfernen Sie das Hauptnavigationshäkchen und löschen Sie oder ändern Sie die aufgeführten Blöcke. Nutzen Sie anschließend <strong>Löschen</strong> und bestätigen Sie den Vorgang. Der Schlüssel wird mit der Definition entfernt; verbliebene Shortcodes bleiben leer.</p><p>Bewahren Sie bei Bedarf JSON-Kopien auf oder nutzen Sie Ihre regulären Moodle-Datenbanksicherungen. Das Speichern eines Menüs legt kein eigenes Revisionsarchiv an.</p>';
$string['updateguard_heading'] = 'Update angehalten';
$string['updateguard_component'] = 'Das Paket für {$a} wurde abgewiesen.';
$string['unsupportedupgrade'] = 'Dieses Paket kann die installierte Version nicht aktualisieren. Stellen Sie die vorherigen Plugin-Dateien wieder her.';
$string['updateguard_hint'] = 'Verwenden Sie ein freigegebenes Paket für die installierte Ausgabe. Sie können den Vorgang erneut prüfen.';
$string['updateguard_unchanged'] = 'Dieser Vorgang hat keine Plugin-Dateien ersetzt.';
$string['updateguard_help'] = 'Das installierte Plugin prüft vor dem Ersetzen die Herausgebersignatur, den Paketinhalt, den erlaubten Updatekanal und die Kompatibilität. Eine gültige Signatur erlaubt keinen Wechsel auf eine andere Ausgabe. Wird ein Paket abgewiesen, stoppt die gesamte Sammelaktion.';
$string['updateguard_signature'] = 'Die Herausgebersignatur oder der Paketinhalt konnte nicht bestätigt werden.';
$string['updateguard_policy'] = 'Das Paket passt nicht zur installierten Ausgabe oder zum erlaubten Paketprofil.';
$string['updateguard_channel'] = 'Updates über diesen Kanal sind für dieses Plugin nicht freigegeben.';
$string['updateguard_metadata'] = 'Die Versions- oder Abhängigkeitsangaben sind nicht konsistent.';
$string['updateguard_dependency'] = 'Eine benötigte Plugin-Version fehlt. Aktualisieren Sie die Abhängigkeit gemeinsam mit diesem Plugin oder vorher.';
$string['updateguard_approval'] = 'Die Pakete oder der installierte Stand haben sich seit der Vorschau geändert. Prüfen Sie den Vorgang erneut.';
$string['updateguard_busy'] = 'Eine andere Installation läuft. Versuchen Sie es nach deren Abschluss erneut.';
$string['updateguard_installed'] = 'Der installierte Updateschutz ist unvollständig. Stellen Sie die Dateien der zuletzt installierten freigegebenen Version wieder her.';
$string['updateguard_operation'] = 'Diese Kombination von Installationsaktionen wird nicht unterstützt. Starten Sie einen einzelnen Updatevorgang erneut.';
$string['updateguard_failed'] = 'Das Update konnte nicht geprüft werden. Es wurde keine Installation gestartet.';
$string['updateguard_back'] = 'Zur Pluginübersicht';
$string['searchbarfilters'] = 'Filter';
$string['searchbardisplay'] = 'Anzeige';
$string['searchbarreset'] = 'Zurücksetzen';
$string['searchbarremove'] = '{$a} entfernen';
$string['searchbarerror'] = 'Die Ergebnisse konnten nicht aktualisiert werden. Die bisherigen Ergebnisse bleiben sichtbar.';
$string['managerdateformat'] = '%d. %b %Y, %H:%M';
$string['uinoresults'] = 'Keine passenden Ergebnisse. Setzen Sie die Suche zurück, um alle Menüs zu sehen.';
$string['uinomenus'] = 'Noch keine Menüs. Legen Sie über Menü anlegen Ihre erste JSON-Definition an.';
$string['searchbarmenus'] = 'Menünamen und Kennungen durchsuchen …';
$string['searchbarloading'] = 'Ergebnisse werden aktualisiert …';
$string['jsoneditorworkspace'] = 'Menü-JSON bearbeiten';
$string['jsoneditorintro'] = 'Bearbeiten Sie das vollständige JSON oder wählen Sie in der Strukturvorschau einen vorhandenen Eintrag aus. Erst Änderungen speichern übernimmt das Menü.';
$string['jsoneditorpreview'] = 'Strukturvorschau';
$string['jsoneditorcomplete'] = 'Vollständiges Menü-JSON';
$string['jsoneditorentry'] = 'Eintrag-JSON';
$string['jsoneditorguidance'] = 'Wichtige Schlüssel: title ist die Beschriftung, source.url das Linkziel, icon die Icon-Kennung und visibility die Sichtbarkeit. Lassen Sie die übrigen Felder stehen, wenn Sie sie nicht gezielt ändern möchten.';
$string['jsoneditorapply'] = 'Eintrag-JSON ins Formular übernehmen';
$string['jsoneditordiscard'] = 'Eintragsänderungen verwerfen';
$string['jsoneditorpending'] = 'Eintrag-JSON geändert. Übernehmen Sie es ins Formular oder verwirf diese Änderungen, bevor Sie einen anderen Eintrag wählen.';
$string['jsoneditorresolve'] = 'Übernehmen Sie oder verwirf zuerst die aktuellen Eintragsänderungen. Ihr Text bleibt im Editor erhalten.';
$string['jsoneditorentrynotice'] = 'Hier bearbeiten Sie einen vorhandenen Eintrag als JSON. Änderungen speichern prüft und speichert das vollständige Menü.';
$string['jsoneditorempty'] = 'Noch keine Einträge. Ergänzen Sie Einträge im vollständigen JSON; ein Beispiel finden Sie im Hilfepanel.';
$string['jsoneditorinvalid'] = 'Die Vorschau benötigt ein JSON-Objekt mit einer items-Liste. Korrigieren Sie das vollständige JSON, um sie zu aktualisieren.';
$string['jsoneditorcontainer'] = 'Untermenü-Überschrift';
$string['jsoneditorconflict'] = 'Das vollständige JSON wurde während dieser Eintragsbearbeitung geändert. Ihr Text bleibt erhalten. Kopieren Sie ihn, bevor Sie zum vollständigen JSON zurückkehren.';
$string['jsoneditorapplied'] = 'Eintrag ins Formular übernommen. Änderungen speichern aktualisiert das Menü.';
$string['jsoneditorentryinvalid'] = 'Geben Sie für diesen Eintrag ein gültiges JSON-Objekt ein.';
$string['updateinfo_heading'] = 'Paketinformationen: {$a}';
$string['updateinfo_verified'] = 'Der eingebettete Herausgebernachweis passt zur installierten Ausgabe.';
$string['updateinfo_missing'] = 'Dieses Paket enthält keinen eingebetteten Herausgebernachweis.';
$string['updateinfo_line'] = 'Dieses Paket gehört zu einer anderen Ausgabe als das installierte Plugin.';
$string['updateinfo_mismatch'] = 'Der eingebettete Herausgebernachweis konnte mit den Angaben des installierten Plugins nicht bestätigt werden.';
$string['updateinfo_unavailable'] = 'Die Herausgeberinformationen für das ausgewählte Paket konnten nicht gelesen werden.';
$string['updateinfo_choice'] = 'Kontrollieren Sie vor dem Fortfahren die Paketquelle und Moodles Prüfergebnisse. Mit „{$a}“ unten können Sie abbrechen.';
