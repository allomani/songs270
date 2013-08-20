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

// Edited 05-03-2010 , ram adv height bug //

define('ADMIN_DIR', (($getcwd = str_replace("\\","/",getcwd())) ? $getcwd : '.'));
chdir('./../');
define('CWD', (($getcwd = str_replace("\\","/",getcwd())) ? $getcwd : '.'));
define('IS_ADMIN', 1);
$is_admin =1 ;

include_once(CWD . "/global.php") ;



//----------- Login Script ----------------------------------------------------------
if ($action == "login" && $username && $password ){

     $result=db_query("select * from songs_user where username='".db_clean_string($username,"code")."'");
     if(db_num($result)){
     $login_data=db_fetch($result);

 
       if($login_data['password']==iif(get_magic_quotes_gpc(),stripslashes($password),$password)){

set_cookie('admin_id', $login_data['id']);
set_cookie('admin_username', $login_data['username']);
set_cookie('admin_password', md5($login_data['password']));

     print "<SCRIPT>window.location=\"index.php\";</script>";
      exit();
       }else{
              print "<link href=\"smiletag-admin.css\" type=text/css rel=stylesheet>\n";
              print "<br><center><table width=60% class=grid><tr><td align=center> $phrases[cp_invalid_pwd] </td></tr></table></center>";

              }
            }else{
                 print " <link href=\"smiletag-admin.css\" type=text/css rel=stylesheet>    \n";
                    print "<br><center><table width=60% class=grid><tr><td align=center>   $phrases[cp_invalid_username] </td></tr></table></center>";

                    }
              }elseif($action == "logout"){
                    set_cookie('admin_id');
                    set_cookie('admin_username');
                    set_cookie('admin_password');
                    


                  print "<SCRIPT>window.location=\"index.php\";</script>";

                      }
