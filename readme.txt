=== Plugin Name ===

Plugin Name:        Handball SHV Resultate Anbindung
Plugin URI:         http://plugins.svn.wordpress.org/tc-shv-resultate/
Contributors:       titaniumcoder
Tags:               handball, resultate, schweiz
Version:            1.1.0
Requires at least:  4.7.3
Tested up to:       5.3
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
- tc-shv-team-highlight  -> team=<TEAMNO> title=<TITLE>, Schöne Darstellung (Nächstes Spiel + Resultat)

== Installation ==

1. Download from Plugins Directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Configure settings under "SHV Resultate" Settings page.
4. Activate server cronjob as described here: https://developer.wordpress.org/plugins/cron/hooking-into-the-system-task-scheduler/
   This is optional but the plugin will not be updated often enough.
5. Use the shortcodes in the description above.

== Frequently Asked Questions ==

= Docker local development =

Just execute the 

docker-compose up-d

It will install everything and have an automatic link into this repository. The only thing that is missing
is a sample page, but the idea is to add this to the admin page (of the plugin) to see samples.

Everything needed is found in the docker-compose.yml that's included with this plugin.

I recommend to add this to the wp-config.php:
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_DISPLAY', false );
define( 'WP_DEBUG_LOG', '/dev/stderr' );
define( 'DISABLE_WP_CRON', true );

and then create a cronjob to do the scheduling, because else the system will be very unreliable:



== Screenshots ==

== Changelog ==

= 1.1.0 =
* Adding logic for logos instead of using the name for teams.

= 1.0.9 =
* Just upgrading the readme.txt for "tested for"

= 1.0.8 =
* Missing Field in Table header

= 1.0.7 =
* Refactored the lastresults and nextgames views to have less information

= 1.0.6 = 
* Bugfix for home games detection

= 1.0.0 =
* First released version.

== Upgrade Notice ==

= 1.0 =
Initial version.
