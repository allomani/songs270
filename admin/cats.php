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

if($action=="cats" ||  $action=="cat_del" || $action=="edit_cat_ok" || $action=="cat_add_ok" || $action=="cat_disable" || $action=="cat_enable"){
        if_admin();

//---------------------------------------------------------
if($action =="cat_add_ok"){
  db_query("insert into songs_cats (name,download_limit,active) values('$name','$download_limit','1')");
        }
//----------------------------------------------------------
 if($action=="cat_del"){
  $id=intval($id);
  
   $qr = db_query("select id from songs_singers where cat='$id'");
   while($data = db_fetch($qr)){
    delete_singer($data['id']);
    }


      db_query("delete from songs_cats where id='$id'");
  
 }
//-----------------------------------------------------------
 if($action=="edit_cat_ok"){
   $id=intval($id);  
 db_query("update songs_cats set name='$name',download_limit='$download_limit' where id='$id'");
         }
//-----------------------------------------------------------

if($action=="cat_disable"){
        db_query("update songs_cats set active=0 where id='$id'");
        }

if($action=="cat_enable"){

       db_query("update songs_cats set active=1 where id='$id'");
        }
//-----------------------------------------------

  print "<center><p class=title>$phrases[the_songs_cats] </p>
   <form method=\"POST\" action=\"index.php\">

   <input type=hidden name='action' value='cat_add_ok'>
   
   <table width=50% class=grid><tr>
   <td><b> $phrases[the_name] </b></td> 
  <td> <input type=text name=name size=30>
    </td></tr>
   <tr> <td>
                <b>$phrases[the_download]</b></td>
                                <td>
                <select size=\"1\" name=\"download_limit\">
                        <option value=\"0\">$phrases[download_for_all_visitors]</option>
                        <option value=\"1\">$phrases[download_for_members_only]</option>
                       </select>
                       </td></tr>
    <tr><td colspan=2 align=center><input type=submit value='$phrases[add_button]'></td>
    </tr></table>



   </center><br>";
           



 $qr = db_query("select * from  songs_cats order by ord");
 print "<center>
 <table width=80% class=grid><tr><td>
  <div id=\"cats_list\">";
 while($data = db_fetch($qr)){
      print "<div id=\"item_$data[id]\" onmouseover=\"this.style.backgroundColor='#EFEFEE'\"
     onmouseout=\"this.style.backgroundColor='#FFFFFF'\">
      <table width=100%><tr>
      <td width=25>
      <span style=\"cursor: move;\" class=\"handle\"><img src='images/move.gif'></span> 
      </td>
      
      <td>$data[name]</td>
      <td width=200>";
      
      if($data['active']){
                        print "<a href='index.php?action=cat_disable&id=$data[id]'>$phrases[disable]</a> - " ;
                        }else{
                        print "<a href='index.php?action=cat_enable&id=$data[id]'>$phrases[enable]</a> - " ;
                        }
                        
      print "<a href='index.php?action=cat_edit&id=$data[id]'>$phrases[edit] </a> - 
      <a href=\"index.php?action=cat_del&id=$data[id]\" onClick=\"return confirm('$phrases[del_cat_warning]');\">$phrases[delete]</a></td>
      </tr></table>
      </div>";
         }
       print "
       </div></td></tr></table>
       ";
       
print "<script type=\"text/javascript\">
        init_cats_sortlist();
</script>";
        }

 //-------------------------------------------------------------
        if($action == "cat_edit"){
               $id = intval($id);
               
               $qr = db_query("select * from songs_cats where id='$id'");

               if(db_num($qr)){
                   $data=db_fetch($qr);
               print "<center>

                <table border=0 width=\"40%\"  style=\"border-collapse: collapse\" class=grid><tr>

                <form method=\"POST\" action=\"index.php\">

                      <input type=hidden name=\"id\" value='$id'>

                      <input type=hidden name=\"action\" value='edit_cat_ok'> ";


                  print "  <tr>
                                <td width=\"50\">
                <b>$phrases[the_name]</b></td><td>
                <input type=\"text\" name=\"name\" value=\"$data[name]\" size=\"29\"></td>
                        </tr>

                        ";
                          if($data['download_limit']=="1"){$chk1="";$chk2="selected";}else{$chk2="";$chk1="selected";}

                              print " <tr> <td>
                <b>$phrases[the_download]</b></td>
                                <td>
                <select size=\"1\" name=\"download_limit\">
                        <option value=\"0\" $chk1>$phrases[download_for_all_visitors]</option>
                        <option value=\"1\" $chk2>$phrases[download_for_members_only]</option>
                       </select>
                       </td></tr>";
                       
                              print " <tr>
                                <td colspan=2>
                <center><input type=\"submit\" value=\" $phrases[edit] \">
                        </td>
                        </tr>
                   

                </table>

</form>    </center>\n";
               }else{
                   print_admin_table("<center>$phrases[err_wrong_url]</center>");
               }
                      }