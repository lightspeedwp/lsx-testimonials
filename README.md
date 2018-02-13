# LSX Testimonials

The LSX Testimonials extension adds the "Testimonials" post type, which you can display front-and-centre on your site.

## Setup

### 1: Install NPM
https://nodejs.org/en/

### 2: Install Gulp
`npm install`

This will run the package.json file and download the list of modules to a "node_modules" folder in the plugin.

### 3: Gulp Commands
`gulp watch`
`gulp compile-sass`
`gulp compile-js`
`gulp wordpress-lang`

## Post Type and Fields:

On activation, the LSX Testimonials plugin creates a Testimonials post type on your site. 

### Testimonials Post Type fields

- Post Title: Client's name
- Post Body: Client's testimonial
- Featured Image: Client's photograph or image
- Gravatar Email Address: Client's email address. If a featured image has not been set, this field will be used to display the client's Gravatar if one is available.
- Byline: Client's byline, generally used to display their position in the company.
- URL: Link to client's website.

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
