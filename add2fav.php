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
print "<html dir=$settings[html_dir]>";
print "<title> $phrases[add2favorite] </title>";
print "<LINK href='css.php' type=text/css rel=StyleSheet>";


 if(check_member_login()){
     $id=intval($id);
 open_table();
if($type && $id){
   
   db_query("insert into songs_favorites (user,data_id,type) values('$member_data[id]','$id','".db_clean_string($type)."')");
      print "<center>  $phrases[add2fav_success]  </center>";
        }
    close_table();
 }else{
print "<form method=\"POST\" action=\"login.php\">
<input type=hidden name=action value=login>
<input type=hidden name=re_link value=\"$_SERVER[REQUEST_URI]\">
<center>
<table border=\"0\" width=\"50%\">
        <tr>
                <td height=\"15\"><span>$phrases[username] :</span></td></tr><tr>
                <td height=\"15\"><input type=\"text\" name=\"username\" size=\"10\"></td>
        </tr>
        <tr>
                <td height=\"12\"><span>$phrases[password] :</span></td></tr><tr>
                <td height=\"12\" ><input type=\"password\" name=\"password\" size=\"10\"></td>
        </tr>
        <tr>
                <td height=\"23\">
                <p align=\"center\"><input type=\"submit\" value=\"$phrases[login]\"></td>
        </tr>

</table>
</form>\n";
         }

?>