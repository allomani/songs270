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

echo "<html dir=$global_dir>\n";
print "<META http-equiv=Content-Language content=\"$settings[site_pages_lang]\">
<META http-equiv=Content-Type content=\"text/html; charset=$settings[site_pages_encoding]\">";
?>
<? print "<title>$phrases[uploader_title]</title>\n";?>
<link href="images/style.css" type=text/css rel=stylesheet>
<script src='js.js' type="text/javascript" language="javascript"></script>
<br>
<?
if (check_login_cookies()) {

if($settings['uploader']){  
        
if(($_FILES['datafile']['name'] || $url) && $folder && $f_name){
   $upload_folder = $settings['uploader_path']."/$folder" ;


     if(!$upload_folder || !file_exists(CWD ."/$upload_folder")){
     print_admin_table("<center>$phrases[err_wrong_uploader_folder]</center>");
     die();
      }

 require_once(CWD. "/includes/class_save_file.php");  
      
   //--------- interial file upload process -------------

   if($external_url !=1){
   
   $imtype = file_extension($_FILES['datafile']['name']);

if(in_array($imtype,$upload_types)){

if($_FILES['datafile']['error']==UPLOAD_ERR_OK){ 
    
$fl = new save_file($_FILES['datafile']['tmp_name'],$upload_folder,$_FILES['datafile']['name']);

if($fl->status){
$saveto_filename =  $fl->saved_filename;
if($default_uploader_chmod){@chmod(CWD . "/". $saveto_filename,$default_uploader_chmod);}   
}else{
print_admin_table("<center>".$fl->last_error_description."</center>");
die();    
}

  }else{
$upload_max = convert_number_format(ini_get('upload_max_filesize'));
$post_max = (convert_number_format(ini_get('post_max_size'))/2) ;

     print_admin_table("<center>Uploading Error , Make Sure that file size is under ".iif($upload_max < $post_max,convert_number_format($upload_max,2,true),convert_number_format($post_max,2,ture))."</center>");  
  die();
  }

}else{
print_admin_table("<center>$phrases[this_filetype_not_allowed]</center>");
die();
}

//---------------- import from external url ---------
   }else{
 
  //---- extension check -----
  $imtype = file_extension($url);
  if(in_array($imtype,$upload_types)){

  $fl = new save_file($url,$upload_folder);
   
if($fl->status){
$saveto_filename =  $fl->saved_filename;
if($default_uploader_chmod){@chmod(CWD . "/". $saveto_filename,$default_uploader_chmod);}   
}else{
print_admin_table("<center>".$fl->last_error_description."</center>");
die();    
}


     }else{
     print_admin_table("<center>$phrases[this_filetype_not_allowed]</center>");
die();
}
   }




//---------- resize pic -----------
if($resize && $saveto_filename){
$uploader_thumb_width = intval($uploader_thumb_width);
$uploader_thumb_hieght = intval($uploader_thumb_hieght);

if($uploader_thumb_width <=0){$uploader_thumb_width=100;}
if($uploader_thumb_hieght <=0){$uploader_thumb_hieght=100;}

	$thumb_saved =  create_thumb($saveto_filename,$uploader_thumb_width,$uploader_thumb_hieght,$fixed);
    if($thumb_saved){
 	 @unlink(CWD . "/". $saveto_filename);
 	 $saveto_filename =   $thumb_saved ;
     if($default_uploader_chmod){@chmod(CWD . "/". $saveto_filename,$default_uploader_chmod);}
    }
	}



print "<script>
";
   
if($frm){
print "opener.document.forms['".$frm."'].elements['" . $f_name . "'].value = \"".$saveto_filename."\";" ;
        }else{
print "opener.document.forms['sender'].elements['" . $f_name . "'].value = \"".$saveto_filename."\";";

if($auto_thumb){
print "opener.document.forms['sender'].elements['" . $thmb_f_name . "'].value = \"".$thumb_filename."\";";
	}
   }

print "
window.close();

</script>\n";



}else{



$folder = htmlspecialchars($folder);
$f_name = htmlspecialchars($f_name);
$frm = htmlspecialchars($frm);

print "
<center>
<table width=90% class=grid>
<tr><td align=center>
<form action='uploader.php' method=post enctype=\"multipart/form-data\">
<center><table width=90%><tr><td>
<input type='radio' name='external_url' value=0 checked onClick=\"show_uploader_options(0);\">$phrases[local_file_uploader]  </td>
<td><input type='radio' name='external_url' value=1 onClick=\"show_uploader_options(1);\">$phrases[external_file_uploader]</td></tr></table></center>


<input type=hidden name=folder value='$folder'>
<input type=hidden name=f_name value='$f_name'>
<input type=hidden name=frm value='$frm'>
<fieldset style=\"width: 90%; padding: 2 \" id=file_field>
<b> $phrases[the_file]  : </b><input type=file dir=ltr size=25 name=datafile>";

$upload_max = convert_number_format(ini_get('upload_max_filesize'));
$post_max = (convert_number_format(ini_get('post_max_size'))/2) ;


if($upload_max || $post_max){
print "Max: ".iif($upload_max < $post_max,convert_number_format($upload_max,2,true),convert_number_format($post_max,2,ture))." ";
}
print "</fieldset>

<fieldset style=\"width: 90%; padding: 2 ;display:none\" id=url_field>
<b> $phrases[the_url]  : </b><input type=text dir=ltr size=30 name=url value='http://'>
</fieldset>
";



print "<fieldset style=\"width: 90%; padding: 2 \">
<input name='resize' type=checkbox value='1'>
$phrases[auto_photos_resize]  ($phrases[cp_photo_resize_width] : <input type=text name=uploader_thumb_width size=2 value=\"$settings[uploader_thumb_width]\"> &nbsp;&nbsp;$phrases[cp_photo_resize_hieght]: <input type=text name=uploader_thumb_hieght size=2 value=\"$settings[uploader_thumb_hieght]\"> <input type=\"checkbox\" name=\"fixed\" value=1>$phrases[fixed])
</fieldset>";

          print "<br>
          <fieldset style=\"width: 90%; padding: 2 \">
<input type=submit value=' $phrases[upload_file_do] '>
</fieldset>
</form>\n ";

$count = count($upload_types);
for ($i=0; $i<$count; $i++) {
$allowed_types .= "$upload_types[$i] &nbsp;";
}

print "<br>
$phrases[allowed_filetypes] :
<font color='#CE0000'>$allowed_types</font>\n

</td></tr></table></center>";

 }
}else{
        print_admin_table("<center>  $settings[uploader_msg] </center> ","90%") ;
        }
}else{
print_admin_table("<center>$phrases[please_login_first]</center>");
     }



     print "</html>";
     ?>