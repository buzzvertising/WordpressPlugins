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


function dogs_css() {
	wp_register_style('dogs_css', plugins_url('css/dog-style.css',__FILE__ ));
	wp_enqueue_style('dogs_css');
}

add_action( 'init','dogs_css');

/* Define custom posts */
/* Define custom posts "Dogs Adoption" */

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
            'menu_position' => 6,
            'supports' => array( 'title' ),
            'taxonomies' => array( '' ),
            'menu_icon' => plugins_url( 'images/dog.png', __FILE__ ),
            'has_archive' => true
        )
    );
}

add_action( 'init', 'create_dogs_adoption' );

function add_new_image_size() {
    add_image_size( 'dog-mainpage', 311, 219, true );
	add_image_size( 'dog-portrait', 269, 269, true );
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


/* Define custom posts "Sponsor Packs" */

function create_sponsor_packages() {
    register_post_type( 'sponsor_packages',
        array(
            'labels' => array(
                'name' => 'Sponsor Packs',
                'singular_name' => 'Sponsor Pack',
                'add_new' => 'Add Pack',
                'add_new_item' => 'Add New Pack',
                'edit' => 'Edit',
                'edit_item' => 'Edit Pack Data',
                'new_item' => 'New Pack',
                'view' => 'View',
                'view_item' => 'View Pack Data',
                'search_items' => 'Search Packs',
                'not_found' => 'No Packs found',
                'not_found_in_trash' => 'No Packs found in Trash',
                'parent' => 'Parent Packs'
            ),
 
            'public' => true,
            'menu_position' => 7,
            'supports' => array( 'title' ),
            'taxonomies' => array(''),
            'menu_icon' => plugins_url( 'images/packs.png', __FILE__ ),
            'has_archive' => true
        )
    );
	register_taxonomy(
		'type',
		'sponsor_packages',
		array(
            'label' => __( 'Type' ),
            'public' => false,
            'rewrite' => false,
            'hierarchical' => true,
        )
	);	
}


add_action( 'init', 'create_sponsor_packages' );

/* Create Taxonomies */

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

add_action( 'init', 'create_states' );

function create_pack_type() {
           $State_args = array( 
            'hierarchical' => true,  
                'labels' => array(
            'name'=> _x('Pack Types', 'taxonomy general name' ),
            'singular_name' => _x('Pack Type', 'taxonomy singular name'),
            'search_items' => __('Search Pack Types'),
            'popular_items' => __('Popular Pack Types'),
            'all_items' => __('All Pack Types'),
            'edit_item' => __('Edit Pack Type'),
            'edit_item' => __('Edit Pack Type'),
            'update_item' => __('Update Pack Type'),
            'add_new_item' => __('Add New Pack Type'),
            'new_item_name' => __('New Pack Type Name'),
            'separate_items_with_commas' => __('Seperate Pack Types with Commas'),
            'add_or_remove_items' => __('Add or Remove Pack Types'),
            'choose_from_most_used' => __('Choose from Most Used Pack Types')
            ),  
                'query_var' => true,  
            'rewrite' => array('slug' =>'pack_type')        
           );
           register_taxonomy('pack_type', 'sponsor_packages',$State_args);
}

add_action( 'init', 'create_pack_type' );

/*------------ Columns on Dogs-------------*/

function my_columns( $columns ) {
    $columns['dogs_age'] = 'Age';
    $columns['dogs_gender'] = 'Gender';
    $columns['dogs_sponsored'] = 'Sponsored';
    $columns['dogs_updates'] = 'Has Updates';
    unset( $columns['comments'] );
    return $columns;
}

add_filter( 'manage_edit-dogs_columns', 'my_columns' );

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
			$dog_id = get_the_ID();
			$table_name = $wpdb->prefix.'donations';
			
			$donate_rw = $wpdb->get_results("SELECT sum(amount) as totalreceive FROM $table_name WHERE dog_id=".$dog_id );
			if( $donate_rw[0]->totalreceive != "") { echo "$".$donate_rw[0]->totalreceive; } else { echo "$0"; }

		  break;	  
	  
	}

}

