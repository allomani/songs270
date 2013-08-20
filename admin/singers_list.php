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
define('CWD', (($getcwd = getcwd()) ? $getcwd : '.'));

require(CWD . "/global.php") ;

if(!check_login_cookies()){die("<center> $phrases[access_denied] </center>");} 

print "<html dir=rtl>
<title>$phrases[singers_list]</title>";
print "<META http-equiv=Content-Language content=\"$settings[site_pages_lang]\">
<META http-equiv=Content-Type content=\"text/html; charset=$settings[site_pages_encoding]\">";
print "<LINK href='images/style.css' type=text/css rel=StyleSheet>
<script src='js.js' type=\"text/javascript\" language=\"javascript\"></script>";

  $qr = db_query("select songs_singers.id as id ,songs_singers.name as name,songs_cats.name as cat from songs_singers,songs_cats where songs_singers.cat=songs_cats.id order by binary songs_cats.name , binary songs_singers.name asc");
 if(db_num($qr)){
print "<br>
         <center><table width=98% class=grid><tr>
<td><b> $phrases[the_cat] </b></td><td><b>$phrases[the_singers]</b></td><td><b>$phrases[the_albums]</b></td>
";

 $tr_ord=1;
  while($data = db_fetch($qr)){

   if($tr_ord ==1){
                   $tr_class="songs_1" ;
                   $tr_ord = 2 ;
                   }else{
                    $tr_class="songs_2";
                    $tr_ord = 1 ;
                           }

         print "<tr class='$tr_class'><td>$data[cat]</td>
         <td><a href=\"javascript:select_singer($data[id]);\">$data[name]</a></td>
         <td>";
         $qr_albums= db_query("select id,name from songs_albums where cat='$data[id]'");
         while($data_albums=db_fetch($qr_albums)){
         print "<a href=\"javascript:select_album($data_albums[id]);\">$data_albums[name]</a> &nbsp;";
         }
         print "</td></tr>";
          }
          print "</table>";
          }else{
          print "<center>  $phrases[no_singers] </center>";
                  }
 

          ?>