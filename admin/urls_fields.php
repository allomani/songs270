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

//---------------------- URLs Fields ---------------------
if($action=="urls_fields" || $action=="urls_fields_edit_ok" || $action=="urls_fields_add_ok" || 
$action=="urls_fields_del" || $action=="urls_fields_disable" || $action=="urls_fields_enable"){

if_admin("urls_fields");


//------- enable / disale -----------//
if($action=="urls_fields_disable"){
        db_query("update songs_urls_fields set active=0 where id='$id'");
        }

if($action=="urls_fields_enable"){

       db_query("update songs_urls_fields set active=1 where id='$id'");
        }
// -------- Del ------------//
if($action=="urls_fields_del"){
$id=intval($id);
if($id !=1){
db_query("delete from songs_urls_fields where id='$id'");
}
}

//------------- edit ---------//
if($action=="urls_fields_edit_ok"){
$id=intval($id);
if($name){
db_query("update songs_urls_fields set name='$name',ord='".intval($ord)."',show_listen='".intval($show_listen)."',show_download='".intval($show_download)."',
download_icon='".db_clean_string($download_icon)."',listen_icon='".db_clean_string($listen_icon)."',
download_alt='".db_clean_string($download_alt)."',listen_alt='".db_clean_string($listen_alt)."',
listen_name='".db_clean_string($listen_name)."',listen_mime='".db_clean_string($listen_mime)."',
listen_content='".db_clean_string($listen_content,"code")."'
 where id='$id'");

}
}
//-------- add -----------//
if($action=="urls_fields_add_ok"){
if($name){
$data = db_qr_fetch("select * from songs_urls_fields where id='1'");
$ord_dt = db_qr_fetch("select max(ord) as max from  songs_urls_fields limit 1");
$ord = intval($ord_dt['max'])+1;

db_query("insert into songs_urls_fields(name,ord,show_listen,show_download,download_icon,listen_icon,download_alt,listen_alt,listen_name,listen_mime,listen_content) 
values(
'".db_clean_string($name,"text","write",false)."',
'$ord',
'$data[show_listen]',
'$data[show_download]',
'".db_clean_string($data['download_icon'],"text","write",false)."',
'".db_clean_string($data['listen_icon'],"text","write",false)."',
'".db_clean_string($data['download_alt'],"text","write",false)."',
'".db_clean_string($data['listen_alt'],"text","write",false)."',
'".db_clean_string($data['listen_name'],"text","write",false)."',
'".db_clean_string($data['listen_mime'],"text","write",false)."',
'".db_clean_string($data['listen_content'],"code","write",false)."'
)");
}
}
//-------------------------//

print "<p align=center class=title> $phrases[urls_fields] </p>

<p align=$global_align><a href='index.php?action=urls_fields_add'><img src='images/add.gif' border=0> $phrases[urls_fields_add] </a></p>

<center><table width=90% class=grid>
<tr><td>
<div id=\"urls_fields_list\">";

$qr= db_query("select * from songs_urls_fields order by ord");
if(db_num($qr)){

while($data=db_fetch($qr)){
print "<div id=\"item_$data[id]\" onmouseover=\"this.style.backgroundColor='#EFEFEE'\"
     onmouseout=\"this.style.backgroundColor='#FFFFFF'\">
<table width=100%><tr>
<td width=25>
      <span style=\"cursor: move;\" class=\"handle\"><img src='images/move.gif'></span> 
      </td>
<td>$data[name]</td>
<td width=150>";


          if($data['active']){
                        print "<a href='index.php?action=urls_fields_disable&id=$data[id]'>$phrases[disable]</a> - " ;
                        }else{
                        print "<a href='index.php?action=urls_fields_enable&id=$data[id]'>$phrases[enable]</a> - " ;
                        }
                        
print "<a href='index.php?action=urls_fields_edit&id=$data[id]'>$phrases[edit]</a> ".iif($data['id']!=1,"- <a href='index.php?action=urls_fields_del&id=$data[id]' onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a>")."</td></tr>
</table></div>";
}

}else{
print "<center>  $phrases[no_data] <center>";
    }

print "</div></td></tr></table></center>";

print "<script type=\"text/javascript\">
        init_urls_fields_sortlist();
</script>";
}

//---------- Add File Field -------------
if($action=="urls_fields_add"){
 if_admin("urls_fields");

print "<center>
<p align=center class=title>$phrases[files_field_add]</p>
<form action=index.php method=post>
<input type=hidden name=action value='urls_fields_add_ok'>
<table width=80% class=grid>";
print "<tr>
<td><b>$phrases[the_name]</b> </td><td><input type=text size=20  name=name value=\"$data[name]\"></td></tr>
<tr><td colspan=2 align=center><input type=submit value=' $phrases[add_button] '></td></tr>";
print "</table></center>";

}


//---------- Edit File Field -------------
if($action=="urls_fields_edit"){

    if_admin("urls_fields");
$id=intval($id);

$qr = db_query("select * from songs_urls_fields where id='$id'");

if(db_num($qr)){
$data = db_fetch($qr);
print "<center><form action=index.php method=post>
<input type=hidden name=action value='urls_fields_edit_ok'>
<input type=hidden name=id value='$id'>
<table width=80% class=grid>";
print "
<tr><td><b>$phrases[the_name]</b> </td><td><input type=text size=20  name=name value=\"$data[name]\"></td></tr>


<tr><td><b>$phrases[the_order]</b> </td><td><input type=text size=3  name=ord value=\"$data[ord]\"></td></tr>
</table>
<br>

<table width=80% class=grid>
<tr><td><b>$phrases[show_download_icon]</b></td><td>";
print_select_row('show_download',array($phrases['no'],$phrases['yes']),$data['show_download']);
print "</td></tr>
<tr><td><b>$phrases[the_download_icon]</b> </td><td><input type=text size=30  name='download_icon' value=\"$data[download_icon]\" dir=ltr></td></tr>  
<tr><td><b>$phrases[download_icon_alt]</b> </td><td><input type=text size=30  name='download_alt' value=\"$data[download_alt]\"></td></tr>  

</table>
<br>
<table width=80% class=grid>
<tr><td><b>$phrases[show_listen_icon]</b></td><td>";
print_select_row('show_listen',array($phrases['no'],$phrases['yes']),$data['show_listen']);
print "</td></tr>
<tr><td><b>$phrases[the_listen_icon]</b> </td><td><input type=text size=30  name='listen_icon' value=\"$data[listen_icon]\" dir=ltr></td></tr>
<tr><td><b>$phrases[listen_icon_alt]</b> </td><td><input type=text size=30  name='listen_alt' value=\"$data[listen_alt]\"></td></tr>
<tr><td><b>$phrases[listen_file_mime]</b> </td><td><input type=text size=30  name='listen_mime' value=\"$data[listen_mime]\"></td></tr>
<tr><td><b>$phrases[listen_file_name]</b> </td><td><input type=text size=30  name='listen_name' value=\"$data[listen_name]\"></td></tr>
<tr><td><b>$phrases[listen_file_content]</b></td><td>
<textarea name='listen_content' rows=10 cols=40 dir=ltr>$data[listen_content]</textarea></td></tr>

</table>

<br>
<table width=80% class=grid>
<tr><td colspan=2 align=center><input type=submit value=' $phrases[edit] '></td></tr>";
print "</table></center>";
}else{
print "<center><table width=70% class=grid>";
print "<tr><td align=center>$phrases[err_wrong_url]</td></tr>";
print "</table></center>";
}

}
?>