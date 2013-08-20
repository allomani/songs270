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

// Edited 12-12-2009

$last_sql = "";
$queries = 0 ;

 //----------- Clean String ----------
 function db_clean_string($str,$type="text",$op="write",$is_gpc=true){

 if(get_magic_quotes_gpc() && $is_gpc){ $str = stripslashes($str);}

if($type=="num"){
return intval($str);
}elseif($type=="text"){

if($op=="write"){
return db_escape_string(htmlspecialchars($str));
}else{
return db_escape_string($str);
}
}elseif($type=="code"){
return db_escape_string($str);
}
 }
 //----------- escape String -----------
 function db_escape_string($str){

 if(function_exists('mysql_real_escape_string')){
 	return mysql_real_escape_string($str);
 	}else{
 	return mysql_escape_string($str);
 	}
 }
 //----------- Connect ----------
 function db_connect($host,$user,$pass,$dbname){
     $cn = @mysql_connect($host,$user,$pass) ;
if(!$cn){
        if(mysql_errno()==1040){
     die("<center> Server Busy  , Please Try again later  </center>");
        }else{
die(mysql_errno()." : connection Error");
                }
                }


@mysql_select_db($dbname) or die("Database Name Error");
 }
 //----------- query ------------------
   function db_query($sql,$type=""){

   	global $show_mysql_errors,$last_sql,$queries ;

if($type==MEMBER_SQL){
	members_remote_db_connect();
	}
     
     $last_sql = $sql ;
      $queries++;       
      $qr  = @mysql_query($sql);
      $err =  mysql_error() ;

      if($err){
          
          if($show_mysql_errors){
      	 	print  "<p align=left><b> MySQL Error: </b> $err </p>";
          }
          
          
        //    @error_log("MySQL Error: $err -- SQL: $sql");
      	 	return false;
      }else{
      if($type==MEMBER_SQL){
	members_local_db_connect();
	}

         return $qr ;
      }


           }

 //---------------- fetch -------------------
    function db_fetch($qr){
    global $show_mysql_errors,$last_sql ;

         $fetch = @mysql_fetch_array($qr);

     $err =  mysql_error() ;

      if($err){
      if($show_mysql_errors){
       	print  "<p align=left><b> MySQL Error: </b> $err </p>";
      }
      
    //  @error_log("MySQL Error: $err -- SQL: $last_sql");  
       		return false;
      }else{
            return $fetch;
            }
            }

// ------------------------ num -----------------------
      function db_num($qr){
         global $show_mysql_errors,$last_sql;

      $num =  @mysql_num_rows($qr);
      $err =  mysql_error() ;

      if($err){
      if($show_mysql_errors){
       	print  "<p align=left><b> MySQL Error: </b> $err </p>";
      }
      
   //     @error_log("MySQL Error: $err -- SQL: $last_sql");  
       		return false;
      }else{
            return $num;
            }



            }

            
 //------------------ Query + fetch ----------------------
    function db_qr_fetch($sql,$type=""){
    global $show_mysql_errors ;


     $qr =  db_query($sql,$type);
  if($qr){
            return db_fetch($qr);
  }else{
      return false;
  }
            
  }
// ------------------- query + num --------------------
             function db_qr_num($sql,$type=""){
                 $qr = db_query($sql,$type);
            if($qr){
            return db_num($qr);
            }else{
                return false;
            }
            }
            
            