<?php
/*
Plugin Name: Rolda Causes
Plugin URI: http://buzzvertising.ro
Description: Adds a custom post-type Causes; Requires "Advanced Custom Fileds" plugin
Version: 1.0
Author: buzzvertising
Author URI: http://buzzvertising.ro
License: GPLv2
*/


function causes_css() {
	wp_register_style('causes_css', plugins_url('css/causes.css',__FILE__ ));
	wp_enqueue_style('causes_css');
}

add_action( 'init','causes_css');

/* Define custom posts */
/* Define custom posts "Rolda Causes" */

function create_causes_adoption() {
    register_post_type( 'causes',
        array(
            'labels' => array(
                'name' => 'Rolda Causes',
                'singular_name' => 'Rolda Cause',
                'add_new' => 'Add Cause',
                'add_new_item' => 'Add New Cause',
                'edit' => 'Edit',
                'edit_item' => 'Edit Cause Data',
                'new_item' => 'New Cause',
                'view' => 'View',
                'view_item' => 'View Cause Data',
                'search_items' => 'Search Causes',
                'not_found' => 'No Causes found',
                'not_found_in_trash' => 'No Causes found in Trash',
                'parent' => 'Parent Causes'
            ),
 
            'public' => true,
            'menu_position' => 6,
            'supports' => array( 'title','editor' ),
            'taxonomies' => array( 'category' ),
            'menu_icon' => plugins_url( 'images/dog.png', __FILE__ ),
            'has_archive' => true
        )
    );
}

add_action( 'init', 'create_causes_adoption' );

function add_new_image_size() {
    add_image_size( 'cause-mainpage', 311, 219, true );
	add_image_size( 'cause-portrait', 269, 269, true );
}
add_action( 'init', 'add_new_image_size' );

/**
 * Filter callback to add image sizes to Media Uploader
 *
 * WP 3.3 beta adds a new filter 'image_size_names_choose' to
 * the list of image sizes which are displayed in the Media Uploader
 * after an image has been uploaded.
 *
 * See image_size_input_fields() in wp-admin/includes/media.php
 * 
 * Tested with WP 3.3 beta 1
 *
 * @uses get_intermediate_image_sizes()
 *
 * @param $sizes, array of default image sizes (associative array)
 * @return $new_sizes, array of all image sizes (associative array)
 * @author Ade Walker http://www.studiograsshopper.ch
 */
function sgr_display_image_size_names_muploader( $sizes ) {
	
	$new_sizes = array();
	
	$added_sizes = get_intermediate_image_sizes();
	
	// $added_sizes is an indexed array, therefore need to convert it
	// to associative array, using $value for $key and $value
	foreach( $added_sizes as $key => $value) {
		$new_sizes[$value] = $value;
	}
	
	// This preserves the labels in $sizes, and merges the two arrays
	$new_sizes = array_merge( $new_sizes, $sizes );
	
	return $new_sizes;
}
add_filter('image_size_names_choose', 'sgr_display_image_size_names_muploader', 11, 1);


/* Define path for templates */

function include_template_function( $template_path ) {
    if ( get_post_type() == 'causes' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'causes-template.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/templates/causes-template.php';
            }
        }
    }
    return $template_path;
}

add_filter( 'template_include', 'include_template_function', 1 );


/*------------ Columns on Dogs-------------*/

function my_columns( $columns ) {
    $columns['cause_signed_people'] = 'People Signing';
    unset( $columns['comments'] );
    return $columns;
}

add_filter( 'manage_edit-causes_columns', 'my_columns' );

function populate_columns( $column ) {

	switch ($column){
		case 'cause_signed_people':
			$signatures = get_post_meta( get_the_ID(), 'cause_signed_people', true );
			echo $signatures;
		  break;
	}

}

add_action( 'manage_posts_custom_column', 'populate_columns' );



/* shortcodes */

?>


