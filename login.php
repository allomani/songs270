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

require("global.php") ;

    if(!$re_link){$re_link=iif($_SERVER['HTTP_REFERER'],$_SERVER['HTTP_REFERER'],"index.php");} 
    
   $re_link = htmlspecialchars($re_link);
 
if($action=="login"){

if(trim($username) == "" || trim($password) == ""){
      site_header();
      open_table($phrases['login']);
        print "<center>$phrases[plz_enter_username_and_pwd]</center>";
       close_table();
       site_footer(); 
       die();
}

  
$qr = db_query("select * from ".members_table_replace("songs_members")." where ".members_fields_replace("username")." like '".db_clean_string($username)."'",MEMBER_SQL);



if(db_num($qr)){
   
   $data = db_fetch($qr);

    if(member_verify_password($data[members_fields_replace('id')],iif(get_magic_quotes_gpc(),stripslashes($password),$password),$md5pwd,$md5pwd_utf)){
            
        if(in_array($data[members_fields_replace('usr_group')],$members_connector['allowed_login_groups'])){
        
                
       set_cookie('member_data_id', $data[members_fields_replace('id')]);
       set_cookie('member_data_password', $data[members_fields_replace('password')]);

         print "<SCRIPT>window.location=\"$re_link\";</script>";

       
  // ------------- Closed Account -----------------       
    }elseif(in_array($data[members_fields_replace('usr_group')],$members_connector['disallowed_login_groups'])){
            site_header();
      open_table($phrases['login']);
                print "<center> $phrases[this_account_closed_cant_login] </center>";
                close_table();
                 site_footer();
 
 //------------- Not Activated Member --------------------                
      }elseif(in_array($data[members_fields_replace('usr_group')],$members_connector['waiting_conf_login_groups'])){ 
                
                site_header();
                open_table($phrases['login']);
                print "<center>  $phrases[this_account_not_activated] </center>";
                close_table();
                
                //------ resend activation msg form ----------
                open_table($phrases['resend_activation_msg']);
                print "<form action=index.php method=post>
                <input type=hidden name=action value='resend_active_msg'>
                <center><table><tr><td>
                $phrases[your_email] : </td><td>
                <input type=text size=30 name=email dir=ltr>
                </td><td><input type=submit value=' $phrases[send] '>
                </td></tr></table></center></form>";
                close_table();
                site_footer();
                }else{
                    site_header(); 
                     open_table();
                     print "<center> Invalid User Group </center>";
                    close_table();
                site_footer();
                }
                
     //---------- if not valid password ---------------------           
            }else{
            site_header();
                open_table($phrases['login']);
                print "<center> $phrases[invalid_pwd] </center>";
                close_table();
                 site_footer();
                    }

   //--------- if not valid username ---------------                 
        }else{
        site_header();
                open_table($phrases['login']);
                print "<center>  $phrases[invalid_username] </center>";
                close_table();
                    site_footer();
                }


  //--------------- Logout ---------------
}elseif($action=="logout"){

            set_cookie('member_data_id', "");
       set_cookie('member_data_password',"");

        print "<SCRIPT>window.location=\"$re_link\";</script>";

//---------- Login Form Redirect -----------------
}else{

 print "<form action=index.php method=post name=lg_form>
 <input type=hidden name='re_link' value=\"$re_link\">
 <input type=hidden name='action' value='login'>
 </form>
 <script>
 lg_form.submit();
 </script>";
 }