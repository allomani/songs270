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

 if(!defined('IS_ADMIN')){die('No Access');}  


//-------------- Remote Members Database ---------------
   if($action=="members_remote_db"){
   if_admin();

print "<p align=center class=title> $phrases[cp_remote_members_db] </p>

<center><table width=60% class=grid><tr><td><b>$phrases[use_remote_db]</b></td><td>".($members_connector['enable'] ? $phrases['yes'] : $phrases['no'])."</td></tr>";
if($members_connector['enable']){
print "<tr><td><b>$phrases[db_host]</b></td><td>$members_connector[db_host]</td></tr>
<tr><td><b>$phrases[db_name]</b></td><td>$members_connector[db_name]</td></tr>
<tr><td><b>$phrases[members_table]</b></td><td>$members_connector[members_table]</td></tr>";
}
print "</table>
<br>
<fieldset style=\"padding: 2;width=400\" >
<legend>$phrases[note]</legend>
$phrases[members_remote_db_wizzard_note]
</fieldset>
<br><br>
<form action='index.php' method=get>
<input type=hidden name=action value='members_remote_db_wizzard'>
<input type=submit value=' $phrases[members_remote_db_wizzard] '>
</form></center>";

   }
 //------------ Members Remote DB Wizzard ---------------
 if($action=="members_remote_db_wizzard"){
     if_admin();
print "<p align=center class=title>$phrases[members_remote_db_wizzard]</p>";


if($members_connector['enable']){
$conx  = @mysql_connect($members_connector['db_host'],$members_connector['db_username'],$members_connector['db_password']);
if($conx){
if(mysql_select_db($members_connector['db_name'])){




//---------------- STEP 1 : CHECK TABLES FIELDS ---------------
  $tables_ok = 1 ;
 if(is_array($required_database_fields_names)){


 $qr = db_query("SHOW FIELDS FROM user",MEMBER_SQL);
  $c=0;
while($data =db_fetch($qr)){

    $table_fields['name'][$c] = $data['Field'];
    $table_fields['type'][$c] = $data['Type'];
    $c++;
    }

print "<center><br><table width=80% class=grid>";
for($i=0;$i<count($required_database_fields_names);$i++){
    
//--------- Neme TD ------
print "<tr><td>".$required_database_fields_names[$i]."</td>";
//------- Type TD  ---------
if(is_array($required_database_fields_types[$i])){$req_type = $required_database_fields_types[$i];}else{$req_type=array($required_database_fields_types[$i]);}

print "<td>";
foreach($req_type as $value){
    print "$value &nbsp;";
    }
    print "</td><td>";
//----------------------------

$searchkey =  array_search($required_database_fields_names[$i],$table_fields['name']);
if($searchkey){


if(in_array($table_fields['type'][$searchkey],$req_type)){
print "<b><font color=green>Valid</font></b>";
}else{
print "<b><font color=red>Not Valid Type</font></b>";
$qrx = db_query("ALTER TABLE ".members_table_replace("songs_members")." CHANGE `".$required_database_fields_names[$i]."` `".$required_database_fields_names[$i]."` ".$req_type[0]." NOT NULL ;",MEMBER_SQL);

    if(!$qrx){
    print "<td><b><font color=red> $phrases[chng_field_type_failed] </font></b></td>";
        $tables_ok = 0;
        }else{
        print "<td><b><font color=green> $phrases[chng_field_type_success] </font></b></td>";
            }
            unset($qrx);
    }
print "</td>";
    }else{
    print "<td><b><font color=red>Not found</font></b></td>";

    $qrx = db_query("ALTER TABLE ".members_table_replace("songs_members")." ADD `".$required_database_fields_names[$i]."` ".$req_type[0]." NOT NULL ;",MEMBER_SQL);

    if(!$qrx){
    print "<td><b><font color=red> $phrases[add_field_failed] </font></b></td>";
        $tables_ok = 0;
        }else{
        print "<td><b><font color=green>$phrases[add_field_success] </font></b></td>";
            }
            unset($qrx);
        }
        }
        print "</table></center><br>";
        }
        //----------- end tables check -----------
        if($tables_ok){
        print_admin_table($phrases['members_remote_db_compatible']);
            }else{
            print_admin_table($phrases['members_remote_db_uncompatible']);
                }
        //--------- clean local db note ------------
        print "<center> <br>
<fieldset style=\"padding: 2;width=400\" >
<legend>$phrases[note]</legend>
$phrases[members_local_db_clean_note]
</fieldset>
<br><br>
<form action='index.php' method=get>
<input type=hidden name=action value='members_local_db_clean'>
<input type=submit value=' $phrases[members_local_db_clean_wizzard] '>
</form></center>";

        }else{
        print_admin_table($phrases['wrong_remote_db_name']);
            }
        }else{
            print_admin_table($phrases['wrong_remote_db_connect_info']);
            }
        }else{
        print_admin_table($phrases['members_remote_db_disabled']);
            }
 }

 //-------------- Clean Members Local DB -------------
 if($action=="members_local_db_clean"){
 print "<p align=center class=title> $phrases[members_local_db_clean_wizzard] </p>
 <center><table width=70% class=grid><tr><td>";
 if($process){
 db_query("TRUNCATE TABLE `songs_favorites`");
 db_query("TRUNCATE TABLE `songs_msgs`");
 db_query("TRUNCATE TABLE `songs_members_fields`");
 db_query("TRUNCATE TABLE `songs_confirmations`");
db_query("TRUNCATE TABLE `songs_playlists`");
db_query("TRUNCATE TABLE `songs_playlists_data`");

  print "<center><b> $phrases[process_done_successfully]</b></center>";
 }else{
 print "<br> <b>$phrases[members_local_db_clean_description]
 <ul>
 <li>$phrases[members_msgs_table]</li>
 <li>$phrases[members_favorite_table]</li>
 <li>$phrases[members_custom_fields_table]</li>
 <li>$phrases[members_confirmations_table]</li>
<li>$phrases[members_playlists_table]</li>  
 </ul></b>
 <center>
 <form action='index.php' method=post>
 <input type=hidden name=action value='members_local_db_clean'>
 <input type=hidden name=process value='1'>
 <input type=submit value=' $phrases[do_button] ' onClick=\"return confirm('$phrases[are_you_sure]');\">
 </form>
 </center>";
 }
 print "</td></tr></table></center>";


 }
