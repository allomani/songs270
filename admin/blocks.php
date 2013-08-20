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



// -------------- Blocks ----------------------------------
if ($action == "blocks" or $action=="del_block" or $action=="edit_block_ok" or $action=="add_block"
|| $action=="block_disable" || $action=="block_enable" || $action=="block_order" || $action=="blocks_fix_order"){


if_admin();
if($action=="blocks_fix_order"){

   $qr=db_query("select * from songs_blocks where pos='r' order by ord ASC");
    if(db_num($qr)){
    $block_c = 1 ;
    while($data = db_fetch($qr)){
    db_query("update songs_blocks set ord='$block_c' where id='$data[id]'");
    ++$block_c;
    }
     }
//-------------------------------
  $qr=db_query("select * from songs_blocks where pos='c' order by ord ASC");
    if(db_num($qr)){
    $block_c = 1 ;
    while($data = db_fetch($qr)){
    db_query("update songs_blocks set ord='$block_c' where id='$data[id]'");
    ++$block_c;
    }
     }
//-------------------------------
  $qr=db_query("select * from songs_blocks where pos='l' order by ord ASC");
    if(db_num($qr)){
    $block_c = 1 ;
    while($data = db_fetch($qr)){
    db_query("update songs_blocks set ord='$block_c' where id='$data[id]'");
    ++$block_c;
    }
     }
        }

if($action=="block_order"){
        db_query("update songs_blocks set ord='$ord' where id = '$idrep'");
        db_query("update songs_blocks set ord='$ordrep' where id = '$id'");
        }


if($action=="block_disable"){
        db_query("update songs_blocks set active=0 where id='$id'");
        }

if($action=="block_enable"){

       db_query("update songs_blocks set active=1 where id='$id'");
        }
//---------------------------------------------------------
if($action=="add_block"){
if($pages){
foreach ($pages as $value) {
       $pg_view .=  "$value," ;
     }
       }else{
               $pg_view = '' ;
               }


if($pos != "l" && $pos != "r" && $pos != "c"){$pos = "c";}

db_query("insert into songs_blocks(title,pos,file,ord,active,template,pages)
values(
'".db_clean_string($title,"code")."',
'".db_clean_string($pos)."',
'".db_clean_string($file,"code")."',
'".db_clean_string($ord,"num")."','1',
'".db_clean_string($template,"num")."',
'".db_clean_string($pg_view)."')");
        }
//------------------------------------------------------------
if ($action=="del_block"){
          db_query("delete from songs_blocks where id='$id'");
            }
//----------------------------------------------------------------
if ($action=="edit_block_ok"){
if($pages){
foreach ($pages as $value) {
       $pg_view .=  "$value," ;
     }
}else{
$pg_view = '' ;
}


if($pos != "l" && $pos != "r" && $pos != "c"){$pos = "c";}

db_query("update songs_blocks set
title='".db_clean_string($title,"code")."',
file='".db_clean_string($file,"code")."',
pos='".db_clean_string($pos)."',
ord='".db_clean_string($ord,"num")."',
template='".db_clean_string($template,"num")."',
pages='".db_clean_string($pg_view)."' where id='".intval($id)."'");

                    }
//------------------------------------------------------------

print "<center><table border=\"0\" width=\"60%\"  cellpadding=\"0\" cellspacing=\"0\" class=\"grid\">
        <tr>
                <td height=\"0\" >


                <form method=\"POST\" action=\"index.php\" name=submit_form>

                      <input type=hidden name=\"action\" value='add_block'>

                    

                        <tr>
                                <td width=\"70\">
                <b>$phrases[the_title]</b></td><td >
                <input type=\"text\" name=\"title\" size=\"29\"></td>
                        </tr>
                       <tr>
                                <td width=\"70\">
                <b>$phrases[the_content]</b></td><td width=\"223\">
                  <textarea name='file' rows=10 cols=29 dir=ltr ></textarea></td>
                        </tr>

                               <tr> <td width=\"50\">
                <b>$phrases[the_position]</b></td>
                                <td width=\"223\">
                <select size=\"1\" name=\"pos\" onchange=\"set_menu_pages(this)\">
                        <option value=\"r\" selected>$phrases[right]</option>
                         <option value=\"c\">$phrases[center]</option>
                        <option value=\"l\">$phrases[left]</option>
                        </select>
                        </td>
                        </tr>
              <tr><td><b>$phrases[the_template]</b></td><td><select name=template><option value='0' selected> $phrases[the_default_template] </option>";
              $qr = db_query("select name,id,cat from songs_templates where protected !=1 order by cat,id ");
              while($data = db_fetch($qr)){
              $t_catname = db_qr_fetch("select name from songs_templates_cats where id='$data[cat]'");
                      print "<option value='$data[id]'>$t_catname[name] : $data[name]</option>";
                      }
                      print "</select></td></tr>
                        <tr>
                                <td width=\"50\">
                <b>$phrases[the_order]</b></td><td width=\"223\">
                <input type=\"text\" name=\"ord\" value=\"1\" size=\"2\"></td>
                        </tr>

 <tr><td> <b> $phrases[appearance_places]</b></td><td><table width=100%><tr><td>";


  if(is_array($actions_checks)){
$c=0;
 for($i=0; $i < count($actions_checks);$i++) {

        $keyvalue = current($actions_checks);

if($c==4){
    print "</td><td>" ;
    $c=0;
    }

print "<input  name=\"pages[$i]\" type=\"checkbox\" value=\"$keyvalue\" checked>".key($actions_checks)."<br>";


$c++ ;

 next($actions_checks);
}
}


          print " </td></tr></table></td></tr><tr><td colspan=2 align=center><input type=\"submit\" value=\"$phrases[add_button]\"></td></tr>


</table>
</form>    </center> <br>\n";

?>
      <style type="text/css">
   div { cursor: move; }
</style>
<?

       $qr_arr[0]=db_query("select * from songs_blocks where pos='l' order by ord asc");
       $qr_arr[1]=db_query("select * from songs_blocks where pos='c' order by ord asc");
       $qr_arr[2]=db_query("select * from songs_blocks where pos='r' order by ord asc");

       if (db_num($qr_arr[0]) || db_num($qr_arr[1]) || db_num($qr_arr[2])){
           print "<center><table border=\"0\" width=\"80%\" cellpadding=\"0\" cellspacing=\"0\" class=\"grid\" dir=ltr>
           <tr>
          <td align=center><b>$phrases[left]</b></td>  
           <td align=center><b>$phrases[center]</b></td>
           
            <td align=center><b>$phrases[right]</b></td>
           </tr>
           <tr>";
           /*
           <tr><td><b>  $phrases[the_title] </b><td><b> $phrases[the_position] </b></td><td><b> $phrases[the_order] </b></td>
           <td colspan=3 align=center><b>  $phrases[the_options] </b></td></tr>";

                                         */
        $i = 0 ;  
       foreach($qr_arr as $qr){  
                                          
         while($data= db_fetch($qr)){
         if($data['pos'] == "r"){
                 $block_color = "#0080C0";
                 }elseif($data['pos'] == "l"){
                   $block_color = "#2C920E";
                   }else{
                   $block_color = "#EA7500";
                           }
       if($last_block_pos != $data['pos']){
          
           if($i > 0){print "</div>";}
           print "</td><td valign=top dir=$global_dir>";
           
            print "<div id='blocks_list_".$data['pos']."'>";
            $i++;
       } 
                          
       $last_block_pos = $data['pos'];
     print "<div id=\"item_$data[id]\" style=\"border: thin dashed ".iif($data['active'],"#C0C0C0","#000000").";".iif(!$data['active'],"background-color:#FFEAEA;")."\" 
     onmouseover=\"this.style.backgroundColor='#EFEFEE'\"
     onmouseout=\"this.style.backgroundColor='".iif($data['active'],"#FFFFFF","#FFEAEA")."'\"><center>
     <table width=96%>
      <tr>
                <td align=center><font color='$block_color'><b>";
                if($data['title']){
                    print $data['title'] ;
                    }else{
                    print "[ $phrases[without_title] ]" ;
                        }
                        print "</b></font></td></tr>
                        <tr >
               
             
                <td align=center>";

                if($data['active']){
                        print "<a href='index.php?action=block_disable&id=$data[id]'>$phrases[disable]</a>" ;
                        }else{
                        print "<a href='index.php?action=block_enable&id=$data[id]'>$phrases[enable]</a>" ;
                        }

                print "- <a href='index.php?action=edit_block&id=$data[id]'>$phrases[edit] </a>
                - <a href='index.php?action=del_block&id=$data[id]' onClick=\"return confirm('Are you sure you want to delete ?');\">$phrases[delete] </a></td>
        </tr>
        </table></center></div>";
            //    $i++;
                 }
       }
                print "</div>
       


               ";
                ?>
<script type="text/javascript">
        init_blocks_sortlist();
</script>
<?
 //<div id=result>result here</div>
                print"</td></tr></table>"; 
                
              /*
                print "<br><form action='index.php' method=post>
                <input type=hidden name=action value='blocks_fix_order'>
                <input type=submit value=' $phrases[cp_blocks_fix_order] '>
                </form><br>";
                 */
                }else{
                        print "<br><center><table width=50% class=grid><tr><td align=center>$phrases[cp_no_blocks]</td></tr></table></center>";
                        }

}
//--------------------- Block Edit ---------------------------
if($action == "edit_block"){

    if_admin();
  $data=db_qr_fetch("select * from songs_blocks where id='$id'");
      $data['file'] = htmlspecialchars($data['file']) ;

 print " <center><table border=\"0\" width=\"60%\"  class=\"grid\" >


                <form method=\"POST\" action=\"index.php\">

                      <input type=hidden name=\"action\" value='edit_block_ok'>
                       <input type=hidden name=\"id\" value='$id'>


                        <tr>
                                <td>
                <b>$phrases[the_title]</b></td><td>
                <input type=\"text\" name=\"title\" value='$data[title]' size=\"29\"></td>
                        </tr>
                       <tr>
                                <td >
                <b>$phrases[the_content]</b></td><td >
                 <textarea name='file' rows=10 cols=50 dir=ltr >$data[file]</textarea></td>
                        </tr>";

                        if($data['pos']=="r"){
                                $option1 = "selected";
                                }elseif($data['pos']=="c"){
                                $option2 = "selected";
                                }else{
                                $option3="selected";
                                }

                              if($data['template']==0){
                                      $def_chk = "selected" ;}else{$def_chk = "" ;}

                             print"  <tr> <td >
                <b>$phrases[the_position]</b></td>
                                <td width=\"223\">
                <select size=\"1\" name=\"pos\">
                        <option value=\"r\" $option1>$phrases[right]</option>
                        <option value=\"c\" $option2>$phrases[center]</option>
                         <option value=\"l\" $option3>$phrases[left]</option>
                        </select>
                        </td>
                        </tr>

                   <tr><td><b>$phrases[the_template] </b></td><td><select name=template><option value='0' $def_chk> $phrases[the_default_template] </option>";

  $qr_template = db_query("select name,id,cat from songs_templates where protected !=1 order by cat,id");
              while($data_template = db_fetch($qr_template)){
              if($data['template'] == $data_template['id']){
                      $chk = "selected" ;
                      }else{
                              $chk = "";
                              }
                      $t_catname = db_qr_fetch("select name from songs_templates_cats where id='$data_template[cat]'");
                      print "<option value='$data_template[id]' $chk>$t_catname[name] : $data_template[name]</option>";
                      }
                      print "</select></td></tr>

                              <tr>
                                <td>
                <b>$phrases[the_order]</b></td><td width='223'>
                <input type='text' name='ord' value='$data[ord]' size='2'></td>
                        </tr>
                        <tr><td> <b> $phrases[appearance_places]</b></td><td><table width=100%><tr><td>";

                         $pages_view = explode(",",$data['pages']);


  if(is_array($actions_checks)){

  $c=0;
 for($i=0; $i < count($actions_checks);$i++) {

        $keyvalue = current($actions_checks);

if($c==4){
    print "</td><td>" ;
    $c=0;
    }

if(in_array($keyvalue,$pages_view)){$chk = "checked" ;}else{$chk = "" ;}

print "<input  name=\"pages[$i]\" type=\"checkbox\" value=\"$keyvalue\" $chk>".key($actions_checks)."<br>";


$c++ ;

 next($actions_checks);
}
}



                          print "</td></tr></table>" ;
           print "</td></tr><tr><td colspan=2 align=center><input type=\"submit\" value=\"$phrases[edit]\"> </td></tr>



</table>
</form>    </center>\n";

        }