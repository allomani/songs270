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

chdir('./../');
define('CWD', (($getcwd = getcwd()) ? $getcwd : '.'));
define('IS_ADMIN', 1);
$is_admin =1 ;

include_once(CWD . "/global.php") ;

if(!check_login_cookies()){die("<center> $phrases[access_denied] </center>");} 

if_admin("members");

print "<html dir=$global_dir>\n";
print "<META http-equiv=Content-Language content=\"$settings[site_pages_lang]\">
<META http-equiv=Content-Type content=\"text/html; charset=$settings[site_pages_encoding]\">";

print "<link href=\"images/style.css\" type=text/css rel=stylesheet>
<script src='js.js' type=\"text/javascript\" language=\"javascript\"></script>";


if ($conf){
if($datastring){$mailing=unserialize(base64_decode($datastring));}

if(is_array($mailing)){extract($mailing);}
                     
                               
if($datastring){$start = intval($start)+intval($perpage);$mailing['start']=$start;unset($datastring);  }

     $start = intval($start);
     $perpage = intval($perpage);

if($send_to=="all"){

       $qr = db_query("select ".members_fields_replace("id").",".members_fields_replace("username").",".members_fields_replace("email")." from ".members_table_replace("songs_members")." order by ".members_fields_replace("id")." limit $start,$perpage",MEMBER_SQL);
        }else{
        $qr = db_query("select ".members_fields_replace("id").",".members_fields_replace("username").",".members_fields_replace("email")." from ".members_table_replace("songs_members")." where binary ".members_fields_replace("username")."='".db_clean_string($username,"code")."'",MEMBER_SQL);
        }


         if(db_num($qr)){

             $count_dt = db_qr_fetch("select count(".members_fields_replace("id").") as count from ".members_table_replace("songs_members").iif($send_to!="all"," where binary ".members_fields_replace("username")."='".db_clean_string($username,"code")."'")." order by ".members_fields_replace("id"),MEMBER_SQL);
         $data_count = $count_dt['count'];
         unset($count_dt);


         while($data = db_fetch($qr)){

          if($op=="msg"){
               print "<li> $phrases[cp_mailing_sending_to] $data[username] .. " ;
         data_flush();
        if(!$from_subject){$from_subject = "$phrases[without_title]";}

        db_query("insert into songs_msgs (user,sender,title,content,date) values('$data[id]','".db_clean_string($from_name)."','".db_clean_string($from_subject)."','".db_clean_string($from_msg)."',now())");
                     print "<font color=green><b>$phrases[done] </b></font></li>";
               data_flush();

          }else{

          $from = "$from_name <$from_email>" ;
      print "<li> $phrases[cp_mailing_sending_to] $data[username] .. " ;
         data_flush();
      $mailResult =  send_email($from_name,$from_email,$data['email'],$from_subject,iif(get_magic_quotes_gpc(),stripcslashes($from_msg),$from_msg),$from_use_html,$from_encoding);

    if($mailResult){
     print "<font color=green><b>$phrases[done] </b></font></li>";
     }else{
      print "<font color=red><b> $phrases[failed] </b></font></li>";
      }
      data_flush();

          }
               }


               if(($start+$perpage) < $data_count){
   print "<br><br>
   <form action='mailing.php' method=post name='mailing_form'>
          <input type=hidden name=conf value='1'>
           <input type=hidden name=datastring value=\"".base64_encode(serialize($mailing))."\">
           <input type=submit value=' $phrases[next_page] '>
           </form>";
           if($auto_pageredirect){
           print "<script>mailing_form.submit();</script>";
           }

   }else{
      print "<br><br> <font size=4> $phrases[process_done_successfully] </font>" ;
   }


                 }else{
                         print "<center>  $phrases[no_results] </center>";
                         }


        }else{


   print "<center>


   <form action='mailing.php' method=post>
   <input type=hidden name=conf value='1'>
   <table width=80% class=grid>
    <tr><td>$phrases[cp_send_as] </td><td>
    <select name=\"mailing[op]\" onclick=\"show_snd_mail_options2(this)\">
    <option value='email'> $phrases[cp_as_email] </option>
    <option value='msg'> $phrases[cp_as_pm] </option>
    </select>
    </td></tr>
     ";

     if($username){$chk="selected";}
     print"
     <tr><td>$phrases[cp_send_to]  </td><td>
    <select name=\"mailing[send_to]\" onclick=\"show_snd_mail_options(this)\">
    <option value='all'> $phrases[all_members] </option>
    <option value='one' $chk> $phrases[one_member] </option>
    </select>
    </td></tr>

   " ;
   if($username){
    print "<tr id='when_one_user_email'>";
    }else{
    print "<tr id='when_one_user_email' style=\"display: none; text-decoration: none\">";
            }
    print "<td>$phrases[cp_username]</td><td>
    <input type=text name='mailing[username]'  value='$username' size=30></td></tr>

   <tr ><td> $phrases[sender_name] </td><td><input type=text name='mailing[from_name]' value='$sitename' size=30></td></tr>
    <tr id='sender_email_tr'><td> $phrases[sender_email] </td><td>

     <input type=text name='mailing[from_email]' value='$settings[mailing_email]' size=30></td></tr>

      <tr id='msg_type_tr'><td>$phrases[msg_type] : </td><td><select name=mailing[from_use_html]>" ;
 if($settings['mailing_default_use_html']){$chk1 = "selected" ; $chk2 ="" ;}else{ $chk2 = "selected" ; $chk1 ="" ;}
 print "<option value=1 $chk1>HTML</option>
 <option value=0 $chk2>TEXT</option>
 </select>
 </td></tr>
  <tr id='msg_encoding_tr'><td>$phrases[msg_encoding]: </td><td><input type=text name=mailing[from_encoding] size=20 value='".iif($settings['mailing_default_encoding'],$settings['mailing_default_encoding'],$settings['site_pages_encoding'])."'></td></tr>

     <tr><td> $phrases[msg_subject] </td><td><input type=text name='mailing[from_subject]' size=30></td></tr>
    <tr><td>  $phrases[the_message] </td><td><textarea name='mailing[from_msg]' cols=50 rows=20></textarea></td></tr>

    <tr><td>$phrases[start_from] </td><td>
    <input type=text name='mailing[start]'  value='0' size=2></td></tr>

    <tr><td>$phrases[mailing_emails_perpage]</td><td>
    <input type=text name='mailing[perpage]'  value='30' size=2></td></tr>

    <tr><td>$phrases[auto_pages_redirection]</td><td>
    <select name='mailing[auto_pageredirect]'>
    <option value=0>$phrases[no]</option>
    <option value=1>$phrases[yes]</option>
    </select>
    </td></tr>

    <tr><td colspan=2 align=center><input type=submit value=' $phrases[send] '></td></tr></table></center>";
}


?>