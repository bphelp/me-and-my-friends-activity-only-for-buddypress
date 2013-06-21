<?php 
/*
Plugin Name: Me And My Friends Activity Stream Only For BuddyPress
Plugin URI: http://www.wordpress.com
Description: Modifies the activity stream so you only see your friends and your own activities. Admins see all site activity. See readme.txt in the plugin file.
Version: 1.5
Requires at least: WordPress 3.2.1 / BuddyPress 1.5.1
Tested up to: WordPress 3.6 beta2 / BuddyPress 1.8 beta1
License: GNU/GPL 2
Author: @bphelp
Author URI: http://www.wordpress.com
*/

function bphelp_my_activity_privacy_init() {
	require( dirname( __FILE__ ) . '/activity-privacy-for-bp.php' );
}
add_action( 'bp_include', 'bphelp_my_activity_privacy_init' );
?>