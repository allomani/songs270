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

// Edited 20-11-2009

 if(!defined('IS_ADMIN')){die('No Access');}  

//----------------------------- Manage Singers ----------------------------------------------
if($action=="singers" || $action=="singer_add_ok" || $action=="singer_del"){


//----------------------------------------------------------------------------------------
$prm_data = db_qr_fetch("select permisions from songs_user where id='$user_info[id]'");

 if($user_info['groupid'] != 1){
  if($id){
  $singer_cat = db_qr_fetch("select cat from songs_singers where id='$id'");

         $songs_permisions = split(",",$prm_data['permisions']);
         if(!in_array($singer_cat['cat'],$songs_permisions)){
   print_admin_table("<center>$phrases[err_cat_access_denied]</center>");
   die();
    }
    }

if(!$prm_data['permisions']){
     print_admin_table("<center>$phrases[err_cat_access_denied]</center>");
     die();
     }
      }
//--------------------------------------------------------------------------------------------


if($action=="singer_del"){
delete_singer($id);
}

if($action=="singer_add_ok"){
if(trim($name)){
 $cat =intval($cat);
 db_query("insert into songs_singers (name,img,cat,active)values('".db_clean_string($name)."','".db_clean_string($img)."','$cat','1')");
 }
     }



if($user_info['groupid'] != 1){
$usr_data = db_qr_fetch("select permisions from songs_user where id='$user_info[id]'");

if($usr_data['permisions']){
    $qr_cat=db_query("select id,name from songs_cats where (id IN ($usr_data[permisions]) and id='$cat') order by ord ASC");
    }

     }else{

$qr_cat=db_query("select id,name from songs_cats where id='$cat' order by ord asc");
      }

  if(db_num($qr_cat)){
  $data_cat = db_fetch($qr_cat);

   print "<a href='index.php?action=singers'><img border=0 src='images/arrw.gif'> $phrases[back_to_cats] </a><br>
   <p align=center class=title>[$data_cat[name]]</p>
   <center><table width=50% class=grid>
  <form name=sender method=POST action='index.php'>
  <input type=hidden name=action value='singer_add_ok'>
   <input type=hidden name=cat value='$cat'>

  <tr><td colspan=3><center><font class=title>$phrases[singer_add]  </font></center></td></tr>
  <tr>
  <td>
  $phrases[the_name] : </td>
  <td><input type=text size=30 name=name></td>
  <td rowspan=3><center><input type=submit value='$phrases[add_button]'></center>

  </tr>
  <tr>
  <td>
  $phrases[the_image] :</td>
  <td> <table><tr><td><input type=text  dir=ltr size=30 name=img></td><td><a href=\"javascript:uploader('singers','img');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a></td></tr></table>

   </td>
   </tr>

   </table>";


 $qr=db_query("select * from songs_singers where cat='$cat' order by binary name ASC");



   if(db_num($qr)){

  $c =0 ;
  print "<center> <br><br>
  <table width=98% class=grid><tr>";
  while($data = db_fetch($qr)){
    print "<td width=25%><center><a href='index.php?action=singer_edit&id=$data[id]'>$data[name]</a></center></td>";
     ++$c ;
     if($c >= 4){print "</tr><tr>";$c=0;}

          }
        print "</td></tr></table></center>";

         }else{
               print "<br><table width=60% class=grid>
               <tr><td><center>$phrases[no_singers_or_no_permissions]</center></td></tr></table>";
               }
        }else{

        if($user_info['groupid'] != 1){
$usr_data = db_qr_fetch("select permisions from songs_user where id='$user_info[id]'");

if($usr_data['permisions']){
    $qr=db_query("select * from songs_cats where id IN ($usr_data[permisions]) order by ord ASC");
    }

     }else{

$qr=db_query("select * from songs_cats order by ord ASC");
      }


                 $c =0 ;
  print "<center> <br><br>
  <table width=98% class=grid><tr>";
  while($data = db_fetch($qr)){
    print "<td width=25%><center><a href='index.php?action=singers&cat=$data[id]'>$data[name]</a></center></td>";
     ++$c ;
     if($c >= 4){print "</tr><tr>";$c=0;}

          }
                        }
        }

