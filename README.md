# Website-Code Burchardt & Kollegen

Versionierte Quelldateien für burchardt-kollegen.de (WordPress/Enfold).

**Stand: 02.08.2026**
Änderung gegenüber der Vorversion: Abschnitt zum AfA-Rechner
Restnutzungsdauer ergänzt (Datei „AfA-Rechner für RND.php",
Umstellung vom Enfold-Codeblock auf ein WPCode-PHP-Snippet).
Übrige Abschnitte unverändert.

---

**quick-css-vollstaendig.css** — Master des Enfold-Quick-CSS.
Regeln: Die Datei ist die einzige Wahrheit; das Quick-CSS-Feld in
Enfold enthält immer exakt ihre letzte Fassung. Änderungen erfolgen
nur additiv als vollständige neue Fassung mit Stand-Datum und
Änderungsvermerk im Dateikopf. Nach jedem Einspielen: WP-Rocket-
Cache leeren.

**kaufpreisaufteilung-rechner-shortcode.php** — WPCode-PHP-Snippet
für den Rechner [bk_kaufpreisaufteilung_rechner] auf der Seite
/kaufpreisaufteilung-immobilie/. Beim Einfügen in WPCode die
führende PHP-Öffnungszeile weglassen.

**AfA-Rechner für RND.php** — WPCode-PHP-Snippet für den
AfA-Rechner Restnutzungsdauer [bk_rnd_rechner] im Beitrag
/restnutzungsdauer-2026/. Einbau über WPCode als PHP-Snippet,
gesamten Inhalt inklusive der Zeile <?php einfügen, automatisch
und überall ausführen. Bewusst als Shortcode statt Codeblock,
weil WordPress div-Container aus dem Beitragsinhalt entfernt;
der Shortcode liefert das Markup erst nach den Content-Filtern
aus. Das zugehörige CSS steht im Quick CSS; das JavaScript wird
im Snippet mitgeliefert und nur geladen, wenn der Shortcode auf
der Seite vorkommt.

---

Pflegeregeln für alle Dateien: nur als vollständige neue Fassung
mit Stand-Datum und Änderungsvermerk im Kopf ausgeben (Quick-CSS
zusätzlich nur additiv fortschreiben). Commit ins Repo, danach
Einspielen in WordPress (Quick-CSS-Feld bzw. WPCode/Codeblock),
anschließend WP-Rocket-Cache leeren.
