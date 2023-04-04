=== BAN Users ===
Contributors: webxmedia
Donate link: http://webxmedia.co.uk
Tags: users, ban, lock account, block account, deny access, security, account, disable, login, logon, suspend, temporary, user, disable user, wp-admin disable, wp-login disable
Requires at least: 4.2
Tested up to: 6.2.0
Stable tag: 1.5.4
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Ban a user from logging into their wordpress account, suspend posts and send notifications. The perfect solution for managing users and blocking unwanted registrations. 

== Description ==

<h4>WP Plugin for truly banning users!</h4>
<p>The BAN Users WordPress Plugin has been developed to enable administrators to quickly BAN (aka disable, suspend…) users from logging into their WordPress user account, as well as the option to change the status of all their posts. For instance setting them to draft to hide them from public viewing. A user can be BANed from the admin users table or via their profile. There are several configurable options that allow the administrator to control how the BAN will be implemented such as redirecting them to a custom holding page, or sending them a custom email notification. Once BANned they will be unable to login until an administrator reinstates their account.</p>

<h4>Recent Reviews</h4>
<ul>
<li><a href="https://wordpress.org/support/topic/impressive-21/">The best plugin I've ever used for banning accounts!</a></li>
<li><a href="https://wordpress.org/support/topic/plugin-for-truly-banning/">At last I found plugin that truly bans...</a></li>
<li><a href="https://wordpress.org/support/topic/outstanding-plugin-support-developer/">OUTSTANDING plugin / support / developer</a></li>
</ul>

<h4>Plugin Features & Options</h4>
<ul>
<li>Quickly ban a user for a day, week, month or forever!</li>
<li>Send WARNING emails to users, including custom messages.</li>
<li>Scramble banned users' password</li>
<li>Change banned users' role</li>
<li>Set spammer status for banned users</li>
<li>Disable password reset for banned users</li>
<li>Automatically change user's Posts status (i.e. to pending) when BANned.</li>
<li>Table listing banned users, with search feature and quick links</li>
<li>Enhanced security to restrict Ban User privilage access</li>
<li>Flat icons as alternatives to text based links</li>
<li>Modal popup for sending custom messages, with textarea instead of text field</li>
<li>Supports Ultimate Member Plugin</li>
<li>Force logout when user BANned.</li>
<li>Set custom logout URL when BANned.</li>
<li>Set message to display when a BANned user attempts to login.</li>
<li>If already logged in, display message to user when BANned.</li>
<li>Send custom email notification to user when banned/unbanned.</li>
<li>Capture unique reason for banning each user.</li>
<li>Set duration of BAN using date picker.</li>
<li>BANned users highlighted in users table with icon/coloured background row.</li>
<li>Users table shows reason user banned.</li>
<li>Users table shows date user banned.</li>
<li>Users table shows date user reinstated.</li>
<li>Support for Accessibility / Screen Readers.</li>
<li>Support for BuddyPress</li>
<li>Support for CPT (Custom Post Types)</li>
<li>Plugin Shortcodes</li>
<li>Template values / custom default messages</li>
</ul>

<h4>Premium Plugin Features:</h4>
<p><a style="pointer-events: none; text-decoration: line-through;" href="https://codecanyon.net/item/wp-ultimate-ban-users/17508338">Buy Premium Version</a></p>
<ul>
<li>Capture users' IP/Geodata during login</li>
<li>Display IP/Geodata in users table</li>
<li>BAN users by date and TIME!</li>
<li>Send notifications to specified email addresses when user publishes first post</li>
<li>Prevent users with banned email address from registering</li>
<li>Prevent users with banned email address from logging in</li>
<li>Prevent users with banned ip address from registering</li>
<li>Prevent users with banned ip address from logging in</li>
<li>Easily manage add/remove/search banned email/ip addresses</li>
<li>Include custom CSS/JS in header/footer</li>
<li>SPAM prevention</li>
<li>Account security - notification for logins when accessed from a new IP</li>
</ul>

