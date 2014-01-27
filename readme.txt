=== Canadian Nutrition Facts Label ===
Contributors: dandelionweb
Tags: food, nutrition, nutrition facts, nutrition label, nutrition labelling, Canadian bilingual nutrition facts, nutrition facts table
Requires at least: 3.0
Tested up to: 3.8
Stable tag: trunk
Version: 1.0.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Use this free WordPress plugin to insert a Canadian Bilingual Nutrition Facts Label to pages or posts with a shortcode.

== Description ==

This plugin creates a 'Label' custom post type which can be assigned to any page or post. 

* Use the shortcode [nutrition-label id=XXX] to display a nutrition label.
* When creating the label you can also specify the page/post and use shortcode [nutrition-label] to display the nutrition label that has been attached to the page/post.
* Developers can add do_shortcode('[nutrition-label]') to their templates.


This plugin was forked from the Easy Nutrition Label Plugin found at http://wordpress.org/plugins/easy-nutrition-facts-label/

* Fields were added for vitamins A, C, Calcium and Iron
* The label was made bilingual (English/French)
* Modified Daily Value (DV) to use Canadian recommendations as per http://healthycanadians.gc.ca/eating-nutrition/label-etiquetage/daily-value-valeur-quotidienne-eng.php
	

== Installation ==

1. Upload canadian-nutrition-facts-label folder to the /wp-content/plugins directory

2. Activate the plugin through the Plugins menu in WordPress

3. Create a label in the new 'Labels' custom post type section of the admin. See units instructions in FAQs for assistance.

4. Include the shortcode [nutrition-label id=XXX] where you want a specific label to be displayed. Change id=XXX to the specific id number for your label. 

5. DEVELOPERS: When creating the label you can also specify the Page or Post you want the label to appear and include echo do_shortcode("[nutrition-label]"); in the template where you want the label.

== Frequently Asked Questions ==

= What's the shortcode for this plugin? =

[nutrition-label] or [nutrition-label id=XXX]  Change id=XXX to the specific id number for your label. 


= What units does the label use? =

* Grams (g): totalfat, satfat, transfat, carbohydrates, fiber, sugars, protein
* Milligrams (mg): cholesterol and sodium
* Percentages: for Vitamines A, C, Calcium and Iron

= How do I include the nutrition label in a page template. =

When creating the label you can also specify the Page or Post you want the label to appear and include echo do_shortcode("[nutrition-label]"); in the template where you want the label.



== Screenshots ==

1. Install and activate the plugin.  Then navigate to the new "Labels" custom post type in the admin. 

2. Add/Edit Label Form - select a page/post to attach the label.  Then simply fill in all the fields

3. Include 'do_shortcode' in your template.  It will search for the first label attached to that page.

4. Add a label with the shortcode and label id [nutrition-label id=XXX].

5. Example generated label

== Upgrade Notice ==
= 1.0.1 =
* fix the daily values - Canada Iron DV is 14mg/US is 18mg and Canada Calcium DV is 1100mg/US is 1000mg 
= 1 =
* Initial Release, forked and updated from easy-nutrition-facts-label plugin


== Changelog ==
= 1.0.1 =
* fix the daily values - Canada Iron DV is 14mg/US is 18mg and Canada Calcium DV is 1100mg/US is 1000mg 
= 1 =
* Initial release
