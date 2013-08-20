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

/* Edited : 17-11-2009
# $full_text_search added
####
### Edited 23-11-2009
# impove queries in browse singers #
# debug support #
###*/

require("global.php");


//----------------- Disable Browsing ------------------
if($settings['enable_browsing']!="1"){
if(check_login_cookies()){
print "<table width=100% dir=$global_dir><tr><td><font color=red> $phrases[site_closed_for_visitors] </font></td></tr></table>";
}else{
print "<center><table width=50% style=\"border: 1px solid #ccc\"><tr><td> $settings[disable_browsing_msg] </td></tr></table></center>";
die();
}
}

//---------------- set vote expire ------------------------
if($action=="vote_add" && $vote_id){
if(!$settings['votes_expire_hours']){$settings['votes_expire_hours'] = 24 ; }
   if(!$HTTP_COOKIE_VARS['songs_vote_added']){
  setcookie('songs_vote_added', "1" , time() + ($settings['votes_expire_hours'] * 60 * 60),"/");
  }
        }
//----------------------------------------------------------


site_header();

  if(!$blocks_width){
            $blocks_width = "17%" ;
            }

print "<table border=\"0\" width=\"100%\"  style=\"border-collapse: collapse\" dir=ltr>

         <tr>" ;
        //------------------------- Block Pages System ---------------------------
        function get_pg_view(){
                global $pg_view ,$action ;
        if($action=="votes" || $action == "vote_add"){
          $pg_view = "votes" ;
          }elseif(!$action){
           $pg_view = "main" ;
        }else{
        $pg_view = $action ;
        }
        if(!$pg_view){$pg_view = "main" ;}
        }
        //--------------------------------------------------------------------------
           get_pg_view();
           if(!in_array($pg_view,$actions_checks)){$pg_view = "main" ;}
       //----------------------- Right Content --------------------------------------------

      $xqr=db_query("select * from songs_blocks where pos='l' and active=1 and pages like '%$pg_view,%' order by ord");
      if(db_num($xqr)){
        print "<td width='$blocks_width' valign=\"top\" dir=$global_dir>
        <center><table width=100%>" ;

        $adv_c = 1 ;
         while($xdata = db_fetch($xqr)){

        print "<tr>
                <td  width=\"100%\" valign=\"top\">";
                open_block($xdata['title'],$xdata['template']);


                 run_php($xdata['file']);


                close_block($xdata['template']);

                print "</td>
        </tr>";

         //---------------------------------------------------
        $adv_menu_qr = db_query("select * from songs_banners where type='menu' and menu_id='$adv_c' and menu_pos='l' and active=1 and pages like '%$pg_view,%' order by ord");

        if(db_num($adv_menu_qr)){
                while($data = db_fetch($adv_menu_qr)){
                db_query("update songs_banners set views=views+1 where id=$data[id]");
                print "<tr>
                <td  width=\"100%\" valign=\"top\">";
                 if($data['c_type']=="code"){
    compile_template($data['content']);
    }else{
                open_block();
             print "<center><a href='banner.php?id=$data[id]' target=_blank><img src='$data[img]' border=0 alt='$data[title]'></a></center>";
                close_block();
                }
                print "</td>
        </tr>";
        }
               }
            ++$adv_c ;
        //----------------------------------------------------
           }
print "</table></center></td>";

unset($xdata,$adv_menu_qr,$data,$adv_c);
}
unset($xqr); 

print "<td  valign=\"top\" dir=$global_dir>";


//---------------------  Banners ----------------------------
$qr = db_query("select * from songs_banners where type='header' and active=1 and pages like '%$pg_view,%' order by ord");
while($data = db_fetch($qr)){
db_query("update songs_banners set views=views+1 where id=$data[id]");
if($data['c_type']=="code"){
compile_template($data['content']);
    }else{
print "<center><a href='banner.php?id=$data[id]' target=_blank><img src='$data[img]' border=0 alt='$data[title]'></a><br><br></center>";
}
        }
 print "<br>";

//-------------------------- CENTER CONTENT ---------------------------------------------
function print_letters_singers(){
print get_template('letters_singers');
}

function print_letters_songs(){
print get_template('letters_songs');
}