add_action( 'manage_posts_custom_column', 'populate_columns' );

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

add_filter( 'request', 'column_orderby' );

function sort_me( $columns ) {
    $columns['dogs_age'] = 'dogs_age';
    $columns['dogs_gender'] = 'dogs_gender';
    $columns['dogs_sponsored'] = 'dogs_sponsored';	
    return $columns;
}

add_filter( 'manage_edit-dogs_sortable_columns', 'sort_me' );

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

add_action( 'restrict_manage_posts', 'my_filter_list' );

function perform_filtering( $query ) {
    $qv = &$query->query_vars;
    if ( ( $qv['State'] ) && is_numeric( $qv['State'] ) ) {
        $term = get_term_by( 'id', $qv['State'], '' );
        $qv['State'] = $term->slug;
    }
}

add_filter( 'parse_query','perform_filtering' );

/* Define custom post status "Adopted" */

function custom_post_status(){
     register_post_status( 'adopted', array(
          'label'                     => _x( 'Adopted', 'dogs' ),
          'public'                    => true,
          'show_in_admin_all_list'    => false,
          'show_in_admin_status_list' => true,
          'label_count'               => _n_noop( 'Adopted <span class="count">(%s)</span>', 'Adopted <span class="count">(%s)</span>' )
     ) );
}
add_action( 'init', 'custom_post_status' );

function append_post_status_list(){
     global $post;
     $complete = '';
     $label = '';
     if($post->post_type == 'dogs'){
          if($post->post_status == 'adopted'){
               $complete = ' selected=\"selected\"';
               $label = '<span id=\"post-status-display\"> Adopted</span>';
          }
          echo '
          <script>
          jQuery(document).ready(function($){
               $("select#post_status").append("<option value=\"adopted\" '.$complete.'>Adopted</option>");
               $(".misc-pub-section label").append("'.$label.'");
          });
          </script>
          ';
     }
}

add_action('admin_footer-post.php', 'append_post_status_list');

function display_adopted_state( $states ) {
     global $post;
     $arg = get_query_var( 'post_status' );
     if($arg != 'adopted'){
          if($post->post_status == 'adopted'){
               return array('Adopted');
          }
     }
    return $states;
}
add_filter( 'display_post_states', 'display_adopted_state' );

/* Define path for templates */

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

add_filter( 'template_include', 'include_template_function', 1 );


/* shortcodes */


