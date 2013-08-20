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

if($members_connector['custom_members_table']){
$members_connector['members_table'] = $members_connector['custom_members_table'];
}else{
$members_connector['members_table'] = "user" ;
}


$member_fields_tofind = array('id','password','last_login','birth','date','usr_group');

$member_fields_toreplace = array('userid','password','lastvisit','birthday','joindate','usergroupid');

$member_table_tofind =array('songs_members');
$member_table_toreplace = array($members_connector['members_table']);

        
$members_groups_array =array(
"6" => "Administrators",
"8" => "Banned Users",
"7" => "Moderators",
"2" => "Registered Users",
"5" => "Super Moderators",
"1" => "Unregistered / Not Logged In",
"3" => "Users Awaiting Email Confirmation",
) ;

$members_connector['time_type'] = "timestamp";
$members_connector['time_format'] = "d-m-Y" ;

$members_connector['is_md5_password'] = true ;

$members_connector['allowed_login_groups'] = array('2','6','5');
$members_connector['disallowed_login_groups'] = array('1');  
$members_connector['waiting_conf_login_groups'] = array('3');  

$search_fields = array(
);

$required_database_fields_names = array('country','active_code');
$required_database_fields_types = array('text','text');

function fetch_user_salt($length = SALT_LENGTH)
	{
		$salt = '';

		for ($i = 0; $i < $length; $i++)
		{
			$salt .= chr(rand(32, 126));
		}

		return $salt;
	}

function connector_member_pwd($userid,$pwd,$op){

    if($op=="update"){
        $salt = fetch_user_salt(3);
        $pwdz = md5(md5($pwd).$salt);
    db_query("update user set password='".$pwdz."',salt='$salt' where userid='".intval($userid)."'",MEMBER_SQL);    
    }
}

function connector_after_reg_process(){
    global $member_id;
    db_query("insert into userfield (userid) values('$member_id')",MEMBER_SQL);
     db_query("insert into usertextfield (userid) values('$member_id')",MEMBER_SQL);
     db_query("update user set options='3159' where userid='$member_id'",MEMBER_SQL); 
}

function connector_get_date($date,$op){
 
if($op=="member_reg_date"){
return strtotime($date);
}elseif($op=="member_birth_date"){
    $tm = strtotime($date);
    return date("m-d-Y",$tm);
}elseif($op=="member_birth_array"){
$birth_data = split("-",$date);
$new_arr['year'] =  $birth_data[2];
$new_arr['month'] =  $birth_data[0];
$new_arr['day'] =  $birth_data[1];
return $new_arr;  
}   
}


function member_verify_password($userid,$pwd,$md5pwd="",$md5pwd_utf=""){
    
$qr=db_query("select ".members_fields_replace('password').",salt from ".members_table_replace('songs_members')." where ".members_fields_replace('id')."='$userid'",MEMBER_SQL);

if(db_num($qr)){
    $data = db_fetch($qr);
    
 //print $pwd ."<br>".chr(ord(substr($pwd,0,1)))."<br>".htmlentities($pwd,ENT_QUOTES,"UTF-8")."<br>".$data[members_fields_replace('password')]."<br>".md5(md5(($pwd)).$data['salt'])."<br>".md5(md5(utf8_encode($pwd)).$data['salt']);
    
 if($data[members_fields_replace('password')]==md5($md5pwd.$data['salt']) || $data[members_fields_replace('password')]==md5($md5pwd_utf.$data['salt']) || $data[members_fields_replace('password')]==md5(md5($pwd).$data['salt'])){
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
    global $phrases,$settings,$scripturl,$user_email,$code,$sitename,$siteurl;
 
 //------------- Rest pwd Request -----------   
 if($action=="lostpwd"){
 open_table("$phrases[forgot_pass]");
if(trim($user_email)){
 $qr=db_query("select * from user where email='".db_clean_string($user_email)."'",MEMBER_SQL);
 if(db_num($qr)){
     $data = db_fetch($qr);
     $active_code = md5($data['email'].time().rand(1,999).rand(1,999).$data['id']);
     $url = $scripturl . "/index.php?action=rest_pwd&code=$active_code";
     
     db_query("delete from songs_confirmations where type='rest_pwd' and cat='$data[id]'");  
     db_query("insert into songs_confirmations (type,cat,code) values('rest_pwd','$data[userid]','$active_code')");
     
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
 $data_member = db_qr_fetch("select username from user where userid='$data[cat]'",MEMBER_SQL);
    
     
     $msg = get_template('pwd_rest_done_msg',array('{name}','{password}','{siteurl}','{sitename}'),
     array($data_member['username'],$new_pwd,$siteurl,$sitename));
     

 send_email($sitename,$mailing_email,$user_email,$phrases['pwd_rest_done_msg_subject'],$msg,$settings['mailing_default_use_html'],$settings['mailing_default_encoding']);
 
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
?>