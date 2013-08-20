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

if(!defined('IS_ADMIN')){die('No Access');}  

//---------------------------------- Videos Cats -----------------------------
if($action=="videos_cats" ||  $action=="videos_cat_del" || $action=="edit_videos_cat_ok" || 
$action=="videos_cat_add_ok" || $action=="videos" || $action=="video_add_ok" || $action=="video_edit_ok" || 
$action=="video_del" || $action=="videos_cats_enable" || $action=="videos_cats_disable"){

 $cat = intval($cat);  
if_videos_cat_admin($cat);




            $dir_data['cat'] = intval($cat) ;
while($dir_data['cat']!=0){
   $dir_data = db_qr_fetch("select name,id,cat from songs_videos_cats where id='$dir_data[cat]'");


        $dir_content = "<a href='index.php?action=videos&cat=$dir_data[id]'>$dir_data[name]</a> / ". $dir_content  ;

        }
   print "<p align=$global_align><img src='images/link.gif'> <a href='index.php?action=videos&cat=0'>$phrases[the_videos]  </a> / $dir_content " . "<b>$data[name]</b></p>";



 //--------- enable / disable cat ------------
 if($action=="videos_cats_disable"){
     if_videos_cat_admin($id); 
        db_query("update songs_videos_cats set active=0 where id='$id'");
        }

if($action=="videos_cats_enable"){
        if_videos_cat_admin($id); 
       db_query("update songs_videos_cats set active=1 where id='$id'");
        }  
//--------------------Cat Add--------------------------------
if($action =="videos_cat_add_ok"){
    if_videos_cat_admin($cat,false);
  db_query("insert into songs_videos_cats (name,cat,img,active,download_limit) values('".db_clean_string($name,"code")."','$cat','".db_clean_string($img,"code")."','1','$download_limit')");
        }
//-----------------Cat Del----------------------------------
 if($action=="videos_cat_del"){
 if($id){
                  $delete_array = get_videos_cats($id);
  foreach($delete_array as $id_del){
     if_videos_cat_admin($id_del);  
     db_query("delete from songs_videos_cats where id='$id_del'");

     $qr = db_query("delete from songs_videos_data where cat='$id_del'");

     }


         }
 }
//--------------------Cat Edit--------------------------------
 if($action=="edit_videos_cat_ok"){
 if_videos_cat_admin($id); 
 db_query("update songs_videos_cats set name='".db_clean_string($name)."',img='".db_clean_string($img)."',download_limit='$download_limit' where id='$id'");
         }
 //-------------------------Video Add------------------------------
if($action=="video_add_ok"){  
db_query("insert into songs_videos_data (name,url,img,cat,date) values('".db_clean_string($name,"code")."','".db_clean_string($url,"code")."','".db_clean_string($img,"code")."','$cat',now())");
}
//------------------------Video Del------------------------------
if($action=="video_del"){
db_query("delete from songs_videos_data where id='$id'");
 }
//-----------------------Video Edit-------------------------
if($action=="video_edit_ok"){
db_query("update songs_videos_data set name='".db_clean_string($name,"code")."',img='".db_clean_string($img,"code")."',url='".db_clean_string($url,"code")."' where id='$id'");
        }        
//-----------------------------------------------------------


//-------- List Cats ---------//
 print "<p align=$global_align><a href='index.php?action=videos_cat_add&cat=$cat'><img src='images/add.gif' border=0> $phrases[add_cat]</a></p>";   

   if($user_info['groupid'] != 1){
$usr_data2 = db_qr_fetch("select permisions_videos from songs_user where id='$user_info[id]'");

if($usr_data2['permisions_videos']){
    $qr=db_query("select * from songs_videos_cats where id IN ($usr_data2[permisions_videos]) and cat='$cat' order by ord ASC");
    }

     }else{

       $qr = db_query("select * from songs_videos_cats where cat='$cat' order by ord asc");
      }
      

 if(db_num($qr)){
 print "<center>
 <p class=title>$phrases[the_cats]</p>
<table width=80% class=grid><tr><td>
<div id=\"videos_cats_list\" >";
 while($data = db_fetch($qr)){
      print "<div id=\"item_$data[id]\" onmouseover=\"this.style.backgroundColor='#EFEFEE'\"
     onmouseout=\"this.style.backgroundColor='#FFFFFF'\">
      <table width=100%><tr>
      <td width=25>
      <span style=\"cursor: move;\" class=\"handle\"><img src='images/move.gif'></span> 
      </td>
      
      <td>
      
      <a href='index.php?action=videos&cat=$data[id]'>$data[name]</a></td>
      <td width=200>";
      if($data['active']){
                        print "<a href='index.php?action=videos_cats_disable&id=$data[id]'>$phrases[disable]</a> - " ;
                        }else{
                        print "<a href='index.php?action=videos_cats_enable&id=$data[id]'>$phrases[enable]</a> - " ;
                        }
      print "<a href='index.php?action=videos_cat_edit&id=$data[id]&cat=$cat'>$phrases[edit] </a> - <a href=\"index.php?action=videos_cat_del&id=$data[id]&cat=$cat\" onClick=\"return confirm('$phrases[del_video_cat_warning]');\">$phrases[delete]</a></td>
      </tr></table></div>";
         }
       print "</div></td></tr></table><br>
       
       <script type=\"text/javascript\">
        init_videos_cats_sortlist();
</script>";
       
       
 }else{
     $no_cats = true;
 }
//------------------------//


 if($cat > 0){

       print "<center>
       <form name=sender action=index.php method=post>
       <input type=hidden name=action value='video_add_ok'>
       <input type=hidden name=cat value='$cat'>
       <table class=grid width=40% >
       <tr><td colspan=2><center><span class=title>$phrases[add_video]</span></td></tr>
       <tr><td> $phrases[the_name] : </td><td><input type=text name=name size=30></td></tr>
       <tr><td> $phrases[the_url] : </td><td>

       <table><tr><td><input type=text  dir=ltr size=30 name=url></td><td><a href=\"javascript:uploader('videos','url');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a></td></tr></table>
       </td></tr>
      <tr><td>
  $phrases[the_image] :</td>
  <td> <table><tr><td><input type=text  dir=ltr size=30 name=img></td><td><a href=\"javascript:uploader('videos','img');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a></td></tr></table>

   </td></tr>
       <tr><td colspan=2 align=center><input type=submit value='$phrases[add_button]'></td></tr>
       </table></form><br>";


    }

    //------------ show videos ------------------//
      $qr=db_query("select * from songs_videos_data where cat='$cat' order by binary name asc");
     
      if(db_num($qr)){
            print "<table class=grid width=70%>" ; 
           while($data = db_fetch($qr)){
                print "<tr><td>$data[name]</td>
                <td><a href='index.php?action=video_edit&id=$data[id]&cat=$cat'>$phrases[edit] </a></td>
                <td>
                <a href='index.php?action=video_del&id=$data[id]&cat=$cat' onClick=\"return confirm('$phrases[are_you_sure]');\"> $phrases[delete] </a></td></tr>";

                   }

                print "</table>";  
              }else{
                if($cat > 0 || $no_cats){
                      print_admin_table("<center>$phrases[no_videos_or_no_permissions]</center>");
                }
                      }


       

                

        }
