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

if($settings['count_visitors_info']){
//------------------- Get Browser info ------------
if(stristr( $http_agent,"MSIE")) $browser = "MSIE";
elseif(stristr( $http_agent,"Chrome")) $browser = "Chrome"; 
elseif(stristr( $http_agent,"Firefox")) $browser = "Firefox"; 
elseif(stristr( $http_agent,"Nokia")) $browser = "Nokia"; 
elseif(stristr( $http_agent,"BlackBerry")) $browser = "BlackBerry";  
elseif(stristr( $http_agent,"iPhone")) $browser = "iPhone"; 
elseif(stristr( $http_agent,"iPod")) $browser = "iPod"; 
elseif(stristr( $http_agent,"Android")) $browser = "Android"; 
elseif(stristr( $http_agent,"Lynx")) $browser = "Lynx";
elseif(stristr( $http_agent,"Opera")) $browser = "Opera";
elseif(stristr( $http_agent,"WebTV")) $browser = "WebTV";
elseif(stristr( $http_agent,"Konqueror")) $browser = "Konqueror";
elseif((stristr( $http_agent,"Nav")) || (stristr( $http_agent,"Gold")) || (stristr( $http_agent,"X11")) || (stristr( $http_agent,"Mozilla")) || (stristr( $http_agent,"Netscape")) AND (!stristr( $http_agent,"MSIE") AND (!stristr( $http_agent,"Konqueror")))) $browser = "Netscape"; 
elseif((stristr( $http_agent,"bot")) || (stristr( $http_agent,"Google")) || (stristr( $http_agent,"Slurp")) || (stristr( $http_agent,"Scooter")) || (stristr( $http_agent,"Spider")) || (stristr( $http_agent,"Infoseek"))) $browser = "Bot";
else $browser = "Other";
                             


//--------- Get Os info -------------------

if(stristr( $http_agent,"Win")) $os = "Windows";
elseif((stristr($http_agent,"Mac")) || (stristr( $http_agent,"PPC"))) $os = "Mac";
elseif(stristr( $http_agent,"Linux")) $os = "Linux";
elseif(stristr( $http_agent,"FreeBSD")) $os = "FreeBSD";
elseif(stristr( $http_agent,"SunOS")) $os = "SunOS";
elseif(stristr( $http_agent,"IRIX")) $os = "IRIX";
elseif(stristr( $http_agent,"BeOS")) $os = "BeOS";
elseif(stristr( $http_agent,"OS/2")) $os = "OS/2";
elseif(stristr( $http_agent,"AIX")) $os = "AIX";
elseif(stristr( $http_agent,"Symbian")) $os = "Symbian";
elseif(stristr( $http_agent,"BlackBerry")) $os = "BlackBerry";  
else $os = "Other";


//-------- OS and Browser Info ------------

db_query("update info_browser set count=count+1 where name like '$browser'");
db_query("update info_os set count=count+1 where name like '$os'");
}


if($settings['count_visitors_hits']){
//------ Visitors Count ----------------
$dot = date("d-m-Y");
$result=db_query("select hits from info_hits where date like '$dot'");
if (db_num($result)){
db_query("update info_hits set hits=hits+1 where date like '$dot'");
 }else{
  db_query("insert into info_hits (date,hits) values ('$dot','1')");
}
}


if($settings['count_online_visitors']){
// ---- visitor timeout ----------------
if($online_visitor_timeout){
$timeoutseconds = intval($online_visitor_timeout);
}else{    
$timeoutseconds=800;
}


   $ip = getenv("REMOTE_ADDR");
     //  $ip = "213.25.52.40";

$time=time();
$timeout=$time-$timeoutseconds;
//$file=$_SERVER['PHP_SELF'];

db_query("DELETE FROM info_online WHERE time<$timeout");


//$sm=split("/",str_replace(".","/",$ip));
//$ip_max = trim("$sm[0].$sm[1].$sm[2]");


  $ip_max = $ip ;

$result = db_query("SELECT * FROM info_online WHERE ip like '$ip_max%'");

if (db_num($result)) {
db_query("UPDATE info_online SET time='$time', ip='$ip' WHERE ip like '$ip_max%'");
} else {
db_query("INSERT INTO info_online (time,ip) VALUES ('$time', '$ip')");
}


//---------- Now Online Visitors ------------

$result=db_query("SELECT DISTINCT ip FROM info_online ");
$users=db_num($result);


//=========Best Visitors Record ==============================================
$now_dt = date("d-M-Y")." $phrases[the_hour] : " .date("H:i");
 $data=db_qr_fetch("select v_count from info_best_visitors");

 if ($users > $data['v_count']){

  $counter['best_visit'] = $users ;
   $counter['best_visit_time'] = $now_dt ;

  db_query("update info_best_visitors set v_count='$users',time='$now_dt'");

 }else{
$best=db_qr_fetch("select * from info_best_visitors");
   $counter['best_visit'] = $best['v_count'] ;
   $counter['best_visit_time'] =  $best['time'];

 }
//==========================================================================

$counter['online_users'] = $users;
}


?>