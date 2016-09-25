<?php

/* le 8/4/16 Guillaume F
*
* Crée des groupes à partir du fichier csv dont le chemin est passé dans l'url "?s=<path>"
*
* modifié le 17/5/16 par GF
*/


ini_set('display_errors', 1);
                          ini_set('display_startup_errors', 1);
                          error_reporting(E_ALL);


// Charge l'environnement wordpress et buddypress (fonctions...)
require ('../../../../wp-blog-header.php');
	// require('../../../plugins/buddypress/bp-forums/bbpress/bb-load.php');



$file = htmlspecialchars($_GET['s']);
$f = file_get_contents($file);

$f = explode("\n", $f);
$x = count($f);

for($i = 1 ; $i < $x ; $i++ ) {

  $groups_info = explode('|', $f[$i]);
  $cat = $groups_info[9];

  $arg = array('group_id'     => $groups_info[0],
  'creator_id'   => $groups_info[1],
  'name'         => $groups_info[2],
  'description'  => $groups_info[3],
  'slug'         => $groups_info[4],
  'status'       => $groups_info[5],
  'enable_forum' => $groups_info[6],
  'date_created' => $groups_info[7]);

  // crée le groupe avec les infos ci-dessus
  $id = groups_create_group( $arg );

  // rajoute certaines meta au groupe ( année, catégorie,...)
  groups_add_groupmeta($id, 'year', $groups_info[8]);
  groups_add_groupmeta($id, 'category', $cat);
  groups_add_groupmeta( $id, 'image', $groups_info[10]);
  groups_add_groupmeta( $id, 'video', $groups_info[11]);  
  echo $groups_info[2] . ' ' . $id . "<br>";


  $title = $groups_info[2];
  echo $title . '<br>';

  // cherche l'id de la page de groupe parent en cherchant parmis les page de groupe la page dont le nom est le même que le nom de l'expé
  for( $i = 2000 ; $i < 1000350 ; $i++){
    // echo get_the_title($i) . ' <br>';
     if( get_the_title($i) == $title){
         $post_id = $i;
         break;
       }

  }
echo '<br><br>' . $post_id;

  // ajoute une méta pour lié le blog avec la page de groupe parent
  // groups_add_groupmeta( $id, 'bpge', 'a:7:{s:8:"gpage_id";i:3001;s:19:"display_page_layout";s:7:"profile";s:12:"display_page";s:6:"public";s:17:"display_page_name";s:6:"Extras";s:10:"gpage_name";s:5:"Pages";s:14:"display_gpages";s:6:"public";s:9:"home_name";s:4:"Home";}' );

  $db = new PDO('mysql:host=localhost;dbname=wordpress2;charset=utf8', 'root' , 'root' );
  $req = $db -> prepare(' INSERT INTO wp_bp_groups_groupmeta(group_id, meta_key, meta_value) VALUES (:group_id, :meta_key, :meta_value) ');
  $req -> execute( array(
    'group_id' => $id, 
    'meta_key' => 'bpge', 
    'meta_value' => 'a:7:{s:8:"gpage_id";i:' . $post_id . ';s:19:"display_page_layout";s:7:"profile";s:12:"display_page";s:6:"public";s:17:"display_page_name";s:6:"Extras";s:10:"gpage_name";s:5:"Pages";s:14:"display_gpages";s:6:"public";s:9:"home_name";s:4:"Home";}'
    ) );

  sleep(2);
}

?>