//-------------------------------------------------------------------------------------------
//--------- add Main user --------//
if($op=="add_main_user"){
$users_num = db_qr_fetch("select count(id) as count from songs_user");
if($users_num['count'] == 0 && trim($cp_username) && trim($cp_password)){
db_query("insert into songs_user (username,password,email,group_id) values('".db_clean_string($cp_username,"code")."','".db_clean_string($cp_password,"code")."','$cp_email','1')");
}
}
//-------- First time setup ----------//
$users_num = db_qr_fetch("select count(id) as count from songs_user");
if($users_num['count'] == 0){

if($global_lang=="arabic"){
$global_dir = "rtl" ;
print "<html dir=$global_dir>
<title>$sitename - ·ÊÕ… «· Õﬂ„ </title>" ;
}elseif($global_lang=="kurdish"){  
$global_dir = "rtl" ;
print "<html dir=$global_dir>
<title>$sitename - Control Panel </title>" ;    
}else{
$global_dir = "ltr" ;
print "<html dir=$global_dir>
<title>$sitename - Control Panel </title>" ;
}

print "<META http-equiv=Content-Language content=\"$settings[site_pages_lang]\">
<META http-equiv=Content-Type content=\"text/html; charset=$settings[site_pages_encoding]\">
<link href=\"images/style.css\" type=text/css rel=stylesheet>
<script src='$scripturl/js/prototype.js'></script>
<script src='$scripturl/js/StrongPassword.js'></script>";

print "<center>
<form action='index.php' method=post name='sender'>
<input type=hidden name=op value='add_main_user'>
<br><br><table width=50% class=grid>
<tr><td colspan=2><h2>$phrases[create_main_user]</h2></td></tr>
<tr><td>$phrases[username]</td><td><input type=text name=cp_username dir=ltr></td></tr>
<tr><td>$phrases[password]</td><td><input type=text id='cp_password' name=cp_password dir=ltr onChange=\"passwordStrength(this.value);\" onkeyup=\"passwordStrength(this.value);\"> &nbsp; <input type=button value=\"Generate\" onClick=\"$('cp_password').value=GenerateAndValidate(12,1);passwordStrength($('cp_password').value);\"></td></tr>
<tr><td>$phrases[email]</td><td><input type=text name=cp_email dir=ltr></td></tr>

<tr><td></td><td>
<div id=\"passwordDescription\">-</div>
<div id=\"passwordStrength\" class=\"strength0\"></div>
</td></tr>
<tr><td colspan=2 align=center><input type=submit value=' $phrases[add_button] '></td></tr>
</table>
</form></center>";
die();   
}
if (check_login_cookies()) {
    
//------------------ generate admin security hash ------------
@session_start();  
if(!$_SESSION['admin_security'] || ($_SESSION['admin_security_expire']+1200) < time()){
$_SESSION['admin_security']  = md5(mt_rand()) ;
$_SESSION['admin_security_expire']  = time();
}

//--------------------------- Backup Job ------------------------------
if($action=="backup_db_do"){
if(!$disable_backup){
if_admin();
require(CWD. '/includes/class_mysql_db_backup.php');
$backup_obj = new MySQL_DB_Backup();
$backup_obj->server = $db_host ;
$backup_obj->port = 3306;
$backup_obj->username = $db_username;
$backup_obj->password = $db_password;
$backup_obj->database = $db_name;
$backup_obj->drop_tables = true;
$backup_obj->create_tables = true;
$backup_obj->struct_only = false;
$backup_obj->locks = true;
$backup_obj->comments = true;
$backup_obj->fname_format = 'm-d-Y-h-i-s';
$backup_obj->null_values = array( '0000-00-00', '00:00:00', '0000-00-00 00:00:00');
if($op=="local"){
$task = MSX_DOWNLOAD;
$backup_obj->backup_dir = 'uploads/';
$filename = "songs_".date('m-d-Y_h-i-s').".sql.gz";
}elseif($op=="server"){
$task = MSX_SAVE ;
}
$use_gzip = true;
$result_bk = $backup_obj->Execute($task, $filename, $use_gzip);
    if (!$result_bk)
        {
                 $output = $backup_obj->error;
        }
        else
        {
                $output = $phrases['backup_done_successfully'];

        }
        }else{
        $output =  $disable_backup ;
                }
}
require (CWD."/".$editor_path."/editor_init_functions.php") ;

editor_init();
if($global_lang=="arabic"){
$global_dir = "rtl" ;
print "<html dir=$global_dir>
<title>$sitename - ·ÊÕ… «· Õﬂ„ </title>" ;
}elseif($global_lang=="kurdish"){  
$global_dir = "rtl" ;
print "<html dir=$global_dir>
<title>$sitename - Control Panel </title>" ; 
}else{
$global_dir = "ltr" ;
print "<html dir=$global_dir>
<title>$sitename - Control Panel </title>" ;
}
print "<META http-equiv=Content-Language content=\"$settings[site_pages_lang]\">
<META http-equiv=Content-Type content=\"text/html; charset=$settings[site_pages_encoding]\">";
//-----------------------------------------------------------------
?>
<link href="images/style.css" type=text/css rel=stylesheet>
<?
print "
<script src='$scripturl/js/prototype.js'></script>
<script src='$scripturl/js/scriptaculous/scriptaculous.js'></script>
<script src='ajax.js' type=\"text/javascript\" language=\"javascript\"></script> 
<script src='js.js' type=\"text/javascript\" language=\"javascript\"></script>  
<script src='$scripturl/js/StrongPassword.js'></script>";
editor_html_init();

if(file_exists(CWD . "/install/")){
print "<h3><center><font color=red>Warning : Installation Folder Exists , Please Delete it</font></center></h3>";
}
?> 
<table width=100% height=100%><tr><td width=20% valign=top>

<?
print str_replace("{username}",$user_info['username'],$phrases['cp_welcome_msg']); 
print " <br><br>";

 require("admin_menu.php") ;
?>

</td>
 <td width=1 background='images/dot.gif'></td>
<td valign=top> <br>
<?
//----------------------Start -------------------------------------------------------
if(!$action){
  $data1 = db_qr_fetch("select count(id) as count from songs_singers");
  $data3 = db_qr_fetch("select count(id) as count from songs_songs");
   $data4 = db_qr_fetch("select count(id) as count from songs_user");
   $data5 = db_qr_fetch("select count(id) as count from songs_videos_data");
   $count_members = db_qr_fetch("select count(".members_fields_replace("id").") as count from ".members_table_replace("songs_members"),MEMBER_SQL);

print "<center><table width=50% class=grid><tr><td align=center><b>$phrases[welcome_to_cp] <br><br>";


   if($global_lang=="arabic"){
  print "„—Œ’ ·‹ : $_SERVER[HTTP_HOST]" ;
  if(COPYRIGHTS_TXT_ADMIN){
  	print "   „‰ <a href='http://allomani.com/' target='_blank'>  «··Ê„«‰Ì ··Œœ„«  «·»—„ÃÌ… </a> " ;
  	}

  	print "<br><br>

   ≈’œ«— : $version_number <br><br>";
   
   }elseif($global_lang=="kurdish"){       
     print "Licensed For : $_SERVER[SERVER_NAME]" ;
  if(COPYRIGHTS_TXT_ADMIN){
      print "   By  <a href='http://allomani.com/' target='_blank'>Allomani&trade;</a> " ;
      }

      print "<br><br>

   Version : $version_number <br><br>";     
  }else{
  print "Licensed For : $_SERVER[SERVER_NAME]" ;
  if(COPYRIGHTS_TXT_ADMIN){
  	print "   By  <a href='http://allomani.com/' target='_blank'>Allomani&trade;</a> " ;
  	}

  	print "<br><br>

   Version : $version_number <br><br>";
  	}

  print "$phrases[cp_statics] : </b><br> $phrases[singers_count] : $data1[count] <br>  $phrases[songs_count] : $data3[count]
  <br>  $phrases[videos_count] : $data5[count] <br> $phrases[members_count] : $count_members[count] <br>
   $phrases[users_count] : $data4[count] </font></td></tr></table></center>";

 print "<br><center><table width=50% class=grid><td align=center>";
    print "<b><span dir=$global_dir>$phrases[php_version] : </span></b> <span dir=ltr>".phpversion()." </span><br> ";

      print "<b><span dir=$global_dir>$phrases[mysql_version] :</span> </b><span dir=ltr>" . mysql_get_server_info() ."</span><br>";
    if(function_exists('zend_loader_version')){
   print "<b><span dir=$global_dir>$phrases[zend_version] :</span> </b><span dir=ltr>" . @zend_loader_version() ."</span><br><br>";
    }

   if(function_exists("gd_info")){
   $gd_info = @gd_info();
   print "<b>  $phrases[gd_library] : </b> <font color=green> $phrases[cp_available] </font><br>
  <b>$phrases[the_version] : </b> <span dir=ltr>".$gd_info['GD Version'] ."</span>";
  }else{
  print "<b>  $phrases[gd_library] : </b> <font color=red> $phrases[cp_not_available] </font><br>
  $phrases[gd_install_required] ";
          }
   print "</td></tr></table>";

  print "<br><center><table width=50% class=grid><td align=center>
  <p><b> $phrases[cp_addons] </b></p>";

   //--------------- Load Admin Plugins --------------------------
$dhx = opendir(CWD ."/plugins");
  $plgcnt = 0 ;
while ($rdx = readdir($dhx)){
         if($rdx != "." && $rdx != "..") {
                 $cur_fl = CWD ."/plugins/" . $rdx . "/admin.php" ;
        if(file_exists($cur_fl)){
                print $rdx ."<br>" ;
                $plgcnt = 1 ;
                }
          }

    }
closedir($dhx);
if(!$plgcnt){
	print "<center> $phrases[no_addons] </center>";
	}
 print "</td></tr></table>";

if($global_lang=="arabic"){
    print "<br><center><table width=50% class=grid><td align=center>
     Ì ’›Õ «·„Êﬁ⁄ Õ«·Ì« $counter[online_users] “«∆—
                                               <br><br>
   √ﬂ»—  Ê«Ãœ ﬂ«‰  $counter[best_visit] ›Ì : <br> $counter[best_visit_time] <br></td></tr></table>";
}elseif($global_lang=="kurdish"){ 
    print "<br><center><table width=50% class=grid><td align=center>
     Now Browsing : $counter[online_users] Visitor
                                               <br><br>
   Best Visitors Count : $counter[best_visit] in : <br> $counter[best_visit_time] <br></td></tr></table>";

 }else{
 	    print "<br><center><table width=50% class=grid><td align=center>
     Now Browsing : $counter[online_users] Visitor
                                               <br><br>
   Best Visitors Count : $counter[best_visit] in : <br> $counter[best_visit_time] <br></td></tr></table>";

 	}
   }


// ---------------------- Songs_Comments -----------------------------
if($action=="comments" || $action =="comment_add_ok" || $action=="comment_del" || $action=="edit_comment_ok"){

 if_admin();

if($action =="comment_add_ok"){
  db_query("insert into songs_comments (name) values('".db_clean_string($name,"code")."')");
        }

//----------------------------------------------------------
 if($action=="comment_del"){
 if($id){
      db_query("delete from songs_comments where id=$id");
         }
 }
//-----------------------------------------------------------
 if($action=="edit_comment_ok"){

 db_query("update songs_comments set name='".db_clean_string($name,"code")."' where id=$id");
         }
//-----------------------------------------------------------

 print "<center><p class=title>$phrases[the_songs_comments] </p>
   <form method=\"POST\" action=\"index.php\">

   <table width=45% class=grid><tr>
   <td> $phrases[the_comment] :
    <input type=hidden name='action' value='comment_add_ok'>
   <input type=text name=name size=30>
    </td>
    <td><input type=submit value='$phrases[add_button]'></td>
    </tr></table>



   </center><br>";

 $qr = db_query("select * from  songs_comments");
 print "<center><table width=80% class=grid>";
 while($data = db_fetch($qr)){
      print "<tr><td>$data[name]</td>
      <td><a href='index.php?action=comment_edit&id=$data[id]'>$phrases[edit] </a></td>
      <td><a href=\"index.php?action=comment_del&id=$data[id]\" onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a></td>
      ";
         }
       print "</table>";




        }

         //-------------------------------------------------------------
        if($action == "comment_edit"){
              $id=intval($id);
               $qr = db_query("select * from songs_comments where id='$id'");
               if(db_num($qr)){
                   $data = db_fetch($qr);
               print "<center>

                <table border=0 width=\"40%\"  style=\"border-collapse: collapse\" class=grid><tr>

                <form method=\"POST\" action=\"index.php\">

                      <input type=hidden name=\"id\" value='$id'>

                      <input type=hidden name=\"action\" value='edit_comment_ok'> ";


                  print "  <tr>
                                <td width=\"50\">
                <b>$phrases[the_comment]</b></td><td width=\"223\">
                <input type=\"text\" name=\"name\" value=\"".htmlspecialchars($data['name'])."\" size=\"29\"></td>
                        </tr>

                        ";

                              print " <tr>
                                <td colspan=2>
                <center><input type=\"submit\" value=\"$phrases[edit]\">
                        </td>
                        </tr>





                </table>

</form>    </center>\n";
               }else{
                   print_admin_table("<center>$phrases[err_wrong_url]</center>");
               }
                      }

//------------- Videos ----------
require(ADMIN_DIR . "/videos.php");
// ------------ Cats ------------
require(ADMIN_DIR . "/cats.php");   
//---------- Songs -------------
require(ADMIN_DIR . "/songs.php");
//---------- URLs Fields -------------
require(ADMIN_DIR . "/urls_fields.php");
//-------------- Blocks  ---------------
require(ADMIN_DIR . "/blocks.php");
//------------ Votes ---------
require(ADMIN_DIR . "/votes.php");
 //------------- News --------
 require(ADMIN_DIR . "/news.php");
//---------- Members --------
require(ADMIN_DIR . "/members.php");
//-------- Pages ----------
require(ADMIN_DIR . "/pages.php");
//-------- Songs Fields --------
require(ADMIN_DIR . "/songs_fields.php");  
//-------------------- Permisions------------------------
if($action=="permisions"){

    if_admin();

    print " <form method=post action=index.php>
           <input type=hidden value='$id' name='user_id'>
               <input type=hidden value='permisions_edit' name='action'>";

$qr =db_query("select * from songs_cats order by name");
         print "<center><span class=title>$phrases[permissions_manage]</span><br><br>
           <table cellpadding=\"0\" border=0 cellspacing=\"0\" width=\"80%\" class=\"grid\">

        <tr><td>
        <center> $phrases[songs_cats_permissions] </center> <br>";
           $i=0;
           $data2 = db_qr_fetch("select permisions from songs_user where id=$id");
   $user_permisions = split(",",$data2['permisions']);

   while($data = db_fetch($qr)){
           ++$i ;
           if(in_array($data['id'],$user_permisions)){$chk = "checked" ;}else{$chk = "" ;}


          print "<input name=\"cat[$i]\" type=\"checkbox\" value=\"$data[id]\" $chk>$data[name]<br>     \n";
           }
           print "</td></tr>
           </table><br>";

     //------------------------------------------------------------------------------

     //-----------------------------------------------------------------------------------
    $qr =db_query("select * from songs_videos_cats order by name");
             print "
           <table cellpadding=\"0\" border=0 cellspacing=\"0\" width=\"80%\" class=\"grid\">


        <tr><td>
        <center>$phrases[videos_cats_permissions] </center> <br>";
           $i=0;
           $data2 = db_qr_fetch("select permisions_videos from songs_user where id=$id");
   $user_permisions = split(",",$data2['permisions_videos']);

   while($data = db_fetch($qr)){
           ++$i ;
           if(in_array($data['id'],$user_permisions)){$chk = "checked" ;}else{$chk = "" ;}


          print "<input name=\"cat_video[$i]\" type=\"checkbox\" value=\"$data[id]\" $chk>$data[name]<br>     \n";
           }
           print "</td></tr>
           </table><br>";
     //------------------------------------------------------------------------------

     $data =db_qr_fetch("select * from songs_user where id='$id'");


      print "<table cellpadding=\"0\" border=0 cellspacing=\"0\" width=\"80%\" class=\"grid\">
     <tr> <td colspan=5 align=center>$phrases[cp_sections_permissions]</td></tr>
            <tr><td><table width=100%><tr>";

            $prms = explode(",",$data['cp_permisions']);
                      

  if(is_array($permissions_checks)){

  $c=0;
 for($i=0; $i < count($permissions_checks);$i++) {

        $keyvalue = current($permissions_checks);

if($c==4){
    print "</tr><tr>" ;
    $c=0;
    }

if(in_array($keyvalue,$prms)){$chk = "checked" ;}else{$chk = "" ;}

print "<td width=25%><input  name=\"cp_permisions[$i]\" type=\"checkbox\" value=\"$keyvalue\" $chk>".key($permissions_checks)."</td>";


$c++ ;

 next($permissions_checks);
}
  }
print "</tr></table></td>

            </tr></table>";

          print "<center> <br><input type=submit value='$phrases[edit]'></form>" ;

        }
//---------------------------- Users ------------------------------------------
if ($action == "users" or $action=="edituserok" or $action=="adduserok" or $action=="deluser" || $action=="permisions_edit"){


if($action=="permisions_edit"){

        if_admin();

$user_id = intval($user_id);

if($cp_permisions){
foreach ($cp_permisions as $value) {
       $perms .=  "$value," ;
     }
       }else{
               $perms = '' ;
               }

 db_query("update songs_user set cp_permisions='$perms' where id='$user_id'");
 
 
if($cat){
foreach ($cat as $value) {
       $prms .=  "$value," ;
     }
       $prms= substr($prms,0,strlen($prms)-1);
     db_query("update songs_user set permisions='$prms' where id='$user_id'") ;
    }else{
    db_query("update songs_user set permisions='' where id='$user_id'") ;
            }
  if($cat_video){
foreach ($cat_video as $value2) {
       $prms2 .=  "$value2," ;
     }
       $prms2= substr($prms2,0,strlen($prms2)-1);
     db_query("update songs_user set permisions_videos='$prms2' where id=$user_id") ;
    }else{
         db_query("update songs_user set permisions_videos='' where id=$user_id") ;
            }

           }

        //---------------------------------------------
        if ($action=="deluser" && $id){
        if($user_info['groupid']==1 ){
             if($hash == $_SESSION['admin_security']){   
db_query("delete from songs_user where id='$id'");
     
             }else{
                   print_admin_table("<center>$phrases[err_sec_code_not_valid]</center>");   
             }
}else{
        print_admin_table("<center>$phrases[access_denied]</center>");
                          die();
        }
        }
        //---------------------------------------------
        if ($action == "adduserok"){
        if($user_info['groupid']==1){
            
           
              if($hash == $_SESSION['admin_security']){  
           
                if(trim($username) && trim($password)){
                if(db_qr_num("select username from songs_user where username='$username'")){
                        print "<center> $phrases[cp_err_username_exists] </center>";
                        }else{
        db_query("insert into songs_user (username,password,email,group_id) values ('".db_clean_string($username)."','".db_clean_string($password,"code")."','".db_clean_string($email)."','$group_id')");
        }
        }else{
                print "<center>  $phrases[cp_plz_enter_usr_pwd] </center>";
                }
              }else{
                    print_admin_table("<center>$phrases[err_sec_code_not_valid]</center>");
              }   
                
                }else{
                          print_admin_table("<center>$phrases[access_denied]</center>");
                          die();
        }
        }
        //------------------------------------------------------------------------------
        if ($action == "edituserok"){
        
          if($hash == $_SESSION['admin_security']){            
                if ($password){
                $ifeditpassword = ", password='".db_clean_string($password,"code")."'" ;
                }

        if ($user_info['groupid'] == 1){
        db_query("update songs_user set username='".db_clean_string($username)."'  , email='".db_clean_string($email)."' ,group_id='$group_id' $ifeditpassword where id='$id'");
        }else{
         if($user_info['id'] == $id){
        db_query("update songs_user set username='".db_clean_string($username)."'  , email='".db_clean_string($email)."'  $ifeditpassword where id='$id'");

                 }else{
                   print_admin_table("<center>$phrases[access_denied]</center>");
                   die(); 
                         }
                }
        if (mysql_affected_rows()){
                print "<center>  $phrases[cp_edit_user_success]  </center>";
        }
        }else{
             print_admin_table("<center>$phrases[err_sec_code_not_valid]</center>");  
        }
        }

if ($user_info['groupid'] == 1){
print "<img src='images/add.gif'><a href='index.php?action=useradd'>$phrases[cp_add_user]</a>";

//----------------------------------------------------
     print "<p align=center class=title>$phrases[the_users]</p>";
       $result=db_query("select * from songs_user order by id asc");


  print " <center> <table cellpadding=\"0\" border=0 cellspacing=\"0\" width=\"80%\" class=\"grid\">

        <tr>
             <td height=\"18\" width=\"134\" valign=\"top\" align=\"center\">$phrases[cp_username]</td>
                <td height=\"18\" width=\"240\" valign=\"top\">
                <p align=\"center\">$phrases[cp_email]</td>
                <td height=\"18\" width=\"105\" valign=\"top\">
                <p align=\"center\">$phrases[cp_user_group]</td>
                <td height=\"18\" width=\"193\" valign=\"top\" colspan=2>
                <p align=\"center\">$phrases[the_options]</td>
        </tr>";

      while($data = db_fetch($result)){


        if ($data['group_id']==1){$groupname="$phrases[cp_user_admin]";
             $permision_link="";
      }elseif($data['group_id']==2){$groupname="$phrases[cp_user_mod]";
       $permision_link="<a href='index.php?action=permisions&id=$data[id]'>$phrases[permissions_manage]</a>";

      }


        print "<tr>
                <td  width=\"134\" >
                <p align=\"center\">$data[username]</p></td>
                <td  width=\"240\" >
                <p align=\"center\">$data[email]</p></td>
                <td  width=\"105\"><p align=\"center\">$groupname</p></td>
                 <td  width=\"105\"><p align=\"center\">$permision_link</p></td>
                <td  width=\"193\"><p align=\"center\">
                 <a href='index.php?action=edituser&id=$data[id]'> $phrases[edit] </a> ";
        if ($data['id'] !="1"){
                print "- <a href='index.php?action=deluser&id=$data[id]&hash=".$_SESSION['admin_security']."' onClick=\"return confirm('".$phrases['are_you_sure']."');\"> $phrases[delete] </a>";
        }
                print " </p>
                </td>
        </tr>";
          }

print "</table></center>\n";




        }else{

                print "<br><center><table width=70% class=grid><tr><td align=center>
                $phrases[edit_personal_acc_only] <br>
                <a href='index.php?action=edituser'> $phrases[click_here_to_edit_ur_account] </a>
                </td></tr></table></center>";
        }
        }
//-------------------------Edit User------------------------------------------

if ($action=="edituser"){
       $id = intval($id);

if($user_info['groupid']!=1){
        $id=$user_info['id'];
}

$qr=db_query("select * from songs_user where id='$id'") ;
if (db_num($qr)){

$data = db_fetch($qr) ;

print "<center>
<FORM METHOD=\"post\" ACTION=\"index.php\">

 <INPUT TYPE=\"hidden\" NAME=\"id\" \" value=\"$data[id]\" >
<INPUT TYPE=\"hidden\" NAME=\"action\"  value=\"edituserok\" >
<input type=\"hidden\" name='hash' value=\"".$_SESSION['admin_security']."\"> 

 

 <TABLE width=70% class=grid>
    <TR>

   

   <TD width=\"100\"><font color=\"#006699\"><b>$phrases[cp_username] : </b></font> </TD>
   <TD width=\"614\"><INPUT TYPE=\"text\" NAME=\"username\" size=\"32\" value=\"$data[username]\" > </TD>
  </TR>
    <TR>
   <TD width=\"100\"><font color=\"#006699\"><b>$phrases[cp_password] : </b></font> </TD>
   <TD width=\"614\"><INPUT TYPE=\"text\" NAME=\"password\" size=\"32\" onChange=\"passwordStrength(this.value);\" onkeyup=\"passwordStrength(this.value);\"> &nbsp; <input type=button value=\"Generate\" onClick=\"document.getElementById('password').value=GenerateAndValidate(12,1);passwordStrength(document.getElementById('password').value);\">
    <br>* $phrases[leave_blank_for_no_change] </TD>
  </TR>
  <tr><td></td><td>
<div id=\"passwordDescription\">-</div>
<div id=\"passwordStrength\" class=\"strength0\"></div>
</td></tr>
   <TR>
   <TD width=\"100\"><font color=\"#006699\"><b>$phrases[cp_email] : </b></font> </TD>
   <TD width=\"614\"><INPUT TYPE=\"text\" NAME=\"email\" size=\"32\" value=\"$data[email]\" > </TD>
  </TR>\n";

  if($user_info['groupid'] != 1){
          print "<input type='hidden' name='group_id' value='2'>";
  }else {
   print "<TR>
   <TD width=\"100\"><font color=\"#006699\"><b>$phrases[cp_user_group]: </b></font> </TD>
   <TD width=\"614\">\n";


if ($data['group_id'] == 1){$ifselected1 = "selected" ; }else{$ifselected2 = "selected";}

print "  <p><select size=\"1\" name=group_id>\n
        <option value='1' $ifselected1> $phrases[cp_user_admin] </option>
  <option value='2' $ifselected2>$phrases[cp_user_mod] </option>" ;


 print "  </select>";
  }

   print "</TD>
  </TR>

    
  <TR>
   <TD COLSPAN=\"2\" width=\"685\">
   <p align=\"center\"><INPUT TYPE=\"submit\" name=\"usereditbutton\" VALUE=\"$phrases[edit]\"></TD>
  </TR>
 </TABLE>
</FORM>
</center>\n";


}else{
    print "<center> $phrases[err_wrong_url]</center>" ;
    }
}
//--------------------- Add User Form -------------------------------------------------------
if($action=="useradd"){
print "   <br>
   <center>

<FORM METHOD=\"post\" ACTION=\"index.php\">
<INPUT TYPE=\"hidden\" NAME=\"action\"  value=\"adduserok\" >      
<input type=\"hidden\" name='hash' value=\"".$_SESSION['admin_security']."\">
 
 <TABLE width=\"70%\" class=grid>
    <TR>
   <td colspan=2 align=center><span class=title> $phrases[cp_add_user] </span></td></tr>
   <tr>


   <TD width=\"150\"><font color=\"#006699\"><b>$phrases[cp_username]: </b></font> </TD>
   <TD ><INPUT TYPE=\"text\" NAME=\"username\" size=\"32\"  </TD>
  </TR>
    <TR>
   <TD width=\"150\"><font color=\"#006699\"><b>$phrases[cp_password] : </b></font> </TD>
   <TD ><INPUT TYPE=\"text\" NAME=\"password\" size=\"32\" onChange=\"passwordStrength(this.value);\" onkeyup=\"passwordStrength(this.value);\"> &nbsp; <input type=button value=\"Generate\" onClick=\"document.getElementById('password').value=GenerateAndValidate(12,1);passwordStrength(document.getElementById('password').value);\"> </TD>
  </TR>
  <tr><td></td><td>
<div id=\"passwordDescription\">-</div>
<div id=\"passwordStrength\" class=\"strength0\"></div>
</td></tr>
   <TR>
   <TD width=\"150\"><font color=\"#006699\"><b>$phrases[cp_email] : </b></font> </TD>
   <TD ><INPUT TYPE=\"text\" NAME=\"email\" size=\"32\" > </TD>
  </TR>

   <TR>
   <TD width=\"150\"><font color=\"#006699\"><b>$phrases[cp_user_group]: </b></font> </TD>
   <TD >\n";


print "  <p><select size=\"1\" name=group_id>\n
        <option value='1' > $phrases[cp_user_admin] </option>
  <option value='2' > $phrases[cp_user_mod]</option>" ;


 print "  </select>";


  print " </TD>
  </TR>
          
  <TR>
   <TD COLSPAN=\"2\" >
   <p align=\"center\"><INPUT TYPE=\"submit\" name=\"useraddbutton\" VALUE=\"$phrases[add_button]\"></TD>
  </TR>
 </TABLE>
</FORM>
</center><br><br>\n";
}

//---------- Banners -----------
require("banners.php");
 //----------------------plugins ----------------------------
if($action=="hooks" || $action=="hook_disable" || $action=="hook_enable" || $action=="hook_add_ok" || $action=="hook_edit_ok" || $action=="hook_del" || $action=="hooks_fix_order"){


    if_admin();
//--------- hook add ---------------
if($action=="hook_add_ok"){
db_query("insert into songs_hooks (name,hookid,code,ord,active) values (
'".db_clean_string($name,"text")."',
'".db_clean_string($hookid,"text")."',
'".db_clean_string($code,"code")."',
'".db_clean_string($ord,"num")."','1')");
}
//------- hook edit ------------
if($action=="hook_edit_ok"){
db_query("update songs_hooks set
name='".db_clean_string($name)."',
hookid='".db_clean_string($hookid)."',
code='".db_clean_string($code,"code")."',
ord='".db_clean_string($ord,"num")."' where id='".intval($id)."'");
}
//--------- hook del --------
if($action=="hook_del"){
    db_query("delete from songs_hooks where id='".intval($id)."'");
    }
//--------- enable / disable -----------------
if($action=="hook_disable"){
        db_query("update songs_hooks set active=0 where id='".intval($id)."'");
        }

if($action=="hook_enable"){

       db_query("update songs_hooks set active=1 where id='".intval($id)."'");
        }
//-------- fix order -----------
if($action=="hooks_fix_order"){

   $qr=db_query("select hookid,id from songs_hooks order by hookid,ord ASC");
    if(db_num($qr)){
    $hook_c = 1 ;
    while($data = db_fetch($qr)){

    if($last_hookid !=$data['hookid']){$hook_c=1;}

    db_query("update songs_hooks set ord='$hook_c' where id='$data[id]'");
     $last_hookid = $data['hookid'];
    ++$hook_c;
    }
     }
     unset($last_hookid);
     }
//---------------------------------------------


$qr =db_query("select * from songs_hooks order by hookid,ord,active");

print "<center><p class=title> $phrases[cp_hooks] </p>

<p align=$global_align><a href='index.php?action=hook_add'><img src='images/add.gif' border=0> $phrases[add] </a></p>";

if(db_num($qr)){
              print "<table width=80% class=grid><tr>";

print "<tr><td><b>$phrases[the_name]</b></td><td><b>$phrases[the_order]</b></td><td><b>$phrases[the_place]</b></td><td><b>$phrases[the_options]</b></td></tr>";
while($data = db_fetch($qr)){

     if($last_hookid !=$data['hookid']){print "<tr><td colspan=4><hr class=separate_line></td></tr>";}

print "<tr><td>$data[name]</td><td><b>$data[ord]</b></td><td>$data[hookid]</td><td>";
 if($data['active']){
                        print "<a href='index.php?action=hook_disable&id=$data[id]'>$phrases[disable]</a>" ;
                        }else{
                        print "<a href='index.php?action=hook_enable&id=$data[id]'>$phrases[enable]</a>" ;
                        }

print "- <a href='index.php?action=hook_edit&id=$data[id]'>$phrases[edit] </a>
- <a href='index.php?action=hook_del&id=$data[id]' onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete] </a>
</td></tr>";


    $last_hookid = $data['hookid'];
    }

          print "</table>
 <br><form action='index.php' method=post>
                <input type=hidden name=action value='hooks_fix_order'>
                <input type=submit value=' $phrases[cp_hooks_fix_order] '>
                </form></center>";

}else{
print "<table width=80% class=grid><tr>
    <tr><td align=center>  $phrases[no_hooks] </td></tr>
    </table></center>";
    }

}

//-------- add hook -------
if($action=="hook_add"){

    if_admin();

print "<center>
<form action='index.php' method=post>
<input type=hidden name=action value='hook_add_ok'>
<table width=80% class=grid>
<tr><td><b>$phrases[the_name]</b></td><td><input type=text size=20 name=name></td></tr>
<tr><td><b>$phrases[the_place]</b></td><td>";
$hooklocations = get_plugins_hooks();
print_select_row("hookid",$hooklocations,"","dir=ltr");
print "</td></tr>
  <tr>
              <td width=\"70\">
                <b>$phrases[the_code]</b></td><td width=\"223\">
                  <textarea name='code' rows=20 cols=45 dir=ltr ></textarea></td>
                        </tr>
<tr><td><b>$phrases[the_order]</b></td><td><input type=text size=3 name=ord value='0'></td></tr>
<tr><td colspan=2 align=center><input type=submit value=' $phrases[add_button] '></td></tr>
</table>
</form></center>";
}

//-------- edit hook -------
if($action=="hook_edit"){

    if_admin();
$id=intval($id);

$qr = db_query("select * from songs_hooks where id='$id'");

if(db_num($qr)){
    $data = db_fetch($qr);
print "<center>
<form action='index.php' method=post>
<input type=hidden name=action value='hook_edit_ok'>
<input type=hidden name=id value='$id'>
<table width=80% class=grid>
<tr><td><b>$phrases[the_name]</b></td><td><input type=text size=20 name=name value=\"$data[name]\"></td></tr>
<tr><td><b>$phrases[the_place]</b></td><td>";
$hooklocations = get_plugins_hooks();
print_select_row("hookid",$hooklocations,"$data[hookid]","dir=ltr");
print "</td></tr>
  <tr>
              <td width=\"70\">
                <b>$phrases[the_code]</b></td><td width=\"223\">
                  <textarea name='code' rows=20 cols=45 dir=ltr >".htmlspecialchars($data['code'])."</textarea></td>
                        </tr>
<tr><td><b>$phrases[the_order]</b></td><td><input type=text size=3 name=ord value=\"$data[ord]\"></td></tr>
<tr><td colspan=2 align=center><input type=submit value=' $phrases[edit] '></td></tr>
</table>
</form></center>";
}else{
print "<center><table width=50% class=grid><tr><td align=center>$phrases[err_wrong_url]</td></tr></table></center>";
}
}         
//------------------- DATABASE BACKUP --------------------------
if($action=="backup_db_do"){
    $output = htmlspecialchars($output) ;
print "<br><center> <table width=50% class=grid><tr><td align=center>  $output </td></tr></table>";
}

  if($action=="backup_db"){

   if_admin();
      print "<br><center>
      <p align=center class=title> $phrases[cp_db_backup] </p>

      <form action=index.php method=post>
      <input type=hidden name=action value='backup_db_do'>
      <table width=50% class=grid><tr><td>
      <input type=\"radio\" name=op value='local' checked onclick=\"document.getElementById('backup_server').style.display = 'none';\"> $phrases[db_backup_saveto_pc]
      <br><input type=\"radio\" name=op value='server' onclick=\"document.getElementById('backup_server').style.display = 'inline';\" > $phrases[db_backup_saveto_server]
      </td></tr>
      <tr><td>
      <div id=backup_server style=\"display: none; text-decoration: none\">
      <b> $phrases[the_file_path] : &nbsp; </b> <input type=text name=filename dir=ltr size=40 value='admin/backup/songs_".date("d-m-Y-h-i-s").".sql.gz'>
      </div>
     </td></tr><tr> <td align=center>
      <input type=submit value=' $phrases[cp_db_backup_do] '>
      </form></td></tr></table></center>";

          }
// ----------------- Repair Database -----------------------

if($action=="db_info"){

    if_admin();

if(!$disable_repair){
print "<script language=\"JavaScript\">\n";
print "function checkAll(form){\n";
print "  for (var i = 0; i < form.elements.length; i++){\n";
print "    eval(\"form.elements[\" + i + \"].checked = form.elements[0].checked\");\n";
print "  }\n";
print "}\n";
print "</script>\n";

        $tables = db_query("SHOW TABLE STATUS");
        print "<form name=\"form1\" method=\"post\" action=\"index.php\"/>
        <input type=hidden name=action value='repair_db_ok'>
        <center><table width=\"96%\"  class=grid>";
        print "<tr><td colspan=\"5\"> <font size=4><b>$phrases[the_database]</b></font> </td></tr>
        <tr><td>
        <input type=\"checkbox\" name=\"check_all\" checked=\"checked\" onClick=\"checkAll(this.form)\"/></td>
        ";
        print "<td><b>$phrases[the_table]</b></td><td><b>$phrases[the_size]</b></td>
        <td><b>$phrases[the_status]</b></td>
            </tr>";
        while($table = db_fetch($tables))
        {
            $size = round($table['Data_length']/1024, 2);
            $status = db_qr_fetch("ANALYZE TABLE `$table[Name]`");
            print "<tr>
            <td  width=\"5%\"><input type=\"checkbox\" name=\"check[]\" value=\"$table[Name]\" checked=\"checked\" /></td>
            <td width=\"50%\">$table[Name]</td>
            <td width=\"10%\" align=left dir=ltr>$size KB</td>
            <td>$status[Msg_text]</td>
            </tr>";
        }

        print "</table><br> <center><input type=\"submit\" name=\"submit\" value=\"$phrases[db_repair_tables_do]\" /></center> <br>
        </form>";
        }else{
              print_admin_table("<center> $disable_repair </center>") ;
            }
    }
//------------------------------------------------
    if($action=="repair_db_ok"){
       if_admin();

    if(!$disable_repair){
        if(!$check){
            print "<center><table width=50% class=grid><tr><td align=center> $phrases[please_select_tables_to_rapair] </td></tr></table></center>";
    }else{
        $tables = $_POST['check'];
        print "<center><table width=\"60%\"  class=grid>";

        foreach($tables as $table)
        {
            $query = db_query("REPAIR TABLE `". $table . "`");
            $que = db_fetch($query);
            print "<tr><td width=\"20%\">";
            print "$phrases[cp_repairing_table] " . $que['Table'] . " , <font color=green><b>$phrases[done]</b></font>";
            print "</td></tr>";
        }

        print "</table></center>";

        }

        }else{
              print_admin_table("<center> $disable_repair </center>") ;
            }
    }

                       //--------------------- Templates ----------------------------------

  if($action =="templates" || $action =="template_edit_ok" || $action=="template_del" ||
  $action =="template_add_ok" || $action=="template_cat_edit_ok" || $action=="template_cat_add_ok" ||
  $action=="template_cat_del"){

 if_admin("templates");
 $id=intval($id);
 $cat =intval($cat);

 if($action != "templates"){
       if($hash != $_SESSION['admin_security']){ 
             print_admin_table("<center>$phrases[err_sec_code_not_valid]</center>");   
             die();
       }
 }
 
 //------- template cat edit ---------
 if($action=="template_cat_edit_ok"){
      
 if(trim($name)){
 db_query("update songs_templates_cats set name='".db_clean_string($name)."',selectable='".db_clean_string($selectable,"num")."' where id='$id'");
     }
     
 }
//------ template cat add ----------
if($action=="template_cat_add_ok"){
db_query("insert into songs_templates_cats (name,selectable) values('".db_clean_string($name)."','".db_clean_string($selectable,"num")."')");
$catid = mysql_insert_id();

$qr = db_query("select * from songs_templates where cat='1' order by id");
while($data = db_fetch($qr)){
db_query("insert into songs_templates (name,title,content,cat,protected) values (
'".db_clean_string($data['name'])."',
'".db_clean_string($data['title'])."',
'".db_clean_string($data['content'],"code","write",false)."',
'$catid','".intval($data['protected'])."')");
    }

}
//--------- template cat del --------
if($action=="template_cat_del"){
if($id !="1"){
db_query("delete from songs_templates where cat='$id'");
db_query("delete from songs_templates_cats where id='$id'");
     }
    }
//-------- template edit -----------
if($action =="template_edit_ok"){
db_query("update songs_templates set title='".db_clean_string($title)."',content='".db_clean_string($content,"code")."' where id='$id'");
}
//--------- template add ------------
if($action =="template_add_ok"){
db_query("insert into  songs_templates (name,title,content,cat) values(
'".db_clean_string($name)."',
'".db_clean_string($title)."',
'".db_clean_string($content,"code")."',
'".intval($cat)."')");
}
//---------- template del ---------
if($action=="template_del"){
      db_query("delete from songs_templates where id='$id' and protected=0");
      db_query("update songs_blocks set template=0 where template='$id'");
}

print "<center>
  <p class=title>  $phrases[the_templates] </p> ";


  if($cat){

$cat_data = db_qr_fetch("select name from songs_templates_cats where id='$cat'");

print "<p align=$global_align><img src='images/link.gif'><a href='index.php?action=templates'>$phrases[the_templates] </a> / $cat_data[name]</p>";


         $qr = db_query("select * from songs_templates where cat='$cat' order by id");
        if (db_num($qr)){
      print "<p align='$global_align'><img src='images/add.gif'> <a href='index.php?action=template_add&cat=$cat'> $phrases[cp_add_new_template] </a></p>
      <br>
      <center>
  <table width=80% class=grid>" ;

   $trx = 1;
    while($data=db_fetch($qr)){
    if($trx == 1){
        $tr_color = "#FFFFFF";
        $trx=2;
        }else{
        $tr_color = "#F2F2F2";
        $trx=1;
        }
    print "<tr bgcolor=$tr_color><td><b>$data[name]</b><br><span class=small>$data[title]</span></td>
   <td align=center> <a href='index.php?action=template_edit&id=$data[id]'> $phrases[edit] </a>";
    if($data['protected']==0){
            print " - <a href='index.php?action=template_del&id=$data[id]&cat=$cat&hash=".$_SESSION['admin_security']."' onclick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a>";
            }
            print "</td></tr>";

     }
      print "</table>";

                }else{
                    print_admin_table($phrases['cp_no_templates']);
                     }

}else{
    $qr = db_query("select * from songs_templates_cats order by id asc");
     print "<p align='$global_align'><img src='images/add.gif'> <a href='index.php?action=template_cat_add'> $phrases[add_style] </a></p>
      <br>
    <center><table width=60% class=grid>";
    while($data =db_fetch($qr)){
    print "<tr><td><a href='index.php?action=templates&cat=$data[id]'>$data[name]</a></td>
    <td align=center> <a href='index.php?action=template_cat_edit&id=$data[id]'> $phrases[style_settings] </a>";
    if($data['id']!=1){
            print " - <a href='index.php?action=template_cat_del&id=$data[id]&hash=".$_SESSION['admin_security']."' onclick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a>";
            }
            print "</td></tr>";
    }
    print "</table></center>";
}



          }
  //--------template cat edit --------
  if($action=="template_cat_edit"){
    if_admin("templates");

      $id= intval($id);
$qr= db_query("select * from songs_templates_cats where id='$id'");
 print  "<p class=title align=center>  $phrases[the_templates] </p> ";
if(db_num($qr)){
$data = db_fetch($qr);
 print "<center>
 <form action=index.php method=post>
 <input type=hidden name=action value='template_cat_edit_ok'>
 <input type=hidden name=id value='$id'>
 <input type=\"hidden\" name='hash' value=\"".$_SESSION['admin_security']."\"> 
 
 <table width=70% class=grid>
 <tr><td><b>$phrases[the_name]</b></td>
 <td>";
 print_text_row("name",$data['name']);
 print "</td></tr>
 <tr><td><b>$phrases[style_selectable]</b></td><td>";
 print_select_row("selectable",array("$phrases[no]","$phrases[yes]"),$data['selectable']);
 print "</td></tr>
 <tr><td align=center colspan=2><input type=submit value=' $phrases[edit] '></td></tr>
 </table>";
}else{
    print_admin_table($phrases['err_wrong_url']);
    }
  }
  //--------template cat add --------
  if($action=="template_cat_add"){
    if_admin("templates");

print  "<p class=title align=center>  $phrases[the_templates] </p> ";

print "<center>
 <form action=index.php method=post>
 <input type=hidden name=action value='template_cat_add_ok'>
 <input type=\"hidden\" name='hash' value=\"".$_SESSION['admin_security']."\"> 
 
 <table width=70% class=grid>
 <tr><td><b>$phrases[the_name]</b></td>
 <td>";
 print_text_row("name");
 print "</td></tr>
 <tr><td><b>$phrases[style_selectable]</b></td><td>";
 print_select_row("selectable",array("$phrases[no]","$phrases[yes]"));
 print "</td></tr>
 <tr><td align=center colspan=2><input type=submit value=' $phrases[add_button] '></td></tr>
 </table>";

  }
 //-------- template edit ------------
          if($action=="template_edit"){
    if_admin("templates");
   $id=intval($id);
$qr = db_query("select * from songs_templates where id='$id'");
      if(db_num($qr)){
      $data = db_fetch($qr);
    $data['content'] = htmlspecialchars($data['content']);
print "
  <center>
          <span class=title>$data[name]</span>  <br><br>
  <form method=\"POST\" action=\"index.php\">
  <input type='hidden' name='action' value='template_edit_ok'>
  <input type='hidden' name='id' value='$data[id]'>
   <input type='hidden' name='cat' value='$data[cat]'>
   <input type=\"hidden\" name='hash' value=\"".$_SESSION['admin_security']."\"> 
   

  <table width=80% class=grid><tr>
  <td> <b> $phrases[template_name] : </b></td><td>$data[name]</td></tr>
  <tr>
  <td> <b> $phrases[template_description] : </b></td><td><input type=text size=30 name=title value='$data[title]'></td></tr>
   <tr><td colspan=2 align=center>
        <textarea dir=ltr rows=\"20\" name=\"content\" cols=\"70\">$data[content]</textarea></td></tr>
        <tr><td colspan=2 align=center>
        <input type=\"submit\" value=\" $phrases[edit] \" name=\"B1\"></td></tr>
        </table>
</form></center>\n";
}else{
print_admin_table($phrases['err_wrong_url']);
        }
 }
//------------ template add ------------
  if($action=="template_add"){
if_admin("templates");
print "
  <center>
          <span class=title>$phrases[add_new_template] </span>  <br><br>
  <form method=\"POST\" action=\"index.php\">
  <input type='hidden' name='action' value='template_add_ok'>
  <input type='hidden' name='cat' value='".intval($cat)."'>
  <input type=\"hidden\" name='hash' value=\"".$_SESSION['admin_security']."\"> 
  
  <table width=80% class=grid><tr>
  <td> <b> $phrases[template_name] : </b></td><td><input type=text size=30 name=name></td></tr>
  <tr>
  <td> <b> $phrases[template_description] : </b></td><td><input type=text size=30 name=title></td></tr>
   <tr><td colspan=2 align=center>
        <textarea dir=ltr rows=\"20\" name=\"content\" cols=\"70\"></textarea></td></tr>
        <tr><td colspan=2 align=center>
        <input type=\"submit\" value=\"$phrases[add_button]\" name=\"B1\"></td></tr>
        </table>
</form></center>\n";

 }


 //----------------------- Settings --------------------------------
 if($action == "settings" || $action=="settings_edit"){
 
     if_admin();


 if($action=="settings_edit"){
     
      if($hash != $_SESSION['admin_security']){ 
             print_admin_table("<center>$phrases[err_sec_code_not_valid]</center>");   
             die();
       }
 
  if(is_array($stng)){
 for($i=0;$i<count($stng);$i++) {

        $keyvalue = current($stng);

       db_query("update songs_settings set value='$keyvalue' where name='".key($stng)."'");


 next($stng);
}
}

         }


 load_settings();
 

 print "<center>
 <p align=center class=title>  $phrases[the_settings] </p>
 <form action=index.php method=post>
 <input type=hidden name=action value='settings_edit'>
  <input type=\"hidden\" name='hash' value=\"".$_SESSION['admin_security']."\">
  
 <table width=70% class=grid>
  
 <tr><td>  $phrases[site_name] : </td><td><input type=text name=stng[sitename] size=30 value='$settings[sitename]'> &nbsp; </td></tr>
  <tr><td> $phrases[show_sitename_in_subpages] </td><td>";
  print_select_row("stng[sitename_in_subpages]",array($phrases['no'],$phrases['yes']),$settings['sitename_in_subpages']);
  print "</td></tr>
 
 
 <tr><td>  $phrases[section_name] : </td><td><input type=text name=stng[section_name] size=30 value='$settings[section_name]'></td></tr>
 <tr><td> $phrases[show_section_name_in_subpages] </td><td>";
  print_select_row("stng[section_name_in_subpages]",array($phrases['no'],$phrases['yes']),$settings['section_name_in_subpages']);
  print "</td></tr>
 
  <tr><td>  $phrases[copyrights_sitename] : </td><td><input type=text name=stng[copyrights_sitename] size=30 value='$settings[copyrights_sitename]'></td></tr>
   <tr><td>  $phrases[mailing_email] : </td><td><input type=text dir=ltr name=stng[mailing_email] size=30 value='$settings[mailing_email]'></td></tr>

 <tr><td> $phrases[page_dir] : </td><td><select name=stng[html_dir]>" ;
 if($settings['html_dir'] == "rtl"){$chk1 = "selected" ; $chk2=""; }else{ $chk2 = "selected" ; $chk1="";}
 print "<option value='rtl' $chk1>$phrases[right_to_left]</option>
 <option value='ltr' $chk2>$phrases[left_to_right]</option>
 </select>
 </td></tr>
  <tr><td>  $phrases[pages_lang] : </td><td><input type=text name=stng[site_pages_lang] size=30 value='$settings[site_pages_lang]'></td></tr>
    <tr><td>  $phrases[pages_encoding] : </td><td><input type=text name=stng[site_pages_encoding] size=30 value='$settings[site_pages_encoding]'></td></tr>
  <tr><td> $phrases[page_keywords] : </td><td><input type=text name=stng[header_keywords] size=30 value='$settings[header_keywords]'></td></tr>

  </table>
   <br>
   <table width=70% class=grid>
  <tr><td>  $phrases[cp_enable_browsing]</td><td><select name=stng[enable_browsing]>";
  if($settings['enable_browsing']=="1"){$chk1="selected";$chk2="";}else{$chk1="";$chk2="selected";}
  print "<option value='1' $chk1>$phrases[cp_opened]</option>
  <option value='0' $chk2>$phrases[cp_closed]</option>
  </select></td></tr>
  <tr><td>$phrases[cp_browsing_closing_msg]</td><td><textarea cols=30 rows=5 name=stng[disable_browsing_msg]>$settings[disable_browsing_msg]</textarea>
  </td></tr>
   </table>
   <br>
   <table width=70% class=grid>
 <tr><td>  $phrases[adding_songs_fields_count] : </td><td><input type=text name=stng[songs_add_limit] size=5 value='$settings[songs_add_limit]'></td></tr>
  <tr><td>  $phrases[songs_perpage] : </td><td><input type=text name=stng[songs_perpage] size=5 value='$settings[songs_perpage]'></td></tr>
    <tr><td>  $phrases[videos_perpage] : </td><td><input type=text name=stng[videos_perpage] size=5 value='$settings[videos_perpage]'></td></tr>
 
 
  <tr><td>  $phrases[news_perpage] : </td><td><input type=text name=stng[news_perpage] size=5 value='$settings[news_perpage]'></td></tr>

 
 <tr><td>  $phrases[images_cells_count] : </td><td><input type=text name=stng[songs_cells] size=5 value='$settings[songs_cells]'></td></tr>
<tr><td>  $phrases[votes_expire_time] : </td><td><input type=text name=stng[votes_expire_hours] size=5 value='$settings[votes_expire_hours]'> $phrases[hour] </td></tr>
<tr><td> $phrases[vote_files_expire_time] : </td><td><input type=text name=stng[vote_file_expire_hours] size=5 value='$settings[vote_file_expire_hours]'> $phrases[hour] </td></tr>

    </table>
     <br>
   <table width=70% class=grid>
   <tr><td> $phrases[visitors_can_sort_songs] : </td><td>" ;
 print_select_row("stng[visitors_can_sort_songs]",array($phrases['no'],$phrases['yes']),$settings['visitors_can_sort_songs']);
 print "</td></tr>
 <tr><td>$phrases[songs_default_orderby] : </td><td>
<select size=\"1\" name=\"stng[songs_default_orderby]\">";
for($i=0; $i < count($orderby_checks);$i++) {

$keyvalue = current($orderby_checks);
if($keyvalue==$settings['songs_default_orderby']){$chk="selected";}else{$chk="";}

print "<option value=\"$keyvalue\" $chk>".key($orderby_checks)."</option>";;

 next($orderby_checks);
}
print "</select>&nbsp;&nbsp; <select name=stng[songs_default_sort]> ";
if($settings['songs_default_sort']=="asc"){$chk1="selected";$chk2="";}else{$chk1="";$chk2="selected";}
print "<option value='asc' $chk1>$phrases[asc]</option>
<option value='desc' $chk2>$phrases[desc]</option>
</select>
</td></tr>
   </table>
    <br>
   <table width=70% class=grid>
  <tr><td> $phrases[stng_singers_letters] : </td><td><select name=stng[letters_singers]>" ;
 if($settings['letters_singers']){$chk1 = "selected" ; $chk2=""; }else{ $chk2 = "selected" ; $chk1="";}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>
 <tr><td> $phrases[stng_songs_letters] : </td><td><select name=stng[letters_songs]>" ;
 if($settings['letters_songs']){$chk1 = "selected" ; $chk2=""; }else{ $chk2 = "selected" ; $chk1="";}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>
 <tr><td> $phrases[stng_songs_multi_select]</td><td><select name=stng[songs_multi_select]>";
 if($settings['songs_multi_select']){$chk1 = "selected" ; $chk2=""; }else{ $chk2 = "selected" ; $chk1="";}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>
 <tr><td>  $phrases[stng_vote_songs] : </td><td><select name=stng[vote_song]>" ;
 if($settings['vote_song']){$chk1 = "selected" ; $chk2=""; }else{ $chk2 = "selected" ; $chk1="";}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>
 <tr><td> $phrases[stng_send_song] : </td><td><select name=stng[snd2friend]>" ;
 if($settings['snd2friend']){$chk3 = "selected" ; $chk4 ="" ;}else{ $chk4 = "selected" ; $chk3 ="" ;}
 print "<option value=1 $chk3>$phrases[enabled]</option>
 <option value=0 $chk4>$phrases[disabled]</option>
 </select>
 </td></tr>

 <tr><td>$phrases[stng_group_singers_by_letters] : </td><td><select name=stng[singers_groups]>" ;
 if($settings['singers_groups']){$chk3 = "selected" ; $chk4 ="" ;}else{ $chk4 = "selected" ; $chk3 ="" ;}
 print "<option value=1 $chk3>$phrases[enabled]</option>
 <option value=0 $chk4>$phrases[disabled]</option>
 </select>
 </td></tr>
     </table>
    <br>
   <table width=70% class=grid>
   <tr><td>  $phrases[stng_vote_videos] : </td><td><select name=stng[vote_clip]>" ;
 if($settings['vote_clip']){$chk1 = "selected" ; $chk2=""; }else{ $chk2 = "selected" ; $chk1="";}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>
 <tr><td> $phrases[stng_send_videos] : </td><td><select name=stng[snd2friend_clip]>" ;
 if($settings['snd2friend_clip']){$chk3 = "selected" ;  $chk4 ="" ;}else{ $chk4 = "selected" ; $chk3 ="" ;}
 print "<option value=1 $chk3>$phrases[enabled]</option>
 <option value=0 $chk4>$phrases[disabled]</option>
 </select>
 </td></tr>

 </table>
 <br>
   <table width=70% class=grid>
   <tr><td colspan=2><b> $phrases[the_listen_file] </b></td></tr>
 <tr><td>  $phrases[ram_banner_width] : </td><td><input type=text name=stng[ramadv_width] size=5 value='$settings[ramadv_width]'></td></tr>
  <tr><td>  $phrases[ram_banner_height] : </td><td><input type=text name=stng[ramadv_height] size=5 value='$settings[ramadv_height]'></td></tr>
</table>
                      <br>
 <table width=70% class=grid>

 <tr><td>$phrases[the_search] : </td><td><select name=stng[enable_search]>" ;
 if($settings['enable_search']){$chk1 = "selected" ; $chk2 ="" ;}else{ $chk2 = "selected" ; $chk1 ="" ;}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>

<tr><td>  $phrases[search_min_letters] : </td><td><input type=text name=stng[search_min_letters] size=5 value='$settings[search_min_letters]'>  </td></tr>

   </table>
   <br>
 <table width=70% class=grid>
  <tr><td>$phrases[default_style]</td><td><select name=stng[default_styleid]>";
  $qrt=db_query("select * from songs_templates_cats order by id asc");
while($datat =db_fetch($qrt)){
print "<option value=\"$datat[id]\"".iif($settings['default_styleid']==$datat['id']," selected").">$datat[name]</option>";
}
  print "</select>
  </td>
 </table>
                     <br>
 <table width=70% class=grid>


 <tr><td>$phrases[os_and_browsers_statics] : </td><td><select name=stng[count_visitors_info]>" ;
 if($settings['count_visitors_info']){$chk1 = "selected" ; $chk2 ="" ;}else{ $chk2 = "selected" ; $chk1 ="" ;}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>

  <tr><td>$phrases[visitors_hits_statics] : </td><td><select name=stng[count_visitors_hits]>" ;
 if($settings['count_visitors_hits']){$chk1 = "selected" ; $chk2 ="" ;}else{ $chk2 = "selected" ; $chk1 ="" ;}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>

  <tr><td>$phrases[online_visitors_statics] : </td><td><select name=stng[count_online_visitors]>" ;
 if($settings['count_online_visitors']){$chk1 = "selected" ; $chk2 ="" ;}else{ $chk2 = "selected" ; $chk1 ="" ;}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>


    </table>
                     
                     <br>
 <table width=70% class=grid>
    <tr><td>$phrases[registration] : </td><td><select name=stng[members_register]>" ;
 if($settings['members_register']){$chk1 = "selected" ; $chk2 ="" ;}else{ $chk2 = "selected" ; $chk1 ="" ;}
 print "<option value=1 $chk1>$phrases[cp_opened]</option>
 <option value=0 $chk2>$phrases[cp_closed]</option>
 </select>
 </td></tr>


   <tr><td>$phrases[stng_download_for_members_only] : </td><td><select name=stng[member_download_only]>" ;
 if($settings['member_download_only']==1){
     $chk1 = "" ; $chk2 ="" ; $chk3="selected";
     }elseif($settings['member_download_only']==2){
         $chk1 = "" ; $chk2 ="selected" ; $chk3="";
         }
 else{ $chk1 ="selected" ; $chk2 = "" ; $chk3="";}

 print "
  <option value=0 $chk1>$phrases[disabled]</option>
  <option value=2 $chk2>$phrases[as_every_cat_settings]</option>
 <option value=1 $chk3>$phrases[enabled_for_all]</option>

 </select>
 </td></tr>

 <tr><td>$phrases[stng_videos_download_for_members_only] : </td><td><select name=stng[videos_member_download_only]>" ;
 if($settings['videos_member_download_only']==1){
     $chk1 = "" ; $chk2 ="" ; $chk3="selected";
     }elseif($settings['videos_member_download_only']==2){
         $chk1 = "" ; $chk2 ="selected" ; $chk3="";
         }
 else{ $chk1 ="selected" ; $chk2 = "" ; $chk3="";}

 print "
  <option value=0 $chk1>$phrases[disabled]</option>
  <option value=2 $chk2>$phrases[as_every_cat_settings]</option>
 <option value=1 $chk3>$phrases[enabled_for_all]</option>

 </select>
 </td></tr>
 
  <tr><td>$phrases[security_code_in_registration] : </td><td><select name=stng[register_sec_code]>" ;
 if($settings['register_sec_code']){$chk1 = "selected" ; $chk2 ="" ;}else{ $chk2 = "selected" ; $chk1 ="" ;}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>

 <tr><td>$phrases[auto_email_activate]: </td><td><select name=stng[auto_email_activate]>" ;
 if($settings['auto_email_activate']){$chk1 = "selected" ; $chk2 ="" ;}else{ $chk2 = "selected" ; $chk1 ="" ;}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>

 <tr><td>  $phrases[msgs_count_limit] : </td><td><input type=text name=stng[msgs_count_limit] size=5 value='$settings[msgs_count_limit]'>  $phrases[message] </td></tr>

<tr><td>  $phrases[username_min_letters] : </td><td><input type=text name=stng[register_username_min_letters] size=5 value='$settings[register_username_min_letters]'> </td></tr>

<tr><td> $phrases[username_exludes] : </td><td><input type=text name=stng[register_username_exclude_list] dir=ltr size=20 value='$settings[register_username_exclude_list]'> </td></tr>


  </table>
                     <br>
                   
 <table width=70% class=grid>
 <tr><td>$phrases[emails_msgs_default_type] : </td><td><select name=stng[mailing_default_use_html]>" ;
 if($settings['mailing_default_use_html']){$chk1 = "selected" ; $chk2 ="" ;}else{ $chk2 = "selected" ; $chk1 ="" ;}
 print "<option value=1 $chk1>HTML</option>
 <option value=0 $chk2>TEXT</option>
 </select>
 </td></tr>
 <tr><td> $phrases[emails_msgs_default_encoding] : </td><td><input type=text name=stng[mailing_default_encoding] size=20 value='$settings[mailing_default_encoding]'> <br> * $phrases[leave_blank_to_use_site_encoding]</td></tr>
</table>";


   //--------------- Load Settings Plugins --------------------------
$dhx = opendir(CWD ."/plugins");
while ($rdx = readdir($dhx)){
         if($rdx != "." && $rdx != "..") {
                 $cur_fl = CWD ."/plugins/" . $rdx . "/settings.php" ;
        if(file_exists($cur_fl)){
        print "  <br>

 <table width=70% class=grid>";

                include $cur_fl ;
        print "</table>";

                }
          }

    }
closedir($dhx);
//----------------------------------------------------------------

  print "
  <br>
                    <table width=70% class=grid>
  <tr><td>  $phrases[uploader_system] : </td><td><select name=stng[uploader]>" ;
 if($settings['uploader']){$chk1 = "selected" ; $chk2=""; }else{ $chk2 = "selected" ; $chk1="";}
 print "<option value=1 $chk1>$phrases[enabled]</option>
 <option value=0 $chk2>$phrases[disabled]</option>
 </select>
 </td></tr>
 <tr><td> $phrases[disable_uploader_msg]  : </td><td><input type=text name=stng[uploader_msg] size=30 value='$settings[uploader_msg]'></td></tr>
 <tr><td>  $phrases[uploader_path] : </td><td><input dir=ltr type=text name=stng[uploader_path] size=30 value='$settings[uploader_path]'></td></tr>
 <tr><td>  $phrases[uploader_allowed_types] : </td><td><input dir=ltr type=text name=stng[uploader_types] size=30 value='$settings[uploader_types]'></td></tr>

<tr><td> $phrases[uploader_thumb_width] : </td><td><input type=text name=stng[uploader_thumb_width] size=5 value='$settings[uploader_thumb_width]'> $phrases[pixel] </td></tr>
<tr><td>  $phrases[uploader_thumb_hieght]  : </td><td><input type=text name=stng[uploader_thumb_hieght] size=5 value='$settings[uploader_thumb_hieght]'> $phrases[pixel] </td></tr>


 <tr><td colspan=2 align=center><input type=submit value=' $phrases[edit] '></td></tr>
 </table></center>" ;

         }
//---------------------------------- Statics ---------------------
if($action=="statics"){
        if_admin();


                if($op){
     print "<center><table width=50% class=grid>
<tr><td><ul>";
  foreach($op as $op){
 //---------------------
 if($op=="statics_rest"){
        db_query("delete from info_hits");
        db_query("update info_browser set count=0");
        db_query("update info_os set count=0");
        db_query("update info_best_visitors  set v_count=0");
        print "<li>$phrases[visitors_statics_rest_done]</li>" ;
                }
 //---------------------
  if($op=="songs_listen_rest"){
        db_query("update songs_urls_data  set listens=0");
        print "<li>$phrases[listen_statics_rest_done]</li>" ;
                }
 //---------------------
  if($op=="songs_downloads_rest"){
        db_query("update songs_urls_data  set downloads=0");
        print "<li>$phrases[download_statics_rest_done]</li>" ;
                }
 //---------------------
  if($op=="songs_votes_rest"){
         db_query("update songs_songs  set votes=0");
         db_query("update songs_songs  set votes_total=0");
        print "<li>$phrases[votes_statics_rest_done]</li>" ;
                }
  //---------------------
  if($op=="videos_views_rest"){
         db_query("update songs_videos_data set views=0");
        print "<li>$phrases[videos_watch_rest_done]</li>" ;
                }
 //---------------------
  if($op=="videos_downloads_rest"){
         db_query("update songs_videos_data set downloads=0");
        print "<li>$phrases[videos_download_rest_done]</li>" ;
                }
  //---------------------
  if($op=="videos_votes_rest"){
         db_query("update songs_videos_data  set votes=0");
         db_query("update songs_videos_data  set votes_total=0");
        print "<li>$phrases[videos_votes_rest_done]</li>" ;
                }
 //---------------------
          }
          print "</ul></td></tr></table>";
          }
$data_frstdate = db_qr_fetch("select * from info_hits order by date asc limit 1");
 if(!$data_frstdate['date']){$data_frstdate['date']= "$phrases[cp_not_available]"; }
 $qr_total=db_query("select hits from info_hits");
 $total_hits = 0 ;
 while($data_total = db_fetch($qr_total)){
 $total_hits += $data_total['hits'];
         }

print "<center><p class=title> $phrases[cp_visitors_statics] </p>
<table width=50% class=grid>
<tr><td><b> $phrases[cp_counters_start_date] </b></td><td>$data_frstdate[date]
</td></tr>
<tr><td><b> $phrases[cp_total_visits] </b></td><td>$total_hits
</td></tr>
</table>
<br>
 <p class=title>  $phrases[cp_rest_counters] </p>
<form action='index.php' method=post onSubmit=\"return confirm('$phrases[are_you_sure]');\">
<input type=hidden name=action value='statics'>
<table width=50% class=grid><tr><td>
<input type='checkbox' value='statics_rest'  name='op[]' >$phrases[cp_visitors_statics]<br><br>

<input type='checkbox' value='songs_listen_rest'  name='op[]' >$phrases[songs_listens_statics]  <br>
<input type='checkbox' value='songs_downloads_rest'  name='op[]' >$phrases[songs_downloads_statics]   <br>
<input type='checkbox' value='songs_votes_rest'  name='op[]' >$phrases[songs_votes_statics]   <br><br>

<input type='checkbox' value='videos_views_rest'  name='op[]' >$phrases[videos_watch_statics] <br>
<input type='checkbox' value='videos_downloads_rest'  name='op[]' >$phrases[videos_download_statics]   <br>
<input type='checkbox' value='videos_votes_rest'  name='op[]' >$phrases[videos_votes_statics]   <br>

</td></tr><tr><td align=center>
<input type=submit value=' $phrases[cp_rest_counters_do] '>
</table></center>
</form>";
        }
        
  //------------------------------------- New songs Menu ------------------------------
if($action=="new_songs_menu" || $action=="new_songs_menu_add" || $action=="new_songs_menu_del"){
       if_admin("new_songs");


if($action=="new_songs_menu_add"){

  if(!is_array($song_id)){ $song_id = array(intval($song_id));}
  
if(is_array($song_id)){
foreach($song_id as $id){ 
$id = intval($id); 
$cntx = db_qr_fetch("select count(id) as count from songs_songs where id='$id'");
     if($cntx['count']){
        db_query("insert into songs_new_songs_menu (song_id) values ('$id')");
        }else{
        print_admin_table("<center>$phrases[err_invalid_song_id]</center>");
        }
}
}

//------------
$c=1;
$qr=db_query("select id from songs_new_songs_menu order by ord asc");
while($data=db_fetch($qr)){
db_query("update songs_new_songs_menu set ord='$c' where id='$data[id]'");
$c++;
}
//------------


}
if($action=="new_songs_menu_del"){
    $id = intval($id);
 db_query("delete from songs_new_songs_menu where id='$id'");
  }

  print "<center>
  <form action=index.php method=post name=sender>
  <input type=hidden name=action value='new_songs_menu_add'>
  <table width=50% class=grid><tr><td align=center> <b> $phrases[song_id] :</b>
  <input type=text name=song_id size=4>
  <input type=submit value='$phrases[add_button]'></td></tr></table></form>
              <br>
          <table width=80% class=grid><tr><td>
          
          <style type='text/css'>
   div { cursor: move; }
</style>
          <div id=\"new_songs_list\">";
          
          
$qr=db_query("select * from songs_new_songs_menu order by ord asc");
if(db_num($qr)){
while($data = db_fetch($qr)){

        $qr2=db_query("select songs_songs.id as id ,songs_songs.name as name,songs_singers.name as singer from songs_songs,songs_singers where songs_songs.album=songs_singers.id and songs_songs.id='$data[song_id]'");
       if(db_num($qr2)){
               $data2 = db_fetch($qr2);
        print "<div id=\"item_$data[id]\" onmouseover=\"this.style.backgroundColor='#EFEFEE'\"
     onmouseout=\"this.style.backgroundColor='#FFFFFF'\">
        <table width=100%>
        <tr><td>$data2[singer] -> <b>$data2[name]</b></td>
      <td width=100><a href=\"index.php?action=new_songs_menu_del&id=$data[id]\" onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a></td>
       </tr></table></div>
       ";
       }else{
       db_query("delete from songs_new_songs_menu where song_id='$data[song_id]'");
               }
        }
        }else{
                print "<center> $phrases[no_data] </center>";
                }
        print "</div></td></tr></table></center>";

         print "<script type=\"text/javascript\">
        init_new_songs_sortlist();
</script>";  

        }
      
//------------------------------------- New Stores Menu ------------------------------
if($action=="new_menu" || $action=="new_menu_add" || $action=="new_menu_del"){
       if_admin("new_stores");


if($action=="new_menu_add"){
    
if($type=="singer"){
$cntx = db_qr_fetch("select count(id) as count from songs_singers where id='$cat'");
}else{
$cntx = db_qr_fetch("select count(id) as count from songs_albums where id='$cat'");
}
     if($cntx['count']){
        db_query("insert into songs_new_menu (`cat`,`type`) values ('$cat','$type')");
        
//------------
$c=1;
$qr=db_query("select id from songs_new_menu order by ord asc");
while($data=db_fetch($qr)){
db_query("update songs_new_menu set ord='$c' where id='$data[id]'");
$c++;
}
//------------

        }else{
        print_admin_table("<center>$phrases[err_invalid_id]</center>");
        }
        }
//------ del ----------//        
if($action=="new_menu_del"){
 db_query("delete from songs_new_menu where id='$id'");
  }
//------------------//

  print "<center>
  <form action=index.php method=post name=sender>
  <input type=hidden name=action value='new_menu_add'>
  <table width=50% class=grid><tr>
  <td> <b> $phrases[the_id]  :</b>
  <input type=text name=cat size=4>
  </td>
  <td>
  <b>$phrases[the_type] : </b>
  <select name=type>
  <option value='singer'>$phrases[singer]</option>
  <option value='album'>$phrases[album]</option>
  </select>
  </td><td>
  
  <input type=submit value='$phrases[add_button]'></td><td><a href=\"javascript:singers_list()\">
  <img src='images/list.gif' alt='$phrases[select_from_menu]' border=0></a></td></tr></table></form>
              <br>
          <table width=80% class=grid><tr><td>";
          
print "<style type='text/css'>
   div { cursor: move; }
</style>
          <div id=\"new_stores_list\">";
          
$qr=db_query("select * from songs_new_menu order by ord");
if(db_num($qr)){
while($data = db_fetch($qr)){

     if($data['type']=="singer"){
     $qr2=db_query("select songs_singers.id as id ,songs_singers.name as name,songs_cats.name as cat from songs_singers,songs_cats where songs_singers.cat=songs_cats.id and songs_singers.id='$data[cat]'");
     }else{
     $qr2=db_query("select songs_albums.id as id ,songs_albums.name as name,songs_singers.name as cat,songs_cats.name as cat_first from songs_singers,songs_albums,songs_cats where songs_albums.cat=songs_singers.id and songs_singers.cat=songs_cats.id and songs_albums.id='$data[cat]'");
     }  
       if(db_num($qr2)){
               $data2 = db_fetch($qr2);
        print "<div id=\"item_$data[id]\" onmouseover=\"this.style.backgroundColor='#EFEFEE'\"
     onmouseout=\"this.style.backgroundColor='#FFFFFF'\">
        <table width=100%><tr>
        <td>".iif($data2['cat_first'],"$data2[cat_first] -> ")."$data2[cat] ->  <b>$data2[name]</b></td>
      <td width=100><a href=\"index.php?action=new_menu_del&id=$data[id]\" onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a></td>
       </tr></table>
       </div>
       ";
       }else{
          // print "wrong $data[cat] . $data[type]";
       db_query("delete from songs_new_menu where cat='$data[cat]'");
               }
        }
        }else{
                print "<center> $phrases[no_data] </center>";
                }
        print "</div></td></tr></table></center>";

        
  print "<script type=\"text/javascript\">
        init_new_stores_sortlist();
</script>";      
        }

//------------------------------ Phrases -------------------------------------
if($action=="phrases" || $action=="phrases_update"){

if_admin("phrases");

$cat = intval($cat);

if($action=="phrases_update"){
        $i = 0;
        foreach($phrases_ids  as $id){
        db_query("update songs_phrases set value='$phrases_values[$i]' where id='$phrases_ids[$i]'");

        ++$i;
                }
                }

if($group){
  $group = htmlspecialchars($group);
$cat_data = db_qr_fetch("select name from songs_phrases_cats where id='$group'");

print "<p align=$global_align><img src='images/link.gif'><a href='index.php?action=phrases'>$phrases[the_phrases] </a> / $cat_data[name]</p>";


         $qr = db_query("select * from songs_phrases where cat='$group'");
        if (db_num($qr)){

        print "<form action=index.php method=post>
        <input type=hidden name=action value='phrases_update'>
        <input type=hidden name=group value='$group'>
        <center><table width=60% class=grid>";

        $i = 0;
        while($data=db_fetch($qr)){
         print "<tr onmouseover=\"set_tr_color(this,'#EFEFEE');\" onmouseout=\"set_tr_color(this,'#FFFFFF');\"><td>$data[name]</td><td>
         <input type=hidden name=phrases_ids[$i] value='$data[id]'>
         <input type=text name=phrases_values[$i] value='$data[value]' size=30>
         </td></tr> ";
         ++$i;
                }
                print "<tr><td colspan=2 align=center><input type=submit value=' $phrases[edit] '></td></tr>
                </table></form></center>";
                }else{
                	 print "<center><table width=60% class=grid><tr><td align=center> $phrases[cp_no_phrases] </td></tr></table></center>";
                	 }

}else{
print "<p class=title align=center> $phrases[the_phrases] </p><br>  ";
	$qr = db_query("select * from songs_phrases_cats order by id asc");
	 print "<center><table width=60% class=grid>";
	while($data =db_fetch($qr)){
	print "<tr><td><a href='index.php?action=phrases&group=$data[id]'>$data[name]</a></td></tr>";
	}
	print "</table></center>";
}
}
 //--------------- Load Admin Plugins --------------------------
$dhx = opendir(CWD ."/plugins");
while ($rdx = readdir($dhx)){
         if($rdx != "." && $rdx != "..") {
                 $cur_fl = CWD ."/plugins/" . $rdx . "/admin.php" ;
        if(file_exists($cur_fl)){
                include $cur_fl ;
                }
          }

    }
closedir($dhx);
//------------------------------------------------
//-----------------------------------------------------------------------------

?>
</td></tr></table>
<?

}else{
if(!$disable_auto_admin_redirect){
if(strchr($_SERVER['HTTP_HOST'],"www.")){
  print "<SCRIPT>window.location=\"http://".str_replace("www.","",$_SERVER['HTTP_HOST']).$_SERVER['REQUEST_URI']."\";</script>";
  die();
  }
 }

if($global_lang=="arabic"){
print "<html dir=$global_dir>
<title>$sitename  - ·ÊÕ… «· Õﬂ„ </title>";
}elseif($global_lang=="kurdish"){  
   print "<html dir=$global_dir>
<title>$sitename  - Control Panel </title>"; 
}else{
	print "<html dir=$global_dir>
<title>$sitename  - Control Panel </title>";
	}
print "<META http-equiv=Content-Language content=\"$settings[site_pages_lang]\">
<META http-equiv=Content-Type content=\"text/html; charset=$settings[site_pages_encoding]\">";
print "<link href=\"images/style.css\" type=text/css rel=stylesheet>
<center>
<br>
<table width=60% class=grid><tr><td align=center>

<form action=\"index.php\" method=\"post\"\">
                 <table><tr><td><img src='images/users.gif'></td><td>

                <table dir=$global_dir cellpadding=\"0\" cellspacing=\"3\" border=\"0\">
                <tr>
                        <td class=\"smallfont\">$phrases[cp_username]</td>
                        <td><input type=\"text\" class=\"button\" name=\"username\"  size=\"10\" tabindex=\"1\" ></td>
                        <td class=\"smallfont\" colspan=\"2\" nowrap=\"nowrap\"></td>
                </tr>
                <tr>
                        <td class=\"smallfont\">$phrases[cp_password]</td>
                        <td><input type=\"password\"  name=\"password\" size=\"10\" tabindex=\"2\" /></td>
                        <td>
                        <input type=\"submit\" class=\"button\" value=\"$phrases[cp_login_do]\" tabindex=\"4\" accesskey=\"s\" /></td>
                </tr>

</td>
</tr>
                </table>
                <input type=\"hidden\" name=\"s\" value=\"\" />
                <input type=\"hidden\" name=\"action\" value=\"login\" />
                </td></tr></table>
                </form> </td></tr></table>
                </center>\n";


if(COPYRIGHTS_TXT_ADMIN_LOGIN){
if($global_lang=="arabic"){
	print "<br>
                <center>
<table width=60% class=grid><tr><td align=center>
  Ã„Ì⁄ ÕﬁÊﬁ «·»—„Ã… „Õ›ÊŸ… <a href='http://allomani.com' target='_blank'> ··Ê„«‰Ì ··Œœ„«  «·»—„ÃÌ… </a>  © 2010
</td></tr></table></center>";
}elseif($global_lang=="kurdish"){ 
 
print "<br>
                <center>
<table width=60% class=grid><tr><td align=center>
  Copyright © 2010 <a href='http://allomani.com' target='_blank'>Allomani&trade;</a>  - All Programming rights reserved
</td></tr></table></center>";   
}else{
print "<br>
                <center>
<table width=60% class=grid><tr><td align=center>
  Copyright © 2010 <a href='http://allomani.com' target='_blank'>Allomani&trade;</a>  - All Programming rights reserved
</td></tr></table></center>";
}
}

if(file_exists("demo_msg.php")){
include_once("demo_msg.php");
}
}
?>