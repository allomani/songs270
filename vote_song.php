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

include "global.php" ;

if($action !="video" && $action !="song"){$action="song";}  


$id = intval($id);
$vote_num = intval($vote_num);
$action = iif(in_array($action,array('video','song')),$action,'song');


$cookie_name = "songs_vote_".$action."_".$id."_added";

//---------------- set vote expire ------------------------
if($vote_num && $action && $id){
if(!$settings['vote_file_expire_hours']){$settings['vote_file_expire_hours'] = 24 ; }

   if(!$HTTP_COOKIE_VARS[$cookie_name]){
  setcookie($cookie_name, "1" , time() + ($settings['vote_file_expire_hours'] * 60 * 60),"/");
  }
        }
//----------------------------------------------------------

print "<html dir=\"$settings[html_dir]\">
<head>
<META http-equiv=Content-Language content=\"$settings[site_pages_lang]\">
<META http-equiv=Content-Type content=\"text/html; charset=$settings[site_pages_encoding]\">
<LINK href='css.php' type=text/css rel=StyleSheet>
</head>";

if($action == "video"){
print "<title> $phrases[vote_video] </title>";
open_table("$phrases[vote_video]");
}else{
print "<title> $phrases[vote_song] </title>";
open_table("$phrases[vote_song]");
        }
if($vote_num && $action && $id){

 if(!$HTTP_COOKIE_VARS[$cookie_name]){
if($action == "video"){
  db_query("update songs_videos_data set votes=votes+$vote_num , votes_total=votes_total+1 where id='$id'");
    print "<center>  $phrases[vote_video_thnx_msg]  </center>";
  }else{
     
  db_query("update songs_songs set votes=votes+$vote_num , votes_total=votes_total+1 where id='$id'");
     print "<center>    $phrases[vote_song_thnx_msg]  </center>";
          }


      }else{
                   print "<center>".str_replace('{vote_expire_hours}',$settings['vote_file_expire_hours'],$phrases['err_vote_file_expire_hours'])."</center>" ;
                     }
        }else{

                print "
<form action='vote_song.php' method=post>
<input type=hidden name=id value='$id'>
<input type=hidden name=action value='$action'>
<center>
<table width=50%>
<tr><td width=30%>
$phrases[vote_select] : </td>
<td>
<select name=vote_num>
<option value=1>1</option>
<option value=2>2</option>
<option value=3>3</option>
<option value=4>4</option>
<option value=5>5</option>
</select>
</td>
<td><input type=submit value='$phrases[vote_do]'></td>
</tr>

</table></form>";
}
close_table();