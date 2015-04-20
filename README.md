**ADOPT ME!** Press This! was completely revamped in WordPres 4.2 and as such I will be focusing any effort that may have been put into Linkmarklet into that feature. As a result I am actively seeking someone to take over development of Linkmarklet. Please contact me if you are interested in taking over development of Linkmarklet. Thank you for considering!

This is a WordPress plugin. [Official download available on the WordPress Plugin Directory](http://wordpress.org/extend/plugins/linkmarklet/).

# Linkmarklet

Linkmarklet is an alternative to the Press This! bookmarklet aimed at rapid linkblogging. Quickly post while saving a link to a Custom Field.

* [Description](#description)
* [Installation](#installation)
* [Usage](#usage)
* [Screenshots](#screenshots)
* [Changelog](#changelog)

## Description

Instead of the traditional Press This! interface, Linkmarklet offers a much more streamlined UI allowing you to linkblog quickly. Upon clicking the bookmarklet, Linkmarklet will present you with a simple way of editing the page title, the page link, the page slug, and the post content. In the settings you can define three things:

1. The category to which Linkmarklet posts will be added
1. The Custom Field name you're using to store the submitted link
1. The Post Format you would like to use for each post
1. Whether or not to Future Post the current post and by how many minutes compared to your future-most scheduled post

That's about it. Clicking Publish pushes the entry live instantly (or schedules it). Clicking Save will store the post as a Draft.

## Installation

1. Download the plugin and extract the files
1. Upload `linkmarklet` to your `~/wp-content/plugins/` directory
1. Activate the plugin through the 'Plugins' menu in WordPress
1. Install the bookmarklet provided on the `Settings > Linkmarklet` screen
1. Customize your installation using the `Settings > Linkmarklet` menu in the WordPress admin

## Usage

1. Install the bookmarklet provided on the `Settings > Linkmarklet` screen
1. When you'd like to linkblog a page you're viewing, simply click the bookmarklet and publish

## Screenshots

##### Linkmarklet UI
![Linkmarklet UI](http://mondaybynoon.com/images/linkmarklet/screenshot-1.png)

##### Linkmarklet Settings
![Linkmarklet Settings](http://mondaybynoon.com/images/linkmarklet/screenshot-2.png)

## Changelog

<dl>

    <dt>0.7</dt>
    <dd>Fixed an issue where post Tags were set after the post was published which caused interference with other plugin processes</dd>

    <dt>0.6</dt>
    <dd>Fixed an issue where Future Publish settings wouldn't properly unset after being set.</dd>
    <dd>Added autocomplete to Tags</dd>

    <dt>0.5.3</dt>
    <dd>Fixed an edge case issue where Linkmarklet would show up as a 404 when invoked. Triggered by certain web hosts (in particular HostGator) that seem to interfere when a $_GET includes a protocol.</dd>

    <dt>0.5.2</dt>
    <dd>Removed unwanted escaping of post content that was causing issue with Markdown and inline HTML</dd>

    <dt>0.5.1</dt>
    <dd>Fixed an issue where offiste image processing would not take place if using HTTPS</dd>

    <dt>0.5</dt>
    <dd>Support for offsite images. If you include a Markdown-formatted image (e.g. <code>![Alt text](http://example.com/image.jpg)</code>) it will be downloaded and imported into your Media library so as to not hotlink someone else's image. The image will be wrapped a link to the source article so as to mimic OEMBED policies. <strong>Note:</strong> image titles are not supported at this time.</dd>

    <dt>0.4</dt>
    <dd>Support for Future Publishing. If you're like me, you like to bulk-linkblog, but you don't want to innundate readers with tons of posts all at once. There's now a setting that will let you auto-schedule a post to go live within a timeframe after your future-most-scheduled post. You can also set a 'bumper' of time between your most recently published posts.</dd>
    <dd>Added (optional) support for Tags</dd>
    <dd>Initial support for <a href="http://wordpress.org/extend/plugins/markdown-on-save/">Markdown on Save</a></dd>

    <dt>0.3</dt>
    <dd>Support for Post Formats</dd>

    <dt>0.2</dt>
    <dd>Better handling of text input fields on mobile devices</dd>
    <dd>Option to auto-generate the slug (or not)</dd>
    <dd>Added 'Save' button to save as a Draft instead of Publish straight away</dd>

    <dt>0.1</dt>
    <dd>Initial release</dd>

</dl>
