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

define("IS_DOWNLOAD_FILE",1);
require("global.php");

$id = intval($id);
$typ = htmlspecialchars($typ);
$action = htmlspecialchars($action); 
$cat = iif($cat,intval($cat),1);



if($action == "video"){
$qr=db_query("select id,url from songs_videos_data where id='$id' and url !=''");
}else{
$qr=db_query("select id,url from songs_urls_data where song_id='$id' and cat='$cat' and url !=''");
}


if (db_num($qr)){
 $data=db_fetch($qr);

//=============== Videos =================//
if($action=="video"){
//-------- Check Permission -------//
if(video_download_permission($id)){ 
//-------- Video Watch ------------//
if($op=="watch"){
db_query("update songs_videos_data set views=views+1 where id='$id'");

header("Content-type: audio/x-pn-realaudio");
header("Content-Disposition:  filename=listen.ram");
header("Content-Description: PHP Generated Data");

   if (strchr($data['url'],"http://")) {
   print $data['url'];
           }else{
  print $scripturl."/".$data['url'];
        }
//--------- video download -----------//        
          }else{
         db_query("update songs_videos_data set downloads=downloads+1 where id='$id'");
         if (strchr($data['url'],"http://")) {
           header("Location: $data[url]");
            }else{
             header("Location: $scripturl/$data[url]");
                    }

                    }
//--------- Redirect -----------//                    
}else{
    login_redirect(true);
}                   
//================ Songs ==================//
}else{
 //-------- Check Permission -------// 

//----------- Song Listen ----------//
if ($op == "listen"){
db_query("update songs_urls_data set listens=listens+1 where song_id='$id' and cat='$cat'");

$listen_file = db_qr_fetch("select * from songs_urls_fields where id='$cat'");

header("Content-type: ".$listen_file['listen_mime']);
header("Content-Disposition:  filename=".$listen_file['listen_name']);
  
 $num_ramadv_data = db_qr_fetch("select count(id) as count from songs_banners where type='listen' and active=1"); 
 $num_ramadv = intval($num_ramadv_data['count']);
 unset($num_ramadv_data);
//---------
compile_template($listen_file['listen_content']);
//--------

//-------------- Song Download ----------//
  }else{   
      if(song_download_permission($id)){   
        // print("update songs_urls_data set downloads=downloads+1 where song_id='$id' and cat='$cat'"); 
         db_query("update songs_urls_data set downloads=downloads+1 where song_id='$id' and cat='$cat'");
         if (strchr($data['url'],"http://")) {
           header("Location: $data[url]");
            }else{
             header("Location: $scripturl/$data[url]");
                    }
                    }else{
login_redirect(true);
}
  }
//-------- Redirect -------//  

}
//------------------------------//


}else{
print "<center> $phrases[err_wrong_url] </center>";
}

?>