<h4>How is it Useful?</h4>
<p>There are multiple reasons why an administrator of a WordPress website may need to block users. One of the most popular reasons is the security of the site. Indeed, most blogs face security issues. Once your site is online and growing in popularity, it can become the target of individuals whose only goal is to destroy your work and to discredit your blog. The BAN Users Plugin enables administrators to deny access to unwanted individuals, and inform those users of the actions taken against them.</p>
<ul>
<li>You want to ban malicious users from logging in.</li>
<li>You want to ban users who have breached terms of use or another policy.</li>
<li>You want to temporarily control access to admin.</li>
<li>You want to restrict a registered user from making any changes during development.</li>
<li>You have a client/user who has an unpaid invoice.</li>
<li>Ideal solution for moderators who monitor wordpress forums/posts.</li>
</ul>

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the plugin files to the /wp-content/plugins directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress
3. Go to Users->All Users to view the list of users.

4a. Click on a username to access their profile page, or hover over the user and select 'Edit'.
5a. Scroll down to the 'Account Management' Section of the profile page.
6a. Click on the checkbox which says 'Ban this User' beside it to either ban or unban the user.
7a. Scroll to the bottom of the page and select 'Update User' to confirm the changes made to the user.

4b. Hover over a user to display a list of possible modifications.
5b. Press the 'Ban' / 'Unban' button in order to change the users current state.

== Frequently Asked Questions ==

= Can all users be banned, regardless of role? =

Yes, anyone can be banned by an administrator. Administrators are not able to ban themselves however.

= Who can ban / unban another user? =

Only Administrators are allowed to ban / unban other users by default.

= How will I know if another user is banned? =

In the user edit page, the user will be marked in a checkbox in the row called 'Ban User'. If you also hover over the user on the 'All Users' page, the user will be the opposite of the stated 'ban' / 'unban' button.

= How can I get unbanned? =

You will need to contact a website administrator and asked to be unbanned. Each website may have their own policy for banning / unbanning users. If all administrators are banned, remove the plugin manually. Make sure an administrator is logged in on the website. Reinstall the plugin but with 'force_logout' set to false (0) and 'custom message' also set to false (0) in the settings file. The administrator can then unban people as usual. 

== Screenshots ==

<p align="center">
1. <img alt="screenshot-1" title="screenshot-1" src="https://ps.w.org/ban-users/assets/screenshot-1.jpg"/>
</p>
<p align="center">
2. <img alt="screenshot-2" title="screenshot-2" src="https://ps.w.org/ban-users/assets/screenshot-2.jpg"/>
</p>
<p align="center">
3. <img alt="screenshot-3" title="screenshot-3" src="https://ps.w.org/ban-users/assets/screenshot-3.png"/>
</p>
<p align="center">
4. <img alt="screenshot-4" title="screenshot-4" src="https://ps.w.org/ban-users/assets/screenshot-4.png"/>
</p>
<p align="center">
5. <img alt="screenshot-5" title="screenshot-5" src="https://ps.w.org/ban-users/assets/screenshot-5.png"/>
</p>
<p align="center">
6. <img alt="screenshot-6" title="screenshot-6" src="https://ps.w.org/ban-users/assets/screenshot-6.png"/>
</p>
<p align="center">
7. <img alt="screenshot-7" title="screenshot-7" src="https://ps.w.org/ban-users/assets/screenshot-7.png"/>
</p>
<p align="center">
8. <img alt="screenshot-8" title="screenshot-8" src="https://ps.w.org/ban-users/assets/screenshot-8.png"/>
</p>
<p align="center">
9. <img alt="screenshot-9" title="screenshot-9" src="https://ps.w.org/ban-users/assets/screenshot-9.png"/>
</p>
<p align="center">
10. <img alt="screenshot-10" title="screenshot-10" src="https://ps.w.org/ban-users/assets/screenshot-10.png"/>
</p>
<p align="center">
11. <img alt="screenshot-11" title="screenshot-11" src="https://ps.w.org/ban-users/assets/screenshot-11.png"/>
</p>
<p align="center">
12. <img alt="screenshot-12" title="screenshot-12" src="https://ps.w.org/ban-users/assets/screenshot-12.png"/>
</p>
<p align="center">
13. <img alt="screenshot-13" title="screenshot-13" src="https://ps.w.org/ban-users/assets/screenshot-13.png"/>
</p>