//[dog_list no_dogs=15 size="small" filter="on" pagination="on" adopted="on" output="xml"]
function do_dog_list( $atts ){

	global $wpdb;

	extract( shortcode_atts( array(
		'no_dogs' => '12',
		'picture_size' => 'normal',
		'filter' => 'off',
		'pagination' => 'off',
		'adopted'=> 'off',
		'output'=>'html'
	), $atts ) );

	$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

	if ( $adopted === 'on' ) {
		$args = array( 
			'post_type' => 'dogs',
			'posts_per_page' => $no_dogs,
			'paged' => $paged,
			'post_status' => 'adopted'
		);
	}
	else {
		$args = array( 
			'post_type' => 'dogs',
			'posts_per_page' => $no_dogs,
			'paged' => $paged,
			'post_status' => 'publish'
		);
		
	}
	
	$dog_filter = array();	
	if ($filter == 'on') {	
		$no_parameters=0;
		
		if(!empty($_GET['gender'])) { $no_parameters++;	}
		if(!empty($_GET['size'])) { $no_parameters++;	}
		if(!empty($_GET['color'])) { $no_parameters++;	}
		if(!empty($_GET['special_needs'])) { $no_parameters++;	}	
		if(!empty($_GET['age'])) {  $no_parameters++;	}

		if ( $no_parameters > 1 ) {
			$args['meta_query'] = array('relation' => 'AND');
		}
			
		if(!empty($_GET['gender'])) {
					$args['meta_query'][] = array(
												'key' => 'gender',
												'compare' => '=', 
												'value' => $_GET['gender']
											);
					unset($foo);
					$foo = array( 'gender' => $_GET['gender']);						
					$dog_filter = array_merge ($dog_filter, $foo);						
				}	
		if(!empty($_GET['size'])) {
					$args['meta_query'][] = array(
												'key' => 'size',
												'compare' => '=', 
												'value' => $_GET['size']
											);
					unset($foo);
					$foo = array( 'size' => $_GET['size']);						
					$dog_filter = array_merge ($dog_filter, $foo);						
				}	

		if(!empty($_GET['color'])) {
					$args['meta_query'][] = array(
												'key' => 'color',
												'compare' => '=', 
												'value' => $_GET['color']
											);
					unset($foo);
					$foo = array( 'color' => $_GET['color']);						
					$dog_filter = array_merge ($dog_filter, $foo);						
				}	

		if(!empty($_GET['special_needs'])) {
					$args['meta_query'][] = array(
												'key' => 'special_needs',
												'compare' => '=', 
												'value' => $_GET['special_needs']
											);
					unset($foo);
					$foo = array( 'special_needs' => $_GET['special_needs']);						
					$dog_filter = array_merge ($dog_filter, $foo);						
				}
		if(!empty($_GET['age'])) {
					$args['meta_query'][] = array(
												'key' => 'birthdate',
												'compare' => 'BETWEEN',
												'type' => 'NUMERIC',
												'value' => explode("-", $_GET['age'])
											);	
					unset($foo);
					$foo = array( 'age' => $_GET['age']);						
					$dog_filter = array_merge ($dog_filter, $foo);						
				}			
		//echo '<pre>'.var_dump($args).'</pre>';
	}									

	if ( $adopted === 'on' ) {
		$foo = array( 'status' => 'adopted');						
		$dog_filter = array_merge ($dog_filter, $foo);
	}
	
	query_posts($args);	
	
	global $wp_query;
    $page_links_total =  $wp_query->max_num_pages;
	
	if ($output === 'html'){
		ob_start();
			?> <div class="avada-row">

			<?php if ($filter == 'on') {
				$genders = $wpdb->get_col("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = 'gender'" );
				$colors = $wpdb->get_col("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = 'color'" );
				$sizes = $wpdb->get_col("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = 'size'" );
				$special_needs = $wpdb->get_col("SELECT DISTINCT meta_value FROM $wpdb->postmeta WHERE meta_key = 'special_needs'" );
			?>
			<h5 class="toggle">
				<a href="#"><span class="arrow"></span>Click to narrow down this list!</a>
			</h5>
			<div class="toggle-content" style="display: block;">
				<section class="reading-box clearfix" style="background-color:#f6f6f6 !important;border-width:1px;border-color:#f6f6f6!important;border-left-width:3px !important;border-left-color:#1a80b6!important;border-style:solid;">
					<form name="dog_filter" action="<?php echo get_permalink(); ?>" method="get">
						<input type="hidden" name="page_id" value="<?php echo get_the_ID(); ?>">
						Gender:
						<select name="gender">
						  <option value="" selected="selected">All</option>
						  <?php foreach($genders as $gender) { ?>
							<option value="<?php echo $gender ?>"><?php echo $gender ?></option>
						  <?php }?>
						</select>	
						Age:
						<select name="age">
						  <option value="" selected="selected">All</option>
						  <option value="<?php echo date( "Ymd", mktime(0, 0, 0, date("m")-18  , date("d"), date("Y")) ). '-' . date( "Ymd", mktime(0, 0, 0, date("m")  , date("d"), date("Y"))) ?>" >up to 18 months</option>
						  <option value="<?php echo date( "Ymd", mktime(0, 0, 0, date("m")  , date("d"), date("Y")-5) ). '-' . date( "Ymd", mktime(0, 0, 0, date("m")-18  , date("d"), date("Y"))) ?>" >1-5 years</option>
						  <option value="<?php echo date( "Ymd", mktime(0, 0, 0, date("m")  , date("d"), date("Y")-10 ) ). '-' . date( "Ymd", mktime(0, 0, 0, date("m")  , date("d"), date("Y")-5)) ?>" ">5-10 years</option>
						  <option value="<?php echo date( "Ymd", mktime(0, 0, 0, date("m")  , date("d"), date("Y")-30) ). '-' . date( "Ymd", mktime(0, 0, 0, date("m")  , date("d"), date("Y")-10)); ?>">10+ years</option>
						</select>	

						Color:
						<select name="color">
						  <option value="" selected="selected">All</option>
						  <?php foreach($colors as $color) { ?>
							<option value="<?php echo $color ?>"><?php echo $color ?></option>
						  <?php }?>
						</select>			
						Size:
						<select name="size">
						  <option value="" selected="selected">All</option>
						  <?php foreach($sizes as $size) { ?>
							<option value="<?php echo $size ?>"><?php echo $size ?></option>
						  <?php }?>
						</select>	
						Has special needs?
						<select name="special_needs">
						  <option value="" selected="selected">All</option>
						  <?php foreach($special_needs as $special_need) { ?>
							<option value="<?php echo $special_need ?>"><?php echo $special_need ?></option>
						  <?php }?>
						</select>
				
					 <a href="javascript:document.dog_filter.submit();" class="continue button large">Filter dogs</a>
					</form>

				</section>
				<div class="clearfix"></div>		
			</div>
			<?php }	?>
					
			<?php 
			while ( have_posts() ) : the_post();
				$dog_id= get_the_ID(); 
				$images = get_field('gallery');


				$link = add_query_arg( $dog_filter, get_permalink($dog_id) );
				
				if ( $images ) : ?>
				<article>
					<header class="entry-header <?php echo $picture_size; ?> <?php echo (get_post_meta( $dog_id, 'gender', true ) == 'male'? 'dogs-male' :  'dogs-female'); ?>" >
						<div class="dog-list">
							<div class="dog-list-picture">
								<?php if( $images ): ?>
								<a href="<?php echo $link; ?>" title="<?php the_title_attribute(); ?>" ><img src="<?php echo $images[0]['sizes']['dog-mainpage']; ?>" alt="<?php  the_sub_field('title');?>" /></a>
								<?php endif; ?>
							</div>
							
							<div class="dog-<?php echo  get_post_meta( $dog_id, 'adoption_status', true ) ; ?>"></div>
							
							<div class="dog-list-name dog-<?php echo  get_post_meta( $dog_id, 'gender', true ) ; ?>">
								<a href="<?php echo $link; ?>" title="<?php the_title_attribute(); ?>" ><?php the_title(); ?></a><br />
							</div>

							<div class="dog-list-age">
								<a href="<?php echo $link; ?>" title="<?php the_title_attribute(); ?>" ><?php echo getAge( get_post_meta( $dog_id, 'birthdate', true ) ) . ' old '; ?></a>
							</div>

						</div>	<!-- END dog-list -->
					</header>
				</article>
				<?php endif;
		endwhile; 
		?> </div>
		
		<?php
		$filter_dogs='false';	
		if ( $pagination == 'on') {
			echo "<hr>";
			
			$cur_page = (get_query_var('paged')) ? get_query_var('paged') : 1;
			
			$page_links = paginate_links( array(
				'base' => add_query_arg( 'paged', '%#%' ),
				'format' => '',
				'total' => $page_links_total,
				'current' => $cur_page,
				'add_args' => $dog_filter
			));
			 
			if ( $page_links ) : 
			?>
			<div class="tablenav-pages">
				<?php $page_links_text = sprintf( '<span class="displaying-num">' . __( 'Displaying %s&#8211;%s of %s' ) . '</span>&nbsp;&nbsp;&nbsp;%s',
							number_format_i18n( ( $cur_page - 1 ) * $wp_query->query_vars['posts_per_page'] + 1 ),
							number_format_i18n( min( $cur_page * $wp_query->query_vars['posts_per_page'], $wp_query->found_posts ) ),
							number_format_i18n( $wp_query->found_posts ),
							$page_links
						); echo $page_links_text; 
				?>
			</div>
			<?php
			endif;		
		}
		return ob_get_clean();
	}
	else { // XML output
//Remove from Post Content
remove_filter('the_content', 'wptexturize');
//Remove from Post Title
remove_filter('the_title', 'wptexturize');
//Remove from Post Excerpt
remove_filter('the_excerpt', 'wptexturize');
//Remove from Post Comments
remove_filter('comment_text', 'wptexturize');

$xmlstr = <<<XML
<?xml version='1.0'?>
<dogs>
</dogs>
XML;
		

		$xml = new SimpleXMLElement($xmlstr);	
		while ( have_posts() ) : the_post();
			$dog_id= get_the_ID(); 
			$dog_name = get_the_title();
			$images = get_field('gallery');
			$link = get_permalink($dog_id);
			$gender = get_post_meta( $dog_id, 'gender', true ) ;
			$image = $images[0]['sizes']['dog-mainpage'];
			$age = getAge( get_post_meta( $dog_id, 'birthdate', true ) );

			$dog = $xml->addChild('dog');
			$dog->addChild('dogname',$dog_name);
			$dog->addChild('gender',$gender);
			$dog->addChild('doglink',$link);
			$dog->addChild('picture',$image);			
			$dog->addChild('age',$age);			
		endwhile; 
		$dom = new DOMDocument('1.0');
		$dom->preserveWhiteSpace = false;
		$dom->formatOutput = true;
		$dom->loadXML($xml->asXML());
		echo $dom->saveXML();
	}	
}