//-------------- Cat Add -----------
if($action=="videos_cat_add"){
    $cat = intval($cat);
  
  if_videos_cat_admin($cat,false); 
  
 $dir_data['cat'] = intval($cat) ;
while($dir_data['cat']!=0){
   $dir_data = db_qr_fetch("select name,id,cat from songs_videos_cats where id='$dir_data[cat]'");


        $dir_content = "<a href='index.php?action=videos&cat=$dir_data[id]'>$dir_data[name]</a> / ". $dir_content  ;

        }
   print "<p align=$global_align><img src='images/link.gif'> <a href='index.php?action=videos&cat=0'>$phrases[the_videos]  </a> / $dir_content " . "<b>$data[name]</b></p>";

  
print "<center><p class=title>$phrases[add_cat] </p>
   <form method=\"POST\" action=\"index.php\" name=sender>

   <table width=45% class=grid><tr>
   <td> <b>$phrases[the_name] </b></td><td>
    <input type=hidden name='action' value='videos_cat_add_ok'>
      <input type=hidden name='cat' value='$cat'>
   <input type=text name=name size=30>
    </td></tr>
       <tr><td>
  <b>$phrases[the_image]</b></td>
  <td> <table><tr><td><input type=text  dir=ltr size=30 name=img></td><td><a href=\"javascript:uploader('videos','img');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a></td></tr></table>

   </td></tr>
   <tr> <td>
                <b>$phrases[the_download]</b></td>
                                <td>
                <select size=\"1\" name=\"download_limit\">
                        <option value=\"0\">$phrases[download_for_all_visitors]</option>
                        <option value=\"1\">$phrases[download_for_members_only]</option>
                       </select>
                       </td></tr>
   <tr>
    <td colspan=2 align=center><input type=submit value='$phrases[add_button]'></td>
    </tr></table>

    </form>

   </center>";
   
   }
 //------------------------- Cat Edit------------------------
        if($action == "videos_cat_edit"){
        $id = intval($id);
        $cat=intval($cat);
        
        $qr =db_query("select * from songs_videos_cats where id='$id'");
        if(db_num($qr)){
          if_videos_cat_admin($id);
           
           $data=db_fetch($qr); 
               print "<center>

                <table border=0 width=\"40%\"  style=\"border-collapse: collapse\" class=grid><tr>

                <form method=\"POST\" action=\"index.php\" name=sender>

                      <input type=hidden name=\"id\" value='$id'>
                      <input type=hidden name=\"cat\" value='$cat'>

                      <input type=hidden name=\"action\" value='edit_videos_cat_ok'> ";


                  print "  <tr>
                                <td width=\"50\">
                <b>$phrases[the_name]</b></td><td width=\"223\">
                <input type=\"text\" name=\"name\" value=\"$data[name]\" size=\"29\"></td>
                        </tr>
                  


                             <tr><td>
  <b>$phrases[the_image]</b></td>
  <td> <table><tr><td><input type=text  dir=ltr size=30 name=img value='$data[img]'></td><td><a href=\"javascript:uploader('videos','img');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a></td></tr></table>

   </td></tr>";
         if($data['download_limit']=="1"){$chk1="";$chk2="selected";}else{$chk2="";$chk1="selected";}

                              print " <tr> <td>
                <b>$phrases[the_download]</b></td>
                                <td>
                <select size=\"1\" name=\"download_limit\">
                        <option value=\"0\" $chk1>$phrases[download_for_all_visitors]</option>
                        <option value=\"1\" $chk2>$phrases[download_for_members_only]</option>
                       </select>
                       </td></tr>
                               <tr>
                                <td colspan=2>
                <center><input type=\"submit\" value=\"$phrases[edit]\">
                        </td>
                        </tr>





                </table>

</form>    </center>\n";
}else{
     print_admin_table("<center> $phrases[err_wrong_url]</center>");
     
 }
                      }

