<?php

/*
 * Créé le 17/5/16 par Guillaume F
 *
 * Exporte les données des blogs 
 *
*/

ini_set('display_errors', 1);
                          ini_set('display_startup_errors', 1);
                          error_reporting(E_ALL);

require('../wp-blog-header.php');

$blog_info = capexpe_latest_blogs(700, 500);
$blog_info = array_reverse($blog_info);
$i = 2000;
foreach ($blog_info as $blog ) {
	$i += 7;	

/* Paramètres de base de création de groupe sur le nouveau capexpe.org :
	'creator_id', 'blog_id','name','description' ,'slug','status','enable_forum','date_created'

	*/

	$creation_date = $blog['expe_year'] . '-' . $blog['expe_month'] . '-01 00:00:00';
	$image = isset($blog['image']);
	$video = isset($blog['video']);
	switch ($blog['default_category']) {
         case "Escalade":
              $cat = "escalade";
              break;
         case "Alpinisme":
               $cat = "alpi";
                     break;
        case "Trekking":
              $cat = "trek";
                    break;
        case "Ski de fond":
              $cat = "skifond";
                    break;
        case "Randonnée nordique":
           $cat = "randonordique";
                 break;
        case "Ski de randonnée":
              $cat = "skirando";
                    break;
        case "Vélo":
              $cat = "velo";
                    break;
        case "Voile":
              $cat = "voile";
                    break;
        case "Ski de piste":
              $cat = "skipiste";
                    break;
       case "Canoé-kayak":
             $cat = "kayak";
                   break;
       case "Cascade de glace":
             $cat = "cascadedeglace";
                   break;
       case "Parapente":
             $cat = "parapente";
                   break;
       case "Canyoning":
              $cat = "canyoning";
                   break;
       default:
               $cat = "autre";
               break;
}

$month_arr['01']=   "janvier";
        $month_arr['02']=   "février";
        $month_arr['03']=   "mars";
        $month_arr['04']=   "avril";
        $month_arr['05']=   "mai";
        $month_arr['06']=   "juin";
        $month_arr['07']=   "juillet";
        $month_arr['08']=   "août";
        $month_arr['09']=   "septembre";
        $month_arr[10]=  "octobre";
        $month_arr[11]=  "novembre";
        $month_arr[12]=  "décembre";

$description = $blog['description'];

if($description == 'Just another Cap Expé weblog'){

		$description = 'Bienvenue sur notre expé ' . $blog['default_category'] . ' - ' . $month_arr[$blog['expe_month']] . ' ' .  $blog['expe_year'] . ' !';

}

echo  ';' . $i . ';' . $blog['author'] . ';' . $description . ';' .  str_replace('http://capexpe.org/', '', str_replace("http://www.capexpe.org/", "", $blog['link'])) . ';public;1;' . $date_created . ';' . $expe_year . ';' . $cat . ';' . $image . ';' . $video . "<br>";

//echo urlencode($blog['name']) . '. . str_replace('http://capexpe.org/', '', str_replace("http://www.capexpe.org/", "", $blog['link'])) . " ";
}


?>