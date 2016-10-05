<?php
/*
 Change made by Dom Snyers on October 4th 2016 to customise the menu
 taken from : https://themekraft.com/customize-profile-and-group-menus-in-buddypress/
*/

define( 'BP_GROUPS_DEFAULT_EXTENSION', 'gpages' );

define( 'BP_DEFAULT_COMPONENT', 'groups' );


/*
function my_bp_groups_first_tab() {
    buddypress()->members->nav->edit_nav( array( 'position' => 4,	), 'activity' 		);
  	buddypress()->members->nav->edit_nav( array( 'position' => 5, 	), 'profile' 		);
  	buddypress()->members->nav->edit_nav( array( 'position' => 7, 	), 'notifications'	);
  	buddypress()->members->nav->edit_nav( array( 'position' => 9,	), 'messages' 	  	);
  	buddypress()->members->nav->edit_nav( array( 'position' => 2,	), 'friends' 	  	);
  	buddypress()->members->nav->edit_nav( array( 'position' => 6,	), 'groups' 		);
  	buddypress()->members->nav->edit_nav( array( 'position' => 3, 	), 'forums' 		);
  	buddypress()->members->nav->edit_nav( array( 'position' => 1, 	), 'settings' 		);
    //bp_core_remove_nav_item( 'blogs' );
    //bp_core_remove_subnav_item( 'extras' );

}
add_action( 'bp_setup_nav', 'my_bp_groups_first_tab' );


function my_remove_em_nav() {
      bp_core_remove_nav_item( 'blogs' );
      bp_core_remove_subnav_item( 'extras' , 'profile' );
      //bp_core_remove_subnav_item( 'extras' , 'groups' );

}
add_action( 'bp_init', 'my_remove_em_nav' );



// Change the menu order in the profile
function bbg_change_profile_tab_order() {
global $bp;
$bp->bp_nav[‘profile’][‘position’] = 10;
$bp->bp_nav[‘groups’][‘position’] = 20;
$bp->bp_nav[‘activity’][‘position’] = 30;
$bp->bp_nav[‘friends’][‘position’] = 40;
$bp->bp_nav[‘blogs’][‘position’] = 50;
$bp->bp_nav[‘messages’][‘position’] = 60;
$bp->bp_nav[‘gallery’][‘position’] = 70;
$bp->bp_nav[‘settings’][‘position’] = 80;

//  Rename a menu item
$bp->bp_nav[‘activity’][‘name’] = ‘Wall’;

// Remove a menu item
$bp->bp_nav[blogs] = false;

}
add_action( ‘bp_setup_nav’, ‘bbg_change_profile_tab_order’, 999 );






//Change the menu order in groups
function my_bp_groups_first_tab() {
global $bp;
$bp->bp_options_nav['groups']['gpages']['position'] = 10;
$bp->bp_options_nav['groups']['activity']['position'] =20;
$bp->bp_options_nav['groups']['friends']['position'] =30;
$bp->bp_options_nav['groups']['invite']['position'] = 40;
$bp->bp_options_nav['groups']['gallery']['position'] = 50;
$bp->bp_options_nav['groups']['manage']['position'] = 60;
$bp->bp_options_nav['activity']['name'] = 'Activité';

}
add_action('bp_setup_nav', 'my_bp_groups_first_tab', 1000);






//Set the forum nav item as the default nav item in groups
function redirect_to_forum() {
global $bp;
$path = clean_url( $_SERVER[‘REQUEST_URI’] );
$path = apply_filters( ‘bp_uri’, $path );
if ( bp_is_group_home() && strpos( $path, $bp->bp_options_nav[‘groups’][‘home’][‘slug’] ) === false )
bp_core_redirect( $path . $bp->bp_options_nav[‘groups’][‘forum’][‘slug’] . ‘/’ );
}
add_action( ‘bp_setup_nav’, ‘redirect_to_forum’, 210 );







add_action( 'wp_head', 'change_my_option_nav_name',9 );
function change_my_option_nav_name()
{
 global $bp;

 $bp->bp_options_nav[bp_get_current_group_slug()]['home']['name'] = 'Information';
}
*/
?>
