=== Important Information ===

As of 2025 I will not be able to continue to maintain this repository (I left the area of wordpress development a long time ago). 
It will be archived. If someone wants to take over (or fork it and move on from there), please contact me. Assuming Wordpress will not slow down too much, I would not assume the plugin to work with any major upgrade version.

=== SHV Results ===

Plugin Name:        handball.ch Club API
Plugin URI:         https://github.com/titaniumcoder/tc-shv-results
Contributors:       metzgric
Tags:               handball, results, schweiz, switzerland, suisse, vat
Version:            1.0.9
Requires at least:  6.1
Requires PHP:       7.0
Tested up to:       6.2.2
Stable tag:         trunk
License:            Apache License, Version 2.
License URI:        http://www.apache.org/licenses/LICENSE-2.0

This plugin helps integrating the API for results presented by the Swiss Handball Federation (SHV) into a wordpress page.

== Description ==

The plugin uses the VAT Dataservice Interface of the SHV (documentation is available in your VAT interface). After installation
you have to set up your username and password for the access on the settings page. Once done the teams of the club will be read out
and block quotes will be available for

- Rankings per team (editor-table)
- Team schedule / past results (calendar)
- Club Next / Last Games (list-view)
- Highlighting Next game / Last Result (format-status)

== Installation ==

1. Download from Plugins Directory or upload the ZIP from the Github Releases Page.
2. Activate the plugin through the 'Plugins' menu in WordPress. It should take you to the settings page.
3. From VAT interface (https://vat.handball.ch), copy club id, username and password into the options form and save it. After saving it will try to load the teams.
4. Check if the teams could be loaded. If that's the case, you can use the blocks.

== Frequently Asked Questions ==

= WP-ENV local development =

My recommended way of development is using wp-env: https://developer.wordpress.org/block-editor/reference-guides/packages/packages-env/

== Screenshots ==

== Changelog ==

= 1.9.8 =
* Ordering has been fixed again in the API

= 1.0.8 =
* Highlight Dates, translated to locale (language of wordpress)
* Value of "how many elements (last results and next games) should be displayed" didn't save correctly. Didn't displayed on backend (frontend worked fine)
* allows to choose how many elements on "team shedule" element

= 1.0.7 =
* Hightlight Result was wrong.

= 1.0.6 =
* Wrong ordering in game planning

= 1.0.5 =
* Removal of modus, default color fixes

= 1.0.4 =
* Bugfix in ranking logic for relegation and relegation candidates

= 1.0.3 =
* Fix applied to teams too

= 1.0.2 =
* Fixing ordering error (or handling)

= 1.0.1 =
* Fixing missing translation and non-closing highlight-div

= 1.0.0 =
* Setup as blocks for all possible apis

== Caveats ==

It's important to note that the team numbers change every season. This also means that you have to press the save button at the beginning of a season to
get the actual teams. This is normally available as soon as the club gets the information about the groups. Or you can just use good old "try-and-error"
and retry on a daily basis to check if they are available.

This also means that all blocks must be edited and the correct teams be selected!
