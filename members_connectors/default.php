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

$members_connector['allowed_login_groups']=array('1');
$members_connector['disallowed_login_groups']=array('2');
$members_connector['waiting_conf_login_groups']=array('0');

$members_groups_array = array("0"=>"$phrases[acc_type_not_activated]","1"=>"$phrases[acc_type_activated]","2"=>"$phrases[acc_type_closed]");


function connector_get_date($date,$op){

if($op=="member_reg_date"){
return $date;
}elseif($op=="member_last_login"){
    return $date;
}elseif($op=="member_birth_date"){
   // $tm = strtotime($date);
   // return date("Y-m-d",$tm);
   return $date;
}elseif($op=="member_birth_array"){
$birth_data = split("-",$date);
$new_arr['year'] =  $birth_data[0];
$new_arr['month'] =  $birth_data[1];
$new_arr['day'] =  $birth_data[2];
return $new_arr;
}
}

function connector_member_pwd($userid,$pwd,$op){

    if($op=="update"){
        $pwdz = md5($pwd);
    db_query("update songs_members set password='".$pwdz."' where id='".intval($userid)."'");
    }
}

function connector_after_reg_process(){
}

function member_verify_password($userid,$pwd,$md5pwd="",$md5pwd_utf=""){ 
$qr=db_query("select password from songs_members where id='$userid'");
if(db_num($qr)){
    $data = db_fetch($qr);

    if($data['password']==$md5pwd || $data['password']==$md5pwd_utf || $data['password'] == md5($pwd)){

 return true;
 }else{
 return false;
 }
}else{
    return false;
}
}

//---------------- PWD REST --------------

function connector_members_rest_pwd($action){
    global $phrases,$settings,$scripturl,$user_email,$code,$sitename,$siteurl,$mailing_email;

 //------------- Rest pwd Request -----------
 if($action=="lostpwd"){
 open_table("$phrases[forgot_pass]");
if(trim($user_email)){
 $qr=db_query("select * from songs_members where email='".db_clean_string($user_email)."'");
 if(db_num($qr)){
     $data = db_fetch($qr);
     $active_code = md5($data['email'].time().rand(1,999).rand(1,999).$data['id']);
     $url = $scripturl . "/index.php?action=rest_pwd&code=$active_code";

     db_query("delete from songs_confirmations where type='rest_pwd' and cat='$data[id]'");
     db_query("insert into songs_confirmations (type,cat,code) values('rest_pwd','$data[id]','$active_code')");
      
       
     $msg = get_template('pwd_rest_request_msg',array('{name}','{url}','{code}','{siteurl}','{sitename}'),
     array($data['username'],$url,$active_code,$siteurl,$sitename));


 send_email($sitename,$mailing_email,$user_email,$phrases['pwd_rest_request_msg_subject'],$msg,$settings['mailing_default_use_html'],$settings['mailing_default_encoding']);

    print "<center>$phrases[rest_pwd_request_msg_sent]</center>";

 }else{
       print "<center>  $phrases[email_not_exists]</center>";
 }
 }else{

         print "<form action=index.php method=post>
         <input type=hidden name=action value=forget_pass>
         <center><table ><tr><td width=100>  $phrases[email] : </td>
         <td><input type=text name=user_email size=20></td><td><input type=submit value='$phrases[continue]'></tr></table></form></center>";
         }
         close_table();
 }
 //------------ Rest pwd Process ----------------
 if($action=="rest_pwd"){
 $qr = db_query("select * from songs_confirmations where code='".db_clean_string($code)."'");
 if(db_num($qr)){
 $data =db_fetch($qr);
 $new_pwd = rand_string();
 connector_member_pwd($data['cat'],$new_pwd,"update");
 $data_member = db_qr_fetch("select username,email from songs_members where id='$data[cat]'");


     $msg = get_template('pwd_rest_done_msg',array('{name}','{password}','{siteurl}','{sitename}'),
     array($data_member['username'],$new_pwd,$siteurl,$sitename));

     
 send_email($sitename,$mailing_email,$data_member['email'],$phrases['pwd_rest_done_msg_subject'],$msg,$settings['mailing_default_use_html'],$settings['mailing_default_encoding']);

 db_query("delete from songs_confirmations where type='rest_pwd' and code='".db_clean_string($code)."'");

 open_table();
 print "<center> $phrases[pwd_rest_done]</center>";
 close_table();
 }else{
 open_table();
 print "<center> $phrases[err_wrong_url]</center>";
 close_table();
 }
 }
}