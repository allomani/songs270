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

//----------------  Banners -------------------------------------
   if($action == "banners" || $action =="adv2" || $action =="adv2_edit_ok" || $action =="adv2_del" || $action =="adv2_add_ok" || $action=="banner_disable" || $action=="banner_enable"){

   if_admin("adv");

//----------- add ----------------
if($action =="adv2_add_ok"){
    if($pages){
foreach ($pages as $value) {
       $pg_view .=  "$value," ;
     }
       }else{
               $pg_view = '' ;
               }

      db_query("insert into  songs_banners (title,url,img,ord,type,date,menu_id,menu_pos,pages,content,c_type,active) values ('".db_clean_string($title)."','$url','$img','$ord','$type',now(),'$menu_id','$menu_pos','$pg_view','".db_clean_string($content,"code")."','$c_type','1')");

          }

//---------- edit --------------
if($action =="adv2_edit_ok"){

 if($pages){
foreach ($pages as $value) {
       $pg_view .=  "$value," ;
     }
       }else{
               $pg_view = '' ;
               }
      db_query("update songs_banners set title='".db_clean_string($title)."',url='$url',img='$img',ord='$ord',type='$type',menu_id='$menu_id',menu_pos='$menu_pos',pages='$pg_view',content='".db_clean_string($content,"code")."',c_type='$c_type' where id='$id'");

          }

//---------- delete -------------
if($action =="adv2_del"){

      db_query("delete from songs_banners where id='$id'");

 }


 if($action=="banner_disable"){
        db_query("update songs_banners set active=0 where id='$id'");
        }

if($action=="banner_enable"){

       db_query("update songs_banners set active=1 where id='$id'");
        }
//-------------------------------------
              print "<center><table  width=\"70%\" class=grid>


                <form method=\"POST\" action=\"index.php\" name=sender>
                 <input type='hidden' value='adv2_add_ok' name='action'>
                 
                
              <tr>
                   <td >
                      $phrases[the_name]<td >
                <input type=\"text\" name=\"title\" size=\"38\"></td>
        </tr>

           <tr>
                   <td >
                   $phrases[the_content_type]    <td >
               <input name=\"c_type\" type=\"radio\" value=\"img\" checked onClick=\"show_banner_img();\" > $phrases[bnr_ctype_img] <br>
               <input name=\"c_type\" type=\"radio\" value=\"code\" onClick=\"show_banner_code();\"> $phrases[bnr_ctype_code]
                </td>
        </tr>

         <tr id=banners_url_area>
                <td >$phrases[the_url]</td>
                <td >
                <input type=\"text\" name=\"url\"  dir=ltr value='http://' size=\"38\"></td>
        </tr>
        <tr id=banners_img_area>
                <td >$phrases[the_image]</td>
                <td >

                <table><tr><td>
                                 <input type=\"text\" name=\"img\" size=\"30\" dir=ltr value=\"$data[img]\">   </td>

                                <td> <a href=\"javascript:uploader('banners','img');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a>
                                 </td></tr></table>

                                 </td>
        </tr>

<tr id=banners_code_area style=\"display: none; text-decoration: none\"> <td>$phrases[the_code] </td>
<td>
<textarea dir=ltr rows=\"8\" name=\"content\" cols=\"50\"></textarea>
</td></tr>

        <tr>
                <td >$phrases[bnr_appearance_places]</td>
                <td ><select name=\"type\" size=\"1\" onChange=\"show_adv_options(this)\">
             ";
                print "
                <option value=\"header\" selected>$phrases[bnr_header]</option>
                <option value=\"footer\">$phrases[bnr_footer]</option>

                   <option value=\"open\" >$phrases[bnr_open]</option>
                 <option value=\"close\" >$phrases[bnr_close]</option>
                 <option value=\"menu\" >$phrases[bnr_menu]</option>
                <option value=\"listen\" >$phrases[bnr_listen]</option>
                </select></td>

                </tr>
        <tr id=add_after_menu style=\"display: none; text-decoration: none\">
                <td>
                
                 $phrases[add_after_menu_number] :</td>
                <td>
              
                <input type=\"text\"  name=\"menu_id\" value=0 size=\"4\">&nbsp;  $phrases[bnr_menu_pos]&nbsp;
                <select name=\"menu_pos\" size=\"1\">

                <option value=\"r\" >$phrases[the_right]</option>
                <option value=\"c\" >$phrases[the_center]</option>
                 <option value=\"l\" >$phrases[the_left]</option>

                </select>  </td>

                </tr>

                <tr>
                <td height=\"43\" width=\"131\">$phrases[the_order]</td>
                <td height=\"43\" width=\"308\"><input type=\"text\" name=\"ord\" value='0' size=\"4\"></td>
                </tr>
                <tr id=banners_pages_area><td>$phrases[bnr_appearance_pages]</td><td>

                <table width=100%><tr><td>";


  if(is_array($actions_checks)){


  $c=0;
 for($i=0; $i < count($actions_checks);$i++) {

        $keyvalue = current($actions_checks);

if($c==3){
    print "</td><td>" ;
    $c=0;
    }

print "<input  name=\"pages[$i]\" type=\"checkbox\" value=\"$keyvalue\" checked>".key($actions_checks)."<br>";


$c++ ;

 next($actions_checks);
}
}
       print"</tr></table> <tr>
                <td colspan=\"2\" align=center>

                <input type=\"submit\" value=\"$phrases[add_button]\"></td>
        </tr>
</table>
        </form></center><br>";

        
//------------Bannners List -----------//

 $qr= db_query("select * from songs_banners order by type , ord");
 
 if(db_num($qr)){
     
?>
      <style type="text/css">
   div { cursor: move; }
</style>
<?

    print "
  <center>
  <table width=90% class=grid>

 
  
  <tr><td>
  ";
  $i=0;
  while($data=db_fetch($qr)){

          if($last_banner_type != $data['type']){
          if($i > 0){print "</div>";}
          $types_array[] =  $data['type'] ;
        print "<div id='banners_list_".$data['type']."'>";
        $i++;
       } 
                          
       $last_banner_type = $data['type'];
       
       
  print "<div id=\"item_$data[id]\" style=\"".iif(!$data['active'],"background-color:#FFEAEA;")."\" 
     onmouseover=\"this.style.backgroundColor='#EFEFEE'\"
     onmouseout=\"this.style.backgroundColor='".iif($data['active'],"#FFFFFF","#FFEAEA")."'\">
  <table width=100%><tr>";
  if($data['c_type']=="code"){
      print "<td width=25><img src='images/code_icon.gif' alt='$phrases[bnr_ctype_code]'></td>";
      }else{
          print "<td width=25><img src='images/image_icon.gif' alt='$phrases[bnr_ctype_img]'></td>";
          }

  print "<td>$data[title]</td>

  
   <td width=60>$data[type]</td>
   <td width=60> ";
   if($data['type'] == "menu"){
   print str_replace("r","$phrases[right]",str_replace("l","$phrases[left]",str_replace("c","$phrases[center]",$data['menu_pos'])));
   }else{
           print "-" ;
           }
           print "</td>
     <td width=100>$data[views] $phrases[bnr_views]</td>
       <td width=100>$data[clicks] $phrases[bnr_visits] </td>
    <td width=200>";
     if($data['active']){
                        print "<a href='index.php?action=banner_disable&id=$data[id]'>$phrases[disable]</a> - " ;
                        }else{
                        print "<a href='index.php?action=banner_enable&id=$data[id]'>$phrases[enable]</a> - " ;
                        }
                        
    print "<a href='index.php?action=adv2_edit&id=$data[id]'>$phrases[edit]</a> - 
    <a href='index.php?action=adv2_del&id=$data[id]' onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete] </a></td>
  </tr>
  </table></div>" ;

      }
       print "</div>
       </td></tr></table></center>\n";
    

if(is_array($types_array)){
print "<script type=\"text/javascript\">"; 
foreach($types_array as $value){
print "Sortable.create
(
    'banners_list_".$value."',{
tag:'div',
        constraint: false,
        onUpdate: function()
        {
      new Ajax.Updater
            (
                'result', 'ajax.php',
                { postBody: Sortable.serialize('banners_list_".$value."',{name:'sort_list'}) +'&action=set_banners_sort'}
            );
        }
    }
);";
    
}
print "</script>";
}
 } 
}

   //-------------------EDIT BANNER-----------------------------
   if ($action == "adv2_edit"){
    if_admin("adv");

$id = db_clean_string($id,"num");

        $data=db_qr_fetch("select * from songs_banners where id='$id'");

          print "<center><table width=\"70%\" class=grid>
        <tr>

                <form name=sender method=\"POST\" action=\"index.php\" name=sender>
                 <input type='hidden' value='adv2_edit_ok' name='action'>
                  <input type='hidden' value='$id' name='id'>

                         <td height=\"13\" width=\"131\">
                       $phrases[the_name]<td height=\"13\" width=\"308\">
                <input type=\"text\" name=\"title\" value='$data[title]' size=\"38\"></td>
        </tr>";

        if($data['c_type']=="code"){$chk2 = "checked";$chk1="";}else{$chk1="checked";$chk2="";}

         print "<tr>
                   <td >
                      $phrases[the_content_type] <td >
               <input name=\"c_type\" type=\"radio\" value=\"img\" $chk1 onClick=\"show_banner_img();\" > $phrases[bnr_ctype_img]  <br>
               <input name=\"c_type\" type=\"radio\" value=\"code\" $chk2 onClick=\"show_banner_code();\"> $phrases[bnr_ctype_code]
                </td>
        </tr>";
        if($data['c_type']=="code"){
         print "<tr id=banners_url_area style=\"display: none; text-decoration: none\">";
         }else{
          print "<tr id=banners_url_area>";
             }
                print "<td >$phrases[the_url]</td>
                <td >
                <input type=\"text\" name=\"url\"  dir=ltr value='$data[url]' size=\"38\"></td>
        </tr>";
        if($data['c_type']=="code"){
        print "<tr id=banners_img_area style=\"display: none; text-decoration: none\">";
        }else{
             print "<tr id=banners_img_area>";
        }
                print "<td >$phrases[the_image]</td>
                <td >

                <table><tr><td>
                                 <input type=\"text\" name=\"img\" size=\"30\" dir=ltr value=\"$data[img]\">   </td>

                                <td> <a href=\"javascript:uploader('banners','img');\"><img src='images/file_up.gif' border=0 alt='$phrases[upload_file]'></a>
                                 </td></tr></table>

                                 </td>
        </tr>";
if($data['c_type']=="code"){
print "<tr id=banners_code_area>";
}else{
print "<tr id=banners_code_area style=\"display: none; text-decoration: none\">";
    }
print " <td>$phrases[the_code] </td>
<td>
<textarea dir=ltr rows=\"8\" name=\"content\" cols=\"50\">$data[content]</textarea>
</td></tr>



        <tr>
                <td height=\"45\">$phrases[bnr_appearance_places]</td>
                <td height=\"45\"><select name=\"type\" size=\"1\" onclick=\"show_adv_options(this)\">
             ";
             if($data['type']=="header"){
                     $opt1 = "selected" ; }
                     elseif($data['type']=="footer"){$opt2="selected" ; }
                      elseif($data['type']=="open"){ $opt3="selected" ;}
                      elseif($data['type']=="close"){ $opt4="selected" ;}
                      elseif($data['type']=="menu"){ $opt5="selected" ;}
                             else{$opt6="selected" ; }

                print "
                <option value=\"header\" $opt1>$phrases[bnr_header]</option>
                <option value=\"footer\" $opt2>$phrases[bnr_footer]</option>
                   <option value=\"open\" $opt3>$phrases[bnr_open]</option>
                 <option value=\"close\" $opt4>$phrases[bnr_close]</option>
                 <option value=\"menu\" $opt5>$phrases[bnr_menu]</option>
                 <option value=\"listen\" $opt6>$phrases[bnr_listen]</option> 
                </select></td>\n";

       print " </tr>
        <tr id=add_after_menu".iif($data['type']!="menu"," style=\"display: none; text-decoration: none\"",'').">
                <td>";
               
               print " $phrases[add_after_menu_number]</td>
                <td>";
               
                print "<input type=\"text\" value='$data[menu_id]' name=\"menu_id\" value='0' size=\"4\">  $phrases[bnr_menu_pos]
                <select name=\"menu_pos\" size=\"1\">
             ";

             if($data['menu_pos']=="r"){$opt11 = "selected" ; }elseif($data['menu_pos']=="c"){$opt21="selected" ; }else{ $opt31="selected" ;}

                print "
                <option value=\"r\" $opt11>$phrases[right]</option>
                <option value=\"c\" $opt21>$phrases[center]</option>
                 <option value=\"l\" $opt31>$phrases[left]</option>

                </select></td>

                </tr>

                <tr>
                <td height=\"43\" width=\"131\">$phrases[the_order]</td>
                <td height=\"43\" width=\"308\"><input type=\"text\" value='$data[ord]' name=\"ord\" value='0' size=\"4\"></td>
                </tr>
                <tr id=banners_pages_area".iif($data['type']=="listen"," style=\"display: none; text-decoration: none\"",'').">
                <td>  $phrases[bnr_appearance_pages]</td><td><table width=100%><tr><td>";

                         $pages_view = explode(",",$data['pages']);


  if(is_array($actions_checks)){

  $c=0;
 for($i=0; $i < count($actions_checks);$i++) {

        $keyvalue = current($actions_checks);

if($c==3){
    print "</td><td>" ;
    $c=0;
    }

if(in_array($keyvalue,$pages_view)){$chk = "checked" ;}else{$chk = "" ;}

print "<input  name=\"pages[$i]\" type=\"checkbox\" value=\"$keyvalue\" $chk>".key($actions_checks)."<br>";


$c++ ;

 next($actions_checks);
}
}



                          print "</tr></table>
        <tr>
                <td height=\"21\" colspan=\"2\">
                <p align=\"center\">
                <input type=\"submit\" value=\"$phrases[edit]\" name=\"B1\"></td>
        </tr>
</table>
        </form></center>\n
             ";

           }
