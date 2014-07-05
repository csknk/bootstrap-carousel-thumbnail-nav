function carawebs_carousel_control(){

	if ( is_singular( 'projects') ) { // only do this for "project" CPTs

    // Register the control script
    wp_register_script( 'carawebs_carousel', get_template_directory_uri() . '/assets/js/vendor/_carousel.js', array( 'jquery' ), null, true);
    wp_register_script('touchswipe', get_template_directory_uri() . '/assets/js/vendor/jquery.touchSwipe.min.js', array( 'jquery' ), null, true);
    wp_register_script('touchControl', get_template_directory_uri() . '/assets/js/vendor/touchControl.js', array( 'jquery' ), null, true);

	// Enqueue the carousel controls - they will be built into the footer
    wp_enqueue_script('carawebs_carousel');
    wp_enqueue_script('touchswipe');
    wp_enqueue_script('touchControl');



	}
}
// Add hooks for front-end
add_action('wp_enqueue_scripts', 'carawebs_carousel_control', 101);
