=== Plugin Name ===

Plugin Name:        Handball SHV Results
Plugin URI:         http://plugins.svn.wordpress.org/tc-shv-resultate/
Contributors:       titaniumcoder
Tags:               handball, results, schweiz, switzerland, vat
Version:            2.0.0
Requires at least:  6.0.0
Tested up to:       6.2.2
Stable tag:         trunk
License:            Apache License, Version 2.
License URI:        http://www.apache.org/licenses/LICENSE-2.0

This plugin helps integrating the API for results presented by the Swiss Handball Federation (SHV) into a wordpress page.

== Description ==

The plugin uses the VAT Dataservice Interface of the SHV (documentation is available in your VAT interface). After installation
you have to set up your username and password for the access on the settings page. Once done the teams of the club will be read out
and block quotes will be available for

- Team information
- Rankings per team
- Team schedule / past results
- Next game highlighting
- Last result highlighting

== Installation ==

1. Download from Plugins Directory
2. Activate the plugin through the 'Plugins' menu in WordPress. It should take you to the settings page.
3. From your VAT intreface, enter club id, username and password into the options form and save it. After saving it will try to load the teams.
4. Check if the teams could be loaded. If that's the case, you can use the block quotes now

== Caveats ==

It's important to note that the team numbers change every season. This also means that you have to press the save button at the beginning of a season to
get the actual teams. This is normally available as soon as the club gets the information about the groups. Or you can just use good old "try-and-error"
and retry on a daily basis to check if they are available.

This also means that all blocks must be edited and the correct teams be selected!

== Frequently Asked Questions ==

= WP-ENV local development =

My recommended way of development is using wp-env: https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/

== Screenshots ==

== Changelog ==

= 2.0.0 =
* Complete rewrite of the plugin due to the API change by SHV
* Removed short codes
* Removed individual tables (not needed anymore)
* Highlight and Rankings will now link to the logo of the teams / clubs
* Default language is english with translation for german (naturally) and french (coming soon)

= 1.1.2 =
* Adding support for newer wordpress version (5), last pre-6 upgrade

= 1.1.1 =
* Hotfix for detecting played game (forfait)

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