== Changelog ==

= 1.5.4 =
* [Added] Karma column to the CPT Banned History is equal to the total banned days per user, higher karma means a greater spammer
* [Added] Registration date column to the all users table
* [Added] Authors dropdown to the edit comments for filtering comments
* [Added] Banned-history comment type to the edit comments dropdown for filtering comments
* [Added] CPT Banned History for the Ban Users logs. Logging activities as comments on the CPT
* [Added] Row actions to the comment rows in the activity dashboard widget
* [Added] Row actions to the woocommerce product reviews table
* [Added] Row actions to the edit comments table
* [Added] Show comments by user row action
* [Fixed] Dependency updates
* [Fixed] Minor PHP code refactoring

= 1.5.3 =
* Thanks to @davsev for suggesting this feature
* [Added] Support for CPT (Custom Post Types) when changing posts status
* [Fixed] Minor PHP code refactoring

= 1.5.2 =
* Massive thanks to Richard Foley for his support in beta testing/feedback
* [Added] Option to hide banned users comments on frontend
* [Added] Change banned users' role upon banning
* [Added] Change banned users' role when unbanned
* [Added] Option to scramble banned users' passwords
* [Added] Option to set spammer status for banned users
* [Added] Option to remove spammer status when unbanning users
* [Added] Option to disable password reset for banned users
* [Added] Quick links when clicked to scroll to section and reveal content
* [Added] Option to collapse all plugin setting sections by default
* [Fixed] Minor CSS amends

= 1.5.1 =
* [Added] Ability to define multiple moderator roles with ban privilage access
* [Added] Ability to define multiple roles that can be moderated (i.e. can be banned)

= 1.5.0 =
* [Fixed] Unexpected string appearing above user table
* [Fixed] Logic errors in security restriction settings
* [Added] Ability to override ban/unban access restrictions for super admins/admins

= 1.4.9 =
* [Added] Restrict ban user administrative access to users based on role hierarchy
* [Added] User insights column which displays banned history data
* [Updated] Plugin libs
* [Fixed] Removed unncessary lib files/folders to reduce plugin size

= 1.4.8 =
* [Added] Table listing banned users, with search feature & quick unban function
* [Added] Updated plugin dependencies
* [Added] Updated quick links on settings page (animation & labels)
* [Added] Save settings confirmation message

= 1.4.7 =
* [Updated] Minor UI changes to plugins settings page

= 1.4.6 =
* [Added] Email templates now support additional TAGS
* [Added] Updated depenancy libraries
* [Added] Plugin now supports localisation for translations

= 1.4.5 =
* [Fixed] Invalid markup causing php warning

= 1.4.4 =
* [Added] Updated plugin dependencies
* [Fixed] Fixed plugin settings collapse accordions
* [Fixed] Misc CSS

= 1.4.3 =
* [Added] Option to set a default ban reason that appears in custom email modal/popup
* [Added] Option to set a default warn reason that appears in custom email modal/popup

= 1.4.2 =
* [Added] Updated Plugin Shortcodes section to support additional arguments

= 1.4.1 =
* [Added] Support for BuddyPress
* [Added] New Plugin Shortcodes section
* [Fixed] Conflicting plugin css with Selectric

= 1.4.0 =
* [Added] %%reason%% tag now supported in default messages
* [Added] %%unban_date%% tag now supported in default messages

= 1.3.9 =
* [Fixed] Missing default values causing warning on Settings page.
* [Fixed] Problem with Warning email template values not saving.

= 1.3.8 =
* [Added] Enhanced security to restrict Ban User privilage access
* [Fixed] Optimised code; refactored & bug fixes.

