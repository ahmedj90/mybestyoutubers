<?php
// Prevent direct script access
if ( !defined( 'ABSPATH' ) )
	die ( 'No direct script access allowed' );

/**
* Child Theme Setup
* 
* Always use child theme if you want to make some custom modifications. 
* This way theme updates will be a lot easier.
*/
function bunchy_child_setup() {
}

add_action( 'after_setup_theme', 'bunchy_child_setup' );