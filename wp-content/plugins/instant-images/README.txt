=== Instant Images - One Click Unsplash Uploads ===
Contributors: dcooney, connekthq
Donate link: https://connekthq.com/donate/
Tags: stock photo, unsplash, prototyping, photos, upload, media library, image upload, free photos
Requires at least: 4.0
Tested up to: 5.7
Stable tag: trunk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

One click uploads of Unsplash photos directly to your WordPress media library.

== Description ==

Instantly upload photos from Unsplash to your website without leaving WordPress!

**Instant Images** is the fastest and easiest way to upload high quality FREE photos from [unsplash.com](http://unsplash.com) directly to your media library.

[youtube https://www.youtube.com/watch?v=s6Q7Kfi2f1c]

The perfect tool for users who want to save time and frustration by uploading images directly inside their WordPress installation and for developers who want to prototype and develop using real world imagery.

**[Visit Plugin Website](https://connekthq.com/plugins/instant-images/)**

= Features =

- **Image Search** - The Instant Images search let’s you quickly find and upload images for any subject in a matter of seconds!
- **Image Orientation** - Filter search results by landscape, portrait or square images.
- **Time Saver** - Quickly upload amazing stock photos without leaving the comfort of your WordPress admin.
- **Theme/Plugin Developers** - A great tool for developers who want to prototype and develop using real world imagery.
- **Gutenberg** - Instant Images directly integrates with Gutenberg as a plugin sidebar.
- **Media Modal** - Instant Images is available as a tab in the WordPress Media Modal.
- **Page Builders** - Instant Images integrates with page builders such as Elementor, Beaver Builder, Brizy and Divi.
- **Edit Image Metadata** - Easily edit image filename, alt text and caption prior to uploading to your media library.
- **Accessibility** - Automatically include a relevant alt description for screen readers, visually reduced users, and SEO.
- **Easy to Use** - It couldn't get much more simple, just click an image and it's automatically uploaded to your media library for use on your site.
- **No Account Needed** - An Unsplash account is not required for use of this plugin. Just activate and you're ready to go.

---

= Tested Browsers =

- Firefox (Mac + PC)
- Chrome (Mac + PC)
- Safari (Mac)
- IE 11 >

---

= How Can You Contribute? =
Pull requests can be submitted via [GitHub](https://github.com/dcooney/instant-images).

---

= Website =
[https://connekthq.com/plugins/instant-images/](https://connekthq.com/plugins/instant-images/)

---

== Frequently Asked Questions ==

= Can I legally use these photos on my website? =
All photos published on Unsplash are licensed under Creative Commons Zero which means you can copy, modify, distribute and use the photos for free, including commercial purposes, without asking permission from or providing attribution to the photographer or Unsplash.
[Learn More](http://creativecommons.org/publicdomain/zero/1.0/)

= Can I search for individual photos by ID? =
Yes! You can enter `id:{photo_id}` into the search box to return a single result.
e.g. `id:YiUi00uqKk8`

= I'm unable to download images, what is the cause of this? =
Unfortunately, there are a number of reasons why Instant Images may not work in your current hosting/server environment. Please read through the [FAQ on our website](https://connekthq.com/plugins/instant-images/#faqs) to view some potential causes.

= Can I update the filename or metadata prior to upload? =
Yes, click the `cog` (options) icon in the bottom corner of the image to bring up an edit screen where you can modify the filename, title, alt and caption before the image is uploaded.

= Are the images upload to the Media Library? =
Yes, once clicked, the images are processed on the server then uploaded to the Media Library into the various sizes set in your theme.

= Are raw uploads stored on the server? =
No, once an image has be uploaded and resized the raw download will be removed from your server.

= Are there server requirements? =
Yes, this plugin is required to write temporary images into an `/instant-images` directory within your WordPress `uploads` directory for image processing prior to being uploaded to the media library.

Some hosts lock down their servers and you may be required to update your php.ini or .htaccess in order to use this plugin.

= Do I need an Unsplash account? =
No, there is no need to sign up from an Unsplash account to access the photos server via Instant Images.


== Installation ==

How to install Instant Images.

= Using The WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Search for 'Instant Images'
3. Click 'Install Now'
4. Activate the plugin on the Plugin dashboard

= Uploading in WordPress Dashboard =

1. Navigate to the 'Add New' in the plugins dashboard
2. Navigate to the 'Upload' area
3. Select `instant-images.zip` from your computer
4. Click 'Install Now'
5. Activate the plugin in the Plugin dashboard

= Using FTP =

1. Download `instant-images.zip`
2. Extract the `instant-images` directory to your computer
3. Upload the `instant-images` directory to the `/wp-content/plugins/` directory
4. Activate the plugin in the Plugin dashboard

== Screenshots ==

1. Dashboard - Browse, search and upload images to your WordPress media library
2. Search - Find and upload images for any subject in a matter of seconds!
3. Image Metadata - Easily edit image filename, alt text and caption prior to uploading to your media library.
4. Post/Page Edit - Unsplash images in a lightbox on your post edit/new/post pages.
5. Gutenberg post edit screens. Add as featured image, insert into post or just upload photo.
6. Instant Images is available in the WordPress media modal as a custom tab. It is available in front end page builder like Elementor, Beaver Builder and Divi.

== Changelog ==

= 4.4.0.2 - June 7, 2021 =
 * Fix: Added fix for CSS conflict causing issues in the WordPress menus section.
 * Fix: Removed browser console error with regards `activeFrame.querySelector is not a function` that could appear when creating a gallery.
 * Updated: Improved coding standard and overall code quality.

= 4.4.0.1 - May 3, 2021 =
* UPDATE - Updated Instant Images settings page to sanitize inputs before saving.


= 4.4.0 - March 26, 2021 =
* UPDATE - 🎉 Massive improvement to image download speeds by [dynamically resizing](https://unsplash.com/documentation#dynamically-resizable-images) the initial download before sending image to media library.
  * Intitial testing revealed up to 4x faster download speeds than previous version of Instant Images 🤯.
* NEW - Added `instant_images_after_upload` hook that is dispatch after a successful upload allowing users to hook into the attachment and run custom functionality.
* UPDATE - Upgraded Axios JS libray to `0.21.1`.


= 4.3.5 - August 14, 2020 =
* FIX - Fixed issue with WP 5.5 and REST API warning messages when `WP_DEBUG` is `true`.
* FIX - Fixed issue with undefined `$suffix` variable when `WP_DEBUG` is `true`.


= 4.3.4 - August 11, 2020 =
* FIX - Fixed issue with Yoast SEO meta boxes not appearing in Classic Editor.
* UPDATE - Code cleanup and some refactoring of media enqueue scripts.


= 4.3.3 - August 10, 2020 =
* NEW - Adding Instant Images tab to Media Modal windows everywhere, including page builders and taxonomy terms pages.
* NEW - Added new plugin setting to hide the Instant Images tab in the Media Modals


= 4.3.2 - May 28, 2020 =
- UPDATE - Switched REST API methods to `POST` requests from `PUT`. This will hopefully reduce issues users are having with `PUT` being disabled on their servers.
- UPDATE - Added Instant Images media button back to Classic Editor post screen.


= 4.3.1 - April 13, 2020 =
- FIX - Fixed issue with Instant Images causing Yoast SEO metabox to not show correctly in the classic WordPress editor. Not really sure why, but the Instant Images JS dependencies seemed to interfere with Yoast.
- FIX - Added user privileges checks to the new Media Modal functionality.


= 4.3.0 - April 9, 2020 =
- NEW - Adding `Instant Images` tab to the WordPress Media Modal.
- NEW - When images are uploaded directly to a post the current Post ID is attached to the upload as the parent post.
- NEW - Adding default alt text directly from Unsplash API.
- UPDATE - Updated image uploader API to _hopefully_ improve stability of the upload and resize process. The new process uses core WordPress functions for the upload.
- UPDATE - Switching tab navigation from `<a/> to`<button/>` for better accessibility.
- FIX - Added a fix for JS error regarding `PluginSidebar` registration on non-gutenberg editor pages.


= 4.2.0 - December 14, 2019 =
- NEW - Added image orientation search filter
- FIX - Fixed issue with instant images being rendered in Gutenberg editor for users without permissions.
- UPDATE - Updated WordPress role requirement from `edit_theme_options` to [`upload_files`](https://wordpress.org/support/article/roles-and-capabilities/#upload_files).


= 4.1.0 - July 23, 2019 =
- NEW - Added support for updating image title prior to upload.
- NEW - Added link to edit image after upload process completes.
- UPDATE - Updated functionality to trigger photo upload immediately after triggering a `Save` when editing image metadata.


= 4.0.1 - April 18, 2019 =
- FIX - Fixed issue where Instant Images sidebar plugin would not appear in Gutenberg if removed as a pinned item.


= 4.0.0 - February 12, 2019 =
- 4.0 adds Gutenberg support. You can now access instant images directly from inside the block editor.
- NEW - Added Instant Images to Gutenberg as a Plugin Sidebar.
- NEW - Added Gutenberg featured image support.
- NEW - Added Gutenberg Create Image Block support.

- UPDATE - Improved a11y (accessibility) of photo listing items.
- UPDATE - Updated REST API methods to prefix function names.
- UPDATE - Various other UI/UX enhancements.

= 3.0 - September 21, 2017 =
** NEW - Instant Images has been completely re-built using React and the WordPress REST API.

= 2.0 - January 12, 2017 =

- Initial Commit
- Updating plugin from UnsplashWP to Instant Images

== Upgrade Notice ==

- This is an upgrade from UnsplashWP
