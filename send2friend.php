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

include "global.php";
print "<html dir=\"$settings[html_dir]\">
<head>
<META http-equiv=Content-Language content=\"$settings[site_pages_lang]\">
<META http-equiv=Content-Type content=\"text/html; charset=$settings[site_pages_encoding]\">
<title> $phrases[send2friend] </title>
<LINK href='css.php' type=text/css rel=StyleSheet>
</head>";

if($op !="video" && $op !="song"){$op="song";}
 
open_table("$phrases[send2friend]");
if($name_from && $email_from && $email_to){

  $name_from = htmlspecialchars($name_from) ;
 $email_from = htmlspecialchars($email_from) ;
 $email_to = htmlspecialchars($email_to) ;
 
if(check_email_address($email_from) && check_email_address($email_to)){
 if($op=="video"){
     
$url_watch = $scripturl."/".get_template('links_video_watch',array('{id}','{cat}'),array($id,'1'))  ;
$url_save = $scripturl."/".get_template('links_video_download',array('{id}','{cat}'),array($id,'1'))  ;

$data = db_qr_fetch("select name,cat from songs_videos_data where id='$id'");
$data_cat = db_qr_fetch("select name from songs_videos_cats where id='$data[cat]'");

$file_title = "$data_cat[name] - $data[name]" ;
     
$msg = get_template("friend_msg_clip",array('{title}','{name_from}','{email_from}','{email_to}','{url_download}','{url_watch}','{sitename}','{siteurl}'),array($file_title,$name_from,$email_from,$name_to,$url_save,$url_watch,$sitename,$siteurl));



 }else{

$url_listen = $scripturl."/".get_template('links_song_listen',array('{id}','{cat}'),array($id,'1'))  ;
$url_save = $scripturl."/".get_template('links_song_download',array('{id}','{cat}'),array($id,'1'))  ;


$data = db_qr_fetch("select name,album from songs_songs where id='$id'");
$data_sngr = db_qr_fetch("select name from songs_singers where id='$data[album]'");

$file_title = "$data_sngr[name] - $data[name]" ;
     
$msg = get_template("friend_msg",array('{title}','{name_from}','{email_from}','{email_to}','{url_download}','{url_listen}','{sitename}','{siteurl}'),array($file_title,$name_from,$email_from,$name_to,$url_save,$url_listen,$sitename,$siteurl));

 }

  
                           
$email_result = send_email($name_from,$mailing_email,$email_to,$phrases['send2friend_subject'],$msg,$settings['mailing_default_use_html'],$settings['mailing_default_encoding']);
if($email_result)  {
print "<center>  $phrases[send2friend_done] </center>";
}else{
    print "<center> $phrases[send2friend_failed] </center>";
        }
}else{
 print "<center>$phrases[invalid_from_or_to_email]</center>";   
}
}else{
$op =  htmlspecialchars($op);
$id = intval($id);

print "
<form action='send2friend.php' method=post>
<input type=hidden name=id value='$id'>
<input type=hidden name=op value='$op'> ";
if($op=="video"){
        $data = db_qr_fetch("select name from songs_videos_data where id='$id'");
     print "<p align=center class=title> $data[name]</p>" ;
     }else{
       $data = db_qr_fetch("select name,album from songs_songs where id='$id'");
       $data_sngr = db_qr_fetch("select name,cat from songs_singers where id='$data[album]'");
     print "<p align=center class=title> $data_sngr[name] - $data[name]</p>" ;

             }
             
check_member_login();

print "<table width=100%>
<tr><td >
$phrases[your_name] : </td>
<td><input type=text name=name_from value='$name_from'></td></tr>

<tr><td>
$phrases[your_email] : </td>
<td><input type=text name=email_from value=\"".iif($email_from,$email_from,htmlspecialchars($member_data['email']))."\" dir=ltr></td></tr>

<tr><td>
$phrases[your_friend_email] : </td>
<td><input type=text name=email_to value='$email_to' dir=ltr></td></tr>
<td><td colspan=2 align=center><input type=submit value='$phrases[send]'></td></tr>
</table></form>";
}
close_table();
?>