//------------------------------- Email Members -----------------------------------
if($action=="members_mailing"){
if_admin("members");
$username = htmlspecialchars($username) ; 
print "<p align=center class=title> $phrases[members_mailing] </p><br>" ;

 print "<center><iframe src='mailing.php?username=$username' width=95% height=800  border=0 frameborder=0></iframe></center>";
        }
//---------------------- Members Fields ---------------------
if($action=="members_fields" || $action=="members_fields_edit_ok" || $action=="members_fields_add_ok" || $action=="members_fields_del"){

 if_admin("members");
if($action=="members_fields_del"){
$id=intval($id);
db_query("delete from songs_members_sets where id='$id'");
db_query("delete from songs_members_fields where cat='$id'"); 
}

if($action=="members_fields_edit_ok"){
$id=intval($id);
if($name){
db_query("update songs_members_sets set name='".db_clean_string($name)."',details='".db_clean_string($details)."',required='$required',type='$type',value='".db_clean_string($value,"code")."',style='$style',ord='".intval($ord)."' where id='$id'");
    }
}

if($action=="members_fields_add_ok"){
$id=intval($id);
if($name){
db_query("insert into songs_members_sets  (name,details,required,type,value,style,ord) values('".db_clean_string($name)."','".db_clean_string($details)."','$required','$type','".db_clean_string($value,"code")."','$style','$ord')");
    }
}


print "<p align=center class=title> $phrases[members_custom_fields]</p>

<p align=$global_align><a href='index.php?action=members_fields_add'><img src='images/add.gif' border=0> $phrases[add_member_custom_field] </a></p>

<center><table width=90% class=grid>";

$qr= db_query("select * from songs_members_sets order by required desc,ord asc");
if(db_num($qr)){
while($data=db_fetch($qr)){
print "<tr><td width=75%>";
if($data['required']){
    print "<b>$data[name]</b>";
    }else{
    print "$data[name]";
        }
        print "</td>
        <td align=center>$data[ord]</td>
<td><a href='index.php?action=members_fields_edit&id=$data[id]'>$phrases[edit]</a> - <a href='index.php?action=members_fields_del&id=$data[id]' onClick=\"return confirm('$phrases[are_you_sure]');\">$phrases[delete]</a></td></tr>";
}

}else{
print "<tr><td align=center>  $phrases[no_members_custom_fields] </td></tr>";
    }

print "</table></center>";


}

