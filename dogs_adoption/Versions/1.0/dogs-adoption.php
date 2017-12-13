<?php
/*
Plugin Name: Dogs Adoption
Plugin URI: http://buzzvertising.ro
Description: Adds a custom post-type Dogs
Version: 1.0
Author: buzzvertising
Author URI: http://buzzvertising.ro
License: GPLv2
*/

add_action( 'init', 'create_dogs_adoption' );
add_action( 'init', 'create_attributes' );
add_action( 'init', 'create_states' );
add_filter( 'manage_edit-dogs_columns', 'my_columns' );
add_action( 'manage_posts_custom_column', 'populate_columns' );
add_filter( 'manage_edit-dogs_sortable_columns', 'sort_me' );


function create_dogs_adoption() {
    register_post_type( 'dogs',
        array(
            'labels' => array(
                'name' => 'Dogs Adoption',
                'singular_name' => 'Dogs Adoption',
                'add_new' => 'Add Dog',
                'add_new_item' => 'Add New Dog',
                'edit' => 'Edit',
                'edit_item' => 'Edit Dog Data',
                'new_item' => 'New Dog',
                'view' => 'View',
                'view_item' => 'View Dog Data',
                'search_items' => 'Search Dogs',
                'not_found' => 'No Dogs found',
                'not_found_in_trash' => 'No Dogs found in Trash',
                'parent' => 'Parent Dogs'
            ),
 
            'public' => true,
            'menu_position' => 3,
            'supports' => array( 'title' ),
            'taxonomies' => array( '' ),
            'menu_icon' => plugins_url( 'images/dog.png', __FILE__ ),
            'has_archive' => true
        )
    );
}

function create_states() {
           $State_args = array( 
            'hierarchical' => true,  
                'labels' => array(
            'name'=> _x('States', 'taxonomy general name' ),
            'singular_name' => _x('State', 'taxonomy singular name'),
            'search_items' => __('Search States'),
            'popular_items' => __('Popular States'),
            'all_items' => __('All States'),
            'edit_item' => __('Edit State'),
            'edit_item' => __('Edit State'),
            'update_item' => __('Update State'),
            'add_new_item' => __('Add New State'),
            'new_item_name' => __('New State Name'),
            'separate_items_with_commas' => __('Seperate States with Commas'),
            'add_or_remove_items' => __('Add or Remove States'),
            'choose_from_most_used' => __('Choose from Most Used States')
            ),  
                'query_var' => true,  
            'rewrite' => array('slug' =>'State')        
           );
           register_taxonomy('State', 'dogs',$State_args);
}

function create_attributes() {
           $attribute_args = array( 
            'hierarchical' => true,  
                'labels' => array(
            'name'=> _x('Attributes', 'taxonomy general name' ),
            'singular_name' => _x('Attribute', 'taxonomy singular name'),
            'search_items' => __('Search Attributes'),
            'popular_items' => __('Popular Attributes'),
            'all_items' => __('All Attributes'),
            'edit_item' => __('Edit Attribute'),
            'edit_item' => __('Edit Attribute'),
            'update_item' => __('Update Attribute'),
            'add_new_item' => __('Add New Attribute'),
            'new_item_name' => __('New Attribute Name'),
            'separate_items_with_commas' => __('Seperate Attributes with Commas'),
            'add_or_remove_items' => __('Add or Remove Attributes'),
            'choose_from_most_used' => __('Choose from Most Used Attributes')
            ),  
                'query_var' => true,  
            'rewrite' => array('slug' =>'Attribute')        
           );
           register_taxonomy('Attribute', 'dogs',$attribute_args);
}

/*------------ Columns -------------*/

function my_columns( $columns ) {
    $columns['dogs_age'] = 'Age';
    $columns['dogs_gender'] = 'Gender';
    $columns['dogs_sponsored'] = 'Sponsored';
    $columns['dogs_updates'] = 'Has Updates';
    unset( $columns['comments'] );
    return $columns;
}

function populate_columns( $column ) {

	switch ($column){
		case 'dogs_age':
			$birthdate = get_post_meta( get_the_ID(), 'birthdate', true );
			$age = getAge($birthdate);
			echo $age;
		  break;
		case 'dogs_gender':
			$dogs_gender = get_post_meta( get_the_ID(), 'gender', true );
			echo $dogs_gender;
		  break;
		case 'dogs_updates':
			$dogs_updates = get_post_meta( get_the_ID(), 'updates_article', true );
			if ( $dogs_updates == 'null' ) { 
				echo 'No';
			}
			else {
				echo 'Yes';
			}
		  break;
		case 'dogs_sponsored':
			global $wpdb;
			$campaign_id = get_post_meta( get_the_ID(), '_campaign_id', true );
			$don_table_name = $wpdb->prefix.'donation';
			
			$donate_rw = $wpdb->get_results("SELECT sum(don_amt) as totalreceive FROM $don_table_name WHERE don_camp_id=".$campaign_id );
			if( $donate_rw[0]->totalreceive != "") { echo "$".$donate_rw[0]->totalreceive; } else { echo "$0"; }

		  break;	  
	  
	}

}

add_filter( 'request', 'column_orderby' );
 
function column_orderby ( $vars ) {
    if ( !is_admin() )
        return $vars;

    if ( isset( $vars['orderby'] ) )
		switch ($vars['orderby']){
			case 'dogs_age':
				$vars = array_merge( $vars, array( 'meta_key' => 'birthdate', 'orderby' => 'meta_value' ) );
			break;		
			case 'dogs_gender':
				$vars = array_merge( $vars, array( 'meta_key' => 'gender', 'orderby' => 'meta_value' ) );
			break;		
			case 'dogs_sponsored':
				$vars = array_merge( $vars, array( 'meta_key' => 'sponsored', 'orderby' => 'meta_value' ) );
			break;		
		}
		
    return $vars;
}

