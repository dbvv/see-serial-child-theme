<?php
/**
 * Dooplay-child Theme functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package dooplay-child
 */

add_action( 'wp_enqueue_scripts', 'dooplay_parent_theme_enqueue_styles' );

/**
 * Enqueue scripts and styles.
 */
function dooplay_parent_theme_enqueue_styles() {
	wp_enqueue_style( 'dooplay-style', get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'dooplay-child-style',
		get_stylesheet_directory_uri() . '/style.css',
		array( 'dooplay-style' )
	);

}

function doo_post_date($format = false, $echo = true){
	if(!is_string($format) || empty($format) || $format === 'F j, Y') {
		$format = get_option('date_format');
	}
	$date = sprintf( __d('%1$s') , get_the_time($format) );
	if($echo){
		echo $date;
	} else {
		return $date;
	}
}

function doo_date_compose($date = false , $echo = true){
	if(class_exists('DateTime')){
		$format = get_option('date_format');
		$class = new DateTime($date);
		if($echo){
			echo $class->format($format);
		}else{
			return $class->format($date);
		}
	} else {
		if($echo){
			echo $date;
		}else{
			return $date;
		}
	}
}

require get_stylesheet_directory() . '/app/customizer.php';
require get_stylesheet_directory() . '/inc/doo_player.php';

function insertAfter(&$arr, $index, $k, $value){
	$tmpArr = [];
	foreach ($arr as $key => $val) {
		$tmpArr[$key] = $val;
		if ($key == $index) {
			$tmpArr[$k] = $value;
		}
	}
	$arr = $tmpArr;

}