//------------------------- Move To Singer List ---------------------
if($action=='song_singer_set'){
print "<center> <p class=title>$phrases[move_songs]</p>";
if(is_array($song_id)){

 if($user_info['groupid'] != 1){
$usr_data = db_qr_fetch("select permisions from songs_user where id='$user_info[id]'");

if($usr_data['permisions'])
{
    $qr=db_query("select id from songs_singers where cat IN ($usr_data[permisions]) and id='$id'");
    }
    if(!db_num($qr)){
        print_admin_table("<center>$phrases[access_denied]</center>");
            die();
            }
     }

 $c = 1 ;
 $data_from = db_qr_fetch("select name from songs_singers where id='$id'");
print "<form action=index.php method=post>
<input type=hidden name=action value='song_singer_set2'>

<input type=hidden name=id value='$id'>

<table width=30% class=grid><tr><td align=center colspan=2><b> $phrases[move_from] : </b> $data_from[name] </td></tr>
<td><b>#</b></td><td align=center><b> $phrases[the_name] </b></td></tr>";

foreach($song_id as $song_idx){
   $data = db_qr_fetch("select name,id from songs_songs where id='$song_idx'");
   print "<input type=hidden name='song_id[]' value='$song_idx'>  ";
print "<tr><td><b>$c</b></td><td>$data[name]</td></tr>";
++$c;
    }
 print "<tr><td colspan=2 align=center><b>$phrases[move_to]  : </b><select name=singer_id>";



 if($user_info['groupid'] != 1){
$usr_data = db_qr_fetch("select permisions from songs_user where id='$user_info[id]'");

if($usr_data['permisions']){
    $qr=db_query("select id,name from songs_singers where cat IN ($usr_data[permisions]) and id !='$id' order by name ASC");
    }

     }else{

$qr=db_query("select id,name from songs_singers where id !='$id' order by binary name asc");
      }



 while($data = db_fetch($qr)){
         print "<option value='$data[id]'>$data[name]</option>";
         }

print "</select> <input type=submit value=' $phrases[next] '></td></tr></table></form>";
}else{
        print "<center>  $phrases[please_select_songs_first] </center>";
        }
        }

//------------------------- Move To Singer List2 ---------------------
if($action=='song_singer_set2'){

print "<center> <p class=title>$phrases[move_songs]</p>";
if(is_array($song_id)){

 if($user_info['groupid'] != 1){
$usr_data = db_qr_fetch("select permisions from songs_user where id='$user_info[id]'");

if($usr_data['permisions'])
{
    $qr=db_query("select id from songs_singers where cat IN ($usr_data[permisions]) and id='$id'");
    }
    if(!db_num($qr)){
          print_admin_table("<center>$phrases[access_denied]</center>");
            die();
            }
     }

 $c = 1 ;
 $data_from = db_qr_fetch("select name from songs_singers where id='$id'");
print "<form action=index.php method=post>
<input type=hidden name=action value='song_singer_set_ok'>

<input type=hidden name=id value='$id'>

<table width=30% class=grid><tr><td align=center colspan=2><b>$phrases[move_from] : </b> $data_from[name] </td></tr>
<td><b>#</b></td><td align=center><b> $phrases[the_name] </b></td></tr>";

foreach($song_id as $song_idx){
   $data = db_qr_fetch("select name,id from songs_songs where id='$song_idx'");
   print "<input type=hidden name='song_id[]' value='$song_idx'>  ";
print "<tr><td><b>$c</b></td><td>$data[name]</td></tr>";
++$c;
    }
    $data_to=db_qr_fetch("select id,name from songs_singers where id ='$singer_id'");
 print "<tr><td colspan=2 align=center><b> $phrases[move_to]  : </b> $data_to[name]
 <input type=hidden name=singer_id value='$singer_id'></tr>
 <tr><td align=center colspan=2><b>$phrases[the_album] :</b> <select name=album_id><option value='0'>$phrases[without_album]</option>";
 $qr_albums = db_query("select * from songs_albums where cat='$singer_id'");
 while($data_albums = db_fetch($qr_albums))
 {
         print "<option value='$data_albums[id]'>$data_albums[name]</option>";
         }




print "</select><input type=submit value=' $phrases[move_do] '></td></tr></table></form>";
}else{
        print "<center>  $phrases[please_select_songs_first] </center>";
        }
        }