function sort_me( $columns ) {
    $columns['dogs_age'] = 'dogs_age';
    $columns['dogs_gender'] = 'dogs_gender';
    $columns['dogs_sponsored'] = 'dogs_sponsored';	
    return $columns;
}

add_action( 'restrict_manage_posts', 'my_filter_list' );

function my_filter_list() {
    $screen = get_current_screen();
    global $wp_query;
    if ( $screen->post_type == 'dogs' ) {
        wp_dropdown_categories( array(
            'show_option_all' => 'Show All Dogs',
            'taxonomy' => 'State',
            'name' => 'State',
            'orderby' => 'name',
            'selected' => ( isset( $wp_query->query['State'] ) ? $wp_query->query['State'] : '' ),
            'hierarchical' => false,
            'depth' => 3,
            'show_count' => false,
            'hide_empty' => true,
        ) );
    }
}

add_filter( 'parse_query','perform_filtering' );

function perform_filtering( $query ) {
    $qv = &$query->query_vars;
    if ( ( $qv['State'] ) && is_numeric( $qv['State'] ) ) {
        $term = get_term_by( 'id', $qv['State'], '' );
        $qv['State'] = $term->slug;
    }
}




function getAge($DateOfBirth) {
	$DateOfBirth      = strtotime($DateOfBirth);
	$DateDifference   = time() - $DateOfBirth;
	$AgeInMonths      = $DateDifference /(60*60*24*30);
	$Years	          = floor($AgeInMonths/12);
	$Months           = round($AgeInMonths%12);
	if ($Years == 0 ) $age = $Months.' months';
	else {$age = $Years.'  years';}
	return $age;
}


add_filter( 'template_include', 'include_template_function', 1 );

function include_template_function( $template_path ) {
    if ( get_post_type() == 'dogs' ) {
        if ( is_single() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'single-dogs.php' ) ) ) {
                $template_path = $theme_file;
            } else {
                $template_path = plugin_dir_path( __FILE__ ) . '/single-dogs.php';
            }
        }
    }
    return $template_path;
}

/* Create a donation campaign for each dog you add */

function campaign_create($post_id) {
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
  if ('dogs' == $_POST['post_type']) {
    if (!current_user_can('edit_page', $post_id)) return;
  } else {
    if (!current_user_can('edit_post', $post_id)) return;
  }
  $campaign_id = get_post_meta($post_id, '_campaign_id', true);
  if ( get_the_title($post_id) != 'Auto Draft' && $campaign_id == '') {
    // it's a new record
	global $wpdb;
	$wpdb->insert( 
		$wpdb->prefix.'campaigns', 
		array( 
			
			'camp_name' => $wpdb->escape('Sponsor dog - '.get_the_title($post_id)),
			'camp_goal_amt' => $wpdb->escape(get_post_meta($post_id, 'amount_required', true)),
			'camp_descr' => $wpdb->escape(get_post_meta($post_id, 'needs_description', true)),
			'camp_field_title' =>  0 ,		
			'camp_field_first_name' =>   0 ,	
			'camp_field_last_name' =>   0 ,
			'camp_field_country' =>   0 ,		
			'camp_field_address' =>   0 ,
			'camp_field_city' =>   0 ,			
			'camp_field_state' =>   0 ,		
			'camp_field_zip' =>  0 ,			
			'camp_field_phone' =>   0 ,		
			'camp_field_email' =>  0 ,		
			'camp_field_anonymous' =>   1 ,						
			'camp_create_date' =>  date("Y-m-d")
			
		)
	);
    
	$lastid = $wpdb->insert_id;
	update_post_meta($post_id, '_campaign_id', $lastid); 

	} else {
    // it's an existing record

		global $wpdb;

		$wpdb->update( 
			$wpdb->prefix.'campaigns', 
			array( 
				
				'camp_name' => $wpdb->escape('Sponsored dog - '.get_the_title($post_id)),
				'camp_goal_amt' => $wpdb->escape(get_post_meta($post_id, 'amount_required', true)),
				'camp_descr' => $wpdb->escape(get_post_meta($post_id, 'needs_description', true)),
				'camp_field_title' =>  0 ,		
				'camp_field_first_name' =>   0 ,	
				'camp_field_last_name' =>   0 ,
				'camp_field_country' =>   0 ,		
				'camp_field_address' =>   0 ,
				'camp_field_city' =>   0 ,			
				'camp_field_state' =>   0 ,		
				'camp_field_zip' =>  0 ,			
				'camp_field_phone' =>   0 ,		
				'camp_field_email' =>  0 ,		
				'camp_field_anonymous' =>   1 ,						
				'camp_create_date' =>  date("Y-m-d")
				
			), 
			array( 'camp_id' => $campaign_id )
		);

	
  }
 
}
add_action('save_post', 'campaign_create');

add_action('wp_trash_post', 'campaign_delete');

function campaign_delete($post_id) {

  if ('dogs' == $_POST['post_type']) {
    if (!current_user_can('delete_posts')) return;
  }
  
  $campaign_id = get_post_meta($post_id, '_campaign_id', true);

  if ( $campaign_id != '') {
	global $wpdb;
	$wpdb->delete( $wpdb->prefix.'campaigns', array( 'camp_id' => $campaign_id ) );
  }

}


?>