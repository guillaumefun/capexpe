<?php
/*
 Change made by Dom Snyers on October 4th 2016 to customise the menu
 taken from : https://themekraft.com/customize-profile-and-group-menus-in-buddypress/
*/

//if ( bp_is_active( 'gpages' )) {
//   define( 'BP_GROUPS_DEFAULT_EXTENSION', 'gpages' );
//}

// added by Laurent to make sure to use gpages as default page when one exists 
function capexpe_landing_page($old_slug) {
    global $bp;

    // If the bpge_nav_order is defined, then we give it our preference
    $order = groups_get_groupmeta( $bp->groups->current_group->id, 'bpge_nav_order' );
    if (is_array($order)) {
        return $old_slug;
    }

    // No page order is defined, so we need to check if an old gpage is available
    $meta = groups_get_groupmeta( $bp->groups->current_group->id, 'bpge' );
    if (is_array($meta) && array_key_exists("display_page",$meta)) {
        return "gpages";
    }

    // Not sur, return the old slug
    return $old_slug;
}

add_filter('bpge_landing_page', 'capexpe_landing_page');

define( 'BP_DEFAULT_COMPONENT', 'groups' );


/*
function my_bp_groups_first_tab() {
    buddypress()->members->nav->edit_nav( array( 'position' => 4,	), 'activity'		);
    buddypress()->members->nav->edit_nav( array( 'position' => 5, 	), 'profile'			);
    buddypress()->members->nav->edit_nav( array( 'position' => 7, 	), 'notifications'		);
    buddypress()->members->nav->edit_nav( array( 'position' => 9,	), 'messages'				);
    buddypress()->members->nav->edit_nav( array( 'position' => 2,	), 'friends'					);
    buddypress()->members->nav->edit_nav( array( 'position' => 6,	), 'groups' 					 );
    buddypress()->members->nav->edit_nav( array( 'position' => 3, 	), 'forums' 					  );
    buddypress()->members->nav->edit_nav( array( 'position' => 1, 	), 'settings' 					   );

}
add_action( 'bp_setup_nav', 'my_bp_groups_first_tab' );


function my_remove_em_nav() {
      bp_core_remove_subnav_item( 'blogs' , 'profile' );
      bp_core_remove_subnav_item( 'extras' , 'groups' );

}
add_action( 'bp_init', 'my_remove_em_nav' );
*/

?>
