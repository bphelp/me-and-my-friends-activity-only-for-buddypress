<?php
/*** Make sure BuddyPress is loaded ********************************/
if ( !function_exists( 'bp_core_install' ) ) {
	require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
	if ( is_plugin_active( 'buddypress/bp-loader.php' ) ) {
		require_once ( WP_PLUGIN_DIR . '/buddypress/bp-loader.php' );
	} else {
		add_action( 'admin_notices', 'bphelp_my_activity_privacy_install_buddypress_notice' );
		return;
	}
}


function bphelp_my_activity_privacy_install_buddypress_notice() {
	echo '<div id="message" class="error fade"><p style="line-height: 150%">';
	_e('<strong>Me And My Friends Activity Stream For BP</strong></a> requires the BuddyPress plugin to work. Please <a href="http://buddypress.org/download">install BuddyPress</a> first, or <a href="plugins.php">deactivate Me And My Friends Activity Stream For BP</a>.');
	echo '</p></div>';
}
/* Stop errors if any */
error_reporting(E_ERROR | E_PARSE);
/* End Stop Errors */

/* A friend only activity stream section 1 */
function are_we_friends_test( $friend_id = false) {
global $bp;

if ( is_super_admin() )
return true;

if ( !is_user_logged_in() )
return false;

if (!$friend_id) {
$potential_friend_id = $bp->displayed_user->id;
} else {
$potential_friend_id = $friend_id;
}

if ( $bp->loggedin_user->id == $potential_friend_id )
return true;

if (friends_check_friendship_status($bp->loggedin_user->id, $potential_friend_id) == 'is_friend')
return true;

return false;
}

function deny_non_friends_from_my_activity( $a, $activities ) {

/* Is current user the administrator */
if ( is_super_admin() )
return $activities;

foreach ( $activities->activities as $key => $activity ) {
/* If your a member of a group get the activity */
if ( $activity->component != 'groups' && $activity->user_id != 0 && !are_we_friends_test($activity->user_id) && !is_at_me_test($activity->content) ) {

unset( $activities->activities[$key] );

$activities->activity_count = $activities->activity_count-1;
$activities->total_activity_count = $activities->total_activity_count-1;
$activities->pag_num = $activities->pag_num -1;

}
}

/* Rearrange array keys if items are missing */
$activities_new = array_values( $activities->activities );
$activities->activities = $activities_new;

return $activities;
}
add_action('bp_has_activities', 'deny_non_friends_from_my_activity', 10, 2 );

function is_at_me_test( $content ) {
global $bp;

if ( !is_user_logged_in() )
return false;

if (!$content)
return false;

$pattern = '/[@]+([A-Za-z0-9-_]+)/';
preg_match_all( $pattern, $content, $usernames );

/* Confirm only one instance of the users name */
if ( !$usernames = array_unique( $usernames[1] ) )
return false;

if ( in_array( bp_core_get_username( $bp->loggedin_user->id ), $usernames ) )
return true;

return false;
}
/* End section 1 */
?>