add_shortcode( 'dog_list', 'do_dog_list' );

//[sponsor_dog purpose='Purpose description' amount='10' recurring='M/Y/1']
function sponsor_dog( $atts ){

	extract( shortcode_atts( array(
		'purpose' => 'Purpose description',
		'amount' => '',
		'recurring' => 'M'
	), $atts ) );

	ob_start();	
	?><div style="text-align: center;">
		<a href="javascript:DoSubmit('<?php echo $amount ?>', '<?php echo $purpose ?>', '<?php echo $recurring ?>');"><img class=give-now-button" src="<?php echo plugins_url( ) . '/dogs-adoption/images/button-givenow.png'; ?>" ></a>	
		<br/>
		<span class="no-account">NO PayPal account Needed</span>
		<script>
			function DoSubmit(a, n, recurring){
				if ( recurring == 1) {
					var input = document.createElement("input");
					input.setAttribute("type", "hidden");
					input.setAttribute("name", "srt");
					input.setAttribute("value", "");
					document.donation.appendChild(input);
					
					document.donation.cmd.value = '_donations';
					document.donation.amount.value = a;
					document.donation.a3.value = a;
					document.donation.p3.value = '1';
					document.donation.t3.value = '0';
					document.donation.src.value = '0';
					document.donation.srt.value = '';
					document.donation.sra.value = '1';
					document.donation.item_name.value = n;
				}
				if ( recurring != 1){
					document.donation.amount.value = a;
					document.donation.a3.value = a;
					document.donation.item_name.value = n;
					document.donation.t3.value = recurring;
				}
				document.donation.submit();
			};
		</script>
		<?php $dextra = get_option( 'DonateExtra' ); ?>
		
		<form name="donation" id="donateextraform" style="display:none;" action="https://www.paypal.com/cgi-bin/webscr" method="post">
			<input type="hidden" id="cmd" name="cmd" value="_donations">
			<input type="hidden" name="amount" id="1" value="0">
			<input type="hidden" name="a3" id="a3" value="0">
			<input type="hidden" name="p3" id="p3" value="1">
			<input type="hidden" name="t3" id="t3" value="Y">  <!-- value="0" Do not repeat, value="M" Month(s), value="Y" Year(s) -->
			<input type="hidden" name="src" id="src" value="1">
			<input type="hidden" name="sra" value="1">
			<input type="hidden" name="notify_url" value="<?php echo plugins_url( ) . '/donate-extra/paypal.php'; ?>">
			<input type="hidden" name="item_name" value="">
			<input type="hidden" name="business" value="<?php echo $dextra['paypal_email']; ?>">
			<input type="hidden" name="lc" value="US">
			<input type="hidden" name="no_note" value="1">
			<input type="hidden" name="no_shipping" value="1">
			<input type="hidden" name="rm" value="2">
			<input type="hidden" name="return" value="<?php echo get_permalink($dextra['wall_url']); ?>">
			<input type="hidden" name="custom" value="">
			<input type="hidden" name="currency_code" value="EUR">
			<input type="hidden" name="bn" value="PP-DonationsBF:btn_donateCC_LG.gif:NonHosted">
			<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1"></p>
		</form>		
		</div>
	<?php
	return ob_get_clean();
}

