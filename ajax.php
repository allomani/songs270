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

include_once("global.php") ;
header("Content-Type: text/html;charset=$settings[site_pages_encoding]");
//------------------------------------------
if($action=="check_register_username"){
if(strlen($str) >= $settings['register_username_min_letters']){
$exclude_list = explode(",",$settings['register_username_exclude_list']) ;

	 if(!in_array($str,$exclude_list)){
//$num = db_num(member_query("select","id",array("username"=>"='$str'")));
$num = db_qr_num("select ".members_fields_replace("id")." from ".members_table_replace("songs_members")." where ".members_fields_replace("username")." like '".db_clean_string($str,"code")."'",MEMBER_SQL);
  
if(!$num){
print "<img src='images/true.gif'>";
}else{
print "<img src='images/false.gif' alt=\"".str_replace("{username}",$str,"$phrases[register_user_exists]")."\">";
	}
	}else{
	print "<img src='images/false.gif' alt=\"$phrases[err_username_not_allowed]\">";
		}
	}else{
	print "<img src='images/false.gif' alt=\"$phrases[err_username_min_letters]\">";
		}
}


//------------------------------------------
if($action=="check_register_email"){
if(check_email_address($str)){
$num = db_qr_num("select ".members_fields_replace("id")." from ".members_table_replace("songs_members")." where ".members_fields_replace("email")." like '".db_clean_string($str,"code")."'",MEMBER_SQL);
if(!$num){
print "<img src='images/true.gif'>";
}else{
print "<img src='images/false.gif' alt=\"$phrases[register_email_exists]\">";
	}
	}else{
	print "<img src='images/false.gif' alt=\"$phrases[err_email_not_valid]\">";
		}
}
//---------------------------------
if($action=="get_playlist_items"){
if(check_member_login()){
  $id= intval($id) ;
  
 set_cookie('last_list_id',$id);
 
$qr_list = db_query("select * from songs_playlists_data where member_id='$member_data[id]' and cat='$id' order by ord");

if(db_num($qr_list)){

while($data_list =db_fetch($qr_list)){

get_playlist_item($data_list['id'],$data_list['song_id'],1);

}
}else{
print "---";
}
}
}
//-------------------------------
if($action=="get_playlists"){
  if(check_member_login()){   
 $last_list_id = intval(get_cookie('last_list_id'));
    
$qr_lists = db_query("select * from songs_playlists where member_id='$member_data[id]'");
print "<center><select name='playlist_id' id='playlist_id' onchange=\"get_playlist_items(this.value);\">
<option value=\"0\">$phrases[default_playlist]</option>";
while($data_lists = db_fetch($qr_lists)){
print "<option value=\"$data_lists[id]\"".iif($last_list_id==$data_lists['id']," selected").">$data_lists[name]</option>";
}
print "</select><br><br>
<a href=\"javascript:playlists_add();\"><img src='images/add_small.gif' border=0 alt=\"$phrases[playlists_add]\"></a>
&nbsp; <a href=\"javascript:playlists_del($('playlist_id').value);\"><img src='images/delete_small.gif' border=0 alt=\"$phrases[playlists_del]\"></a></center><br>";
  }
  }
//-------------------------------
if($action=="playlists_add"){
if(check_member_login()){
     
 db_query("insert into songs_playlists (name,member_id) values('".db_clean_string($name)."','$member_data[id]')");
  $id = mysql_insert_id();
  set_cookie('last_list_id',intval($id));
  print $id;
} 
}
//-------------------------------
if($action=="playlists_del"){
if(check_member_login()){
     
 db_query("delete from songs_playlists where id='$id' and member_id='$member_data[id]'");
  set_cookie('last_list_id','0');
  print "0";
} 
}
//---------------------------------------
if($action=="playlist_add_song"){
if(check_member_login()){
    $last_list_id = intval(get_cookie('last_list_id')); 
    $song_id = intval($song_id);
      
 db_query("insert into songs_playlists_data (song_id,member_id,cat) values('$song_id','$member_data[id]','$last_list_id')");
  $id = mysql_insert_id();
print $id;
}
}
//----------------------------
if($action=="playlist_get_item"){
get_playlist_item($id,0,0);
}
//---------------------------------------
if($action=="playlist_delete_song"){
if(check_member_login()){
    $id=intval($id);
 db_query("delete from songs_playlists_data where id='$id' and member_id='$member_data[id]'");
}
}
//----------------------------------
if($action=="set_playlist_sort"){
    if(check_member_login()){   
  if(is_array($sort_list)){
 for ($i = 0; $i < count($sort_list); $i++) {
    db_query("UPDATE songs_playlists_data SET ord = '$i' WHERE `id` = '".intval($sort_list[$i])."' and member_id='$member_data[id]'");
 }
}
    }
}
//-----------------------------------------

?>