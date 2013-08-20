<?
/**
 *  Allomani Audio and Video v2.7
 * 
 * @package Allomani.Audio.and.Video
 * @version 2.7
 * @copyright (c) 2006-2009 Allomani , All rights reserved.
 * @author Ali Allomani <info@allomani.com>
 * @link http://allomani.com
 * @license GNU General Public License version 3.0 (GPLv3)
 * 
 */

chdir('./../');
define('CWD', (($getcwd = str_replace("\\","/",getcwd())) ? $getcwd : '.'));
define('IS_ADMIN', 1);
$is_admin =1 ;

include_once(CWD . "/global.php") ;
header("Content-Type: text/html;charset=$settings[site_pages_encoding]");

if(!check_login_cookies()){die("<center> $phrases[access_denied] </center>");}  


//----- Set Blocks Sort ---------//
if($action=="set_blocks_sort"){
 //   file_put_contents("x.txt","d".$data[0]); 
 if_admin();
if(is_array($blocks_list_r)){
$sort_list = $blocks_list_r ;
$pos="r";
}elseif(is_array($blocks_list_c)){
$sort_list = $blocks_list_c ;
$pos="c";
}else{
$sort_list = $blocks_list_l ;
$pos="l";
}
 
if(is_array($sort_list)){
 for ($i = 0; $i < count($sort_list); $i++) {  
    db_query("UPDATE songs_blocks SET ord = '$i',pos='$pos' WHERE `id` = $sort_list[$i]");
 }
}
 }
 
 //------------ Set Banners Sort ---------------
if($action=="set_banners_sort"){
    if_admin("adv");
if(is_array($sort_list)){
 for ($i = 0; $i < count($sort_list); $i++) {  
    db_query("UPDATE songs_banners SET ord = '$i' WHERE `id` = $sort_list[$i]");
 }
}
}

 //------------- Set Cats Sort -----------------
if($action=="set_cats_sort"){
if_admin();
if(is_array($sort_list)){
 for ($i = 0; $i < count($sort_list); $i++) {  
    db_query("UPDATE songs_cats SET ord = '$i' WHERE `id` = $sort_list[$i]");
 }
}
}
 
 
 //--------- New Stores Menu Sort ------------
if($action=="set_new_stores_sort"){
    if_admin("new_stores");
if(is_array($sort_list)){
 for ($i = 0; $i < count($sort_list); $i++) {  
    db_query("UPDATE songs_new_menu SET ord = '$i' WHERE `id` = $sort_list[$i]");
 }
}
}

 //--------- New Songs Menu Sort ------------
if($action=="set_new_songs_sort"){
    if_admin("new_songs");
if(is_array($sort_list)){
 for ($i = 0; $i < count($sort_list); $i++) {  
    db_query("UPDATE songs_new_songs_menu SET ord = '$i' WHERE `id` = $sort_list[$i]");
 }
}
}

 //--------- Videos Cats  Sort ------------
if($action=="set_videos_cats_sort"){
if(is_array($sort_list)){
 for ($i = 0; $i < count($sort_list); $i++) {  
    if_videos_cat_admin($sort_list[$i]); 
    db_query("UPDATE songs_videos_cats SET ord = '$i' WHERE `id` = $sort_list[$i]");
 }
}
}
 //--------- Songs Fields  Sort ------------
if($action=="set_songs_custom_fields_sort"){
     if_admin("songs_fields"); 
if(is_array($sort_list)){
 for ($i = 0; $i < count($sort_list); $i++) {  
   
    db_query("UPDATE songs_custom_sets SET ord = '$i' WHERE `id` = $sort_list[$i]");
 }
}
}
 //--------- Songs Fields  Sort ------------
if($action=="set_urls_fields_sort"){
     if_admin("urls_fields"); 
if(is_array($sort_list)){
 for ($i = 0; $i < count($sort_list); $i++) {  
   
    db_query("UPDATE songs_urls_fields SET ord = '$i' WHERE `id` = $sort_list[$i]");
 }
}
}
//---------------------------------------------- 
 