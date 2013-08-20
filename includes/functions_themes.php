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

$templates_cache = array(); 


//----------- get template ----------------
function get_template($name,$tfind="",$treplace=""){
 global $styleid,$templates_cache ;

$name=strtolower($name) ;

if(isset($templates_cache[$name])){
    
    $content = $templates_cache[$name] ;
}else{
$qr = db_query("select content from songs_templates where name like '$name' and cat='$styleid'");
 
 if(db_num($qr)){
     
     $data = db_fetch($qr);
    $content = $data['content'] ;
    $templates_cache[$name] = $data['content'];
    unset($data);
   }else{
   $content =  "<b>Error : </b> Template ".htmlspecialchars($name)." Not Exists <br>";
       }
}

return iif(($tfind || $tfind=="0")&&($treplace || $treplace=="0"),str_replace($tfind,$treplace,$content),$content) ;
  
}

//----------  templates cache --------------
function templates_cache($names){
    global $templates_cache,$styleid;
    
if(!is_array($names)){$names[]=$names;}

$sql = "select name,content from songs_templates where name IN (";

for($i=0;$i<count($names);$i++){
$sql .= "'".$names[$i]."'".iif($i < count($names)-1,",");    
}

$sql .= ") and cat='$styleid'";

$qr = db_query($sql);
while($data=db_fetch($qr)){
$template_name = strtolower($data['name']);    
$templates_cache[$template_name] = $data['content'];   
}
}


function print_style_selection(){
global $styleid;
$qr=db_query("select * from songs_templates_cats where selectable=1 order by id asc");
if(db_num($qr)){
print "<select name=styleid onChange=\"window.location='index.php?styleid='+this.value;\">";
while($data =db_fetch($qr)){
print "<option value=\"$data[id]\"".iif($styleid==$data['id']," selected").">$data[name]</option>";
}
print "</select>";
}
}

$theme['header'] = get_template("header") ;
$theme['footer'] = get_template("footer") ;
$theme['table'] = explode("{content}",get_template("table")) ;
$theme['block'] = explode("{content}",get_template("block")) ;

$theme['table_open'] = $theme['table'][0] ;
$theme['table_close'] = $theme['table'][1] ;

$theme['block_open'] = $theme['block'][0] ;
$theme['block_close'] = $theme['block'][1] ;

function site_header(){
global $theme,$sitename,$phrases,$settings,$keyword,$action,$id,$op,$cat,$section_name,$sec_name,$meta_description,$meta_keywords,$title_sub,$album_id;
              
//------ Songs Title ---------------
if($action == "songs" && $id){
$qr = db_query("select name from songs_singers where id='$id'");
if(db_num($qr)){
$data = db_fetch($qr) ;
$title_sub = "$data[name]" ;
if($album_id){
$album_id = intval($album_id);    
$data_album = db_qr_fetch("select name from songs_albums where id='$album_id'");
$title_sub .= iif($data_album['name']," - ".$data_album['name'],"");
}
        }else{
 $title_sub = "" ;
 }
 }
//------ Lyrics Title ---------------
if($action == "lyrics" && $id){
$qr = db_query("select name from songs_songs where id='$id'");
if(db_num($qr)){
$data = db_fetch($qr) ;
$title_sub = "$phrases[lyrics] : $data[name]" ;
        }else{
 $title_sub = "" ;
 }
 }
//------ Songs Cats Title ---------------
if($action == "browse" && $op=="cat" && $id){
$qr = db_query("select name from songs_cats where id='$id'");
if(db_num($qr)){
$data = db_fetch($qr) ;
$title_sub = "$data[name]" ;
        }else{
 $title_sub = "" ;
 }
 }
//------ Videos Cats Title ---------------
if($action == "browse_videos" && $cat){
$qr = db_query("select name from songs_videos_cats where id='$cat'");
if(db_num($qr)){
$data = db_fetch($qr) ;
$title_sub = "$data[name]" ;
        }else{
 $title_sub = "" ;
 }
 }
 //------ News Title ---------------
if($action == "news"){
$qr = db_query("select title from songs_news where id='$id'");
if(db_num($qr)){
$data = db_fetch($qr) ;
$title_sub = "$data[title]" ;
        }else{
 $title_sub = "$phrases[the_news]" ;
 }
 }
  //------ Pages Title ---------------
if($action == "pages"){
$qr = db_query("select title from songs_pages where id='$id'");
if(db_num($qr)){
$data = db_fetch($qr) ;
$title_sub = "$data[title]" ;
        }else{
 $title_sub = "$phrases[the_pages]" ;
 }
 }
   //------ Search Title ---------------
if($action == "search" && $keyword){

$title_sub = $phrases['the_search'] ;
$meta_description = htmlspecialchars($keyword);
$keys_arr = explode(" ",htmlspecialchars($keyword));
if(count($keys_arr)){
    foreach($keys_arr as $value){
    $meta_keywords .= $value.",";
    }
    unset($keys_arr);
}else{
    $meta_keywords = htmlspecialchars($keyword);
}    

 }
//-------------------------------------
//if($section_name){
//$sec_name = " -  $section_name" ;
       // }
   
 if(!$meta_description){ $meta_description= iif($title_sub,$title_sub,$sitename);}
  if(!$meta_keywords){$meta_keywords = iif($title_sub,$title_sub,$sitename);}
   
 // if($title_sub){ $title_sub = " -  $title_sub";}
 
compile_template(get_template('page_head'));
if(COPYRIGHTS_TXT_ADMIN){ 
print "
<META name=\"Developer\" content=\"www.allomani.com\" >";
}
print "
</HEAD>
";

compile_template($theme['header']);

print get_template("js_functions");
}