//---------- Add Member Field -------------
if($action=="members_fields_add"){
 if_admin("members");
print "<center>
<p align=center class=title>$phrases[add_member_custom_field]</p>
<form action=index.php method=post>
<input type=hidden name=action value='members_fields_add_ok'>
<input type=hidden name=id value='$id'>
<table width=80% class=grid>";
print "<tr><td><b> $phrases[the_name]</b> </td><td><input type=text size=20  name=name></td></tr>
<tr><td><b> $phrases[the_description] </b></b></td><td><input type=text size=30  name=details></td></tr>
<tr><td><b>$phrases[the_type]</b></td><td><select name=type>
<option value='text'>$phrases[textbox]</option>
<option value='textarea'>$phrases[textarea]</option>
<option value='select'>$phrases[select_menu]</option>
<option value='radio'>$phrases[radio_button]</option>
<option value='checkbox'>$phrases[checkbox]</option>
</select>
</td></tr>
<tr><td><b>$phrases[default_value_or_options]</b><br><br>$phrases[put_every_option_in_sep_line]</td><td>
<textarea name='value' rows=10 cols=30>$data[value]</textarea></td></tr>

<tr><td><b>$phrases[addition_style]</b> </td><td><input type=text size=30  name=style value=\"$data[style]\" dir=ltr></td></tr>


<tr><td><b>$phrases[required]</b></td><td><select name=required>";
print "<option value=1>$phrases[yes]</option>
<option value=0>$phrases[no]</option>
</select></td></tr>

<tr><td><b>$phrases[the_order]</b> </td><td><input type=text size=3  name=ord value=\"$data[ord]\"></td></tr>

<tr><td colspan=2 align=center><input type=submit value=' $phrases[add_button] '></td></tr>";
print "</table></center>";

}


//---------- Edit Member Field -------------
if($action=="members_fields_edit"){

    if_admin("members");
$id=intval($id);

$qr = db_query("select * from songs_members_sets where id='$id'");

if(db_num($qr)){
$data = db_fetch($qr);
print "<center><form action=index.php method=post>
<input type=hidden name=action value='members_fields_edit_ok'>
<input type=hidden name=id value='$id'>
<table width=80% class=grid>";
print "<tr><td><b> $phrases[the_name]</b> </td><td><input type=text size=20  name=name value=\"$data[name]\"></td></tr>
<tr><td><b> $phrases[the_description] </b></b></td><td><input type=text size=30  name=details value=\"$data[details]\"></td></tr>
<tr><td><b>$phrases[the_type]</b></td><td><select name=type>";

if($data['type']=="text"){
    $chk1 = "selected";
    $chk2 = "";
    $chk3 = "";
    $chk4 = "";
    $chk5 = "";
}elseif($data['type']=="textarea"){
    $chk1 = "";
    $chk2 = "selected";
    $chk3 = "";
    $chk4 = "";
    $chk5 = "";
}elseif($data['type']=="select"){
    $chk1 = "";
    $chk2 = "";
    $chk3 = "selected";
    $chk4 = "";
    $chk5 = "";
}elseif($data['type']=="radio"){
    $chk1 = "";
    $chk2 = "";
    $chk3 = "";
    $chk4 = "selected";
    $chk5 = "";
}elseif($data['type']=="checkbox"){
    $chk1 = "";
    $chk2 = "";
    $chk3 = "";
    $chk4 = "";
    $chk5 = "selected";
}

print "<option value='text' $chk1>$phrases[textbox]</option>
<option value='textarea' $chk2>$phrases[textarea]</option>
<option value='select' $chk3>$phrases[select_menu]</option>
<option value='radio' $chk4>$phrases[radio_button]</option>
<option value='checkbox' $chk5>$phrases[checkbox]</option>
</select>
</td></tr>
<tr><td><b>$phrases[default_value_or_options]</b><br><br>$phrases[put_every_option_in_sep_line]</td><td>
<textarea name='value' rows=10 cols=30>$data[value]</textarea></td></tr>

<tr><td><b>$phrases[addition_style]</b> </td><td><input type=text size=30  name=style value=\"$data[style]\" dir=ltr></td></tr>


<tr><td><b>$phrases[required]</b></td><td><select name=required>";
if($data['required']){$chk1="selected";$chk2="";}else{$chk1="";$chk2="selected";}
print "<option value=1 $chk1>$phrases[yes]</option>
<option value=0 $chk2>$phrases[no]</option>
</select></td></tr>

<tr><td><b>$phrases[the_order]</b> </td><td><input type=text size=3  name=ord value=\"$data[ord]\"></td></tr>

<tr><td colspan=2 align=center><input type=submit value=' $phrases[edit] '></td></tr>";
print "</table></center>";
}else{
print "<center><table width=70% class=grid>";
print "<tr><td align=center>$phrases[err_wrong_url]</td></tr>";
print "</table></center>";
}

}