//------------ Letters ------------------------
if(!$action || $action=="songs" || $action=="browse"){
if($settings['letters_songs'] || $settings['letters_singers']){
 open_table();
 if($settings['letters_songs'] && $settings['letters_singers']){
 print "<table width=100%><tr><td>  $phrases[the_singers] : </td><td>";
        print_letters_singers();
        print "</td></tr><tr><td>  $phrases[the_songs] : </td><td>";
        print_letters_songs();
        print "</td></tr></table>";
         }else{
    if($settings['letters_songs']){
            print_letters_songs();
            }
        if($settings['letters_singers']){
            print_letters_singers();
            }
  }
 close_table();

        }
}




     get_pg_view();
         if(!in_array($pg_view,$actions_checks)){$pg_view = "none" ;}

     //--------- open banners ----------//
    $qr= db_query("select * from songs_banners where type='open' and active=1 and pages like '%$pg_view,%' order by ord");
    $bnx = 0 ;
   while($data = db_fetch($qr)){

    if ($data['url']){
     db_query("update songs_banners set views=views+1 where id='$data[id]'");
   print "<script>
   banner_pop_open(\"$data[url]\",\"displaywindow_$bnx\");
       </script>\n";
         $bnx++;
          }

    }
    
    //----------- close banners ----------- //
   $data= db_qr_fetch("select * from songs_banners where type='close' and active=1 and pages like '%$pg_view,%'");

    if ($data['url']){
         db_query("update songs_banners set views=views+1 where id='$data[id]'");
   print "<script>
   function pop_close(){
       banner_pop_close(\"$data[url]\",\"displaywindow_close\");
        }
        </script>\n";


            }else{
             print "<script>
   function pop_close(){
       }
        </script>\n";
                    }




 $yqr=db_query("select * from songs_blocks where pos='c' and active=1 and pages like '%$pg_view,%' order by ord");
  $adv_c = 1 ;
         while($ydata = db_fetch($yqr)){


                open_table($ydata['title'],$ydata['template']);


           run_php($ydata['file']);


                close_table($ydata['template']);



                       //---------------------------------------------------

        $adv_menu_qr = db_query("select * from songs_banners where type='menu' and menu_id='$adv_c' and menu_pos='c' and active=1 and pages like '%$pg_view,%' order by ord");
       if(db_num($adv_menu_qr)){
                $data = db_fetch($adv_menu_qr) ;
                db_query("update songs_banners set views=views+1 where id=$data[id]");
            if($data['c_type']=="code"){
  compile_template($data['content']);
    }else{
             print "<center><a href='banner.php?id=$data[id]' target=_blank><img src='$data[img]' border=0 alt='$data[title]'></a></center><br>";
            }
               }
            ++$adv_c ;
        //----------------------------------------------------
                    }
                 
  unset($yqr,$ydata,$adv_menu_qr,$data,$adv_c); 
  
  //--------------------------- Video Browse ---------------------------------------
  if($action=="browse_videos"){
 $cat=intval($cat);
 
 compile_hook('videos_start');
 
   $dir_data['cat'] = intval($cat) ;
while($dir_data['cat']!=0){
   $dir_data = db_qr_fetch("select name,id,cat from songs_videos_cats where id='$dir_data[cat]'");


        $dir_content = "<a href='".get_template('links_browse_videos','{id}',$dir_data['id'])."'>$dir_data[name]</a> / ". $dir_content  ;

        }
   print "<p align=$global_align><img src='images/arrw.gif'> <a href='".get_template('links_browse_videos','{id}','0')."'>$phrases[the_videos] </a> / $dir_content " . "<b>$data[name]</b></p>";

  compile_hook('videos_after_path_links'); 
          
//-------- cats -------
    $qr2 = db_query("select * from songs_videos_cats where cat='$cat' and active=1 order by ord asc");
if(db_num($qr2)){
   
    open_table();
    print "<table width=100%><tr>";
while($data = db_fetch($qr2)){
    if ($c==$settings['songs_cells']) {
print "  </tr><TR>" ;
$c = 0 ;
}
   ++$c ;

   print "<td><center><a href='".get_template('links_browse_videos','{id}',$data['id'])."'>
            <img border=0 src='".get_image($data['img'],"images/folder.gif")."'>
<br>$data[name] </a>


 </center>    </td>";

}
print "</tr></table>";
close_table();
  }else{
    
      $no_cats = true;
  }
 //------------------------
 
 
    //----------------------
   $start = intval($start);
   $perpage = $settings['videos_perpage'];
   $page_string = get_template('links_browse_videos_w_pages','{id}',$cat);
   //---------------------
   
     
      
    $qr = db_query("select songs_videos_data.* from songs_videos_data,songs_videos_cats where songs_videos_data.cat=songs_videos_cats.id and songs_videos_cats.active=1 and songs_videos_cats.id='$cat' order by songs_videos_data.id DESC limit $start,$perpage");
  
   
       
        
    if(db_num($qr)){
        
        $videos_count  = db_qr_fetch("select count(id) as count from songs_videos_data where cat='$cat'");
         $data_cat = db_qr_fetch("select name from songs_videos_cats where id='$cat'");
         
        open_table($data_cat['name']);
    print "<center><table width=100%>" ;
    $c=0;
        while($data = db_fetch($qr)){



if ($c==$settings['songs_cells']) {
print "  </tr><TR>" ;
$c = 0 ;
}
    ++$c ;

    compile_template(get_template('browse_videos'));


           }
           print "</tr></table></center>";
           close_table();
           
           
//-------------------- pages system ------------------------
print_pages_links($start,$videos_count['count'],$perpage,$page_string); 
//-----------------------------
 
            }else{
                if($no_cats){
                 open_table();    
                    print "<center> $phrases[err_no_videos] </center>";
                    close_table();
                }
                    }
          

 compile_hook('videos_end');         
  }
  
  

 //--------------------------- Browse Functions -----------------------------------
 if ($action =="browse"){

 compile_hook('singers_start');
     
 //-------- Show Cats -------
 if(!$id && $op !="letter"){
$qr2 = db_query("select * from songs_cats where active=1 order by ord asc");
if(db_num($qr2)){
  $c=0;
    $content.= "<table width=100%><tr>";
while($data = db_fetch($qr2)){

    if ($c==$settings['songs_cells']) {
 $content.= "  </tr><TR>" ;
$c = 0 ;
}
   ++$c ;
   
    $content.= "<td><center><a href='".get_template('links_browse_cat','{id}',$data['id'])."'>
            <img border=0 src='".get_image($data['img'],"images/folder.gif")."'>
<br>$data[name] </a>


 </center>    </td>";

}
 $content.= "</tr></table>";

}
}
//------------------------


  if ($op != "letter"){
  $data_title = db_qr_fetch("select name from songs_cats where id='$id'");
     $title = $data_title['name'];
      }



   if ($op=="letter"){
   
   if(ereg("^([a-zA-Z])*$", $letter)){    
   $letter_query = "(songs_singers.name like '".db_clean_string(strtolower($letter))."%' or songs_singers.name like '".db_clean_string(strtoupper($letter))."%')";
   }else{
        $letter_query = "binary songs_singers.name like '".db_clean_string($letter)."%'";
   }
   
   
   $qr = db_query("select songs_singers.* from songs_singers,songs_cats where songs_singers.active=1 and songs_singers.cat=songs_cats.id and songs_cats.active=1 and $letter_query order by binary songs_singers.name ASC");
     $title = strip_tags($letter) ;
          }else{

        $qr = db_query("select songs_singers.* from songs_singers,songs_cats where songs_singers.active=1 and songs_singers.cat=songs_cats.id and songs_cats.active=1 and songs_cats.id='$id' order by binary songs_singers.name ASC");
     $data_title = db_qr_fetch("select name from songs_cats where id='$id' and active=1");
     $title = $data_title['name'];
                  }

        open_table($title);

       if (db_num($qr)){
if($settings['singers_groups'] && $op !="letter"){
//----------------------- Letters Groups System --------------------------------
       $lt_arr[0] = array('«','√','≈','¬');
       $lt_arr[1] = array('»',' ','À');
       $lt_arr[2] = array('Ã','Õ','Œ');
       $lt_arr[3] = array('œ','–','—','“');
       $lt_arr[4] = array('”','‘');
       $lt_arr[5] = array('’','÷','ÿ','Ÿ');
       $lt_arr[6] = array('⁄','€');
       $lt_arr[7] = array('›','ﬁ');
       $lt_arr[8] = array('ﬂ','·');
       $lt_arr[9] = array('„','‰');
       $lt_arr[10] = array('Â');
       $lt_arr[11] = array('Ê','Ì');
       $lt_arr[12] = array('A','B','C');
       $lt_arr[13] = array('D','E','F');
       $lt_arr[14] = array('G','H','I');
       $lt_arr[15] = array('J','K','L');
       $lt_arr[16] = array('M','N');
       $lt_arr[17] = array('O','P','Q');
       $lt_arr[18] = array('R','S','T');
       $lt_arr[19] = array('U','V','W');
       $lt_arr[20] = array('X','Y','Z');

       $content = "" ;

while($data = db_fetch($qr)){
 $lt_found = "" ;
for($cx=0;$cx < count($lt_arr) ;++$cx){
if(in_array(substr(strtoupper($data['name']),0,1),$lt_arr[$cx])){
          $data_arr[$cx][] = $data ;
     //   $data_arr[$cx][] = $data['id'] ;
        $lt_found = "y" ;
        break;
  }
}
if($lt_found !="y"){
 // $data_arr2[] = $data['id'] ;
 $data_arr2[] = $data ;  
  }

   }
  
  unset($data);  
 //------------------ end sync data -------------------


 for($cy = 0;$cy < count($lt_arr) ;++$cy){

       $data_arr_main = $data_arr[$cy];
      if(count($data_arr_main)){
      $cur_str = "";
 foreach($lt_arr[$cy] as $ltx){
          $cur_str .= "$ltx ";
          }
        $content .= "<span align=right class=title>$cur_str</span><hr class=separate_line 1px\" size=\"1\">";

        $content .=  "<table width=100%><tr>" ;

   $c = 0 ;
// foreach($data_arr_main as $data_arr_sub){

  //$qr2 = db_query("select * from songs_singers where id='$data_arr_sub'");

//while($data = db_fetch($qr2)){
  //----------


    //   $singer_songs_count = db_qr_fetch("select count(id) as count from songs_songs where album='$data[id]'");
   //----------

   foreach($data_arr_main as $data){  
if ($c==$settings['songs_cells']) {
$content .= "  </tr><TR>" ;
$c = 0 ;
}
   ++$c ;

          $content .= "<td>";
          ob_start();
          compile_template(get_template('browse_singers'));
          $content .=ob_get_contents(); 
          ob_end_clean();
          
          $content .= "</td>";




             }

  //  }
   $content .= "</tr></table>";

     }
    }
    
    unset($data);
    //---------------------- others array --------------------
    if(count($data_arr2)){
      $content .= "<span align=right class=title>$phrases[singers_other_letters]</span><hr class=separate_line size=\"1\">";

        $content .=  "<table width=100%><tr>" ;

   $c = 0 ;
 //foreach($data_arr2 as $data_others){

 // $qr2 = db_query("select * from songs_singers where id='$data_others'");

//while($data = db_fetch($qr2)){
  //----------


     //  $singer_songs_count = db_qr_fetch("select count(id) as count from songs_songs where album='$data[id]'");
   //----------
  foreach($data_arr2 as $data){  
      
if ($c==$settings['songs_cells']) {
$content .= "  </tr><TR>" ;
$c = 0 ;
}
   ++$c ;

          $content .= "<td>";
           ob_start();
          compile_template(get_template('browse_singers'));
          $content .=ob_get_contents(); 
          ob_end_clean();
          $content .= "</td>";
             }

   // }
   $content .= "</tr></table>";
   }
   
   unset($data);
//---------------------------- END Letters Groups System -------------------
}else{
  $content .=  "<table width=100%><tr>" ;
 while($data = db_fetch($qr)){
        // $singer_songs_count = db_qr_fetch("select count(id) as count from songs_songs where album='$data[id]'");
   //----------

if ($c==$settings['songs_cells']) {
$content .= "  </tr><TR>" ;
$c = 0 ;
}
   ++$c ;

          $content .= "<td>";
          ob_start();
          compile_template(get_template('browse_singers'));
          $content .=ob_get_contents(); 
          ob_end_clean();
          $content .= "</td>";
   }
    $content .= "</tr></table>";

        }
    }
             if(trim($content)){
                     print $content ;
                     }else{
                       
                      print "<center>  $phrases[err_no_cats] </center>";
                    
                      }



                    close_table();
                    
 compile_hook('singers_end');                   
         }

 // -------------------- Show Songs --------------------------------------------------
 if($action =="songs"){
 compile_hook('songs_start');
 
 $id=intval($id);
 
 if(!$op){
     $qr=db_query("select * from songs_singers where id='$id'");   
     
     if(db_num($qr)){
         $data = db_fetch($qr);
         $data3 = db_qr_fetch("select count(id) as count from songs_songs where album='$id'");
         $data_albums_count = db_qr_fetch("select count(id) as count from songs_albums where cat='$id'");
          $data4 = db_qr_fetch("select date from songs_songs where album='$id' order by date DESC limit 1");
         $hdr = db_qr_fetch("select * from songs_cats where id='$data[cat]'");

   print "<p> <img src='images/album.gif' border=0> <a class=path_link href='".get_template('links_browse_cat','{id}',$hdr['id'])."'>$hdr[name]</a> / <a class=path_link href='".get_template('links_browse_songs','{id}',$data['id'])."'>$data[name]</a> / ";
  
   if(isset($album_id)){
      
  if($album_id == "others" || ($data_albums_count['count'] && $album_id == 0) ){
   
     print "$phrases[another_songs]" ;
    }else{
   $album_id = intval($album_id);              
   $album_name = db_qr_fetch("select name,img from songs_albums where id='$album_id'");
   print "$album_name[name]";
   }
           }
   print" </p>" ;

  
   compile_hook('songs_after_path_links');
   //-------------- Get Album Image ------------------
           if ($album_name['img']){
     $img_url = $album_name['img'];
    }else{
  if($data['img']){
              $img_url = $data['img'] ;
            }else{

    $img_url = "images/no_pic.gif" ;
    }
    }
  //---------------------------------------------------

         open_table($data['name']);
        print "<table width=100%><tr><td width=20%>";
        compile_hook('songs_before_singer_info_img');
        print "<img src='$img_url' border=0>";
        compile_hook('songs_after_singer_info_img');
        print "</td>
        <td> ";
        compile_hook('songs_before_singer_info_text');
        if($data4['date']){ print "<b>$phrases[last_update]  : </b>".substr($data4['date'],0,10)."  <br>" ;  }
        if($data_albums_count['count']){ print "<b>$phrases[the_albums_count]  : </b>$data_albums_count[count] <br>" ;}
        print " <b> $phrases[the_songs_count] : </b>$data3[count]";
        compile_hook('songs_after_singer_info_text');
        print " </td></tr></table>";
close_table();

compile_hook('songs_after_singer_table');

}}

  if($data_albums_count['count'] && !isset($album_id)){
      compile_hook('songs_before_albums_table');  
    $qr=db_query("select * from songs_albums where cat='$id' order by id DESC");

  open_table();
  print "<table width=100%><tr>";
$c=0 ;

  while($data = db_fetch($qr))
  {



if ($c==$settings['songs_cells']) {
print "  </tr><TR>" ;
$c = 0 ;
}
 ++$c ;

  $album_songs_count = db_qr_fetch("select count(id) as count from songs_songs where album_id='$data[id]' and album='$id'");
  $album_songs_num = $album_songs_count['count'];
  $data_singer['id']=$id;
 
//  $albums_template = str_replace(array('{singer_id}','{album_id}','{name}','{img}','{songs_count}'),array("$id","$data[id]","$data[name]","$img_url","$album_songs_count[count]"),get_template('browse_albums'));


          print "<td>";
           compile_template(get_template('browse_albums')); 
           print "</td>";

          }

$album2_songs_count = db_qr_fetch("select count(id) as count from songs_songs where album_id=0 and album='$id'");
$album_songs_num = $album2_songs_count['count'];

if($album2_songs_count['count']){

if ($c==$settings['songs_cells']) {
print "  </tr><TR>" ;
}
  ++$c ;

  $data['img'] = 'images/others_songs.gif' ;
  $data['id'] = 'others' ;
  $data['name'] =  $phrases['another_songs'] ;
  $data_singer['id']=$id;
 
 // $albums_template = str_replace(array('{singer_id}','{album_id}','{name}','{img}','{songs_count}'),array("$id","others","$phrases[another_songs]","$img_url","$album2_songs_count[count]"),);


print "<td align=center>";
 compile_template(get_template('browse_albums')); 
print "</td>";
}
            print "</tr></table>";

          close_table();
compile_hook('songs_after_albums_table');
 //--------------------------- Show Songs Start -----------------------------
  }else{

//---- order by vars -------//    
if(!$orderby || !$settings['visitors_can_sort_songs'] || !in_array($orderby,$orderby_checks)){$orderby=($settings['songs_default_orderby'] ? $settings['songs_default_orderby'] : "binary name");}
if(!$sort || !$settings['visitors_can_sort_songs'] || !in_array($sort,array('asc','desc'))){$sort=($settings['songs_default_sort'] ? $settings['songs_default_sort'] : "asc");}

if($orderby == "votes"){
    $orderby_qr = "(votes / votes_total)";
}elseif($orderby=="name"){
    $orderby_qr = "binary name";
}else{$orderby_qr=$orderby;}
//-------------------------//

  //   if($album_id == "others"){$album_id=0;}  
   $album_id=intval($album_id);  
//----------------- start pages system ----------------------
    $start=intval($start);
    
       if($op=="letter"){
       $page_string= "index.php?action=songs&op=letter&letter=$letter&start={start}" ;
       }else{
       $page_string= get_template('links_browse_songs_w_pages',array('{id}','{album_id}','{orderby}','{sort}'),array($id,$album_id,$orderby,$sort));
       }
    $songs_perpage = $settings['songs_perpage'];    
  //------------------------------------------------------
  
    
         

    if($op=="letter"){
        
  if(ereg("^([a-zA-Z])*$", $letter)){    
   $letter_query = "(name like '".db_clean_string(strtolower($letter))."%' or name like '".db_clean_string(strtoupper($letter))."%')";
   }else{
        $letter_query = "binary name like '".db_clean_string($letter)."%'";
   }
   
    
$letter = strip_tags($letter);

$qr = db_query("select * from songs_songs where $letter_query order by $orderby_qr $sort limit $start,$songs_perpage");
$page_result = db_qr_fetch("SELECT count(*) as count from songs_songs where $letter_query");


$lyrics_count = db_qr_fetch("select count(id) as count from songs_songs where $letter_query and trim(lyrics)!='' limit $start,$songs_perpage");
 $lyrics_count= $lyrics_count['count'] ;
          }else{
   
  $qr = db_query("select * from songs_songs where album='$id' and album_id='$album_id' order by $orderby_qr $sort limit $start,$songs_perpage");
  $page_result = db_qr_fetch("SELECT count(*) as count from songs_songs where album='$id' and album_id='$album_id'");
  
 //$lyrics_count = db_qr_fetch("select count(id) as count from songs_songs where album='$id' and album_id='$album_id' and trim(lyrics)!='' limit $start,$songs_perpage");
 
  $lyrics_count = db_qr_fetch("select count(id) as count from songs_songs where album='$id' and album_id='$album_id' and trim(lyrics)!=''");

 $lyrics_count= $lyrics_count['count'] ;
  }

$numrows=$page_result['count'];


  if(db_num($qr)){
compile_hook('songs_before_songs_table');

compile_template(get_template("browse_songs_header"));

//---- comments array ----
unset($comments_arr);
 $qrcm = db_query("select * from songs_comments");
 while($datacm=db_fetch($qrcm)){
     $comments_arr[$datacm['id']] = $datacm;
 }
 
//------------------------------------
$tr_ord=1;
while($data = db_fetch($qr)){

           if($tr_ord ==1){
                   $tr_class="songs_1" ;
                   $tr_ord = 2 ;
                   }else{
                    $tr_class="songs_2";
                    $tr_ord = 1 ;
                           }

        //  $data_comment = db_qr_fetch("select * from songs_comments where id='$data[comment]'");
            $data_comment =   $comments_arr[$data['comment']] ;
            
          print "<tr class='$tr_class'>";

          if($op=="letter"){
          $data_singer = db_qr_fetch("select id,name from songs_singers where id='$data[album]'");
         }


         //------------ sync urls data  ------------//
         $urls_data = sync_urls_data($data['id']);
         
     
          compile_template(get_template('browse_songs'));
         unset($urls_data);
          print "</tr>";
    }
   compile_template(get_template('browse_songs_footer'));
   
   unset($data,$qr,$urls_sets);
//-------------------- pages system ------------------------
print_pages_links($start,$numrows,$songs_perpage,$page_string); 
//------------ end pages system -------------

compile_hook('songs_after_songs_table'); 
    }else{
      open_table();
      print "<center> $phrases[err_no_songs] </center>";
      close_table();
            }
    }

         }
 //------------------------------- Lyrics --------------------------------------
if ($action=="lyrics"){
    compile_hook('lyrics_start');
    
        $qr =  db_query("select * from songs_songs where id='$id'") ;


     if(db_num($qr)){
             $data = db_fetch($qr);

        $data1 = db_qr_fetch("select * from songs_singers where id='$data[album]'");
         $data3 = db_qr_fetch("select count(songs_songs.id) as count from songs_songs,songs_singers where songs_songs.album=songs_singers.id and songs_singers.id='$data[album]'");
         $data_albums_count = db_qr_fetch("select count(songs_albums.id) as count from songs_albums,songs_singers where songs_albums.cat=songs_singers.id and songs_singers.id='$data[album]'");
          $data4 = db_qr_fetch("select songs_songs.date from songs_songs,songs_singers where songs_songs.album=songs_singers.id and songs_singers.id='$data[album]' order by songs_songs.date DESC limit 1");
         $hdr = db_qr_fetch("select songs_cats.* from songs_cats,songs_singers where songs_cats.id=songs_singers.cat and songs_singers.id='$data[album]'");

   print "<p> <img src='images/album.gif' border=0>
   <a href='".get_template('links_browse_cat','{id}',$hdr['id'])."'>$hdr[name]</a> /
   <a href='".get_template('links_browse_songs','{id}',$data1['id'])."'>$data1[name]</a> / $data[name]";


   print" </p>" ;

  compile_hook('lyrics_after_path_links');
  
   //-------------- Get Album Image ------------------
           if ($data2['img']){
     $img_url = $data2['img'];
    }else{
  if($data1['img']){
              $img_url = $data1['img'] ;
            }else{

    $img_url = "images/no_pic.gif" ;
    }
    }
  //---------------------------------------------------

         open_table($data1['name']);
        print "<table width=100%><tr><td width=20%><img src='$img_url' border=0></td>
        <td> ";
        if($data4['date']){ print "<b>$phrases[last_update]  : </b>".substr($data4['date'],0,10)."  <br>" ;  }
        if($data_albums_count['count']){ print "<b>$phrases[the_albums_count]  : </b>$data_albums_count[count] <br>" ;}
        print " <b> $phrases[the_songs_count] : </b>$data3[count] </td></tr></table>";
close_table();




                open_table("$data[name]");
                print "<center>$data[lyrics]</center>";
                close_table();
                }
compile_hook('lyrics_end');                
                 }
//------------------------- Statics --------------------------
if($action=="statics"){
      $year = intval($year);
$month = intval($month);
 require(CWD . '/includes/functions_statics.php');


 //-------- browser and os statics ---------
if($settings['count_visitors_info']){
open_table("$phrases[operating_systems]");
get_statics_info("select * from info_os where count > 0 order by count DESC","name","count");
close_table();

open_table("$phrases[the_browsers]");
get_statics_info("select * from info_browser where count > 0 order by count DESC","name","count");
close_table();

$printed  = 1 ;
}

//--------- hits statics ----------
if($settings['count_visitors_hits']){
$printed  = 1 ;

if (!$year){$year = date("Y");}

open_table("$phrases[monthly_statics_for] $year ");

for ($i=1;$i <= 12;$i++){

$dot = $year;

if($i < 10){$x="0$i";}else{$x=$i;}


$sql = "select * from info_hits where date like '%-$x-$dot' order by date" ;
$qr_stat=db_query($sql);

if (db_num($qr_stat)){
$total = 0 ;
while($data_stat=db_fetch($qr_stat)){
$total = $total + $data_stat['hits'];
}

$rx[$i-1]=$total  ;

}else{
        $rx[$i-1]=0 ;
        }

  }

    for ($i=0;$i <= 11;$i++){
    $total_all = $total_all + $rx[$i];
         }

         if ($total_all !==0){

         print "<br>";

  $l_size = @getimagesize("images/leftbar.gif");
    $m_size = @getimagesize("images/mainbar.gif");
    $r_size = @getimagesize("images/rightbar.gif");


 echo "<table cellspacing=\"0\" cellpadding=\"2\" border=\"0\" align=\"center\">";
 for ($i=1;$i <= 12;$i++)  {

    $rs[0] = $rx[$i-1];
    $rs[1] =  substr(100 * $rx[$i-1] / $total_all, 0, 5);
    $title = $i;

    echo "<tr><td>";



   print " $title:</td><td dir=ltr align='$global_align'><img src=\"images/leftbar.gif\" height=\"$l_size[1]\" width=\"$l_size[0]\">";
    print "<img src=\"images/mainbar.gif\"  height=\"$m_size[1]\" width=". $rs[1] * 2 ."><img src=\"images/rightbar.gif\" height=\"$r_size[1]\" width=\"$l_size[0]\">
    </td><td>
    $rs[1] % ($rs[0])</td>
    </tr>\n";

}
print "</table>";
 }else{
        print "<center>$phrases[no_results]</center>";
        }
  print "<br><center>[ $phrases[the_year] : ";
  $yl = date('Y') - 3 ;
  while($yl != date('Y')+1){
      print "<a href='index.php?action=statics&year=$yl'>$yl</a> ";
      $yl++;
      }
  print "]";
close_table();

if (!$month){
        $month =  date("m")."-$year" ;
        }else{
                $month= "$month-$year";
                }

open_table("$phrases[daily_statics_for] $month ");
$dot = $month;
get_statics_info("select * from info_hits where date like '%$dot' order by date","date","hits");

print "<br><center>
          [ $phrases[the_month] :
          <a href='index.php?action=statics&year=$year&month=1'>1</a> -
          <a href='index.php?action=statics&year=$year&month=2'>2</a> -
          <a href='index.php?action=statics&year=$year&month=3'>3</a> -
          <a href='index.php?action=statics&year=$year&month=4'>4</a> -
          <a href='index.php?action=statics&year=$year&month=5'>5</a> -
          <a href='index.php?action=statics&year=$year&month=6'>6</a> -
          <a href='index.php?action=statics&year=$year&month=7'>7</a> -
          <a href='index.php?action=statics&year=$year&month=8'>8</a> -
          <a href='index.php?action=statics&year=$year&month=9'>9</a> -
          <a href='index.php?action=statics&year=$year&month=10'>10</a> -
          <a href='index.php?action=statics&year=$year&month=11'>11</a> -
          <a href='index.php?action=statics&year=$year&month=12'>12</a>
          ]";
          close_table();
}

if(!$printed){
    open_table();
   print "<center>$phrases[no_results]</center>";
    close_table();
    }

        }

 //------------------------------------- News -----------------------------------
  if($action == "news")
          {
  compile_hook('news_start');

if ($id){
    compile_hook('news_inside_start');
              $qr = db_query("select * from songs_news where id='$id'");
              if(db_num($qr)){
              $data = db_fetch($qr);
       print "<img src='images/arrw.gif'>&nbsp;<a href='".get_template('links_browse_news','{id}',"0")."'> $phrases[the_news] </a><br><br>";
      open_table($data['title']);
     compile_template(get_template('browse_news_inside'));
     close_table();
     }else{
     open_table();
     print "<center>$phrases[err_wrong_url]</center>";
     close_table();
             }
   compile_hook('news_inside_end');
        }else{

  compile_hook('news_outside_start');

          $qr = db_query("select left(date,7) as date from songs_news group by left(date,7)");
          if(db_num($qr) > 1){
          open_table();
          print "<form action=index.php>
          <input type=hidden name=action value='news'>
           $phrases[the_date] : <select name=date>
           <option value=''> $phrases[all] </option>";
          while($data = db_fetch($qr)){
          if($date == $data['date']){$chk="selected" ;}else{$chk="";}

                  print "<option value='$data[date]' $chk>$data[date]</option>";
                  }
                  print "</select>&nbsp;<input type=submit value=' $phrases[view_do] '></form>";
                  close_table();
                  }
    compile_hook('news_outside_after_date');
           //----------------- start pages system ----------------------
    $start=intval($start);
    if(!$date){$date=0;}
       $page_string= get_template('links_browse_news_w_pages','{date}',$date);
         $news_perpage = intval($settings['news_perpage']);
        //--------------------------------------------------------------


  
            open_table("$phrases[the_news_archive]");
            if($date){
            $qr = db_query("select * from songs_news where date like '".db_clean_string($date)."%' order by id DESC limit $start,$news_perpage");
            $page_result = db_qr_fetch("SELECT count(*) as count from songs_news where date like '".db_clean_string($date)."%'");
            }else{
             $qr = db_query("select * from songs_news order by id DESC limit $start,$news_perpage");
            $page_result = db_qr_fetch("SELECT count(*) as count from songs_news");
            }

$numrows=$page_result['count'];


  if(db_num($qr)){
            print "<hr class=separate_line size=\"1\">";
            while ($data = db_fetch($qr)){
  
   compile_template(get_template('browse_news'));
       print "<hr class=separate_line size=\"1\">" ;
                    }
     }else{
             print "<center>$phrases[no_news]</center>" ;
             }
            close_table();
compile_hook('news_outside_before_pages');
//-------------------- pages system ------------------------
print_pages_links($start,$numrows,$news_perpage,$page_string);
//------------ end pages system -------------

compile_hook('news_outside_end');
 }
   compile_hook('news_end');
                  }
  //-------------------------------------------------------------------
  if($action=="contactus"){
      compile_hook('contactus_start');  
          open_table("$phrases[contact_us]");
         print get_template("contactus");
          close_table();
       compile_hook('contactus_end'); 
          }
 // --------------------------- Votes ---------------------------------
  if($action =="votes" || $action == "vote_add"){
   $vote_id = intval($vote_id);
      
      compile_hook('votes_start');  
      
          if ($action=="vote_add")
          {
            if(!$HTTP_COOKIE_VARS['songs_vote_added']){
                  db_query("update songs_votes set cnt=cnt+1 where id='$vote_id'");
                  }else{
                          open_table();

                          print "<center>".str_replace('{vote_expire_hours}',$settings['votes_expire_hours'],$phrases['err_vote_expire_hours'])."</center>" ;
                      close_table();
                      }

          }

          $data_title = db_qr_fetch("select * from songs_votes_cats where active=1");
          open_table("$data_title[title]");


          $sql = "select * from songs_votes where cat=$data_title[id]" ;
          $qr_stat=db_query($sql);


if (db_num($qr_stat)){
while($data_stat=db_fetch($qr_stat)){
$total = $total + $data_stat['cnt'];
}

    if($total){
         print "<br>";

  $l_size = @getimagesize("images/leftbar.gif");
    $m_size = @getimagesize("images/mainbar.gif");
    $r_size = @getimagesize("images/rightbar.gif");

$qr_stat=db_query($sql);
 echo "<table cellspacing=\"0\" cellpadding=\"2\" border=\"0\" align=\"center\">";
while($data_stat=db_fetch($qr_stat)){

    $rs[0] = $data_stat['cnt'];
    $rs[1] =  substr(100 * $data_stat['cnt'] / $total, 0, 5);
    $title = $data_stat['title'];

    echo "<tr><td>";


   print " $title:</td><td dir=ltr align='$global_align'><img src=\"images/leftbar.gif\" height=\"$l_size[1]\" width=\"$l_size[0]\">";
    print "<img src=\"images/mainbar.gif\"  height=\"$m_size[1]\" width=". $rs[1] * 2 ."><img src=\"images/rightbar.gif\" height=\"$r_size[1]\" width=\"$l_size[0]\">
    </td><td>
    $rs[1] % ($rs[0])</td>
    </tr>\n";

}
print "</table>";
}else{
        print "<center> $phrases[no_results] </center>";
        }
}

close_table();

 compile_hook('votes_end'); 
  }
 //------------------------------- Search -------------------------------------

 if($action=="search"){
     
 if($settings['enable_search']){ 
     
  
         $keyword = trim($keyword);
         
        if(strlen($keyword) >= $settings['search_min_letters']){
          
              $keyword = htmlspecialchars($keyword); 
              
                compile_hook('search_start');   
       open_table("$phrases[search_results]" );
       

       if(!$op || $op=="songs"){

        //----------------- start pages system ----------------------
   $start=intval($start);
       $page_string= "index.php?action=search&op=songs&keyword=".urlencode($keyword)."&start={start}" ;
      $songs_perpage = $settings['songs_perpage'] ;
        //--------------------------------------------------------------
   
   
     if($full_text_search && strlen($keyword) >=4){ 
     $qr=db_query("select *,match(name) against('".db_clean_string($keyword,"code","read")."') as score from songs_songs where match(name) against('".db_clean_string($keyword,"code","read")."') order by score desc limit $start,$perpage");
         $page_result=db_qr_fetch("select count(*) as count from songs_songs where match(name) against('".db_clean_string($keyword,"code","read")."')");
     }else{
       
       
     $qr=db_query("select * from songs_songs where name like '%".db_clean_string($keyword,"code")."%' order by name ASC limit $start,$songs_perpage");
        $page_result = db_qr_fetch("SELECT count(*) as count from songs_songs where name like '%".db_clean_string($keyword,"code")."%'");
     }
     
     
$numrows=$page_result['count'];


       $cnt = db_num($qr) ;

 if($cnt>0){
compile_template(get_template("browse_songs_header"));

      $tr_ord = 1 ;
     while($data = db_fetch($qr)){


$data_comment = db_qr_fetch("select * from songs_comments where id=$data[comment]");

              if($tr_ord ==1){
                   $tr_class="songs_1" ;
                   $tr_ord = 2 ;
                   }else{
                    $tr_class="songs_2";
                    $tr_ord = 1 ;
                           }



          $data_singer = db_qr_fetch("select id,name from songs_singers where id='$data[album]'");

         $data['name'] = str_replace("$keyword","<font class=\"search_replace\">$keyword</font>",$data['name']);

        print "<tr class='$tr_class'>";
        //------------ sync urls data  ------------//
         $urls_data = sync_urls_data($data['id']);
         
  compile_template(get_template('browse_songs'));
          print "</tr>";
    }
    
   compile_template(get_template('browse_songs_footer'));
      
//-------------------- pages system ------------------------
print_pages_links($start,$numrows,$settings['songs_perpage'],$page_string);
//------------ end pages system -------------
      }else{
                print "<center>  $phrases[no_results] </center>";
                }
//---------------------------------------------------------------------------------

}elseif($op=="videos"){
         $qr=db_query("select * from songs_videos_data where name like '%".db_clean_string($keyword,"code")."%'");
       $cnt2 = db_num($qr) ;

         if($cnt2 > 0){
         print "<table width=100%>";
        while($data = db_fetch($qr)){


if ($c==$settings['songs_cells']) {
print "  </tr><TR>" ;
$c = 0 ;
}
 ++$c ;


  $data_cat = db_qr_fetch("select name,id from songs_videos_cats where id='$data[cat]'");
 compile_template(get_template('browse_videos'));


        }
        print "</tr></table>";
              }else{
                 print "<center>  $phrases[no_results] </center>";
                      }
//---------------------------------------------------------
        }elseif($op=="singers"){
            
if($full_text_search && strlen($keyword) >=4){
$qr=db_query("select *,match(name) against('".db_clean_string($keyword,"code","read")."') as score from songs_singers where match(name) against('".db_clean_string($keyword,"code","read")."') order by score desc limit $start,$perpage");
}else{     
$qr = db_query("select * from songs_singers where name like '%".db_clean_string($keyword,"code")."%' order by binary name ASC");
}


    if(db_num($qr)){

    print "<table width=100%><tr>";
    while($data = db_fetch($qr)){
    $data_cat = db_qr_fetch("select id,name from songs_cats where id='$data[cat]'");

if ($c==$settings['songs_cells']) {
print "  </tr><TR>" ;
$c = 0 ;
}
    ++$c ;
    //   $singer_songs_count = db_qr_fetch("select count(id) as count from songs_songs where album='$data[id]'");
         

       print "<td>";
       compile_template(get_template('browse_singers'));
       print "</td>";

             }
             print "</tr></table>";

            }else{
               print "<center>  $phrases[no_results] </center>";
                    }
//-----------------------------------------------------
}elseif($op=="news"){


              //----------------- start pages system ----------------------
    $start=intval($start);
       $page_string= "index.php?action=search&op=news&keyword=".urlencode($keyword)."&start={start}" ;
       $news_perpage = $settings['news_perpage'];
        //--------------------------------------------------------------


      
       $qr = db_query("select * from songs_news where title like '%".db_clean_string($keyword,"code")."%' or content  like '%".db_clean_string($keyword,"code")."%' or details  like '%".db_clean_string($keyword,"code")."%' order by id desc limit $start,$news_perpage");
       $page_result = db_qr_fetch("SELECT count(*) as count from songs_news where title like '%".db_clean_string($keyword,"code")."%' or content  like '%".db_clean_string($keyword,"code")."%' or details  like '%".db_clean_string($keyword,"code")."%'");


$numrows=$page_result['count'];



    if(db_num($qr)){

       print "<hr class=separate_line size=\"1\">";
    while($data = db_fetch($qr)){

    $data['content'] = str_replace("$keyword","<font class=\"search_replace\">$keyword</font>",$data['content']);
  
  compile_template(get_template('browse_news'));  
       print "<hr class=separate_line size=\"1\">" ;


             }

//-------------------- pages system ------------------------
print_pages_links($start,$numrows,$settings['news_perpage'],$page_string);
//------------ end pages system -------------

            }else{
               print "<center>  $phrases[no_results] </center>";

        }
        
        }
//-----------------------------------------------------
close_table();

compile_hook('search_end'); 
//----------------
         }else{
         open_table();
         $phrases['type_search_keyword'] = str_replace('{letters}',$settings['search_min_letters'],$phrases['type_search_keyword']);
                 print "<center>  $phrases[type_search_keyword] </center>";
                 close_table();
                 }
                 
                 
}else{
 open_table();
 print "<center> $phrases[sorry_search_disabled]</center>";
 close_table();
     }


         }
 //---------------------------- Pages -------------------------------------
if($action=="pages"){
        $qr = db_query("select * from songs_pages where active=1 and id='".intval($id)."'");

         compile_hook('pages_start');

         if(db_num($qr)){
         $data = db_fetch($qr);
          compile_hook('pages_before_data_table');
         open_table("$data[title]");
          compile_hook('pages_before_data_content');
                  run_php($data['content']);
           compile_hook('pages_after_data_content');
                  close_table();
          compile_hook('pages_after_data_table');
                  }else{
                  open_table();
                          print "<center> $phrases[err_no_page] </center>";
                          close_table();
                          }
             compile_hook('pages_end');
             }
//--------------------- Copyrights ----------------------------------
 if($action=="copyrights"){
     global $global_lang;

     open_table();
if($global_lang=="arabic"){
     print "<center>
     „—Œ’ ·‹ : $_SERVER[HTTP_HOST]   „‰ <a href='http://allomani.com/' target='_blank'>  «··Ê„«‰Ì ··Œœ„«  «·»—„ÃÌ… </a> <br><br>

   Ã„Ì⁄ ÕﬁÊﬁ «·»—„Ã… „Õ›ÊŸ…
                        <a target=\"_blank\" href=\"http://allomani.com/\">
                       ··Ê„«‰Ì ··Œœ„«  «·»—„ÃÌ…
                        © 2009";
  }else{
       print "<center>
     Licensed for : $_SERVER[HTTP_HOST]   by <a href='http://allomani.com/' target='_blank'>Allomani&trade; Programming Services </a> <br><br>

   <p align=center>
Programmed By <a target=\"_blank\" href=\"http://allomani.com/\"> Allomani&trade; Programming Services </a> © 2009";
      }
     close_table();
         }
//------------------ Register -------------------------
  if($action == "register" || $action=="register_complete_ok"){


 compile_hook('register_start');

open_table("$phrases[register]");

  if(!check_member_login()){
  if($settings['members_register']){


//---------- filter fields -----------------
$email = htmlspecialchars($email);
$email_confirm = htmlspecialchars($email_confirm);
$username = htmlspecialchars($username);
$password = htmlspecialchars($password);
$re_password = htmlspecialchars($re_password);

/*
//--------- filter custom_id fields --------------
if(is_array($custom_id)){
 for($i=0;$i<=count($custom_id);$i++){
 $custom_id[$i] = htmlentities($custom_id[$i]);
 }
 }
//--------- filter custom fields --------------
if(is_array($custom)){
 for($i=0;$i<=count($custom);$i++){
 $custom[$i] = htmlentities($custom[$i]);
 }
 }
    */

   if($action=="register_complete_ok"){
      $all_ok = 1 ;

    //---------------- check security image ------------------
   if($settings['register_sec_code']){
   if(!$sec_img->verify_string($sec_string)){
   print  "<li>$phrases[err_sec_code_not_valid]</li>";
    $all_ok = 0 ;
    }
    }

if(check_email_address($email)){
$email = db_clean_string($email);

$exsists = db_qr_num("select ".members_fields_replace('id')." from ".members_table_replace('songs_members')." where ".members_fields_replace('email')." like '$email'",MEMBER_SQL);
      //------------- check email exists ------------
       if($exsists){
                         print "<li>$phrases[register_email_exists]<br>$phrases[register_email_exists2] <a href='index.php?action=forget_pass'>$phrases[click_here] </a></li>";
              $all_ok = 0 ;
           }
      }else{
       print "<li>$phrases[err_email_not_valid]</li>";
      $all_ok = 0;
      }
       $username = db_clean_string($username);

        //------- username min letters ----------
       if(strlen($username) >= $settings['register_username_min_letters']){
       $exclude_list = explode(",",$settings['register_username_exclude_list']) ;

         if(!in_array($username,$exclude_list)){

     $exsists2 = db_qr_num("select ".members_fields_replace('id')." from ".members_table_replace('songs_members')." where ".members_fields_replace('username')."like '$username'",MEMBER_SQL);

       //-------------- check username exists -------------
            if($exsists2){
                         print(str_replace("{username}",$username,"<li>$phrases[register_user_exists]</li>"));
                $all_ok = 0 ;
           }
           }else{
           print "<li>$phrases[err_username_not_allowed]</li>";
         $all_ok= 0;
               }
          }else{
         print "<li>$phrases[err_username_min_letters]</li>";
         $all_ok= 0;
          }
       //----------------- check required fields ---------------------
        if($email && $email_confirm && $password && $re_password && $username){

        if($password != $re_password){
        print "<li>$phrases[err_passwords_not_match]</li>";
        $all_ok = 0 ;
        }

        if($email != $email_confirm){
        print "<li>$phrases[err_emails_not_match]</li>";
        $all_ok = 0 ;
        }



        }else{
        print  "<li>$phrases[err_fileds_not_complete]</li>";
         $all_ok = 0 ;
            }

//--------------- check required custom fields -------------
if(is_array($custom) && is_array($custom_id)){

   for($i=0;$i<=count($custom);$i++){
   if($custom_id[$i]){
       $m_custom_id=intval($custom_id[$i]);
   $qx = db_qr_fetch("select name,required from songs_members_sets where id='$m_custom_id'");


   if($qx['required']==1 && trim($custom[$i])==""){
   print  "<li>$phrases[err_fileds_not_complete]</li>";
         $all_ok = 0 ;
         break;
       }
   }
   }
   }

//----------------------------------------

 }


 if($all_ok){

if($settings['auto_email_activate']){
    $member_group = $members_connector['allowed_login_groups'][0] ;
    }else{
    $member_group = $members_connector['waiting_conf_login_groups'][0] ;
    }


   db_query("insert into ".members_table_replace('songs_members')." (".members_fields_replace('email').",".members_fields_replace('username').",".members_fields_replace('date').",".members_fields_replace('usr_group').",".members_fields_replace('birth').",".members_fields_replace('country').")
  values('".db_clean_string($email)."','".db_clean_string($username)."','".connector_get_date(date("Y-m-d H:i:s"),'member_reg_date')."','$member_group','".connector_get_date("$date_y-$date_m-$date_d",'member_birth_date')."','".db_clean_string($country)."')",MEMBER_SQL);


    $member_id=mysql_insert_id();


//------------- Custom Fields  ------------------
   if(is_array($custom) && is_array($custom_id)){
   for($i=0;$i<=count($custom);$i++){
   if($custom_id[$i] && $custom[$i]){
   $m_custom_id=intval($custom_id[$i]);
   $m_custom_name =$custom[$i] ;
   db_query("insert into songs_members_fields (member,cat,value) values('$member_id','$m_custom_id','".db_clean_string($m_custom_name)."')");

       }
   }
   }
//-----------------------------------------------



   connector_member_pwd($member_id,$password,'update');
   connector_after_reg_process();

   if($settings['auto_email_activate']){
       print "<center>  $phrases[reg_complete] </center>";
   }else{
   print "<center>  $phrases[reg_complete_need_activation] </center>";
   snd_email_activation_msg($member_id);
   }

           }else{

 compile_hook('register_before_fields');
print "<script type=\"text/javascript\" language=\"javascript\">
<!--
function pass_ver(theForm){
if ((theForm.elements['email'].value !='') && (theForm.elements['email'].value == theForm.elements['email_confirm'].value)){
if ((theForm.elements['password'].value !='') && (theForm.elements['password'].value == theForm.elements['re_password'].value)){
        if(theForm.elements['username'].value  && theForm.elements['sec_string'].value){
        return true ;
        }else{
       alert (\"$phrases[err_fileds_not_complete]\");
return false ;
}
}else{
alert (\"$phrases[err_passwords_not_match]\");
return false ;
}
}else{
alert (\"$phrases[err_emails_not_match]\");
return false ;
}
}
//-->
</script>

<form action=index.php method=post onsubmit=\"return pass_ver(this)\">
          <input type=hidden name=action value=register_complete_ok>
          <fieldset style=\"padding: 2\">


          <table width=100%><tr>
            <td width=20%> $phrases[username] :</td><td><input type=text name=username value='$username' onblur=\"ajax_check_register_username(this.value);\"></td><td id='register_username_area'></td> </tr>

           <tr><td colspan=2>&nbsp;</td></tr>
          <tr>  <td>  $phrases[password] : </td><td><input type=password name=password></td>   </tr>
          <tr>  <td>  $phrases[password_confirm] : </td><td><input type=password name=re_password></td>   </tr>


   <tr><td colspan=2>&nbsp;</td></tr>

          <td width=20%>$phrases[email] :</td><td><input type=text name=email value=\"$email\" onblur=\"ajax_check_register_email(this.value);\"></td><td id='register_email_area'></td> </tr>
          <td width=20%>$phrases[email_confirm] :</td><td><input type=text name=email_confirm value=\"$email_confirm\"></td> </tr>

         <tr><td colspan=2>&nbsp;</td></tr>
             </table>
            </fieldset>";

$cf = 0 ;

$qr = db_query("select * from songs_members_sets where required=1 order by ord");
   if(db_num($qr)){
    print "<br><fieldset style=\"padding: 2\">
    <legend>$phrases[req_addition_info]</legend>
<br><table width=100%>";

while($data = db_fetch($qr)){
    print "
    <input type=hidden name=\"custom_id[$cf]\" value=\"$data[id]\">
    <tr><td width=25%><b>$data[name]</b><br>$data[details]</td><td>";
    print get_member_field("custom[$cf]",$data);
        print "</td></tr>";
$cf++;
}
print "</table>
</fieldset>";
}

            print "<br><fieldset style=\"padding: 2\">
    <legend>$phrases[not_req_addition_info]</legend>
<br><table>
    <tr><td><b> $phrases[birth] </b> </td><td><select name='date_d'> <option value='00'></option>";
           for($i=1;$i<=31;$i++){
            if(strlen($i) < 2){$i="0".$i;}
           print "<option value=$i>$i</option>";
           }
           print "</select>
           - <select name=date_m> <option value='00'></option>";
            for($i=1;$i<=12;$i++){
             if(strlen($i) < 2){$i="0".$i;}
           print "<option value=$i>$i</option>";
           }
           print "</select>
           - <select name='date_y'>
           <option value='00'></option>";
           for($i=(date('Y')-10);$i>=(date('Y')-70);$i--){

           print "<option value='$i'>$i</option>";
           }
           print"</select></td></tr>
            <tr>  <td><b>$phrases[country] </b> </td><td><select name=country><option value=''> $phrases[select_from_menu] </option> ";


           $c_qr = db_query("select * from songs_countries order by binary name asc");
   while($c_data = db_fetch($c_qr)){


        print "<option value='$c_data[name]' $chk>$c_data[name]</option>";
           }
           print "</select></td></tr>";

           $qr = db_query("select * from songs_members_sets where required=0 order by ord");
   if(db_num($qr)){

while($data = db_fetch($qr)){
    print "
    <input type=hidden name=\"custom_id[$cf]\" value=\"$data[id]\">
    <tr><td width=25%><b>$data[name]</b><br>$data[details]</td><td>";
    print get_member_field("custom[$cf]",$data);
        print "</td></tr>";
$cf++;
}
}

           print "</table>
           </fieldset>";


           print " <br><fieldset style=\"padding: 2\"><table width=100%><tr>";

           if($settings['register_sec_code']){
           print "<td><b>$phrases[security_code]</b></td><td>".$sec_img->output_input_box('sec_string','size=7')."</td>
           <td><img src=\"sec_image.php\" alt=\"Verification Image\" /></td>";
           }

           print "<td align=center><input type=submit value=' $phrases[register_do] '></td></tr>
          </table>
          </fieldset></form>";
    compile_hook('register_after_fields');
            }
        }else{
                print "<center>$phrases[register_closed]</center>";
                }
   }else{
           print "<center> $phrases[registered_before] </center>" ;
           }
           close_table();

 compile_hook('register_end');
          }
//---------------------------- Forget Password -------------------------
 if($action == "forget_pass" || $action=="lostpwd" ||  $action=="rest_pwd"){
     if($action == "forget_pass"){$action="lostpwd";}

        connector_members_rest_pwd($action,$useremail);
         }
//-------------------------- Resend Active Message ----------------
if($action=="resend_active_msg"){

   $qr = db_query("select * from ".members_table_replace('songs_members') ." where ".members_fields_replace('email')."='".db_clean_string($email)."'",MEMBER_SQL);
   if(db_num($qr)){
           $data = db_fetch($qr) ;
           open_table();
   if(in_array($data[members_fields_replace('usr_group')],$members_connector['allowed_login_groups'])){
    print "<center> $phrases[this_account_already_activated] </center>";
    }elseif(in_array($data[members_fields_replace('usr_group')],$members_connector['disallowed_login_groups'])){
            print "<center> $phrases[closed_account_cannot_activate] </center>";
    }elseif(in_array($data[members_fields_replace('usr_group')],$members_connector['waiting_conf_login_groups'])){
   snd_email_activation_msg($data[members_fields_replace('id')]);
   print "<center>  $phrases[activation_msg_sent_successfully] </center>";
   }
   close_table();
   }else{
           open_table();
           print "<center>  $phrases[email_not_exists] </center>";
           close_table();
           }
        }
//-------------------------- Active Account ------------------------
if($action == "activate_email"){
        open_table("$phrases[active_account]");
        $qr = db_query("select * from songs_confirmations where code='".db_clean_string($code)."'");
if(db_num($qr)){
$data = db_fetch($qr);

$qr_member=db_query("select ".members_fields_replace('id')." from ".members_table_replace('songs_members') ." where ".members_fields_replace('id')."='$data[cat]'  and ".members_fields_replace('usr_group')."='".$members_connector['waiting_conf_login_groups'][0]."'",MEMBER_SQL);

 if(db_num($qr_member)){
      db_query("update ".members_table_replace('songs_members') ." set ".members_fields_replace('usr_group')."='".$members_connector['allowed_login_groups'][0]."' where ".members_fields_replace('id')."='$data[cat]'",MEMBER_SQL);
      db_query("delete from songs_confirmations where code='".db_clean_string($code)."'");
    print "<center> $phrases[active_acc_succ] </center>" ;
 }else{
      print "<center> $phrases[active_acc_err] </center>" ;
 }
        }else{
      print "<center> $phrases[active_acc_err] </center>" ;
 }
        close_table();
        }

//-------------------------- Confirmations ------------------------
if($action == "confirmations"){
    //----- email change confirmation ------//
if($op=="member_email_change"){
open_table();
$qr=db_query("select * from songs_confirmations where code='".db_clean_string($code)."' and type='".db_clean_string($op)."'");

if(db_num($qr)){
$data = db_fetch($qr);

      db_query("update ".members_table_replace('songs_members')." set ".members_fields_replace('email')."='".$data['new_value']."' where ".members_fields_replace('id')."='$data[cat]'",MEMBER_SQL);
      db_query("delete from songs_confirmations where code='".db_clean_string($code)."'");
    print "<center> $phrases[your_email_changed_successfully] </center>" ;
}else{
     print "<center> $phrases[err_wrong_url] </center>" ;
}
 close_table();
}

        }

//------------------------ Members Login ---------------------------
 if($action=="login"){
 if(@file_exists("login_form.php")){
     include "login_form.php";
 }else{
    $re_link = htmlspecialchars($re_link) ;

         open_table();
print "<script type=\"text/javascript\" src=\"js/md5.js\"></script>

<form method=\"POST\" action=\"login.php\" onsubmit=\"md5hash(password, md5pwd, md5pwd_utf, 1)\">

<input type=hidden name='md5pwd' value=''>
<input type=hidden name='md5pwd_utf' value=''>


<input type=hidden name=action value=login>
<input type=hidden name=re_link value=\"$re_link\">

<table border=\"0\" width=\"200\">
        <tr>
                <td height=\"15\"><span>$phrases[username] :</span></td>
                <td height=\"15\"><input type=\"text\" name=\"username\" size=\"10\"></td>
        </tr>
        <tr>
                <td height=\"12\"><span>$phrases[password]:</span></td>
                <td height=\"12\" ><input type=\"password\" name=\"password\" size=\"10\"></td>
        </tr>
        <tr>
                <td height=\"23\" colspan=2>
                <p align=\"center\"><input type=\"submit\" value=\"$phrases[login]\"></td>
        </tr>
        <tr>
                <td height=\"38\" colspan=2><span>
                <a href=\"index.php?action=register\">$phrases[newuser]</a><br>
                <a href=\"index.php?action=forget_pass\">$phrases[forgot_pass]</a></span></td>
        </tr>
</table>
</form>\n";
close_table();
 }
         }
 //--------------- Load Index Plugins --------------------------
$dhx = opendir(CWD ."/plugins");
while ($rdx = readdir($dhx)){
         if($rdx != "." && $rdx != "..") {
                 $cur_fl = CWD ."/plugins/" . $rdx . "/index.php" ;
        if(file_exists($cur_fl)){
                include ($cur_fl) ;
                }
          }

    }
closedir($dhx);
//---------------------  Banners ------------------------------------------------------
$qr = db_query("select * from songs_banners where type='footer' and active=1 and pages like '%$pg_view,%' order by ord");
while($data = db_fetch($qr)){
db_query("update songs_banners set views=views+1 where id='$data[id]'");

if($data['c_type']=="code"){
    compile_template($data['content']);
    }else{
print "<center><a href='banner.php?id=$data[id]' target=_blank><img src='$data[img]' border=0 alt='$data[title]'></a><br><br></center>";
}
        }
 print "<br>";

//---------------------------END OF CENTER CONTENT--------------------------------------
print "</td>" ;
get_pg_view();
 if(!in_array($pg_view,$actions_checks)){$pg_view = "main" ;}

 $zqr=db_query("select * from songs_blocks where pos='r' and active=1 and pages like '%$pg_view,%' order by ord");

  if(db_num($zqr)){
print "<td width='$blocks_width' valign=\"top\" dir=$global_dir>";

print "<center><table width=100%>";


             $adv_c= 1 ;
         while($zdata = db_fetch($zqr)){
        print "<tr>
                <td  width=\"100%\" valign=\"top\">";
                open_block($zdata['title'],$zdata['template']);

                run_php($zdata['file']);

                close_block($zdata['template']);

                print "</td>
        </tr>";

              //---------------------------------------------------

        $adv_menu_qr = db_query("select * from songs_banners where type='menu' and menu_id=$adv_c and menu_pos='r' and active=1 and pages like '%$pg_view,%' order by ord");
          if(db_num($adv_menu_qr)){
                 while($data = db_fetch($adv_menu_qr)){  
                db_query("update songs_banners set views=views+1 where id='$data[id]'");
                print "<tr>
                <td  width=\"100%\" valign=\"top\">";
                if($data['c_type']=="code"){
    compile_template($data['content']);
    }else{
                open_block();
             print "<center><a href='banner.php?id=$data[id]' target=_blank><img src='$data[img]' border=0 alt='$data[title]'></a></center>";
               close_block();
               }
                print "</td>
        </tr>";  
                 }
               }
            ++$adv_c ;
        //----------------------------------------------------
           }
   
print "</table></center></td>" ;
unset($zdata,$adv_menu_qr,$data,$adv_c); 
}
unset($zqr);
print "</tr></table>\n";


print_copyrights();

site_footer();
           
if($debug){      
if(check_login_cookies()){                                                  
print "<br><div dir=ltr><b>Memory Usage :</b> " .  convert_number_format(memory_get_usage(),2,true,true)."</div>";
print "<br><div dir=ltr><b>Queries :</b> " .  $queries."</div>";  
}
}
/*
function array_size($arr) {
  ob_start();
  print_r($arr);
  $mem = ob_get_contents();
  ob_end_clean();
  $mem = preg_replace("/\n +/", "", $mem);
  $mem = strlen($mem);
  return $mem;
}
print "<b>Phrases : </b>" .convert_number_format(array_size($phrases),2,true,true);
*/
?>