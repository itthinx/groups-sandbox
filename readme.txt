=== Groups Sandbox ===
Contributors: itthinx, proaktion
Donate link: http://www.itthinx.com/plugins/groups
Tags: groups, access, access control, membership, memberships, member, members, capability, capabilities, content, download, downloads, file, file access, files, paypal, permission, permissions, subscription, subscriptions, woocommerce
Requires at least: 4.0
Tested up to: 4.8.1
Stable tag: 1.0.0
License: GPLv3

A sandbox plugin for examples and useful code related to <a href=" https://wordpress.org/plugins/groups">Groups</a>.

== Description ==

A sandbox plugin for examples and useful code related to <a href=" https://wordpress.org/plugins/groups">Groups</a>.

This plugin is not perfect, it might simply help someone working with Groups.

The plugin currently only provides the [groups_sandbox_posts] shortcode which lists posts.
This shortcode uses the get_posts() function.
It accepts these attribtues:

- group : can be left empty to show all posts; indicate group ids or group names to limit the posts shown to those that are restricted by the given groups
- numberposts : limit the number of results; defaults to -1 for unlimited results
- order : 'asc' or 'desc' for ascending or descending order
- orderby : used to determine how the output is sorted; defaults to 'title', also uses 'none', 'ID', 'date', ... see https://codex.wordpress.org/Template_Tags/get_posts
- post_type : one or more post types separated by comma; defaults to 'post'
- post_status : one or more post statuses separated by comma; defaults to 'publish'
- suppress_filters : 'yes' or 'no', defaults to 'no' - set to 'yes' if you want to include posts that are restricted and the current user is not allowed to see see them

== Examples ==

Put these shortcodes on a page, save it and view it on the front end.
View it while logged out and compare the output while logged in and belonging to the relevant groups:

a) With no attributes:

	[groups_sandbox_posts]

b) Showing posts restricted to the "Registered" group:

	[groups_sandbox_posts group="Registered" suppress_filters="no"]

The same thing but skipping filters - visitors who are not logged in or don't have an account will also see the links:

	[groups_sandbox_posts group="Registered" suppress_filters="yes"]

c) Using a couple of group, "Test, Secret" :

	[groups_sandbox_posts group="Test,Secret" suppress_filters="no"]

Using groups="Test, Secret" and suppress_filters="yes":

	[groups_sandbox_posts group="Test,Secret" suppress_filters="yes"]
