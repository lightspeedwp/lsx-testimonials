# Change log

## [[1.3.1]](https://github.com/lightspeeddevelopment/lsx-testimonials/releases/tag/1.3.1) - 2020-01-15

### Fixed
- Updated the single H5 to H1 for SEO and semantic.

### Updated
- Documentation and support links.
- Updated the changelog.

### Security
- General testing to ensure compatibility with latest WordPress version (5.6).

## [[1.3.0]](https://github.com/lightspeeddevelopment/lsx-testimonials/releases/tag/1.3.0) - 2020-10-04

### Added

- Added default WP 5.5 lazyloading.

### Changed

- Changed UIX for CMB2.
- Changed the archive template to display 2 testimonials instead of 1.

### Security

- Updating dependencies to prevent vulnerabilities.
- Updating PHPCS options for better code.
- General testing to ensure compatibility with latest WordPress version (5.5).
- General testing to ensure compatibility with latest LSX Theme version (2.9).

## [[1.2.3]](https://github.com/lightspeeddevelopment/lsx-testimonials/releases/tag/1.2.3) - 2020-03-30

### Added

- Adding support for the WordPress REST API.
- Adding 'Tag' taxonomy.

### Fixed

- Fixed issue `PHP Deprecated: dbx_post_advanced is deprecated since version 3.7.0! Use add_meta_boxes instead`.

### Security

- Updating dependencies to prevent vulnerabilities.
- General testing to ensure compatibility with latest WordPress version (5.4).
- General testing to ensure compatibility with latest LSX Theme version (2.7).

## [[1.2.2]](https://github.com/lightspeeddevelopment/lsx-testimonials/releases/tag/1.2.2) - 2019-12-19

### Security

- General testing to ensure compatibility with latest WordPress version (5.3).
- Checking compatibility with LSX 2.6 release.

## [[1.2.1]](https://github.com/lightspeeddevelopment/lsx-testimonials/releases/tag/1.2.1) - 2019-11-13

### Fixed

- Fixing the `Undefined variable: col_enabled` error.

## [[1.2.0]](https://github.com/lightspeeddevelopment/lsx-testimonials/releases/tag/1.2.0) - 2019-10-01

### Added

- Adding the .gitattributes file to remove unnecessary files from the WordPress version.
- Added in lazyloading for the sliders.
- Added in a "Review" Schema using the Yoast API.

## [[1.1.8]](https://github.com/lightspeeddevelopment/lsx-testimonials/releases/tag/1.1.8) - 2019-09-27

### Added

- Added in the Review Schema integrated with Yoast WordPress SEO.

## [[1.1.7]](https://github.com/lightspeeddevelopment/lsx-testimonials/releases/tag/1.1.7) - 2019-08-06

### Fixed

- If the excerpt does not have content it will show a trim version of the content as excerpt.
- Travis and best practices fixes.

### Changed

- Changing enqueuing scripts priority.

## [[1.1.6]](https://github.com/lightspeeddevelopment/lsx-testimonials/releases/tag/1.1.6) - 2019-06-14

### Added

- Changed the JS to trigger on page load and not right away.
- Updating NPM Dependencies and package files.
- Updating packages and making sure the border color for the block quote is grey.

### Fixed

- Services will appear only if service plugin is active.
- Updated the uix-core.js to remove the Cyclic error when saving the theme options

## [[1.1.5]](https://github.com/lightspeeddevelopment/lsx-testimonials/releases/tag/1.1.5) - 2018-08-28

### Deprecated

- Remove the inline attributes for the "data-slick" attribute and marged them with the JS code.

## [[1.1.4]](https://github.com/lightspeeddevelopment/lsx-testimonials/releases/tag/v1.1.4) - 2018-04-09

### Added

- Removed the deprecated "create_function" call.

### Fixed

- Fixing the broken method name 's_numeric'.

## [[1.1.1]]()

### Added

- Added compatibility with LSX Videos.
- Added compatibility with LSX Search.
- Fixed PHP warning issues.

## [[1.1.0]]()

### Added

- Added compatibility with LSX 2.0.
- New project structure.
- UIX copied from TO 1.1 + Fixed issue with sub tabs click (settings).
- Added compatibility with LSX Services.
- New single option - Featured post.

### Fixed

- Fixed scripts/styles loading order.
- Fixed small issues.

## [[1.0.4]]()

### Changed

- Changed the "Insert into Post" button text from media modal to "Select featured image".

### Added

- Updated NPM modules.
- Added .editorconfig file.
- Added complete language files.
- Added carousel option to function and widget.

## [[1.0.3]]()

### Fixed

- Testimonials Class error.
- Testimonials Widget Class constructor.
- Adjusted the plugin settings link inside the LSX API Class.

## [[1.0.2]]()

### Fixed

- Fixed all prefixes replaces (to* > lsx_to*, TO* > LSX_TO*).

## [[1.0.1]]()

### Fixed

- Reduced the access to server (check API key status) using transients.
- Made the API URLs dev/live dynamic using a prefix "dev-" in the API KEY.

## [[1.0.0]]()

### Fixed

- First Version.