= 1.3.7 =
* [Added] Overhauled settings page. Simplified & organised layout
* [Added] Option to enable/disable support for 3rd Party plugins
* [Added] Option to disable plugin's enqueued files to allow them to be manually included
* [Fixed] Force Logout bug when on front end
* [Fixed] Logic mistakes on settings page
* [Fixed] Optimised code; refactored & bug fixes

= 1.3.6 =
* [Fixed] Optimised code; refactored & bug fixes

= 1.3.5 =
* [Fixed] Improved options page layout, including misc fixes

= 1.3.4 =
* [Added] Ban user by duration (1 day, 1 week etc) using new dropdown select
* [Added] Improvements to date picker; moved to new modal popup
* [Fixed] Issue where Unban Cron not executed correctly
* [Fixed] PHP Compatibility issue (Thank you alfredopacino)

= 1.3.3 =
* [Added] Added accessibility option to choose text links instead of icons
* [Fixed] Improved accessibility, misc amends

= 1.3.2 =
* [Fixed] Accessibility SR issue fixed affecting fontawesoome icons

= 1.3.1 =
* [Added] Send WARNING emails to users, including custom messages
* [Added] NEW! Introduced flat icons as alternatives to text based links
* [Added] Modal popup for sending custom messages, with textarea instead of text field
* [Fixed] Various minor amends/enhancements to methods

= 1.3.0 =
* [Added] Support for Ulimate Member Plugin
* [Added] Display banned message on front end of website in a diaglog box

= 1.2.9 =
* [Fixed] Removed http links from enqueue to support SSL

= 1.2.8 =
* [Added] Promoted the Ultimate BAN Users version of the plugin

= 1.2.7 =
* [Added] Option to capture reason for ban without needing to send email notification
* [Fixed] Default reason for BAN not working in all conditions

= 1.2.6 =
* [Added] Set preferred date format (i.e. dd-mm-yyyy)
* [Added] New email template tag for including ban lift date
* [Fixed] Minor amends

= 1.2.5 =
* [Fixed] Email charset switched to UTF8
* [Fixed] BAN reason function updated to support accented characters

= 1.2.4 =
* [Fixed] Updated options save function to support accented characters
* [Fixed] Turned display/log PHP error debug off

= 1.2.3 =
* [Fixed] Undefined index error accessing default login message
* [Fixed] Undefined index error accessing default reason message

= 1.2.2 =
* [Added] Set message to display when a BANned user attempts to login
* [Added] Users table includes tooltip to show reason user banned
* [Added] Users table includes tooltip to show date user banned
* [Added] Users table includes tooltip to show date user reinstated

= 1.2.1 =
* [Added] Toggle BANned column in users table on/off
* [Added] Toggle BANned highlighted red row in users table
* [Added] Minor security enhancements

= 1.2.0 =
* [Added] Set duration of BAN using date picker
* [Fixed] Move PHP functions into class structure
* [Added] Custom UnBAN email template for user notification
* [Fixed] Secured plugin files by preventing direct access

= 1.1.1 =
* [Fixed] Fatal error: Can't use function return value in write context

= 1.1.0 =
* [Added] BAN user email notification
* [Added] Custom BAN email template for user notification
* [Fixed] Corrected force logout bug
* [Added] Ability to capture reason for BANning user
* [Added] WordPress Uninstall configuration

= 1.0.1 =
* Initial release.

== Upgrade Notice ==

= 1.3.7 =
If using the 'Ultimate Member Plugin' you will need to tick the checkbox 'Ultimate Member Plugin' under the '3rd Party Plugin Support' section in Ban Users settings page. This is because ver 1.3.7 of the Ban Users plugin now allows you to enable/disable support - as required - for 3rd Party code; but doesn't assume support by default. However, I've included a check so that until the new Ban Users settings are saved, it will continue to automatically support the 'Ultimate Member Plugin' so your sites don't break during this update.

= 1.0.1 =
Initial release
