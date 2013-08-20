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

include "global.php" ;

$num = intval($num);
$cat = intval($cat) ;

if(!$num){$num=10;}

if($cat){
$qr = db_query("select songs_urls_data.url from songs_songs,songs_singers,songs_urls_data where songs_urls_data.cat=1 and songs_urls_data.song_id=songs_songs.id and songs_songs.album=songs_singers.id and songs_singers.cat='$cat' order by rand() limit $num");
}else{
$qr = db_query("select url from songs_urls_data where cat=1 order by rand() limit $num");
}

//$cont = "[playlist]\nNumberOfEntries=$num\n";

$c=1 ;
while($data = db_fetch($qr)){

 if (strchr($data['url'],"http://")) {
           $file_url = $data['url'] ;
           }else{

  $file_url = $scripturl."/$data[url]";
        }

//$cont .= "File".$c."=".$file_url."\n" ;
$cont .= $file_url."
" ;

$c++;
        }

   header("Content-type: audio/x-pn-realaudio");
 header("Content-Disposition:  filename=random.ram");
 header("Content-Description: PHP Generated Data");
 print $cont ;