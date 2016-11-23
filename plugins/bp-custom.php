<?php
// Exit if accessed directly.
defined( 'ABSPATH' ) || exit;

/*
 Change made by Dom Snyers on October 4th 2016 to customise the menu
 taken from : https://themekraft.com/customize-profile-and-group-menus-in-buddypress/
*/
define( 'BP_DEFAULT_COMPONENT', 'groups' );

/*
 * Added by Laurent to make sure to use gpages as default page when one exists
 */
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

/*
 *
 */
function capexpe_groups_group_create_complete($group_id) {
  global $current_blog, $bpge;

  // Get the group
  $group = groups_get_group( array( 'group_id' => $group_id ) );

  // Bail if group cannot be found
  if ( empty( $group ) ) {
    error_log("We have an empty group which is unexpected.");
    return false;
  }

  // Retrieve meta if exists, Initialize a new array if not
  if (isset($group->extras) && is_array($group->extras)) {
    $meta = $group->extras;
  } else {
    $meta = array();
  }

  $meta['display_page']        = "public";
  $meta['display_page_name']   = "Gestion des Pages";
  $meta['display_page_layout'] = "profile";

  $meta['gpage_name']     = "Pages";
  $meta['display_gpages'] = "public";

  $meta['home_name'] = "Activités";

  groups_update_groupmeta( $group_id, 'bpge', $meta );

  // Create a root page
  if ( empty( $current_blog ) || ! isset( $current_blog->blog_id ) ) {
    $current_blog          = new stdClass();
    $current_blog->blog_id = 1;
  }

  $admin = get_user_by( 'email', get_blog_option( $current_blog->blog_id, 'admin_email' ) );
  $old_data = groups_get_groupmeta( $group->id, 'bpge' );

  // Save as a post_type
  $page = array(
    'comment_status' => 'closed',
    'ping_status'    => 'closed',
    'post_author'    => $admin->ID,
    'post_content'   => $group->description,
    'post_name'      => $group->slug,
    'post_status'    => 'publish',
    'post_title'     => $group->name,
    'post_type'      => 'gpages'
  );

  $old_data['gpage_id'] = wp_insert_post( $page );
  groups_update_groupmeta( $group->id, 'bpge', $old_data );

  // Add a first content page
  $page = array(
    'comment_status' => 'open',
    'ping_status'    => 'open',
    'post_author'    => $admin->ID,
    'post_title'     => 'A propos',
    'post_name'      => 'about',
    'post_content'   => 'Editez cette page pour ajouter une description détaillée de votre expé.',
    'post_parent'    => $old_data['gpage_id'],
    'post_status'    => 'publish',
    'menu_order'     => 1,
    'post_type'      => 'gpages'
  );

  $page_id = wp_insert_post( $page );
  update_post_meta( $page_id, 'group_id', $group->id );
}
add_action( 'groups_group_create_complete', 'capexpe_groups_group_create_complete');
?>
