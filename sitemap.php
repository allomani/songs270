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

require("global.php");
print "<?xml version=\"1.0\" encoding=\"$settings[site_pages_encoding]\" ?> \n";
?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xsi:schemaLocation="http://www.google.com/schemas/sitemap/0.84 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">
<?

//---------- cats -------------
$qr=db_query("select id,name from songs_cats order by id desc");
while($data = db_fetch($qr)){
print "<url>
<loc>$scripturl/".get_template('links_browse_cat','{id}',$data['id'])."</loc>
<changefreq>daily</changefreq>
<priority>0.50</priority>
</url>";    
}
//---------- singers -------------
$qr=db_query("select id,name from songs_singers order by id desc");
while($data = db_fetch($qr)){
print "<url>
<loc>$scripturl/".get_template('links_browse_songs','{id}',$data['id'])."</loc>
<changefreq>daily</changefreq>
<priority>0.50</priority>
</url>";    
}


//---------- albums -------------
$qr=db_query("select id,cat from songs_albums order by id desc");
while($data = db_fetch($qr)){
    
$data_singer=db_qr_fetch("select * from songs_singers where id='$data[cat]' and active=1") ;

print "<url>
<loc>$scripturl/".get_template('links_browse_songs_w_album',array('{id}','{album_id}'),array($data_singer['id'],$data['id']))."</loc>
<changefreq>daily</changefreq>
<priority>0.50</priority>
</url>";    
}

//---------- videos cats -------------
$qr=db_query("select id from songs_videos_cats order by id desc");
while($data = db_fetch($qr)){
print "<url>
<loc>$scripturl/".get_template('links_browse_videos','{id}',$data['id'])."</loc>
<changefreq>daily</changefreq>
<priority>0.50</priority>
</url>";    
}

//---------- News -------------
$qr=db_query("select id from songs_news order by id desc");
while($data = db_fetch($qr)){
print "<url>
<loc>$scripturl/".get_template('links_browse_news','{id}',$data['id'])."</loc>
<changefreq>daily</changefreq>
<priority>0.50</priority>
</url>";    
}
//--------------------------

print "</urlset>";