//---------------- Members Search  ------------------------------
 if($action == "members_search"){

if_admin("members");

$limit = intval($limit);
$start  = intval($start);

//-------- check remote and local db connection ------
if($members_connector['enable']){
$srch_remote_db = $members_connector['db_name'];
$srch_local_db = $db_name ;
}else{
$srch_remote_db = $db_name ;
$srch_local_db = $db_name ;
}


 print "<p align=center class=title> $phrases[the_members] </p>
             ";

if($date_y || $date_m || $date_d){

   $birth_struct =  iif($date_y,$date_y."-","0000-").iif($date_m,$date_m."-","01-").iif($date_d,$date_d,"01");
  // print $birth_struct;

$birth = connector_get_date($birth_struct,'member_birth_date');
//print $birth;
    }else{
$birth = "";
}

$cond = "binary ".$srch_remote_db.".".members_table_replace("songs_members").".".members_fields_replace("username")." like '%$username%' and ".$srch_remote_db.".".members_table_replace("songs_members").".".members_fields_replace("email")." like '%$email%' ";


$cond .= "and ".$srch_remote_db.".".members_table_replace('songs_members').".".members_fields_replace('birth')." like '%$birth%' and ".$srch_remote_db.".".members_table_replace('songs_members').".country like '%$country%'";

$c_custom = 0 ;
if(!$members_connector['enable'] || $members_connector['same_connection']){
//------------- Custom Fields  ------------------
   if(is_array($custom) && is_array($custom_id)){

   for($i=0;$i<=count($custom_id);$i++){
   if($custom_id[$i] & $custom[$i] ){
   $m_custom_id=$custom_id[$i];
   $m_custom_name =$custom[$i] ;
if(trim($m_custom_id) && trim($m_custom_name)){
    $c_custom++;
$cond .= " and (".$srch_local_db.".songs_members_fields.cat = '$m_custom_id' and  ".$srch_local_db.".songs_members_fields.value like '%$m_custom_name%' and ".$srch_local_db.".songs_members_fields.member = ".$srch_remote_db.".".members_table_replace('songs_members').".".members_fields_replace('id').")";
}

       }
       }
  $cond .= " ";
   }

}

$cond .= " group by ".$srch_remote_db.".".members_table_replace("songs_members").".".members_fields_replace("username");

if((!$members_connector['enable'] || $members_connector['same_connection']) && $c_custom >0){
$sql= "select ".$srch_remote_db.".".members_table_replace("songs_members").".* from ".$srch_remote_db.".".members_table_replace("songs_members").",".$srch_local_db.".songs_members_fields where ".$cond ." limit $start,$limit";
$page_result_sql =  "select ".$srch_remote_db.".".members_table_replace("songs_members").".".members_fields_replace('id')." from ".$srch_remote_db.".".members_table_replace("songs_members").",".$srch_local_db.".songs_members_fields where ".$cond ;

}else{
$sql= "select ".$srch_remote_db.".".members_table_replace('songs_members').".* from ".$srch_remote_db.".".members_table_replace('songs_members')." where ".$cond ." limit $start,$limit";
$page_result_sql = "select ".$srch_remote_db.".".members_table_replace('songs_members').".".members_fields_replace('id')." from ".$srch_remote_db.".".members_table_replace('songs_members')." where ".$cond;

}

 //  print $page_result_sql;
$qr = db_query($sql,MEMBER_SQL);


 if(db_num($qr)){
// $page_result = db_qr_fetch($page_result_sql,MEMBER_SQL);
$page_result['count'] = db_qr_num($page_result_sql,MEMBER_SQL);
 print "<b> $phrases[view]  </b>".($start+1)." - ".($start+$limit) . "<b> $phrases[from] </b> $page_result[count]<br><br>";


$numrows=$page_result['count'];
$previous_page=$start - $m_perpage;
$next_page=$start + $m_perpage;
$m_perpage = $limit ;
$page_string = "index.php?".substr($_SERVER['QUERY_STRING'],0,strpos($_SERVER['QUERY_STRING'],"&start="));

 print " <center>


      <table width=100% class=grid><tr>
      <td><b>$phrases[username]</b></td><td><b>$phrases[email]</b></td>
 <td><b>$phrases[birth]</b></td>
 <td><b>$phrases[register_date]</b></td><td><b>$phrases[last_login]</b></td></tr>";
 while($data = db_fetch($qr)){
 print "<tr><td><a href='index.php?action=member_edit&id=".$data[members_fields_replace("id")]."'>$data[username]</td>
 </td><td>".$data[members_fields_replace("email")]."</td>
 <td>".$data[members_fields_replace("birth")]."</td>
 <td>".member_time_replace($data[members_fields_replace("date")])."</td>
 <td>".member_time_replace($data[members_fields_replace("last_login")])."</td>
 </tr>";

         }
         print "</table>";

//-------------------- pages system ------------------------
if ($numrows>$m_perpage){
print "<p align=center>$phrases[pages] : ";
//----------------------------
if($start >0)
{
$previouspage = $start - $m_perpage;
echo "<a href=$page_string&start=$previouspage><</a>\n";
}
//------------------------------------------
$pages=intval($numrows/$m_perpage);
//---------------------------------------
if ($numrows%$m_perpage)
{
$pages++;
}
//--------------------------------------
for ($i = 1; $i <= $pages; $i++) {

$nextpag = $m_perpage*($i-1);
//-----------------------------------------

if ($nextpag == $start)
{
echo "<font size=2 face=tahoma><b>$i</b></font>&nbsp;\n";
}
else
{
echo "<a href=$page_string&start=$nextpag>[$i]</a>&nbsp;\n";
}
}
//--------------------------------------------------

if (! ( ($start/$m_perpage) == ($pages - 1) ) && ($pages != 1) )
{
$nextpag = $start+$m_perpage;
echo "<a href=$page_string&start=$nextpag>></a>\n";
}
//--------------------------------------------------------------

echo "</p>";
}
//------------ end pages system -------------
         }else{

                 print " <center><table width=50% class=grid><tr>
                 <tr><td align=center> $phrases[no_results] </td></tr>";
                   print "</table></center>";
                 }



        }

