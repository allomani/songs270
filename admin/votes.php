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

  //-------------------------- Votes ------------------------------------------
if ($action == "votes" ||  $action=="vote_del" ||  $action == "vote_active"  || $action=="vote_add" ){

            if_admin("votes");

 if($action=="vote_add"){
        db_query("insert into songs_votes_cats (title) values('$title')");
        }


//------------------------------
 if($action=="vote_del"){
         db_query("delete from songs_votes_cats where id=$id");
         db_query("delete from songs_votes where cat=$id");
         }

//---------------------------------
if($action == "vote_active"){
db_query("update songs_votes_cats set active=0");
db_query("update songs_votes_cats set active=1 where id=$id");
        }

         print "<center><p class=title > $phrases[the_votes] </p>
         <form action=index.php method=post>
         <input type=hidden name=action value='vote_add'>
         <table width=50% class=grid><tr><td>
           <center><p class=title>$phrases[vote_add] </p></center>
         </td></tr>
         <td align=center><b>  $phrases[the_title] :  </b><input name=title size=30> <input type=submit value=' $phrases[add_button] '> </td></tr>

         </table></form><br>";

       $qr = db_query("select * from songs_votes_cats");
print " <table class=grid width=70%>" ;
while($data = db_fetch($qr)){

     print "<tr><td >$data[title]  &nbsp;&nbsp;&nbsp;";
     if($data['active']){ print "[$phrases[default]]" ;}
     print "</td><td align=left><a href='index.php?action=vote_active&id=$data[id]'> $phrases[set_default] </a> - <a href='index.php?action=vote_edit&cat=$data[id]'>$phrases[edit_or_options]</a> - <a href='index.php?action=vote_del&id=$data[id]' onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a> </td></tr>" ;

     }
    print "</table></center>";
      }
  //----------------------------------------------------------------------------
  if($action=="vote_edit" || $action=="vote2_add" || $action=="vote2_del" || $action=="vote2_edit_ok" ||$action=="vote_edit_ok" ){
  //--------------------------------
   if($action=="vote_edit_ok"){
      db_query("update songs_votes_cats set title='$title' where id=$id");

         }
  //------------------------------------------
    if ($action=="vote2_add"){
            db_query("insert into songs_votes (title,cat) values('$title','$cat')");
            }
  //---------------------------------------
  if($action=="vote2_del"){
          db_query("delete from songs_votes where id=$id");
          }
  //-----------------------------------------
  if($action=="vote2_edit_ok"){
          db_query("update songs_votes set title='$title' where id=$id");
          }
  //---------------------------------------

  $data=db_qr_fetch("select id,title from songs_votes_cats where id=$cat");

   print "<center>
  <form action=index.php mothod=post>
  <input type=hidden name=id value=$data[id]>
  <input type=hidden name=cat value=$cat>
  <input type=hidden name=action value='vote_edit_ok'>
  <table width=50% class=grid>
  <tr><td align=center>
  $phrases[the_title] : <input type=text value='$data[title]' name=title size=30>
  <input type=submit value=' $phrases[edit]  '></td></tr></table> </form>";

  print "
  <br>
  <form action=index.php method=post>
  <input type=hidden name=action value='vote2_add'>
  <input type=hidden name=cat value='$cat'>
  <table width=50% class=grid><tr><td align=center>
  <p class=title> $phrases[add_options] </p>
  $phrases[the_title] : <input type=text name=title size=30>
  <input type=submit value=' $phrases[add_button] '></td></tr></table><br>
  <table width=50% class=grid>";
  $qr=db_query("select * from songs_votes where cat=$cat");
  while($data = db_fetch($qr)){
    print "<tr><td width=70%> $data[title] </td><td> <a href='index.php?action=vote2_edit&id=$data[id]&cat=$cat'> $phrases[edit] </a>
    - <a href='index.php?action=vote2_del&id=$data[id]&cat=$cat' onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a> </td>
    </tr>";

          }
       print "</table></center>";
          }
  //------------------------------------------------------
  if($action == "vote2_edit"){

  $data = db_qr_fetch("select * from songs_votes where id=$id") ;
  print "<center>
  <form action=index.php mothod=post>
  <input type=hidden name=id value=$id>
  <input type=hidden name=cat value=$cat>
  <input type=hidden name=action value='vote2_edit_ok'>
  <table width=50% class=grid>
  <tr><td align=center>
  $phrases[the_title] : <input type=text value='$data[title]' name=title size=30>
  <input type=submit value=' $phrases[edit] '></td></tr></table> </form></center>";
  }
?>