//------------------------------ Manage Songs -----------------------------------------
if($action=="singer_edit" || $action=="singer_edit_ok" ||
   $action=="album_add" || $action=="album_edit_ok" || $action=="album_del" ||
    $action=="song_add_ok" || $action=="song_edit_ok" || $action=="song_del" || $action=="song_album_set" ||
    $action=='song_singer_set_ok' || $action=='song_comment_set' || $action=="singer_enable" || $action=="singer_disable"){



  //$album_id = $id ;


  //----------------------------------------------------------------------------------------
$prm_data = db_qr_fetch("select permisions from songs_user where id='$user_info[id]'");

 if($user_info['groupid'] != 1){
  if($id){
  $singer_cat = db_qr_fetch("select cat from songs_singers where id=$id");

         $songs_permisions = split(",",$prm_data['permisions']);
         if(!in_array($singer_cat['cat'],$songs_permisions)){
   print_admin_table("<center>$phrases[err_cat_access_denied]</center>");
   die();
    }
    }

if(!$prm_data['permisions']){
      print_admin_table("<center>$phrases[err_cat_access_denied]</center>");
      die();
     }
      }

      
 //----------------------------------
 if($action=="singer_disable"){
        db_query("update songs_singers set active=0 where id='$id'");
        }

if($action=="singer_enable"){

       db_query("update songs_singers set active=1 where id='$id'");
        }     
//--------------------------------------------------------------------------------------------
if($action=="singer_edit_ok"){
if($name){                                                                                                                
 db_query("update songs_singers set name='".db_clean_string($name)."',img='".db_clean_string($img)."',cat='".intval($cat)."' where id='$id'");
 }
        }
//-----------------------------------------------------
 if($action=="album_edit_ok"){
         db_query("update songs_albums set name='".db_clean_string($name)."',img='".db_clean_string($img)."' where id='$album_id'");
         }
//-----------------------------------------------------
 if($action=="album_del"){
         db_query("delete from songs_albums where id='$album_id'");
         db_query("update songs_songs set album_id='0' where album_id='$album_id'");
         }
//--------------------------------------------------------
if($action=="album_add"){
if(trim($name)){
     db_query("insert into songs_albums(name,img,cat) values('".db_clean_string($name)."','".db_clean_string($album_img)."','".intval($id)."')");
     }
        }
//--------------------------------------------------------------
if($action=='song_album_set'){
        if(is_array($song_id)){
foreach($song_id as $n_song_id){
    db_query("update songs_songs set album_id='$album_id' where id='$n_song_id'");
    }
    }
    }
//------------------------------------------------
if($action=='song_singer_set_ok'){
        if(is_array($song_id)){
foreach($song_id as $song_idx){

 if($user_info['groupid'] != 1){
$usr_data = db_qr_fetch("select permisions from songs_user where id='$user_info[id]'");

if($usr_data['permisions'])
{
    $qr=db_query("select id from songs_singers where cat IN ($usr_data[permisions]) and id='$singer_id'");
    $qr2=db_query("select songs_singers.id from songs_singers,songs_songs where songs_singers.cat IN ($usr_data[permisions]) and songs_songs.album=songs_singers.id and songs_songs.id='$song_idx'");
    }
    if(!db_num($qr) ||!db_num($qr2)){
        print_admin_table("<center>$phrases[access_denied]</center>");
            die();
            }
     }

   db_query("update songs_songs set album='$singer_id',album_id='$album_id' where id='$song_idx'");

    }
    }
    }
//------------------------------------------------
if($action=='song_comment_set'){
        if(is_array($song_id)){
foreach($song_id as $song_idx){
   db_query("update songs_songs set comment='$comment_id' where id='$song_idx'");

    }
    }
    }
//-----------------------------------------------------------------------------------------
 if($action=="song_del"){
 if(!is_array($song_id)){$song_id=array($song_id);}
    foreach($song_id as $del_id){
        delete_song($del_id);
    }
         }
//-------------------------------------------------------------------------
 if($action=="song_add_ok"){
        //  print_r($custom_url);
    for ($i = 0; $i <= count($name); $i++)
        {

if($name["$i"]){

db_query("insert into songs_songs(name,lyrics,album,date,comment,album_id) 
values('".db_clean_string($name[$i],"code")."','".db_clean_string($lyrics[$i],"code")."','".intval($id)."',now(),'$comment[$i]','$album_id');");

$song_id = mysql_insert_id();
  //------------ URLs------------
  if(is_array($custom_url_data) && is_array($custom_url_id)){ 
for($m = 0;$m < count($custom_url_id["$i"]);$m++){
if($custom_url_id["$i"]["$m"]){
db_query("insert into songs_urls_data (url,cat,song_id) values('".db_clean_string($custom_url_data["$i"]["$m"])."','".$custom_url_id["$i"]["$m"]."','$song_id')");
  
}

    }
}
//------------- Songs Custom Fields  ------------------
 if(is_array($song_field_data) && is_array($song_field_id)){
for($z=0;$z<count($song_field_id[$i]);$z++){
if($song_field_id[$i][$z]){

 db_query("insert into songs_custom_fields (song_id,cat,value) values('$song_id','".$song_field_id[$i][$z]."','".$song_field_data[$i][$z]."')");

}
}
 }
//--------------------------
                        }
             }
                        }
//---------------------Song Edit ------------------------------------------------
      if($action == "song_edit_ok"){

        for ($i = 0; $i < count($song_id); $i++)
        {
db_query("update songs_songs set name='".db_clean_string($name[$i],"code")."',lyrics='".db_clean_string($lyrics[$i],"code")."',comment='$comment[$i]' where id='".intval($song_id[$i])."'");
    
    
//---------- URLs------
if(is_array($custom_url_data) && is_array($custom_url_id)){ 
for($m = 0;$m < count($custom_url_id["$i"]);$m++){
if($custom_url_id["$i"]["$m"]){
    
$qr = db_query("select id from songs_urls_data where cat='".$custom_url_id[$i][$m]."' and song_id='".$song_id[$i]."'"); 
if(db_num($qr)){    
db_query("update songs_urls_data set url='".db_clean_string($custom_url_data["$i"]["$m"])."' where cat='".$custom_url_id["$i"]["$m"]."' and song_id='".$song_id[$i]."'");
}else{
db_query("insert into songs_urls_data (url,cat,song_id) values('".db_clean_string($custom_url_data["$i"]["$m"])."','".$custom_url_id["$i"]["$m"]."','".$song_id[$i]."')");

}  
}

    }
}
 //------------- Songs Custom Fields  ------------------

 if(is_array($song_field_data) && is_array($song_field_id)){
for($z=0;$z<count($song_field_id[$i]);$z++){
if($song_field_id[$i][$z]){


$qr = db_query("select id from songs_custom_fields where cat='".$song_field_id[$i][$z]."' and song_id='".$song_id[$i]."'");
if(db_num($qr)){
   db_query("update songs_custom_fields set value='".$song_field_data[$i][$z]."' where cat='".$song_field_id[$i][$z]."' and song_id='".$song_id[$i]."'");
 }else{
   db_query("insert into songs_custom_fields (song_id,cat,value) values('".$song_id[$i]."','".$song_field_id[$i][$z]."','".$song_field_data[$i][$z]."')");
}

}
}
}
     //--------------------------------- 

        }
       }



//--------------------------- Singer Edit Form ----------------------------
  $data = db_qr_fetch("select * from songs_singers where id='$id'");

  print "<a href='index.php?action=singers&cat=$data[cat]'><img border=0 src='images/arrw.gif'> $phrases[back_to_singers] </a><br>
  <center><table width=50% class=grid>
  <form name=sender method=POST action='index.php'>
  <input type=hidden name=action value='singer_edit_ok'>
  <input type=hidden name=id value='$id'>
  <tr><td colspan=2><center><font class=title>$phrases[edit_singer]</font></center></td></tr>
  <tr>
  <td>
  $phrases[the_name] : </td>
  <td><input type=text value=\"$data[name]\" size=30 name=name></td>
  </tr>
  <tr>
  <td>
  $phrases[the_image] :</td>
  <td>
  <table><tr><td><input type=text value=\"$data[img]\" dir=ltr size=30 name=img></td><td><a href=\"javascript:uploader('singers','img');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a></td></tr></table>
   </td>
   </tr><tr>
   <td> $phrases[the_cat] : </td>

   <td><select name=cat>";

   $qr_cat=db_query("select * from songs_cats order by ord ASC");
   while($data_cat = db_fetch($qr_cat)){
   if($data_cat['id']==$data['cat']){
           $select = "selected" ;
           }else{
                   $select = "";
                   }

      print "<option value='$data_cat[id]' $select>$data_cat[name]</option>";
           }
   print"</select></td>
   </tr>
   <tr><td colspan=2><center><input type=submit value='$phrases[edit]'> </center>
<p align=left>[";
if($data['active']){
                        print "<a href='index.php?action=singer_disable&id=$data[id]'>$phrases[disable]</a>" ;
                        }else{
                        print "<a href='index.php?action=singer_enable&id=$data[id]'>$phrases[enable]</a>" ;
                        }
print " - <a href=\"index.php?action=singer_del&id=$data[id]&cat=$data[cat]\" onClick=\"return confirm('$phrases[del_singer_warning]');\">$phrases[delete]</a>]</p>
     </td></tr>
   </table></form>";

   print "<br> </center>";

//------------ set last update date ---------------------
$lstupd_qr = db_query("select date from songs_songs where album='$id' order by id desc limit 1");
if(db_num($lstupd_qr)){
          $lstupd_data = db_fetch($lstupd_qr);

           db_query("update songs_singers set last_update='$lstupd_data[date]' where id='$id'");


          }else{
            db_query("update songs_singers set last_update='0000-00-00 00:00:00' where id='$id'");
                  }
//-------------------------------- Albums Managment ----------------------------
      print "<center><p align=center class=title> $phrases[the_albums] </p>
      <form action='index.php' method=post name=sender2>
      <input type=hidden name='action' value='album_add'>
      <input type=hidden name='id' value='$id'>

      <table width=50% class=grid>
      <tr><td>$phrases[the_name]:</td><td><input type=text name=name size=20></td><td></td></tr>
<tr>
  <td>
  $phrases[the_image] :</td>
  <td> <table><tr><td><input type=text  dir=ltr size=20 name=album_img></td><td><a href=\"javascript:uploader2('albums','album_img','sender2');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a></td></tr></table>

   </td>
    <td colspan=2><input type=submit value='$phrases[add_button]'></td></tr>
      </table></form>
      <br>" ;
     $qr_albums = db_query("select * from songs_albums where cat='$id' order by id desc");
     if(db_num($qr_albums)){
      print "<table width=70% class=grid><tr><td colspan=3>
      </td></tr>";

      while($data_albums =db_fetch($qr_albums)){
          $song_count = db_qr_fetch("select count(id) as count from songs_songs where album_id='$data_albums[id]'");

      print "<tr><td><center><font class=title>$data_albums[name]</title></td><td> <center> $song_count[count] $phrases[song] </td>
      <td><center><a href='index.php?action=album_edit&id=$id&album_id=$data_albums[id]'>$phrases[edit] </a></td>
      <td><center><a href=\"index.php?action=album_del&id=$id&album_id=$data_albums[id]\"
      onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a></td>";


              }
              print "</table><br>";
            }

//------------------------------- Songs Managment -----------------------------
  print "<center>

 <br><center><font class=title> $phrases[the_songs] </font>
   <br><br> [<a href='index.php?action=song_add&id=$id'> $phrases[add_songs]  </a>";
   if(if_admin("",true)){
           print "- <a href='index.php?action=auto_add&id=$id'> $phrases[auto_search]  </a>";
           }
           print "]<br><br>


   <table width=70% class=grid>";

   $qr = db_query("select * from songs_songs where album='$id' order by album_id DESC,binary name ASC");

   if (db_num($qr)){
    print "
    <form action=index.php method=post  name=submit_form>

   <input type=hidden name=id value='$id'>" ;
       $c = 1 ;
   while($data = db_fetch($qr)){
      $data_comment = db_qr_fetch("select name from songs_comments where id='$data[comment]'");
       print " <tr id=song_tr_$c onmouseover=\"set_tr_color(this,'#EFEFEE');\" onmouseout=\"set_tr_color(this,'#FFFFFF');\">
       <td width=2><input name='song_id[$c]' type='checkbox' value='$data[id]' onclick=\"set_checked_color('song_tr_$c',this)\"></td>
 <td>$data[name]" ;

 if(trim($data_comment['name'])){
   print "&nbsp;&nbsp;  <i><font color=#D20000>$data_comment[name]</font></i>";
   }

   print "</td>
 <td align=center>";
 $get_album = db_qr_fetch("select name from songs_albums where id=$data[album_id]");
 if($get_album['name']){print $get_album['name'];}else{print "$phrases[without_album]";}

 print "</td><td align=center><a href='index.php?action=song_edit&song_id=$data[id]&id=$id'>$phrases[edit] </a> </td><td align=center>
                <a href='index.php?action=song_del&song_id=$data[id]&id=$id' onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a>
                </td>
                </tr>";
                $c++;
           }
          print "<tr><td width=2><img src='images/arrow_".$global_dir.".gif'></td>
          <td width=100% colspan=5>
          <table><tr><td>

          <a href='#' onclick=\"CheckAll(); return false;\"> $phrases[select_all] </a> -
          <a href='#' onclick=\"UncheckAll(); return false;\">$phrases[select_none] </a>
          &nbsp;&nbsp;  ";
           $qr_albums = db_query("select * from songs_albums where cat='$id' order by id desc");

          print "<select name=action onChange=\"show_options(this)\"><option value='song_comment_set'>$phrases[change_comment]</option>" ;
           if(db_num($qr_albums)){
          print "<option value='song_album_set'>$phrases[move_to_album]</option>" ;
          }

          print "
          <option value='song_singer_set'>$phrases[move_to_singer]</option>
          <option value='song_edit'>$phrases[edit_songs]</option>
          <option value='song_del'>$phrases[delete_songs]</option>
          <option value='new_songs_menu_add'>$phrases[add_to_new_songs_menu]</option> 
          </select></td><td><div id=albums_set_div style=\"display: none; text-decoration: none\">";

     if(db_num($qr_albums)){
      print "<img src='images/arrow_".$global_dir."2.gif'><select name='album_id'>
      <option value='0'> $phrases[without_album] </option> ";

      while($data_albums =db_fetch($qr_albums)){
      print "<option value='$data_albums[id]'>$data_albums[name]</option>";
              }
              print "</select>";
           }

          print "</div>
          <div id=comments_set_div style=\"visibility: inline; text-decoration: none\">";
          $qr_comments = db_query("select * from songs_comments order by id");
           if(db_num($qr_comments)){
      print "<img src='images/arrow_".$global_dir."2.gif'><select name='comment_id'>
      <option value='0'> $phrases[without_comment] </option> ";

      while($data_comments =db_fetch($qr_comments)){
      print "<option value='$data_comments[id]'>$data_comments[name]</option>";
              }
              print "</select>";
           }
          print "</div>
          </td><td><input type=submit value=' $phrases[do_button] ' onClick=\"return confirm('$phrases[are_you_sure]');\"></td></tr></table>
          </td></tr></form> ";
           }else{
              print " <tr><td align=center> $phrases[err_no_songs] </td></tr>";
                   }
           print "</table>

          </center>";
  }

  //--------------------------- Song Edit Form ------------------------------
  if($action=="song_edit"){
  if(!is_array($song_id)){$song_ids=array("$song_id");}else{$song_ids=$song_id;}
    //unset($song_id);

   print "<center><form name=sender method=POST action='index.php'>
    <input type=hidden name=action value='song_edit_ok'>
    <input type=hidden name=id value='$id'>";
   $i = 0 ;
   foreach($song_ids as $song_id){
    $qr = db_query("select * from songs_songs where id='$song_id'");
   if(db_num($qr)){
           $data = db_fetch($qr) ;
  
      print "<input type=hidden name=song_id[$i] value='$song_id'>

   <table width=80% class=grid>

    <tr>
    <td><b>$phrases[the_name]</b> </td>
    <td><input name='name[$i]' type='text' value=\"$data[name]\">
    </td>
    <td><b> $phrases[the_comment] </b></td>
    <td>  <select name='comment[$i]'>
    <option value='0'>$phrases[without_comment]</option>
    ";
    $qr2=db_query("select * from songs_comments");
    while($data2 = db_fetch($qr2)){
    if($data['comment']==$data2['id']){$chk="selected";}else{$chk="";}

       print "<option value='$data2[id]' $chk >$data2[name]</option>";

            }
    print "</select></td>
    </tr>  ";

    
//----------- Urls -------------//
$qr3 = db_query("select * from songs_urls_fields where active=1 order by ord");
if(db_num($qr3)){
    print "<tr>";

$c = 0;
$m = 0 ;
while($data3 = db_fetch($qr3)){

if($c==2){
    print "</tr><tr>" ;
    $c=0;
    }

$field_data = db_qr_fetch("select * from songs_urls_data where cat='$data3[id]' and song_id='$data[id]'");
print "<td>
                               <b>$data3[name]</b></td>

                               <td>
                               <input type=\"hidden\" name=\"custom_url_id[$i][$m]\" value=\"$data3[id]\">

                                <table><tr><td><input type=\"text\" name=\"custom_url_data[$i][$m]\" size=\"30\" dir=ltr value=\"$field_data[url]\"></td><td>
                                <a href=\"javascript:uploader('songs','custom_url_data[$i][$m]','win".$data3['id'].$i."');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a>
                                </td></tr></table>

                                </td>";
                                $c++ ;
                                $m++;
                                
}
print "</tr>";
unset($field_data,$data3,$m,$c);
}
//----------- Custom Fields -------------//
$qr4 = db_query("select * from songs_custom_sets where active=1 order by ord");
if(db_num($qr4)){
    print "<tr>";

$c = 0;
$m = 0 ;
while($data4 = db_fetch($qr4)){

if($c==2){
    print "</tr><tr>" ;
    $c=0;
    }

//$field_data = db_qr_fetch("select * from songs_custom_fields where cat='$data4[id]' and song_id='$data[id]'");
print "<td>
                               <b>$data4[name]</b></td>
                               <td>
                               <input type=\"hidden\" name=\"song_field_id[$i][$m]\" value=\"$data4[id]\">
                               ".get_song_field("song_field_data[$i][$m]",$data4,"edit",$data['id'])."</td>";
                                $c++ ;
                                $m++;

}
print "</tr>";
unset($data4,$m,$c);
}
//-------------------------------


    print "<tr>
    <td><b>$phrases[lyrics]</b></td>
    <td><textarea name='lyrics[$i]' rows=5 cols=40>$data[lyrics]</textarea>
    </td></tr></table><br>";
    ++$i;
    }
            }

            if($i > 0){
            print "<input type=submit value='  $phrases[edit]  '></center>";
                    }else{
            print_admin_table("<center> $phrases[err_wrong_url] </center>");
            }
            print "</form>";
          }
 //------------------------------ Songs Add Form -----------------------------
  if($action=="song_add"){
      $id=intval($id); 
      
$qr = db_query("select songs_singers.id,songs_singers.name,songs_cats.name as cat_name , songs_cats.id as cat_id from songs_singers,songs_cats where songs_cats.id=songs_singers.cat and songs_singers.id='$id'");
 
 if(db_num($qr)){
     
$data = db_fetch($qr);


 if(!$add_limit){
$add_limit = $settings['songs_add_limit'] ;
  }
  
  $add_limit = intval($add_limit);
  

  print "
  <p align=$global_dir><img src='images/arrw.gif'> <a href='index.php?action=singers&cat=$data[cat_id]'>$data[cat_name]</a> / <a href='index.php?action=singer_edit&id=$data[id]'>$data[name]</a> / $phrases[add_songs]</p>";
  unset($qr,$data);
  
  print "<center>
  <form method=\"POST\" action=\"index.php\">

      <input type=\"hidden\" name=\"id\" value='$id'>
      <input type=hidden name=action value=song_add>
      <table width=30% class=grid>
      <tr><td align=center> $phrases[fields_count] : <input type=text name=add_limit value='$add_limit' size=3>
      &nbsp;&nbsp;<input type=submit value='$phrases[edit]'></td></tr></table></form>

      <br>
       <form method=\"POST\" action=\"index.php\" name=\"sender\">
<div align=\"center\">
<input type=\"hidden\" name=\"action\" value=\"song_add_ok\">
      <input type=\"hidden\" name=\"id\" value=\"$id\">
        <input type=hidden name=add_limit value='$add_limit'>

    <table width=30% class=grid>
      <tr><td align=center> $phrases[the_album] : <select name=album_id><option value='0'>$phrases[without_album]</option>";
      $qr_albums = db_query("select * from songs_albums where cat='$id' order by id desc");

      while($data_albums =db_fetch($qr_albums)){
      print "<option value='$data_albums[id]'>$data_albums[name]</option>";
              }


     print "</select></td></tr></table>
      <br>";




//-------------- Auto Add Operation ----------
    if($auto_add && in_array($auto_folder,$autosearch_folders)){
      //  $dir_for_read = CWD . ($script_path ? "/" . $script_path  :"") . "/".$dir_for_read ;

       $dir_for_read = $auto_folder . $auto_subfolder ;
     //  print $dir_for_read;
     //$auto_search_exclude_exts

     if(file_exists($dir_for_read)){
       $allowed_types_arr = explode(",",trim($allowed_ext));
       $exclude_types_arr = explode(",",trim($auto_search_exclude_exts));

       foreach($allowed_types_arr as $ext){
           if(!in_array($ext,$exclude_types_arr)){
           $allowed_types[] = $ext;
           }
       }

       $files_list = get_files($dir_for_read,$allowed_types,$subdirs_search);
       $i =0;
       foreach($files_list as $file_name){
           $dataf = db_query("select songs_urls_data.id,songs_urls_data.url from songs_songs,songs_urls_data where
            songs_urls_data.url like '%".mysql_escape_string($file_name)."'".iif($search_in_cat_only," and songs_urls_data.song_id = songs_songs.id and songs_songs.album='$id'",""));
           if(!db_num($dataf)){
               $new_files_list[$i] = $file_name ;
               $i++;
           }
       }
      //  print_r($new_files_list) ;
       unset($files_list);

if(count($new_files_list)){
$add_limit = count($new_files_list) ;
$auto_add_ok = 1;
}else{
 print_admin_table("<center> $phrases[no_new_files] </center>") ;
}
     }else{
         print_admin_table("<center> $phrases[err_autosearch_folder_not_exists] </center>") ;
     }
    }
    //-----------------------------------
    
for ($i=0;$i<$add_limit;$i++){
                     print "<br><table  class=grid cellspacing=\"0\" width=\"98%\" >

                     <tr><td ><b>#".($i+1)."</b></td></tr>";

if($auto_add_ok){

switch ($auto_url_field){
case "url" : $url_value = iif($use_complete_url,$scripturl."/").$new_files_list[$i];break;
case substr($auto_url_field,0,7)=="custom_" : $custom_url_value[substr($auto_url_field,7,strlen($auto_url_field)-7)]=iif($use_complete_url,$scripturl."/").$new_files_list[$i];break;
}


$auto_name_value = basename($new_files_list[$i]);
switch ($auto_name_field){
case "name" : $name_value = $auto_name_value;break;
case substr($auto_url_field,0,7)=="custom_" : $custom_url_value[substr($auto_url_field,7,strlen($auto_url_field)-7)]=$auto_name_value;break;
}

 }
 
print "<tr>
                                <td>
                                <b>$phrases[the_name]</b></td><td><input type=\"text\" name=\"name[$i]\" value=\"$name_value\" size=\"20\"></td>

                                <td> <b>  $phrases[the_comment] </b></td>
                                <td>
                                 <select name='comment[$i]'>
    <option value='0'>$phrases[without_comment]</option>
    ";
    $qr2=db_query("select * from songs_comments");
    while($data2 = db_fetch($qr2)){
       print "<option value='$data2[id]'>$data2[name]</option>";

            }
    print "</select> </td>
                                </tr>";
//-------- Custom Urls --------------
$qr = db_query("select * from songs_urls_fields where active=1 order by ord");
if(db_num($qr)){
    print "<tr>";

$c = 0;
$m = 0 ;
while($data = db_fetch($qr)){

if($c==2){
    print "</tr><tr>" ;
    $c=0;
    }

print "<td>
                               <b>$data[name]</b></td>

                               <td>
                               <input type=\"hidden\" name=\"custom_url_id[$i][$m]\" value=\"$data[id]\">

                                <table><tr><td><input type=\"text\" name=\"custom_url_data[$i][$m]\" size=\"30\" dir=ltr value=\"".$custom_url_value[$data['id']]."\"></td><td>
                                <a href=\"javascript:uploader('songs','custom_url_data[$i][$m]','win".$data['id'].$i."');\"><img src='images/file_up.gif' border=0 alt='$phrases[uplaod_file]'></a>
                                </td></tr></table>

                                </td>";
                                $c++ ;
                                $m++;

}
print "</tr>";
}-
//----------- Custom Fields -------------//
$qr4 = db_query("select * from songs_custom_sets where active=1 order by ord");
if(db_num($qr4)){
    print "<tr>";

$c = 0;
$m = 0 ;
while($data4 = db_fetch($qr4)){

if($c==2){
    print "</tr><tr>" ;
    $c=0;
    }

//$field_data = db_qr_fetch("select * from songs_custom_fields where cat='$data4[id]' and song_id='$data[id]'");
print "<td>
                               <b>$data4[name]</b></td>
                               <td>
                               <input type=\"hidden\" name=\"song_field_id[$i][$m]\" value=\"$data4[id]\">
                               ".get_song_field("song_field_data[$i][$m]",$data4,"add",null,$data4['value'])."</td>";
                                $c++ ;
                                $m++;

}
print "</tr>";
unset($data4,$m,$c);
}
//--------------------------------------
                        print "
                          <tr>
                                 <td>
                                <b>$phrases[lyrics]</b></td>

<td>                            
                                 <textarea rows='2' name='lyrics[$i]' cols='30'></textarea> </td>

                        </tr>
                        </table><br>";

                        }

print "<br>
<input type=\"submit\" value=\"$phrases[add_button]\" style=\"width:100\">

     <br>   </form>\n";

 }else{
     print_admin_table("<center> $phrases[err_wrong_url]</center>");
 }
 }

//-------------------------------- Auto Add --------------------------------------------------
if($action=="auto_add"){

    $id=intval($id);

   //if_cat_admin($cat);

   $qr = db_query("select songs_singers.id,songs_singers.name,songs_cats.name as cat_name , songs_cats.id as cat_id from songs_singers,songs_cats where songs_cats.id=songs_singers.cat and songs_singers.id='$id'");
 
 if(db_num($qr)){
     
$data = db_fetch($qr);



  print "
  <p align=$global_dir><img src='images/arrw.gif'> <a href='index.php?action=singers&cat=$data[cat_id]'>$data[cat_name]</a> / <a href='index.php?action=singer_edit&id=$data[id]'>$data[name]</a> / $phrases[auto_search]</p>";
  unset($qr,$data);


   print "<form action=index.php method=post>
   <input type=hidden name=action value='song_add'>
   <input type=hidden name=auto_add value='1'>
   <input type=hidden name=id value='$id'>
   <center><table dir=ltr width=80% class=grid>
   <tr><td colspan=2 align=center> <p class=title>$phrases[auto_search] </p></td></tr>

   <tr><td width=150>Folder : </td><td>";
   print_select_row("auto_folder",$autosearch_folders,null,null,null,null,true);
   print  "<input type=text name=auto_subfolder value='/'></td></tr>
   <tr><td></td><td><input type=\"checkbox\" name=\"subdirs_search\" value=1 checked> Include Sub-Directories </td></tr>
   <tr><td></td><td><input type=\"checkbox\" name=\"search_in_cat_only\" value=1> Search For Exists Files in This Singer ONLY </td></tr>

   <tr><td> Extentions : </td><td>
    <input type=text name=allowed_ext value='$auto_search_default_exts' size=50> </td></tr>

    <tr><td width=150> URL Field : </td><td><select name=auto_url_field>";
    /*
foreach($data_fields_checks as $key=>$value){
if($value !='image_n_thumb'){
print "<option value='$value'>$key</option>";
        }
}
*/

  $qr=db_query("select * from songs_urls_fields order by id");
  while($data = db_fetch($qr)){
      print "<option value='custom_$data[id]'>$data[name]</option>";
  }
   print  "</select> <input type=\"checkbox\" name=\"use_complete_url\" value=1> Use Complete URL</td></tr>

     <tr><td width=150> Filename Field : </td><td><select name=auto_name_field>
     <option value=''>None</option>
     <option value='name'>$phrases[the_name]</option>";
     /*
foreach($data_fields_checks as $key=>$value){
if($value !='image_n_thumb'){
print "<option value='$value'>$key</option>";
        }
}
        */
  $qr=db_query("select * from songs_urls_fields order by id");
  while($data = db_fetch($qr)){
      print "<option value='custom_$data[id]'>$data[name]</option>";
  }
   print  "</select></td></tr>
    <tr><td colspan=2 align=center>
   <input type=submit value=' Search '></td></tr></table></form>";
 }else{
     print_admin_table("<center>$phrases[err_wrong_url]</center>");
 }
   }

 //----------------------------- Album Edit --------------------------------
if($action=="album_edit"){
  $data = db_qr_fetch("select * from songs_albums where id='$album_id'");

  print "<center>

  <table width=50% class=grid>
  <form name=sender method=POST action='index.php'>
  <input type=hidden name=action value='album_edit_ok'>
  <input type=hidden name=id value='$id'>
  <input type=hidden name=album_id value='$album_id'>

  <tr><td colspan=2><center><font class=title>$phrases[edit_album] </font></center></td></tr>
  <tr>
  <td>
  $phrases[the_name] : </td>
  <td><input type=text value=\"$data[name]\" size=30 name=name></td>
  </tr>
  <tr>
 <td>
  $phrases[the_image] :</td>
  <td> <table><tr><td><input type=text value=\"$data[img]\" dir=ltr size=30 name=img></td><td><a href=\"javascript:uploader('albums','img');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a></td></tr></table>

   </td>
   </tr>
   <tr><td colspan=2 align=center><input type=submit value='$phrases[edit]'></td></tr>
   </table>";
}