add_shortcode( 'sponsor_dog', 'sponsor_dog' );


//[halloffame sponsor_picture="" dog_name="" dog_picture="" ] description [/halloffame]
function halloffame( $atts  , $content = null ){
	// Attributes
	extract( shortcode_atts( array(
		'sponsor_picture' => 'http://sponsoradog.rolda.org/wp-content/uploads/sites/5/2013/12/no-photo.jpg',
		'dog_picture' => 'http://sponsoradog.rolda.org/wp-content/uploads/sites/5/2013/12/footprint-dog.png',
		'dog_name' => ''
	), $atts ) );
	
	$display = "display:none;";
	if($dog_name <> '') {
		$temp_picture = get_dog_picture_by_name($dog_name);
		if($temp_picture) { 
			$dog_picture = $temp_picture;
			$display = "display:block;";
		}
	}
	
	ob_start();	
	?><?php echo $amount ?>
		<figure class="halloffame">
			<img src="<?php echo $sponsor_picture ?>" width="150px" height="150px" />
			<img src="<?php echo $dog_picture ?>" width="150px" height="150px" />
			<figcaption><?php echo $content ?>
				<span style="<?php echo $display; ?>">&nbsp;Read more about <a href="http://sponsoradog.rolda.org/dogs/<?php echo $dog_name ?>/"> <?php echo strtoupper($dog_name) ?>!</a></span>
			</figcaption>
		</figure>	
	
	<?php
	return ob_get_clean();
}