//------------------------ Video Edit --------------------------------------
if($action == "video_edit"){

$id=intval($id);
 $qr=db_query("select * from songs_videos_data where id='$id'"); 
 
 if(db_num($qr)){
     if_videos_cat_admin($id);
     $data= db_fetch($qr);
   
         print "<center>" ;
       print "<form name=sender action=index.php method=post>
       <input type=hidden name=action value='video_edit_ok'>
       <input type=hidden name=cat value='$data[cat]'>
       <input type=hidden name=id value='$id'>
       <table class=grid width=40% >

       <tr><td> $phrases[the_name] : </td><td><input type=text name=name size=30 value=\"$data[name]\"></td></tr>
    <tr><td>
  $phrases[the_url] :</td>
  <td> <table><tr><td><input type=text  dir=ltr size=30 name=url value=\"$data[url]\"></td>
  <td><a href=\"javascript:uploader('videos','url');\"><img src='images/file_up.gif' border=0 alt='$phrase[upload_file]'></a></td></tr></table>

   </td></tr>
      
       <tr><td>
  $phrases[the_image] :</td>
  <td> <table><tr><td><input type=text  dir=ltr size=30 name=img value=\"$data[img]\"></td><td><a href=\"javascript:uploader('videos','img');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a></td></tr></table>

   </td></tr>
       <tr><td colspan=2 align=center><input type=submit value='$phrases[edit]'></td></tr>
       </table></form><br>";
 }else{
     print_admin_table("<center> $phrases[err_wrong_url]</center>");
     
 }
        }
