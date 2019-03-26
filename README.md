# LSX Search
Contributors: feedmymedia
Donate link: https://donate.lsdev.biz/
Tags: lsx theme, search, facetwp, Gutenberg, category
Requires at least: 4.3
Tested up to: 5.1.1
Requires PHP: 7.0
Stable tag: 1.0.9
License: GPLv3

The [LSX Search Extension](https://lsx.lsdev.biz/extensions/lsx-search/) is a FacetWP templating plugin that provides deep integration with the LSX Theme

## Description

The is a FacetWP templating plugin that provides deep integration with the LSX Theme

## Dependancies

The LSX Search plugin enhances the search functionality for the LSX Theme and requires FacetWP to be installed to function properly.

1. Requires the [LSX Theme](https://lsx.lsdev.biz/) to be installed and active on the website.
2. Requires [FacetWP](https://facetwp.com/) to be installed and active on the website.

## Works with the LSX Theme
Our theme [theme](https://lsx.lsdev.biz/) works perfectly with the LSX Search, improving search capabilties.

## Gutenberg Compatible
Have you updated to the new WordPress Gutenberg editor? We've got you covered! [The LSX Theme](https://lsx.lsdev.biz/) and all of its extensions have been optimised to work perfectly with the new Gutenberg update. 

## It's free, and always will be.
We’re firm believers in open source - that’s why we’re releasing the LSX Search plugin for free, forever.

## Support
We offer premium support for this plugin. Premium support that can be purchased via [our website.](https://www.lsdev.biz/services/support/).

## Installation
To download and install the LSX Banners Extension, follow the steps below:

1. Login to the backend of your website.
2. Navigate to the “Plugins” dashboard item.
3. Select “Add New” when on the plugins page.
4. Search for “LSX Search” in the plugin searchbar.
5. Download and activate the plugin.

## Frequently Asked Questions
### Where can I find LSX Search plugin documentation and user guides?
For help setting up and configuring the Search plugin please refer to our [user guide](https://www.lsdev.biz/documentation/lsx/search-extension/)

### Where can I get support or talk to other users
For add-on support from LightSpeed, [get a quote](https://www.lsdev.biz/contact-us/) from us!

### Will the LSX Search plugin work with my theme?
Not unless you are making use of the [The LSX theme!](https://lsx.lsdev.biz/) 

All of the LSX Extensions were built for the LSX theme. Be sure to have it installed and activated for this extension to function. 

### Where can I report bugs or contribute to the project?
If you are encountering a bug issue with the LSX theme or one of the LSX extensions, report the issue by getting in touch! If the bug is a problem within the core of the plugin, we will resolve the issue as soon as possible. 

### The LSX Search plugin is awesome! Can I contribute?
Yes you can! Join in on our [GitHub repository](https://github.com/lightspeeddevelopment/lsx-search) :)

## LSX Theme Resources

Enable LSX Search for your custom taxonomy
https://gist.github.com/krugazul/fe06b489741aeff32516efb92bf9ec7f


## Shortcode:

Insert the shortcode `[testimonials]` into any page to display all testimonials.

### Optional shortcode parameters:

### Columns
 Set the number of columns to Display
 
 - options: 1, 2, 3, 4
 - default: 1
 
 For Example `[testimonials columns=4]`

### Orderby
 Choose how the testimonails should be ordered
 
 - options: none, ID, name, date, rand (or any of the orderby options accepted by WP_Query - http://codex.wordpress.org/Class_Reference/WP_Query)
 - default: name
 
 For Example `[testimonials orderby=rand]`

### Order
 Whether to display testimonials in Ascending or Descending order (Based on the Orderby Parameter)
 
 - options: ASC, DESC
 - default: ASC
 
 For Example `[testimonials order='DESC']`

### Limit

 Set the Maximum number of testimonials to be returned
 
 For Example `[testimonials limit=4]`

### Include
 Specify which testimonials to include by entering a comma seperated list of IDs. (This overrides the order and limit parameters; testimonials will display in the order in which the IDs are entered)
 
 For Example `[testimonials include='7, 38, 19']`

### Size
 Set the featured image or Gravatar size to display on each testimonial. Accepts numbers only, exclude the 'px'.
 
 - default: 150
 
 For Example `[testimonials size=200]`

### Responsive
 Choose whether the images should resize according to the size of the viewport (enabled by default)
 
 - default: true
 
 For Example `[testimonials responsive=false]`

### Show_image
 Choose whether the images should display (enabled by default)
 
 - default: true
 
 For Example `[testimonials show_image=false]`

## Function Call:

The testimonials function can be called directly in your theme templates. It accepts an array of the same parameters used in the shortcode.

eg:
```
<?php
	if ( class_exists( 'LSX_Testimonials' ) ) {
        lsx_testimonials( array(                                        
            'size' => 150,
            'responsive' => false,
            'columns' => 3,
            'limit' => 6,
            'carousel' => true,
        ) );
    };
?>
```