add_shortcode( 'halloffame', 'halloffame' );

/* Other functions */


function get_dog_picture_by_name($dog_name) {
    global $wpdb;
	$dog_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='dogs'", $dog_name ));
	if ( $dog_id ) {
		$images = get_post_meta( $dog_id, 'gallery', true );
		$image = wp_get_attachment_image_src( $images[0], 'thumbnail' );
		return $image[0];
	}
    return null;
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

function get_offset_post_link($format, $link, $offset, $post_type, $order_by, $custom_query_array, $status = 'publish', $parameters){
	global $post;
	$current_post_id = $post->ID;
	
	$list_query = New WP_Query(array(
		'post_type' 		=> $post_type,
		'orderby'			=> $order_by,
		'order'				=> 'DESC',
		'posts_per_page'	=> -1,
		'post_status' 		=> $status,
		'meta_query' 		=> $custom_query_array
	 ));	
	$i = 0;

	if ( $list_query->have_posts() ) while ( $list_query->have_posts() ) : $list_query->the_post();
		
		$item[$i] = get_post($post->ID);
		

		if($post->ID == $current_post_id){
			$target_index = $i + $offset;
			if($target_index >= 0){
				if($target_index <= $i){
					$target_post = 	$item[$target_index];			
					break;
				}else{
					if($target_index <= $list_query->post_count){
						while($i < $target_index){
							$target_post = $list_query->next_post();
							if($i == $target_index){
								break;
							}
							$i++;
						}
					}else{
						//offset exceeds the upper bounds of the record set.
					}
				}
			}else{
				//offset exceeds the lower bounds of the record set.				
			}
		}
		$i++;
	endwhile;


	wp_reset_query();
	wp_reset_postdata();
	
	if($target_post){
		$link = str_replace('%title', $target_post->post_title, $link);
		$link = '<a href="' . add_query_arg($parameters, get_permalink($target_post->ID)) . '">' . $link . '</a>';
		
		$format = str_replace('%link', $link, $format);

		return $format;
	}
}

?>