function site_footer (){
global $theme;
compile_template($theme['footer']);
}


function open_block($table_title="",$template=0){
global $theme;
if(!$template || $template == 0){
      $table_content = $theme['block_open'];
      }else{
      $qr = db_query("select * from songs_templates where id='$template'");
     if(db_num($qr)){
    $data = db_fetch($qr);
    $custom_table_open = split("{content}",$data['content']) ;
     $table_content =   $custom_table_open[0];

              }else{
         $table_content = $theme['block_open'];
                      }
              }

if($table_title){

        $table_content = str_replace("{title}","<center><span class=title>$table_title</span></center>", $table_content);
         $table_content = str_replace("{new_line}","<br>",$table_content);
        }else{
            $table_content = str_replace("{title}","", $table_content);
            $table_content = str_replace("{new_line}","",$table_content);
                }

print $table_content ;
}

function close_block($template=0){
global $theme;

if(!$template || $template == 0){
      $table_content = $theme['block_close'];
      }else{
      $qr = db_query("select * from songs_templates where id='$template'");
     if(db_num($qr)){
    $data = db_fetch($qr);
    $custom_table_close = split("{content}",$data['content']) ;
     $table_content =   $custom_table_close[1];
              }else{
          $table_content = $theme['block_close'];
                      }
              }

print $table_content;
}


function open_table($table_title="",$template=0){
global $theme;

if(!$template || $template == 0){
      $table_content = $theme['table_open'];
      }else{
      $qr = db_query("select * from songs_templates where id='$template'");
     if(db_num($qr)){
    $data = db_fetch($qr);
    $custom_table_open = split("{content}",$data['content']) ;
     $table_content =   $custom_table_open[0];

              }else{
         $table_content = $theme['table_open'];
                      }
              }
if($table_title){

        $table_content = str_replace("{title}","<center><span class=title>$table_title</span></center>",$table_content);
        $table_content = str_replace("{new_line}","<br>",$table_content);
        }else{
            $table_content = str_replace("{title}","", $table_content);
              $table_content = str_replace("{new_line}","",$table_content);
                }

print $table_content ;
}

function close_table($template=0){
global $theme;

if(!$template || $template == 0){
      $table_content = $theme['table_close'];
      }else{
      $qr = db_query("select * from songs_templates where id='$template'");
     if(db_num($qr)){
    $data = db_fetch($qr);
    $custom_table_close = split("{content}",$data['content']) ;
     $table_content =   $custom_table_close[1];
              }else{
          $table_content = $theme['table_close'];
                      }
              }

print $table_content;
}



?>