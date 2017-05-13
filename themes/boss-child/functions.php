<?php
/**
 * @package Boss Child Theme
 * The parent theme functions are located at /boss/buddyboss-inc/theme-functions.php
 * Add your own functions in this file.
 */

/**
 * Sets up theme defaults
 *
 * @since Boss Child Theme 1.0.0
 */
function boss_child_theme_setup()
{
  /**
   * Makes child theme available for translation.
   * Translations can be added into the /languages/ directory.
   * Read more at: http://www.buddyboss.com/tutorials/language-translations/
   */

  // Translate text from the PARENT theme.
  load_theme_textdomain( 'boss', get_stylesheet_directory() . '/languages' );

  // Translate text from the CHILD theme only.
  // Change 'boss' instances in all child theme files to 'boss_child_theme'.
  // load_theme_textdomain( 'boss_child_theme', get_stylesheet_directory() . '/languages' );

}
add_action( 'after_setup_theme', 'boss_child_theme_setup' );

/**
 * Enqueues scripts and styles for child theme front-end.
 *
 * @since Boss Child Theme  1.0.0
 */
function boss_child_theme_scripts_styles()
{
  /**
   * Scripts and Styles loaded by the parent theme can be unloaded if needed
   * using wp_deregister_script or wp_deregister_style.
   *
   * See the WordPress Codex for more information about those functions:
   * http://codex.wordpress.org/Function_Reference/wp_deregister_script
   * http://codex.wordpress.org/Function_Reference/wp_deregister_style
   **/

  /*
   * Styles
   */
  wp_enqueue_style( 'boss-child-custom', get_stylesheet_directory_uri().'/css/custom.css' );

  /*
   * Scripts
   */
  wp_enqueue_script( 'boss-child-javascript', get_stylesheet_directory_uri().'/js/boss_child.js');
}
add_action( 'wp_enqueue_scripts', 'boss_child_theme_scripts_styles', 9999 );


// BuddyPress Honeypot to lure spammers
function add_honeypot() {
  echo '';
}
add_action('bp_after_signup_profile_fields','add_honeypot');

function check_honeypot() {
  if (!empty($_POST['system55'])) {
    global $bp; wp_redirect(home_url());
    exit;
  }
}
add_filter('bp_core_validate_user_signup','check_honeypot');

/****************************** CUSTOM FUNCTIONS ******************************/

// Add your own custom functions here
function bb_cover_image_settings( $settings = array() ) {
    $settings['callback'] = 'buddyboss_cover_image_callback';
    $settings['theme_handle'] = 'buddyboss-bp-frontend';
    $settings['width'] = 1920;
    $settings['height'] = 640;

    return $settings;
}
remove_filter( 'bp_before_xprofile_cover_image_settings_parse_args', 'buddyboss_cover_image_settings', 10, 1 );
remove_filter( 'bp_before_groups_cover_image_settings_parse_args', 'buddyboss_cover_image_settings', 10, 1 );
add_filter( 'bp_before_xprofile_cover_image_settings_parse_args', 'bb_cover_image_settings', 10, 1 );
add_filter( 'bp_before_groups_cover_image_settings_parse_args', 'bb_cover_image_settings', 10, 1 );

// add cookies to allow filtering of profile info e.g. expes
function set_user_cookie() {
    if ( !is_admin() && !isset($_COOKIE['bp-groups-scope'])) {
        setcookie( 'bp-groups-scope', personal, time()+3600*24*100, COOKIEPATH, COOKIE_DOMAIN, false);
    }
}

add_action( 'init', 'set_user_cookie');



?>
