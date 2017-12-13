<?php
/*
Plugin Name: Customizations of Rolda
Plugin URI: http://buzzvertising.ro
Description: Variouns Customiztions of the current install. Shortcodes: [dog_speech dogimage="http://dogimage.jpg"] Text in bubble [/dog_speech]
Author: Mircea Dima
Version: 1.0
Author URI: http://buzzvertising.ro

*/

function custom_css() {
	wp_register_style('custom_css', plugins_url('css/customization.css',__FILE__ ));
	wp_enqueue_style('custom_css');
}

add_action( 'init','custom_css');


add_shortcode( 'dog_speech', 'dog_speech_bubble' );
//[dog_speech dogimage="http://dogimage.jpg"] text in bubble [/dog_speech]

function dog_speech_bubble( $atts, $content = null ){

	global $wpdb;

	extract( shortcode_atts( array(
		'dogimage' => ''
	), $atts ) );

	ob_start();	
	?>
		<div class="dog-speech">
			<blockquote class="rectangle-speech-border">
				<h1><?php echo $content ?></h1>
			</blockquote>
			<div class="dog-portret" style = 'background-image: url(<?php echo $dogimage ?>)'></div>
		</div>
	<?php
	return ob_get_clean();	
}

?>