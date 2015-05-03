=== Linkmarklet ===
Contributors: jchristopher
Tags: adopt-me, link, linkblog, press this
Requires at least: 3.3
Tested up to: 4.2.1
Stable tag: 0.8
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

[ADOPT ME!] Linkmarklet is an alternative to the Press This! bookmarklet aimed at rapid linkblogging. Quickly post while saving a link to a Custom Field.

== Description ==

**ADOPT ME!** Press This! was completely revamped in WordPres 4.2 and as such I will be focusing any effort that may have been put into Linkmarklet into that feature. As a result I am actively seeking someone to take over development of Linkmarklet. Thank you for considering!

Instead of the traditional Press This! interface, Linkmarklet offers a much more streamlined UI allowing you to linkblog quickly. Upon clicking the bookmarklet, Linkmarklet will present you with a simple way of editing the page title, the page link, the page slug, and the post content. In the settings you can define three things:

1. The category to which Linkmarklet posts will be added
2. The Custom Field name you're using to store the submitted link
3. The Post Format you would like to use for each post
4. Whether or not to Future Post the current post and by how many minutes compared to your future-most scheduled post

That's about it. Clicking Publish pushes the entry live instantly (or schedules it). Clicking Save will store the post as a Draft.

== Installation ==

1. Download the plugin and extract the files
1. Upload `linkmarklet` to your `~/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Install the bookmarklet provided on the `Settings > Linkmarklet` screen
1. Customize your installation using the `Settings > Linkmarklet` menu in the WordPress admin

== Changelog ==

= 0.8 =
* ADOPT ME! Linkmarklet is up for adoption, if you would like to take ownership please let me know! Contact information on my wordpress.org Profile page
* Misc code standardization, formatting, and CSS updates

= 0.7 =
* Fixed an issue where post Tags were set after the post was published which caused interference with other plugin processes

= 0.6 =
* Fixed an issue where Future Publish settings wouldn't properly unset after being set.
* Added autocomplete to Tags

= 0.5.3 =
* Fixed an edge case issue where Linkmarklet would show up as a 404 when invoked. Triggered by certain web hosts (in particular HostGator) that seem to interfere when a $_GET includes a protocol.

= 0.5.2 =
* Removed unwanted escaping of post content that was causing issue with Markdown and inline HTML

= 0.5.1 =
* Fixed an issue where offiste image processing would not take place if using HTTPS

= 0.5 =
* Support for offsite images. If you include a Markdown-formatted image (e.g. `!&#91;Alt text&#93;&#40;http://example.com/image.jpg&#41;`) it will be downloaded and imported into your Media library so as to not hotlink someone else's image. The image will be wrapped a link to the source article so as to mimic OEMBED policies. **Note:** image titles are not supported at this time.

= 0.4 =
* Support for Future Publishing. If you're like me, you like to bulk-linkblog, but you don't want to innundate readers with tons of posts all at once. There's now a setting that will let you auto-schedule a post to go live within a timeframe after your future-most-scheduled post. You can also set a 'bumper' of time between your most recently published posts.
* Added (optional) support for Tags
* Initial support for [Markdown on Save](http://wordpress.org/extend/plugins/markdown-on-save/)

= 0.3 =
* Support for Post Formats

= 0.2 =
* Better handling of text input fields on mobile devices
* Option to auto-generate the slug (or not)
* Added 'Save' button to save as a Draft instead of Publish straight away

= 0.1 =
* Initial release

== Screenshots ==

1. Linkmarklet UI
2. Linkmarklet Settings
