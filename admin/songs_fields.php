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
                              
//---------------------- Songs Fields ---------------------
if($action=="songs_fields" || $action=="songs_fields_edit_ok" || $action=="songs_fields_add_ok" || 
$action=="songs_fields_del" || $action=="songs_fields_disable" || $action=="songs_fields_enable"){

if_admin("songs_fields");

//------- enable / disale -----------//
if($action=="songs_fields_disable"){
        db_query("update songs_custom_sets set active=0 where id='$id'");
        }

if($action=="songs_fields_enable"){

       db_query("update songs_custom_sets set active=1 where id='$id'");
        }
//-------- del -------//
if($action=="songs_fields_del"){
$id=intval($id);
db_query("delete from songs_custom_sets where id='$id'");
db_query("delete from songs_custom_fields where cat='$id'"); 
}

//----- edit -----//
if($action=="songs_fields_edit_ok"){
$id=intval($id);
if($name){
db_query("update songs_custom_sets set name='".db_clean_string($name)."',type='$type',value='$value',style='$style',ord='".intval($ord)."' where id='$id'");

}
}

//------- add -------//
if($action=="songs_fields_add_ok"){
$id=intval($id);
if($name){
db_query("insert into songs_custom_sets  (name,type,value,style,active) values('".db_clean_string($name)."','$type','$value','$style','1')");
    }
}


print "<p align=center class=title> $phrases[songs_custom_fields] </p>

<p align=$global_align><a href='index.php?action=songs_fields_add'><img src='images/add.gif' border=0> $phrases[songs_field_add] </a></p>

<center>";

$qr= db_query("select * from songs_custom_sets order by ord asc");
if(db_num($qr)){
print "<table width=90% class=grid>
<tr><td width=100%>
<div id=\"songs_custom_fields_list\">";
while($data=db_fetch($qr)){
print "
<div id=\"item_$data[id]\" onmouseover=\"this.style.backgroundColor='#EFEFEE'\"
     onmouseout=\"this.style.backgroundColor='#FFFFFF'\">
<table width=100%>
<tr>
<td width=25>
      <span style=\"cursor: move;\" class=\"handle\"><img src='images/move.gif'></span> 
      </td>
      
<td width=60><b>ID $data[id]</b></td><td>$data[name]</td><td width=150>";

          if($data['active']){
                        print "<a href='index.php?action=songs_fields_disable&id=$data[id]'>$phrases[disable]</a> - " ;
                        }else{
                        print "<a href='index.php?action=songs_fields_enable&id=$data[id]'>$phrases[enable]</a> - " ;
                        }
                        
print "<a href='index.php?action=songs_fields_edit&id=$data[id]'>$phrases[edit]</a> - <a href='index.php?action=songs_fields_del&id=$data[id]' onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a></td></tr>
</table></div>";
}

print "</div></td></tr></table></center>";


print "<script type=\"text/javascript\">
        init_songs_custom_fields_sortlist();
</script>";
}else{
print_admin_table("<center>  $phrases[no_data] </center>");
    }



}

//---------- Add Song Field -------------
if($action=="songs_fields_add"){
 if_admin("songs_fields");

print "<center>
<p align=center class=title>$phrases[songs_field_add]</p>
<form action=index.php method=post>
<input type=hidden name=action value='songs_fields_add_ok'>
<input type=hidden name=id value='$id'>
<table width=80% class=grid>";
print "<tr>
<td><b>$phrases[the_name]</b> </td><td><input type=text size=20  name=name value=\"$data[name]\"></td></tr>
<tr><td><b>$phrases[the_type]</b></td><td><select name=type>
<option value='text'>$phrases[textbox]</option>
<option value='textarea'>$phrases[textarea]</option>
<option value='select'>$phrases[select_menu]</option>
<option value='radio'>$phrases[radio_button]</option>
<option value='checkbox'>$phrases[checkbox]</option>
</select>
</td></tr>
<tr><td><b>$phrases[default_value_or_options]</b><br><br>$phrases[put_every_option_in_sep_line]</td><td>
<textarea name='value' rows=10 cols=30>$data[value]</textarea></td></tr>

<tr><td><b>$phrases[field_style]</b> </td><td><input type=text size=30  name=style value=\"$data[style]\" dir=ltr></td></tr>

<tr><td colspan=2 align=center><input type=submit value=' $phrases[add_button] '></td></tr>";
print "</table></center>";

}


//---------- Edit Song Field -------------
if($action=="songs_fields_edit"){

    if_admin("songs_fields");
$id=intval($id);

$qr = db_query("select * from songs_custom_sets where id='$id'");

if(db_num($qr)){
$data = db_fetch($qr);
print "<center><form action=index.php method=post>
<input type=hidden name=action value='songs_fields_edit_ok'>
<input type=hidden name=id value='$id'>
<table width=80% class=grid>";
print "
<tr><td><b>$phrases[the_name]</b> </td><td><input type=text size=20  name=name value=\"$data[name]\"></td></tr>
<tr><td><b>$phrases[the_type]</b></td><td><select name=type>";

if($data['type']=="text"){
    $chk1 = "selected";
    $chk2 = "";
    $chk3 = "";
    $chk4 = "";
    $chk5 = "";
    $chk6 = "";
}elseif($data['type']=="textarea"){
    $chk1 = "";
    $chk2 = "selected";
    $chk3 = "";
    $chk4 = "";
    $chk5 = "";
    $chk6 = "";
}elseif($data['type']=="select"){
    $chk1 = "";
    $chk2 = "";
    $chk3 = "selected";
    $chk4 = "";
    $chk5 = "";
    $chk6 = "";
}elseif($data['type']=="radio"){
    $chk1 = "";
    $chk2 = "";
    $chk3 = "";
    $chk4 = "selected";
    $chk5 = "";
    $chk6 = "";
}elseif($data['type']=="checkbox"){
    $chk1 = "";
    $chk2 = "";
    $chk3 = "";
    $chk4 = "";
    $chk5 = "selected";
    $chk6 = "";
}

print "<option value='text' $chk1>$phrases[textbox]</option>
<option value='textarea' $chk2>$phrases[textarea]</option>
<option value='select' $chk3>$phrases[select_menu]</option>
<option value='radio' $chk4>$phrases[radio_button]</option>
<option value='checkbox' $chk5>$phrases[checkbox]</option>
</select>
</td></tr>
<tr><td><b>$phrases[default_value_or_options]</b><br><br>$phrases[put_every_option_in_sep_line]</td><td>
<textarea name='value' rows=10 cols=30>$data[value]</textarea></td></tr>

<tr><td><b>$phrases[field_style]</b> </td><td><input type=text size=30  name=style value=\"$data[style]\" dir=ltr></td></tr>


<tr><td><b>$phrases[the_order]</b> </td><td><input type=text size=3  name=ord value=\"$data[ord]\"></td></tr>

<tr><td colspan=2 align=center><input type=submit value=' $phrases[edit] '></td></tr>";
print "</table></center>";
}else{
print "<center><table width=70% class=grid>";
print "<tr><td align=center>$phrases[err_wrong_url]</td></tr>";
print "</table></center>";
}

}
?>