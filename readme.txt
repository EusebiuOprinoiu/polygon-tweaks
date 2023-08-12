# Polygon Tweaks

Contributors:      PolygonThemes, EusebiuOprinoiu
Tags:              tweaks, media, login
Stable tag:        trunk
Requires PHP:      7.4
Tested up to:      6.3
Requires at least: 5.8
License:           GPLv3 or later
License URI:       https://choosealicense.com/licenses/gpl-3.0
Donate link:       https://paypal.me/EusebiuOprinoiu

Tweaks and fixes for a better WordPress experience.

## Description

Polygon Tweaks is a simple plugin with tweaks and fixes for a better experience on your WordPress website.

## Installation

### Automatic Installation

The automatic installation is the easiest option to install a plugin as WordPress handles the file transfers itself. To do an automatic install, log in to your WordPress dashboard and follow the steps below:

1. Download the plugin to your local computer
2. Navigate to the Plugins menu and click "Add New"
3. Click "Upload Plugin" and select the zip file downloaded earlier
4. Install the plugin by clicking "Install Now"
5. Activate "Polygon Tweaks" from the "Plugins" menu

### Manual Installation

The manual installation method involves downloading the plugin and uploading it on your server via SFTP. To do a manual install follow the steps below:

1. Download the plugin to your local computer
2. If downloaded as a zip archive extract it to your Desktop
3. Upload the plugin folder to the /wp-content/plugins/ directory
4. Activate "Polygon Tweaks" from the "Plugins" menu

## Frequently Asked Questions

No questions yet!

## Changelog

#### Version 1.2.2
- Increase the admin email check interval from 6 months to 2 years

#### Version 1.2.1
- Minor improvements to admin notices
- New links to remove from plugin meta

#### Version 1.2.0
- Minor code refactoring
- Show a plugin icon and banner while performing updates
- Language files updated

#### Version 1.1.6
- Remove Jetpack and WooCommerce nags and upsells

#### Version 1.1.5
- Removed ShortPixel tweaks

#### Version 1.1.4
- Default JPEG quality set to 100 for new thumbnails

#### Version 1.1.3
- Default JPEG quality set to 85 for new thumbnails

#### Version 1.1.2
- Prevent ShortPixel from re-adding removed image sizes

#### Version 1.1.1
- Minor code refactoring

#### Version 1.1.0
- disable attachment pages and prevent them from reserving URL paths
- replace all references to attachment pages with URLs to the attachment files
- prefix all new attachment slugs to minimize potential name conflicts if the plugin is ever deactivated
- redirect attachment pages to the attachment file (in case an attachment page is called via code)

#### Version 1.0.6
- Use a filter hook to remove image sizes, not an action

#### Version 1.0.5
- Load a stylesheet in the WordPress editor with minor tweaks for the UI

#### Version 1.0.4
- Disabled the generation of a small thumbnail

#### Version 1.0.3
- New image sizes for improved adaptive images
- Change srcset size limit from default value to 3840px

#### Version 1.0.2
- Better detection for useless links in plugin meta

#### Version 1.0.1
- Removed useless links from plugin meta (Donate, Leave a review, Upgrade to premium, etc)

#### Version 1.0.0
- First release