//------------------------- Memebers Operations ---------------------------------
if($action=="members" || $action=="member_add_ok" || $action=="member_edit_ok" || $action=="member_del"){
if_admin("members");

if($action=="member_add_ok"){

    $all_ok = 1;
 if(check_email_address($email)){
$email = db_clean_string($email);

$exsists = db_qr_num("select ".members_fields_replace('id')." from ".members_table_replace('songs_members')." where ".members_fields_replace('email')."='$email'",MEMBER_SQL);
      //------------- check email exists ------------
       if($exsists){
                         print "<li>$phrases[register_email_exists]<br>$phrases[register_email_exists2] <a href='index.php?action=forget_pass'>$phrases[click_here] </a></li>";
              $all_ok = 0 ;
           }
      }else{
       print_admin_table("$phrases[err_email_not_valid]");
      $all_ok = 0;
      }
       $username = db_clean_string($username);

        //------- username min letters ----------
       if(strlen($username) >= $settings['register_username_min_letters']){
       $exclude_list = explode(",",$settings['register_username_exclude_list']) ;

         if(!in_array($username,$exclude_list)){

     $exsists2 = db_qr_num("select ".members_fields_replace('id')." from ".members_table_replace('songs_members')." where binary ".members_fields_replace('username')."='$username'",MEMBER_SQL);

       //-------------- check username exists -------------
            if($exsists2){
                         print(str_replace("{username}",$username,"<li>$phrases[register_user_exists]</li>"));
                $all_ok = 0 ;
           }
           }else{
           print_admin_table("$phrases[err_username_not_allowed]");
         $all_ok= 0;
               }
          }else{
         print_admin_table("$phrases[err_username_min_letters]");
         $all_ok= 0;
          }
if($all_ok){
if($username && $email && $password){


 db_query("insert into ".members_table_replace('songs_members')." (".members_fields_replace('username').",".
 members_fields_replace('email').",".members_fields_replace('country').",".members_fields_replace('birth').",".
 members_fields_replace('usr_group').",".members_fields_replace('date').")
 values('$username','$email','$country','".connector_get_date("$date_y-$date_m-$date_d",'member_birth_date')."','$usr_group','".connector_get_date(date("Y-m-d H:i:s"),'member_reg_date')."')",MEMBER_SQL);


 $member_id=mysql_insert_id();

//------------- Custom Fields  ------------------
   if(is_array($custom) && is_array($custom_id)){
   for($i=0;$i<=count($custom);$i++){
   if($custom_id[$i]){
   $m_custom_id=$custom_id[$i];
   $m_custom_name =$custom[$i] ;
   db_query("insert into songs_members_fields (member,cat,value) values('$member_id','$m_custom_id','$m_custom_name')");

       }
   }
   }
//-----------------------------------------------


connector_member_pwd($member_id,$password,'update');

 print "<center><table width=50% class=grid><tr><td align=center>
    $phrases[member_added_successfully]
    </td></tr></table></center><br>";

}else{
 print "<center><table width=50% class=grid><tr><td align=center>
   $phrases[please_fill_all_fields]
    </td></tr></table></center><br>";
}
}
        }

//------ delete memeber query --------
if($action == "member_del"){
db_query("delete from ".members_table_replace('songs_members')." where ".members_fields_replace('id')."='$id'",MEMBER_SQL);
db_query("delete from songs_members_fields where member='$id'");

print_admin_table( "<center>$phrases[member_deleted_successfully]</center>");
        }


 if($action == "member_edit_ok"){




db_query("update ".members_table_replace('songs_members')." set ".members_fields_replace('username').
"='$username',".members_fields_replace('email')."='$email',".members_fields_replace('country')."='$country',".
members_fields_replace('birth')."='".connector_get_date("$date_y-$date_m-$date_d",'member_birth_date')."',".
members_fields_replace('usr_group')."='$usr_group'  where ".members_fields_replace('id')."='$id'",MEMBER_SQL);

 //-------- if change password --------------
          if ($password){
              if($password == $re_password){
               connector_member_pwd($id,$password,'update');
              }else{

              print_admin_table("<center>$phrases[err_passwords_not_match]</center>");

              }
           }

//------------- Custom Fields  ------------------
   if(is_array($custom) && is_array($custom_id)){
   for($i=0;$i<=count($custom);$i++){
   if($custom_id[$i]){
   $m_custom_id=$custom_id[$i];
   $m_custom_name =$custom[$i] ;

$qr = db_query("select id from songs_members_fields where cat='$m_custom_id' and member='$id'");
if(db_num($qr)){
   db_query("update songs_members_fields set value='$m_custom_name' where cat='$m_custom_id' and member='$id'");
 }else{
   db_query("insert into songs_members_fields (member,cat,value) values('$id','$m_custom_id','$m_custom_name')");
}

       }
   }
   }

   print_admin_table("<center>$phrases[member_edited_successfully]</center>");
         }

//---------- show members search form ---------
print "<p align=center class=title> $phrases[the_members] </p>
        <p align=$global_align><a href='index.php?action=member_add'><img src='images/add.gif' border=0> $phrases[add_member] </a></p>
              <center>
     <form action=index.php method=get>
      <fieldset style=\"width:80%;padding: 2\">
      <table width=100%>
   <input type=hidden name='action' value='members_search'>

   <tr><td> $phrases[username] : </td><td><input type=text name=username size=30></td></tr>
   <tr><td> $phrases[email]  : </td><td><input type=text name=email size=30></td></tr>";
    print "</table>
</fieldset>";

      print "<br><br><fieldset style=\"width:80%;padding: 2\">
<table width=100%>
    <tr><td><b> $phrases[birth] </b> </td><td>
    <input type=text size=1 name='date_d'> - <input type=text size=1 name='date_m'> - <input type=text size=4 name='date_y'></td></tr>

            <tr>  <td><b>$phrases[country] </b> </td><td><select name=country><option value=''></option>";
            $c_qr = db_query("select * from songs_countries order by binary name asc");
   while($c_data = db_fetch($c_qr)){


        print "<option value='$c_data[name]'>$c_data[name]</option>";
           }
           print "</select></td>   </tr></table></fieldset>";

   $cf = 0 ;

   //------------ custom fields -----
   if(!$members_connector['enable'] || $members_connector['same_connection']){
$qr = db_query("select * from songs_members_sets order by required,ord");
   if(db_num($qr)){
    print "<br><br><fieldset style=\"width:80%;padding: 2\">
    <legend>$phrases[addition_fields] </legend>
<br><table width=100%>";

while($data = db_fetch($qr)){
    print "
    <input type=hidden name=\"custom_id[$cf]\" value=\"$data[id]\">
    <tr><td width=25%><b>$data[name]</b><br>$data[details]</td><td>";
    print get_member_field("custom[$cf]",$data,"search");
        print "</td></tr>";
$cf++;
}
print "</table>
</fieldset>";
}
   }

   print "<br><br><fieldset style=\"width:80%;padding: 2\">
      <table width=100%>

      <tr><td width=30%>$phrases[records_perpage]</td><td><input type=text name=limit size=3 value='30'></td><td align=center><input type='submit' value=' $phrases[search_do] '></td></tr>
  </table></fieldset>
   <input type=hidden name=start value=\"0\">
   </form></center>" ;
        }
 //-----------------------------------------------------
if($action=="member_edit"){
   if_admin("members");

           $qr = db_query("select * from ".members_table_replace("songs_members")." where ".members_fields_replace("id")."='$id'",MEMBER_SQL);

    if(db_num($qr)){
                   $data = db_fetch($qr);
          $birth_data = connector_get_date($data[members_fields_replace('birth')],"member_birth_array");
           print "
                   <script type=\"text/javascript\" language=\"javascript\">
<!--
function pass_ver(theForm){
 if (theForm.elements['password'].value == theForm.elements['re_password'].value){

        if(theForm.elements['email'].value && theForm.elements['username'].value){
        return true ;
        }else{
       alert (\"$phrases[err_fileds_not_complete]\");
return false ;
}
}else{
alert (\"$phrases[err_passwords_not_match]\");
return false ;
}
}
//-->
</script>

           <center>  <p class=title>  $phrases[member_edit] </p>

           <form action=index.php method=post onsubmit=\"return pass_ver(this)\">
          <input type=hidden name=action value=member_edit_ok>
          <input type=hidden name=id value='".intval($id)."'>

          <fieldset style=\"width:70%;padding: 2\"><table width=100%>

     <tr>
          <td width=20%>
         $phrases[username] :
          </td><td ><input type=text name=username value='".$data[members_fields_replace("username")]."'></td>  </tr>
           <td width=20%>
          $phrases[email] :
          </td><td ><input type=text name=email value='".$data[members_fields_replace("email")]."' size=30></td>  </tr>
          <tr>  <td>  $phrases[password] : </td><td><input type=password name=password></td>   </tr>
          <tr>  <td>  $phrases[password_confirm] : </td><td><input type=password name=re_password></td>   </tr>
         <tr><td colspan=2><font color=#D90000>*  $phrases[leave_blank_for_no_change] </font></td></tr>
             <tr><td colspan=2>&nbsp;</td></tr>




 <tr>   <td>$phrases[member_acc_type] : </td><td>";
                print_select_row("usr_group",get_members_groups_array(),$data[members_fields_replace('usr_group')]);
                    /*
             if($data[members_fields_replace('usr_group')]==member_group_replace(1)){$chk2 = "selected" ; $chk1="";$chk3="";}
             elseif($data[members_fields_replace('usr_group')]==member_group_replace(2)){$chk2 = "" ; $chk1="";$chk3="selected";}
             elseif($data[members_fields_replace('usr_group')]==member_group_replace(0)){$chk2 = "" ; $chk1="selected";$chk3="";}

            print " <select name=usr_group><option value=0 $chk1>лэб уфди</option>
            <option value=1 $chk2>ункс</option>
            <option value=2 $chk3>улсо</option>
            </select>";
            */
            print "</td>     </tr>
</table></fieldset>";

 $cf = 0 ;

$qrf = db_query("select * from songs_members_sets where required=1 order by ord");
   if(db_num($qrf)){
    print "<br><fieldset style=\"width:70%;padding: 2\">
    <legend>$phrases[req_addition_info]</legend>
<br><table width=100%>";

while($dataf = db_fetch($qrf)){
    print "
    <input type=hidden name=\"custom_id[$cf]\" value=\"$dataf[id]\">
    <tr><td width=25%><b>$dataf[name]</b><br>$dataf[details]</td><td>";
    print get_member_field("custom[$cf]",$dataf,"edit",$data[members_fields_replace("id")]);
        print "</td></tr>";
$cf++;
}
print "</table>
</fieldset>";
}

            print "<br><fieldset style=\"width:70%;padding: 2\">
    <legend>$phrases[not_req_addition_info]</legend>
<br><table width=100%>
    <tr><td><b> $phrases[birth] </b> </td><td><select name='date_d'>";
    for($i=1;$i<=31;$i++){
             if(strlen($i) < 2){$i="0".$i;}
                 if($birth_data['day'] == $i){$chk="selected" ; }else{$chk="";}
           print "<option value=$i $chk>$i</option>";
           }
           print "</select>
           - <select name=date_m>";
            for($i=1;$i<=12;$i++){
                    if(strlen($i) < 2){$i="0".$i;}
                    if($birth_data['month'] == $i){$chk="selected" ; }else{$chk="";}
           print "<option value=$i $chk>$i</option>";
           }
           print "</select>
           - <input type=text size=3 name='date_y' value='$birth_data[year]'></td></tr>
            <tr>  <td><b>$phrases[country] </b> </td><td><select name=country><option value=''></option>";
            $c_qr = db_query("select * from songs_countries order by binary name asc");
   while($c_data = db_fetch($c_qr)){

           if($data['country']==$c_data['name']){$chk="selected";}else{$chk="";}
        print "<option value='$c_data[name]' $chk>$c_data[name]</option>";
           }
           print "</select></td>   </tr>";

           $qrf = db_query("select * from songs_members_sets where required=0 order by ord");
   if(db_num($qrf)){

while($dataf = db_fetch($qrf)){
    print "
    <input type=hidden name=\"custom_id[$cf]\" value=\"$dataf[id]\">
    <tr><td width=25%><b>$dataf[name]</b><br>$dataf[details]</td><td>";
    print get_member_field("custom[$cf]",$dataf,"edit",$data[members_fields_replace("id")]);
        print "</td></tr>";
$cf++;
}
}

           print "</table>
           </fieldset>";


          print "<br><br><fieldset style=\"width:70%;padding: 2\"><table width=100%>

           <tr><td align=center><input type=submit value=' $phrases[edit] '></td></tr>
                     <tr><td align=left><a href='index.php?action=members_mailing&username=".$data[members_fields_replace("username")]."'>$phrases[send_msg_to_member] </a> - <a href='index.php?action=member_del&id=$id' onclick=\"return confirm('".$phrases['are_you_sure']."');\">$phrases[delete]</a></td></tr>
          </tr></table></fieldset>
         </form> ";
         }else{
                 print "<center>  $phrases[this_member_not_exists] </center>";
                 }
        }
 //------------------------- add member --------
 if($action=="member_add"){
   if_admin("members");

           print "
                   <script type=\"text/javascript\" language=\"javascript\">
<!--
function pass_ver(theForm){
 if (theForm.elements['password'].value == theForm.elements['re_password'].value){

        if(theForm.elements['email'].value && theForm.elements['username'].value){
        return true ;
        }else{
       alert (\"$phrases[err_fileds_not_complete]\");
return false ;
}
}else{
alert (\"$phrases[err_passwords_not_match]\");
return false ;
}
}
//-->
</script>

           <center><p class=title>  $phrases[add_member] </p> <table width=70% class=grid>

           <form action=index.php method=post onsubmit=\"return pass_ver(this)\">
          <input type=hidden name=action value=member_add_ok>

     <tr>
          <td width=20%>
         $phrases[username] :
          </td><td ><input type=text name=username></td>  </tr>
           <td width=20%>
          $phrases[email] :
          </td><td ><input type=text name=email size=30></td>  </tr>
          <tr>  <td>  $phrases[password] : </td><td><input type=password name=password></td>   </tr>
          <tr>  <td>  $phrases[password_confirm] : </td><td><input type=password name=re_password></td>   </tr>

             <tr><td colspan=2>&nbsp;</td></tr>

             <tr>   <td>$phrases[member_acc_type] : </td><td>";
              print_select_row("usr_group",get_members_groups_array());


            print "
            </td>     </tr>
            </table>";

   $cf = 0 ;

$qrf = db_query("select * from songs_members_sets where required=1 order by ord");
   if(db_num($qrf)){
    print "<br><fieldset style=\"width:70%;padding: 2\">
    <legend>$phrases[req_addition_info]</legend>
<br><table width=100%>";

while($dataf = db_fetch($qrf)){
    print "
    <input type=hidden name=\"custom_id[$cf]\" value=\"$dataf[id]\">
    <tr><td width=25%><b>$dataf[name]</b><br>$dataf[details]</td><td>";
    print get_member_field("custom[$cf]",$dataf,"add");
        print "</td></tr>";
$cf++;
}
print "</table>
</fieldset>";
}

            print "<br><fieldset style=\"width:70%;padding: 2\">
    <legend>$phrases[not_req_addition_info]</legend>
<br><table width=100%>
    <tr><td><b> $phrases[birth] </b> </td><td><select name='date_d'>";
    for($i=1;$i<=31;$i++){
             if(strlen($i) < 2){$i="0".$i;}

           print "<option value=$i>$i</option>";
           }
           print "</select>
           - <select name=date_m>";
            for($i=1;$i<=12;$i++){
                    if(strlen($i) < 2){$i="0".$i;}

           print "<option value=$i>$i</option>";
           }
           print "</select>
           - <input type=text size=3 name='date_y' value='0000'></td></tr>
            <tr>  <td><b>$phrases[country] </b> </td><td><select name=country><option value=''></option>";
            $c_qr = db_query("select * from songs_countries order by binary name asc");
   while($c_data = db_fetch($c_qr)){


        print "<option value='$c_data[name]'>$c_data[name]</option>";
           }
           print "</select></td>   </tr>";

           $qrf = db_query("select * from songs_members_sets where required=0 order by ord");
   if(db_num($qrf)){

while($dataf = db_fetch($qrf)){
    print "
    <input type=hidden name=\"custom_id[$cf]\" value=\"$dataf[id]\">
    <tr><td width=25%><b>$dataf[name]</b><br>$dataf[details]</td><td>";
    print get_member_field("custom[$cf]",$dataf,"add");
        print "</td></tr>";
$cf++;
}
}

           print "</table>
           </fieldset>";


          print "<br><br><fieldset style=\"width:70%;padding: 2\"><table width=100%>


           <tr><td align=center><input type=submit value=' $phrases[add_button] '></td></tr>
                </table></fieldset>
         </form> ";
        }
?>