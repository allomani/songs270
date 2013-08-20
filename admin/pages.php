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


// ------------------------------- pages ----------------------------------------
 if ($action == "pages" or $action=="del_pages" or $action=="edit_pages_ok" or $action=="pages_add_ok" || $action=="page_enable" || $action=="page_disable"){

 if_admin("pages");

if($action=="page_enable"){
        db_query("update songs_pages set active=1 where id='$id'");
        }

if($action=="page_disable"){
        db_query("update songs_pages set active=0 where id='$id'");
        }

if($action=="pages_add_ok"){
         db_query("insert into songs_pages(title,content)values('".db_clean_string($title)."','".db_clean_string($content,"code")."')");
}
        //==========================================
    if ($action=="del_pages"){
          db_query("delete from songs_pages where id='$id'");
            }
            //==============================================
            if ($action=="edit_pages_ok"){
                db_query("update songs_pages set title='".db_clean_string($title)."',content='".db_clean_string($content,"code")."' where id='$id'");

                    }
                    //================================================
  print "<p align=center class=title>$phrases[the_pages]</p>
                <p align=$global_align><a href='index.php?action=pages_add'><img src='images/add.gif' border=0>$phrases[pages_add]</a></p>";


       $qr=db_query("select * from songs_pages order by id DESC")   ;
          print "<br><center><table border=0 width=\"90%\"   cellpadding=\"0\" cellspacing=\"0\" class=\"grid\">";
       if (db_num($qr)){



         while($data= db_fetch($qr)){
     print "            <tr>
                <td >$data[title]</td>
                <td align=center> <a target=_blank href='../index.php?action=pages&id=$data[id]'>$phrases[view_page]</a> </td>
                <td align=left>" ;

                if($data['active']){
                        print "<a href='index.php?action=page_disable&id=$data[id]'>$phrases[disable] </a>" ;
                        }else{
                        print "<a href='index.php?action=page_enable&id=$data[id]'>$phrases[enable] </a>" ;
                        }

                print " - <a href='index.php?action=edit_pages&id=$data[id]'>$phrases[edit] </a> - <a href='index.php?action=del_pages&id=$data[id]' onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a></td>
        </tr>";

                 }


                }else{
                        print "<tr><td width=100%><center> $phrases[no_pages] </center></td></tr>";
                        }
                      print" </table>\n";
}
//--------- Edit Pages ----------------
if($action == "edit_pages"){
$id=intval($id);
    
$qr  = db_query("select * from songs_pages where id='$id'");

if(db_num($qr)){
  $data=db_fetch($qr);

      print " <center><table  width=\"90%\"  style=\"border-collapse: collapse\"  class=grid>

                <form method=\"POST\" action=\"index.php\">

                    <input type=hidden name=\"action\" value='edit_pages_ok'>
                       <input type=hidden name=\"id\" value='$id'>



                        <tr>
                                <td width=\"70\">
                <b>$phrases[the_title]</b></td><td >
                <input type=\"text\" name=\"title\" size=\"29\" value='$data[title]'></td>
                        </tr>


                             <tr> <td width=\"50\">
                <b>$phrases[the_content]</b></td>
                                <td>";
                if($use_editor_for_pages){
                               editor_print_form("content",600,300,"$data[content]");
                }else{
                print "<textarea cols=60 rows=10 name='content' dir=ltr>$data[content]</textarea>"; 
                }
                 print "</td></tr>
                 <tr>
                 <td colspan=2 align=center>
                 <input type=\"submit\" value=\"$phrases[edit]\">
                        </td>
                        </tr>






</table>
</form>    </center>\n";
}else{
print_admin_table("<center>$phrases[err_wrong_url]</center>");
}
        }
        
//-------------- Pages Add ------------
if($action=="pages_add"){
print "<center><table border=\"0\" width=\"90%\" class=\"grid\">

                <form method=\"POST\" action=\"index.php\">

                      <input type=hidden name=\"action\" value='pages_add_ok'>



                        <tr>
                                <td width=\"70\">
                <b>$phrases[the_title]</b></td><td >
                <input type=\"text\" name=\"title\" size=\"50\"></td>
                        </tr>



                             <tr> <td width=\"50\">
                <b>$phrases[the_content]</b></td>
                                <td>";
                                
                                if($use_editor_for_pages){
                               editor_print_form("content",600,300,"$data[content]");
                }else{
                print "<textarea cols=60 rows=10 name='content' dir=ltr>$data[content]</textarea>"; 
                }

                 print "</td></tr>
                 <tr>
                 <td colspan=2 align=center>
                 <input type=\"submit\" value=\"$phrases[add_button]\">
                        </td>
                        </tr>

                </table>

</form>    </center>";
}        