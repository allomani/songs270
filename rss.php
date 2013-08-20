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

header('Content-type: text/xml');
include "global.php" ;
print "<?xml version=\"1.0\" encoding=\"$settings[site_pages_encoding]\" ?> \n";
?>
<rss version="2.0">
<channel>
<? print "<title><![CDATA[$sitename]]></title>\n";?>
<description></description>
<?print "<link>http://".$_SERVER['HTTP_HOST']."</link>\n";
print "<copyright><![CDATA[$settings[copyrights_sitename]]]></copyright>";
?>

<?
if(!$op){

$qr=db_query("select songs_singers.*,songs_cats.name as cat_name,songs_cats.id as cat_id from songs_singers,songs_cats where songs_cats.id = songs_singers.cat order by songs_singers.last_update desc limit 200") ;


while($data = db_fetch($qr)){
                                                      
   print "  <item>
        <title><![CDATA[".$data["name"]."]]></title>
         <description><![CDATA[ <img align=center src=\"".get_image($data['img'])."\"><br>$data[last_update]]]></description>";

                print "
        <link>".htmlentities($scripturl."/".get_template("links_browse_songs","{id}",$data['id']))."</link>
        <category><![CDATA[$data[cat_name]]]></category>
     </item>\n";
     }
}elseif($op=="songs"){

$qr=db_query("select songs_songs.*,songs_singers.name as singer_name,songs_singers.id as singer_id from songs_songs,songs_singers where songs_singers.id=songs_songs.album order by songs_songs.id desc limit 200") ;


while($data = db_fetch($qr)){

   print "  <item>
        <title><![CDATA[".$data['singer_name'] . " - " . $data["name"]."]]></title>";

                print "
        <link>".htmlentities($scripturl."/".get_template('links_song_listen',array('{cat}','{id}'),array('1',$data['id'])))."</link>
        <category><![CDATA[$data[singer_name]]]></category>
     </item>\n";
     }

}elseif($op=="videos"){
    
$qr=db_query("select songs_videos_data.*,songs_videos_cats.name as cat_name,songs_videos_cats.id as cat_id from songs_videos_data,songs_videos_cats where songs_videos_cats.id=songs_videos_data.cat order by songs_videos_data.id desc limit 200") ;


while($data = db_fetch($qr)){
                                                           
   print "  <item>
        <title><![CDATA[".$data['cat_name'] . " - " . $data["name"]."]]></title>";

                print "
        <link>".htmlentities($scripturl."/".get_template('links_video_watch','{id}',$data['id']))."</link>
        <category><![CDATA[$data[cat_name]]]></category>
     </item>\n";
     }
}

print "</channel>
</rss>";