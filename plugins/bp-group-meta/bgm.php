<?php
/*
Plugin Name: Buddypress groups metadata
Plugin URI: http://www.capexpe.org
Description: Plugin to add and manage metadata on wordpress
Version: 0.4
Author: Guillaume Funck
License: GPL2
*/

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

Class groups_metadata {


    public function __construct(){
        $this -> setup_hooks();
    }

    private function setup_hooks(){
        // Add meta boxes in admin part
        add_action( 'bp_groups_admin_meta_boxes', array( $this, 'admin_ui_edit_meta' ) );
        // Save metadata
        add_action( 'bp_group_admin_edit_after',  array( $this, 'admin_ui_save_meta'), 10, 1 );

        // Add meta boxes in group creation
        add_action( 'groups_custom_group_fields_editable' , array( $this, 'group_creation_step_meta' ));
        add_action( 'groups_create_group_step_save_group-details', array( $this, 'group_save_meta' ));
        add_action( 'groups_group_details_edited' , array( $this, 'admin_ui_save_meta'), 10, 1 );

        // Add search parameter
        add_filter( 'bp_ajax_querystring', array( $this, 'filter_ajax_querystring' ), 20, 2 );
        add_action( 'bp_groups_directory_order_options', array( $this, 'new_options' ) );

        // Set default cover photo
        add_filter( "buddyboss_cover_photo_stock_pick_filename", array( $this, 'default_cover_photo' ) );

    }

    /**
     * registers a new metabox in Edit Group Administration screen, edit group panel
     */
    public function admin_ui_edit_meta(){
        add_meta_box(
            'groups_metadata_mb',
            __(  'Metadata' ),
            array( &$this, 'admin_ui_metabox'),
            get_current_screen()->id,
            'side',
            'core'
        );
    }

    /**
     * Displays the meta box
     */
    public function admin_ui_metabox( $item = false ) {
        if( empty( $item ) )
            return;

        ?>
            <p>
                <label for="year" >Année de l'expé</label>
                <select name="year" id="year" >
                    <?php

                        for ($i=date("Y") +1; $i > 1991 ; $i--) {
                            $selected = '';
                            if(groups_get_groupmeta( $item->id, 'year') != '' ){
                                if(groups_get_groupmeta( $item->id, 'year') == $i) $selected = "selected";
                            }else if(date("Y") == $i){
                                $selected = "selected";
                            }
                            echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
                        }

                    ?>
                </select>
            </p>
            <p>
                <label for="category" >Catégorie de l'expé</label>
                <select name="category" id="category" >
                    <option <?php if ( groups_get_groupmeta( $item->id, 'category') == 'autre') echo ' selected="selected"'; ?> value="autre" >Autre</option>
                    <option <?php if ( groups_get_groupmeta( $item->id, 'category') == 'alpi') echo ' selected="selected"'; ?> value="alpi" >Alpinisme</option>
                    <option <?php if ( groups_get_groupmeta( $item->id, 'category') == 'kayak') echo ' selected="selected"'; ?> value="kayak" >Canoé-kayak</option>
                    <option <?php if ( groups_get_groupmeta( $item->id, 'category') == 'packraft') echo ' selected="selected"'; ?> value="packraft" >Packraft</option>
                    <option <?php if ( groups_get_groupmeta( $item->id, 'category') == 'canyoning') echo ' selected="selected"'; ?> value="canyoning" >Canyoning</option>
                    <option <?php if ( groups_get_groupmeta( $item->id, 'category') == 'cascadedeglace') echo ' selected="selected"'; ?> value="cascadedeglace" >Cascade de glace</option>
                    <option <?php if ( groups_get_groupmeta( $item->id, 'category') == 'escalade') echo ' selected="selected"'; ?> value="escalade" >Escalade</option>
                    <option <?php if ( groups_get_groupmeta( $item->id, 'category') == 'snowkite') echo ' selected="selected"'; ?> value="snowkite" >Snow-kite</option>
                    <option <?php if ( groups_get_groupmeta( $item->id, 'category') == 'parapente') echo ' selected="selected"'; ?> value="parapente" >Parapente</option>
                    <option <?php if ( groups_get_groupmeta( $item->id, 'category') == 'randonordique') echo ' selected="selected"'; ?> value="randonordique" >Randonnée nordique</option>
                    <option <?php if ( groups_get_groupmeta( $item->id, 'category') == 'skifond') echo ' selected="selected"'; ?> value="skifond" >Ski de fond</option>
                    <option <?php if ( groups_get_groupmeta( $item->id, 'category') == 'skipiste') echo ' selected="selected"'; ?> value="skipiste" >Ski de piste</option>
                    <option <?php if ( groups_get_groupmeta( $item->id, 'category') == 'skirando') echo ' selected="selected"'; ?> value="skirando" >Ski de randonnée</option>
                    <option <?php if ( groups_get_groupmeta( $item->id, 'category') == 'speleo') echo ' selected="selected"'; ?> value="speleo" >Spéléologie</option>
                    <option <?php if ( groups_get_groupmeta( $item->id, 'category') == 'trek') echo ' selected="selected"'; ?> value="trek" >Trekking</option>
                    <option <?php if ( groups_get_groupmeta( $item->id, 'category') == 'velo') echo ' selected="selected"'; ?> value="velo" >Vélo</option>
                    <option <?php if ( groups_get_groupmeta( $item->id, 'category') == 'voile') echo ' selected="selected"'; ?> value="voile" >Voile</option>

                </select>
            </p>
        <?php

        wp_nonce_field( 'bpm_category_save_' . $item->id, 'bpm_category_admin' );
        wp_nonce_field( 'bpm_year_save_' . $item->id, 'bpm_year_admin' );

    }

    public function save_meta(  $group_id , $meta_name ){

        check_admin_referer( 'bpm_' . $meta_name . '_save_' . $group_id, 'bpm_' . $meta_name . '_admin' );

        $new = htmlspecialchars($_POST[$meta_name]);

        if( groups_get_groupmeta($group_id, $meta_name) == '' || groups_get_groupmeta($group_id, 'year') == NULL){
            return groups_add_groupmeta( $group_id , $meta_name, $new);
        }else{
            return groups_update_groupmeta( $group_id , $meta_name, $new);

        }

        return 0;


    }

    public function admin_ui_save_meta( $group_id = 0 ) {

        if ( 'POST' !== strtoupper( $_SERVER['REQUEST_METHOD'] ) || empty( $group_id ) )
            return false;

        $this->save_meta( $group_id, 'year');
        $this->save_meta( $group_id, 'category');

    }

    public function group_creation_step_meta(){

        $id = bp_get_group_id(false);
        $category = groups_get_groupmeta( $id, 'category');

        ?>

        <div>
            <label for="year" >Année de l'expé</label>
            <select name="year" id="year" >
                <?php

                    for ($i=date("Y")+1; $i > 1991 ; $i--) {
                            $selected = '';
                            if(groups_get_groupmeta( $id, 'year') != '' ){
                                if(groups_get_groupmeta( $id, 'year') == $i) $selected = "selected";
                            }else if(date("Y") == $i){
                                $selected = "selected";
                            }
                            echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
                    }

                ?>
            </select>
        </div>
        <label for="category">Catégorie de l'expé</label>
        <div>
            <select name="category" id="category" >
                    <option <?php if ( $category == 'autre') echo ' selected="selected"'; ?> value="autre" >Autre</option>
                    <option <?php if ( $category == 'alpi') echo ' selected="selected"'; ?> value="alpi" >Alpinisme</option>
                    <option <?php if ( $category == 'kayak') echo ' selected="selected"'; ?> value="kayak" >Canoé-kayak</option>
                    <option <?php if ( $category == 'packraft') echo ' selected="selected"'; ?> value="packraft" >Packraft</option>
                    <option <?php if ( $category == 'canyoning') echo ' selected="selected"'; ?> value="canyoning" >Canyoning</option>
                    <option <?php if ( $category == 'cascadedeglace') echo ' selected="selected"'; ?> value="cascadedeglace" >Cascade de glace</option>
                    <option <?php if ( $category == 'escalade') echo ' selected="selected"'; ?> value="escalade" >Escalade</option>
                    <option <?php if ( $category == 'snowkite') echo ' selected="selected"'; ?> value="snowkite" >Snow-kite</option>
                    <option <?php if ( $category == 'parapente') echo ' selected="selected"'; ?> value="parapente" >Parapente</option>
                    <option <?php if ( $category == 'randonordique') echo ' selected="selected"'; ?> value="randonordique" >Randonnée nordique</option>
                    <option <?php if ( $category == 'skifond') echo ' selected="selected"'; ?> value="skifond" >Ski de fond</option>
                    <option <?php if ( $category == 'skipiste') echo ' selected="selected"'; ?> value="skipiste" >Ski de piste</option>
                    <option <?php if ( $category == 'skirando') echo ' selected="selected"'; ?> value="skirando" >Ski de randonnée</option>
                    <option <?php if ( $category == 'speleo') echo ' selected="selected"'; ?> value="speleo" >Spéléologie</option>
                    <option <?php if ( $category == 'trek') echo ' selected="selected"'; ?> value="trek" >Trekking</option>
                    <option <?php if ( $category == 'velo') echo ' selected="selected"'; ?> value="velo" >Vélo</option>
                    <option <?php if ( $category == 'voile') echo ' selected="selected"'; ?> value="voile" >Voile</option>

                </select>

        </div>

        <?php

        wp_nonce_field( 'bpm_category_save_' . $id, 'bpm_category_admin' );
        wp_nonce_field( 'bpm_year_save_' . $id, 'bpm_year_admin' );

    }

    public function save_meta_creation_step(  $group_id , $meta_name ){
        $new = htmlspecialchars($_POST[$meta_name]);
        groups_add_groupmeta( $group_id , $meta_name, $new);

        if( groups_get_groupmeta($group_id, $meta_name) == '' || groups_get_groupmeta($group_id, 'year') == NULL){
            return groups_add_groupmeta( $group_id , $meta_name, $new);
        }else{
            return groups_update_groupmeta( $group_id , $meta_name, $new);

        }

        return 0;
    }

    public function group_save_meta(){

        check_admin_referer( 'groups_create_save_group-details' );

        $bp    = buddypress();
        $group_id = $bp->groups->new_group_id;

        $this -> save_meta_creation_step( $group_id, 'year');
        $this -> save_meta_creation_step($group_id, 'category');
    }

    public function filter_ajax_querystring( $querystring = '', $object = '' ) {
        /* bp_ajax_querystring is also used by other components, so you need
        to check the object is groups, else simply return the querystring and stop the process */
        if( $object != 'groups' )
            return $querystring;

        // Let's rebuild the querystring as an array to ease the job
        $defaults = array(
          'type'            => 'active',
          'action'          => 'active',
          'scope'           => 'all',
          'page'            => 1,
          'user_id'         => 0,
          'search_terms'    => '',
          'exclude'         => false,
        );
        $bpm_querystring = wp_parse_args( $querystring, $defaults );

        /* Prepare the meta-query using extras arguments from the AJAX request */
        $extras = wp_parse_args((isset($_POST['extras']) ? $_POST['extras'] : NULL));
        $meta_query = [];

        /* Check if a date was selected */
        $year = isset($extras['year']) ? $extras['year'] : NULL;
        if($year && preg_match('/^[0-9]+$/', $year)){
          array_push($meta_query, array(
            'key' => 'year',
            'value' => intval($year),
            'type' => 'numeric',
            'compare' => '='
            )
          );
        }

        /* Check if a category was selected */
        $category = isset($extras['category']) ? $extras['category'] : NULL;
        if ($category && strpos($category, 'Toutes') === false) {
          array_push($meta_query, array(
            'key' => 'category',
            'value' => sanitize_text_field($category),
            'compare' => '='
            )
          );
        }

        /* If there is at least one meta-query, we pass it further along */
        if (count($meta_query)>0) {
          $bpm_querystring['meta_query'] = $meta_query;
        }

        /* Using a filter will help other plugins to eventually extend this feature */
        return apply_filters( 'bpm_filter_ajax_querystring', $bpm_querystring, $querystring );
    }

    public function new_options(){
        ?>

      </select>
    </li>

    <li id="groups-year-select" class="filter">
      <label for="groups-year">Ann�e de l'exp�:</label>
      <select name="year" id="groups-year" >
        <option >Toutes années</option>
        <?php

        for ($i=date("Y")+1; $i > 1991 ; $i--) {
          $selected = '';
          echo '<option value="' . $i . '" ' . $selected . '>' . $i . '</option>';
        }

        ?>
      </select>
    </li>

    <li id="groups-category-select" class="filter">
      <label for="groups-category">Catégorie de l'expé</label>
      <select name="category" id="groups-category" >
        <option >Toutes catégories</option>
        <option <?php if ( '' == 'autre') echo ' selected="selected"'; ?> value="autre" >Autre</option>
        <option <?php if ( '' == 'alpi') echo ' selected="selected"'; ?> value="alpi" >Alpinisme</option>
        <option <?php if ( '' == 'kayak') echo ' selected="selected"'; ?> value="kayak" >Canoé-kayak</option>
        <option <?php if ( '' == 'packraft') echo ' selected="selected"'; ?> value="packraft" >Packraft</option>
        <option <?php if ( '' == 'canyoning') echo ' selected="selected"'; ?> value="canyoning" >Canyoning</option>
        <option <?php if ( '' == 'cascadedeglace') echo ' selected="selected"'; ?> value="cascadedeglace" >Cascade de glace</option>
        <option <?php if ( '' == 'escalade') echo ' selected="selected"'; ?> value="escalade" >Escalade</option>
        <option <?php if ( '' == 'snowkite') echo ' selected="selected"'; ?> value="snowkite" >Snow-kite</option>
        <option <?php if ( '' == 'parapente') echo ' selected="selected"'; ?> value="parapente" >Parapente</option>
        <option <?php if ( '' == 'randonordique') echo ' selected="selected"'; ?> value="randonordique" >Randonnée nordique</option>
        <option <?php if ( '' == 'skifond') echo ' selected="selected"'; ?> value="skifond" >Ski de fond</option>
        <option <?php if ( '' == 'skipiste') echo ' selected="selected"'; ?> value="skipiste" >Ski de piste</option>
        <option <?php if ( '' == 'skirando') echo ' selected="selected"'; ?> value="skirando" >Ski de randonnée</option>
        <option <?php if ( '' == 'speleo') echo ' selected="selected"'; ?> value="speleo" >Spéléologie</option>
        <option <?php if ( '' == 'trek') echo ' selected="selected"'; ?> value="trek" >Trekking</option>
        <option <?php if ( '' == 'velo') echo ' selected="selected"'; ?> value="velo" >Vélo</option>
        <option <?php if ( '' == 'voile') echo ' selected="selected"'; ?> value="voile" >Voile</option>

        <?php
    }

    public function default_cover_photo(){
        $category = groups_get_groupmeta( bp_get_group_id(), 'category');
        return $category . '.png';
    }
}

New groups_metadata();

?>
