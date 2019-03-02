=== Plugin Name ===

Plugin Name:        Handball SHV Resultate Anbindung
Plugin URI:         http://plugins.svn.wordpress.org/tc-shv-resultate/
Contributors:       titaniumcoder
Tags:               handball, resultate, schweiz
Version:            1.0.6
Requires at least:  4.7.3
Tested up to:       5.1
Stable tag:         trunk
License:            Apache License, Version 2.
License URI:        http://www.apache.org/licenses/LICENSE-2.0

Mit diesem Plugin werden die Resultate des SHV auf einer Wordpress-Seite integriert.

== Description ==

Das Plugin verwendet die VAT Dataservice Schnittstelle des SHV (siehe auch
https://www.handball.ch/media/1845/vat-anleitung-dataservice_de.pdf
). Die Resultate werden über sogenannte Shortcodes verfügbar. Liste der Shortcodes:

- tc-shv-resultate ->       Letzte Resultate des Vereins
- tc-shv-spiele ->          Nächste Spiele des Vereins
- tc-shv-halle ->           Parameter: halle=<HALLENAME>, nächste Spiele in einer Halle (bei welchen der Club beteiligt ist)
                      
- tc-shv-team ->            Parameter: team=<TEAMNO>, Info eines teams 
- tc-shv-team-lastresult -> team=<TEAMNO>, letztes Resultat eines Teams
- tc-shv-team-nextgame   -> team=<TEAMNO>, Nächtes Spiel eines Teams

== Installation ==

1. Download from Plugins Directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure settings under "SHV Resultate" Settings page.
4. Activate server cronjob as described here: https://developer.wordpress.org/plugins/cron/hooking-into-the-system-task-scheduler/
   This is optional but the plugin will not be updated often enough.
5. Use the shortcodes in the description above.
== Frequently Asked Questions ==

= A question that someone might have =

An answer to that question.

== Screenshots ==

== Changelog ==

= 1.0.6 = 
* Bugfix for home games detection

= 1.0.0 =
* First released version.

== Upgrade Notice ==

= 1.0 =
Initial version.
