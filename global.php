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


//-----------------------------
define(MEMBER_SQL,"member_sql");
//----------------------------------
$version_number = "2.7.0" ;
//---------------------------------------------
//----------- current work dir definition -------
if (!defined('CWD')){define('CWD', (($getcwd = getcwd()) ? $getcwd : '.'));}
//---------------------------------------------
require(CWD . "/config.php") ;


//----------------- php5 varialbs support -----------------------------
$ver_str = phpversion();
list($php_major, $php_minor, $php_sub) = explode( ".", $ver_str);
if( intval($php_major) >= 5) {
$reg_long_arrays = ini_get('register_long_arrays');
if( $reg_long_arrays == 0 ) {

$HTTP_POST_VARS   = !empty($HTTP_POST_VARS)   ? $HTTP_POST_VARS   : $_POST;
$HTTP_GET_VARS    = !empty($HTTP_GET_VARS)    ? $HTTP_GET_VARS    : $_GET;
$HTTP_COOKIE_VARS = !empty($HTTP_COOKIE_VARS) ? $HTTP_COOKIE_VARS : $_COOKIE;
$HTTP_SERVER_VARS = !empty($HTTP_SERVER_VARS) ? $HTTP_SERVER_VARS : $_SERVER;
$HTTP_POST_FILES = !empty($HTTP_POST_FILES) ? $HTTP_SERVER_VARS : $_FILES;
$HTTP_ENV_VARS = !empty($HTTP_ENV_VARS) ? $HTTP_SERVER_VARS : $_ENV;

}
}
//--------- extract variabls -----------------------
 if (!empty($HTTP_POST_VARS)) {extract($HTTP_POST_VARS);}
if (!empty($HTTP_GET_VARS)) {extract($HTTP_GET_VARS);}
if (!empty($HTTP_ENV_VARS)) {extract($HTTP_ENV_VARS);}

$_SERVER['QUERY_STRING'] = strip_tags($_SERVER['QUERY_STRING']);
$_SERVER['PHP_SELF'] = strip_tags($_SERVER['PHP_SELF']);
$_SERVER['REQUEST_URI'] = strip_tags($_SERVER['REQUEST_URI']);

$PHP_SELF = $_SERVER['PHP_SELF'];

define("CUR_FILENAME",$PHP_SELF);

//---------------------- id errors protect -----------------------
 if($id && !is_array($id)){
         if(!is_numeric($id)){
                 die("<script>window.location=\"index.php\"</script>");
                 }
 }

 //---------------------- cat errors protect -----------------------
 if($cat && !is_array($cat)){
         if(!is_numeric($cat)){
                 die("<script>window.location=\"index.php\"</script>");
                 }
 }
//-------------------------------------------------------------------

require(CWD . "/includes/functions_db.php") ;
//---------------------------
db_connect($db_host,$db_username,$db_password,$db_name);


 

// ------------- lang dir -------------
if($global_lang=="arabic"){
$global_dir = "rtl" ;
$global_align = "right" ;
}else{
$global_dir = "ltr" ;
$global_align = "left" ;
}

//--------------- Load Phrases ---------------------
$phrases = array();
$qr = db_query("select * from songs_phrases");
while($data = db_fetch($qr)){

$phrases["$data[name]"] = $data['value'] ;
        }
 //------------------------------       
   
$actions_checks = array(
"$phrases[main_page]" => 'main' ,
"$phrases[the_singers]" => 'browse',
"$phrases[the_songs]" => 'songs', 
"$phrases[lyrics]" => 'lyrics',   
"$phrases[the_videos]" => 'browse_videos',
"$phrases[the_news]" => 'news',
"$phrases[pages]" => 'pages',
"$phrases[the_search]" => 'search' ,
"$phrases[the_votes]" => 'votes',
"$phrases[the_statics]" => 'statics',
"$phrases[contact_us]" => 'contactus'
);


$permissions_checks = array(
"$phrases[urls_fields]" => 'urls_fields',
"$phrases[songs_custom_fields]" => 'songs_fields',
"$phrases[new_songs_menu]" => 'new_songs' ,
"$phrases[new_stores_menu]" => 'new_stores' , 
"$phrases[the_templates]" => 'templates' ,
"$phrases[the_news]" => 'news' ,
"$phrases[the_phrases]" => 'phrases' ,
"$phrases[the_banners]" => 'adv',
"$phrases[the_votes]" => 'votes',
"$phrases[the_members]" => 'members'  
);


$orderby_checks = array(
"$phrases[the_date]" => 'id',
"$phrases[the_name]" => 'name',
"$phrases[the_most_voted]" => 'votes'
);
        

        
$settings = array();
$ramadv_settings = array();
//--------------- Get Settings --------------------------
function load_settings(){
global  $settings ;
$qr = db_query("select * from songs_settings");
while($data = db_fetch($qr)){

$settings["$data[name]"] = $data['value'] ;
        }
}
//-------------------------------------------------------
function get_ramadv_settings(){
global $ramadv_settings ;
        $data =  db_qr_fetch("select content,active,width,height from songs_ramadv");
         $ramadv_settings['width'] = $data['width'];
         $ramadv_settings['height'] = $data['height'];
         $ramadv_settings['active'] = $data['active'];
         $ramadv_settings['content'] = $data['content'];
        }

 //------------------ Load Settings ---------
load_settings();

$sitename = $settings['sitename'] ;
$section_name = $settings['section_name'] ;
$siteurl = "http://$_SERVER[HTTP_HOST]" ;
$scripturl = $siteurl . iif(trim($script_path),"/".$script_path,"");
$upload_types = explode(',',str_replace(" ","",$settings['uploader_types']));
$mailing_email = $settings['mailing_email'];


//------ validate styleid functon ------
function is_valid_styleid($styleid){
if(is_numeric($styleid)){
$data = db_qr_fetch("select count(id) as num from songs_templates_cats where id='$styleid' and selectable=1");
if($data['num']){
    return true;
}else{
    return false;
    }
}else{
    return false;
}
}
//----- check if valid styleid -------

$styleid=(isset($styleid) ? intval($styleid) : get_cookie("styleid"));
if(!is_valid_styleid($styleid)){
$styleid = $settings['default_styleid'];
if(!is_valid_styleid($styleid)){
$styleid = 1;
}
}
set_cookie('styleid', intval($styleid));

//------- theme file ---------
require(CWD . "/includes/functions_themes.php") ;


require(CWD . "/includes/functions_members.php") ; 



if(!defined("IS_DOWNLOAD_FILE") && !defined("IS_RAM_BANNERS_FILE")){
require(CWD . '/includes/class_security_img.php');
$sec_img = new sec_img_verification();
}

//-------- counter file ---------
require(CWD . "/counter.php");


function if_admin($dep="",$continue=0){
        global $user_info,$phrases ;

        if(!$dep){

        if($user_info['groupid'] != 1){



        if(!$continue){

        print_admin_table("<center>$phrases[access_denied]</center>");

         die();

         }
           return false;
         }else{
                 return true;
                 }
          }else{
           if($user_info['groupid'] != 1){

                  $data=db_qr_fetch("select * from songs_user where id='$user_info[id]'");
                  $prm_array = explode(",",$data['cp_permisions']);

                  if(!in_array($dep,$prm_array)){

        if(!$continue){
         print_admin_table("<center>$phrases[access_denied]</center>");
         die();
                           }
                            return false;
                          }else{
                          return true;
                                  }
                 }else{
                         return true;
                         }
            }
         }
//-------------------------------------------------------------
function get_image($src,$default=''){
         if($src){
              return $src ;
            }else{

    return iif($default,$default,"images/no_pic.gif") ;
    }
    }
//------------ copyrights text ---------------------
function print_copyrights(){
global $_SERVER,$settings,$copyrights_lang ;

if(COPYRIGHTS_TXT_MAIN){
if($copyrights_lang == "arabic"){
print "<p align=center>Ã„Ì⁄ «·ÕﬁÊﬁ „Õ›ÊŸ… ·‹ :
<a target=\"_blank\" href=\"http://$_SERVER[HTTP_HOST]\">$settings[copyrights_sitename]</a> © " . date('Y') . " <br>
»—„Ã… <a target=\"_blank\" href=\"http://allomani.com/\"> «··Ê„«‰Ì ··Œœ„«  «·»—„ÃÌ… </a> © 2009";
}else{
print "<p align=center>Copyright © ". date('Y')." <a target=\"_blank\" href=\"http://$_SERVER[HTTP_HOST]\">$settings[copyrights_sitename]</a> - All rights reserved <br>
Programmed By <a target=\"_blank\" href=\"http://allomani.com/\"> Allomani </a> © 2009";
    }
}
        }

//---------------------- Read File ------------------------
function read_file($filename){
$fn = fopen($filename,"r");
$fdata = fread($fn,filesize($filename));
fclose($fn);
return $fdata ;
}


//---------- validate email --------
function check_email_address($email) {
    // First, we check that there's one @ symbol, and that the lengths are right
    if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
        // Email invalid because wrong number of characters in one section, or wrong number of @ symbols.
        return false;
    }
    // Split it into sections to make life easier
    $email_array = explode("@", $email);
    $local_array = explode(".", $email_array[0]);
    for ($i = 0; $i < sizeof($local_array); $i++) {
         if (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
            return false;
        }
    }
    if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) { // Check if domain is IP. If not, it should be valid domain name
        $domain_array = explode(".", $email_array[1]);
        if (sizeof($domain_array) < 2) {
                return false; // Not enough parts to domain
        }
        for ($i = 0; $i < sizeof($domain_array); $i++) {
            if (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|([A-Za-z0-9]+))$", $domain_array[$i])) {
                return false;
            }
        }
    }
    return true;
}
        
        
//---------------------------------------------
function execphp_fix_tag($match)
{
        // replacing WPs strange PHP tag handling with a functioning tag pair
        $output = '<?php'. $match[2]. '?>';
        return $output;
}
//------------------------------------------------------------
function run_php($content)
{

$content = str_replace(array("&#8216;", "&#8217;"), "'",$content);
$content = str_replace(array("&#8221;", "&#8220;"), '"', $content);
$content = str_replace("&Prime;", '"', $content);
$content = str_replace("&prime;", "'", $content);
        // for debugging also group unimportant components with ()
        // to check them with a print_r($matches)
        $pattern = '/'.
                '(?:(?:<)|(\[))[\s]*\?php'. // the opening of the <?php or [?php tag
                '(((([\'\"])([^\\\5]|\\.)*?\5)|(.*?))*)'. // ignore content of PHP quoted strings
                '\?(?(1)\]|>)'. // the closing ? > or ?] tag
                '/is';
      $content = preg_replace_callback($pattern, 'execphp_fix_tag', $content);
        // to be compatible with older PHP4 installations
        // don't use fancy ob_XXX shortcut functions
        ob_start();
         eval(" ?> $content ");


        $output = ob_get_contents();
        ob_end_clean();
        print $output;
}
//---------------------------- Admin Login Function ---------------------------------
$user_info = array();
function check_login_cookies(){
      global $user_info;

$user_info['username'] = get_cookie('admin_username');
$user_info['password'] = get_cookie('admin_password');
$user_info['id'] = intval(get_cookie('admin_id'));


   if($user_info['id']){
   $qr = db_query("select * from songs_user where id='$user_info[id]'");
         if(db_num($qr)){
           $data = db_fetch($qr);
           if($data['username'] == $user_info['username'] && md5($data['password']) == $user_info['password']){
                   $user_info['email'] = $data['email'];
           $user_info['groupid'] = $data['group_id'];
                   return true ;
                   }else{
                           return false ;
                           }

                 }else{
                         return false ;
                         }

           }else{
                   return false ;
                   }

        }
 

//--------- Generate Random String -----------
function rand_string($length = 8){

  // start with a blank password
  $password = "";

  // define possible characters
  $possible = "0123456789bcdfghjkmnpqrstvwxyz";

  // set up a counter
  $i = 0;

  // add random characters to $password until $length is reached
  while ($i < $length) {

    // pick a random character from the possible ones
    $char = substr($possible, mt_rand(0, strlen($possible)-1), 1);

    // we don't want this character if it's already in the password
    if (!strstr($password, $char)) {
      $password .= $char;
      $i++;
    }

  }

  // done!
  return $password;

}
//--------------------------------- Check Functions ---------------------------------
function check_safe_functions($condition_value){

  global $safe_functions,$phrases ;
      if (preg_match_all('#([a-z0-9_{}$>-]+)(\s|/\*.*\*/|(\#|//)[^\r\n]*(\r|\n))*\(#si', $condition_value, $matches))
                        {

                                $functions = array();
                                foreach($matches[1] AS $key => $match)
                                {
                                        if (!in_array(strtolower($match), $safe_functions) && function_exists(strtolower($match)))
                                        {
                                                $funcpos = strpos($condition_value, $matches[0]["$key"]);
                                                $functions[] = array(
                                                        'func' => stripslashes($match),
                                                    //    'usage' => substr($condition_value, $funcpos, (strpos($condition_value, ')', $funcpos) - $funcpos + 1)),
                                                );
                                        }
                                }
                                if (!empty($functions))
                                {
                                        unset($safe_functions[0], $safe_functions[1], $safe_functions[2]);



                                        foreach($functions AS $error)
                                        {
                                                $errormsg .= "$phrases[err_function_usage_denied]: <code>" . htmlspecialchars($error['func']) . "</code>
                                                <br>\n";
                                        }

                                        echo "<p dir=rtl>$errormsg</p>";
                                        return false ;
                                }else{
                                         return true ;
                                          }
                        }
                        return true ;
                        }
//---------------------- Compile Safe Tempalte -------------------
function compile_template($template)
{
global $safe_functions ;
     //   $orig_template = $template;
     //   $template = addslashes($template);
     //   $template = process_template_conditionals($template) ;




    //    $template = str_replace('\\\\$', '\\$', $template);

       if(check_safe_functions($template)){

      run_php($template);
        }
}


//---------------------- Send Email Function -------------------
function send_email($from_name,$from_email,$to_email,$subject,$msg,$html=0,$encoding=""){
        global $PHP_SELF,$smtp_settings,$settings ;
//  $from_name = utf8_encode($from_name);
    //$from_email = htmlspecialchars($from_email);
   // $to_email = htmlspecialchars($to_email);
  //  $subject = utf8_encode($subject);
   // $msg=htmlspecialchars($msg);


    $from = $from_name." <".$from_email.">" ;
      

    $mailHeader  = 'From: '.$from.' '."\r\n";
    $mailHeader .= "Reply-To: $from_email\r\n";
    $mailHeader .= "Return-Path: $from_email\r\n";
    
    
     if($smtp_settings['enable']){  
    $mailHeader .= "To: $to_email\r\n";
     }
     
     
    $mailheader.="MIME-Version: 1.0\r\n";
    $mailHeader .= "Content-Type: ".iif($html,"text/html","text/plain")."; charset=".($encoding ? $encoding : $settings['site_pages_encoding'])."\r\n";
    
     if($smtp_settings['enable']){  
    $mailHeader .= "Subject: $subject\r\n";
     }
     
     
    $mailHeader .= "Date: ".strftime("%a, %d %b %Y %H:%M:%S %Z")."\r\n";
    $mailHeader .= "X-EWESITE: Allomani\r\n";
    $mailHeader .= "X-Mailer: PHP/".phpversion()."\r\n";
    $mailHeader .= "X-Mailer-File: "."http://".$_SERVER['HTTP_HOST'].($script_path ? "/".$script_path:"").$PHP_SELF."\r\n";
    $mailHeader .= "X-Sender-IP: {$_SERVER['REMOTE_ADDR']}\r\n";



    
    if($smtp_settings['enable']){

   if(!class_exists("smtp_class")){
   require_once(CWD ."/includes/class_smtp.php");
   }

   $smtp=new smtp_class;

    $smtp->host_name=$smtp_settings['host_name'];
    $smtp->host_port=$smtp_settings['host_port'];
    $smtp->ssl=$smtp_settings['ssl'];
    $smtp->localhost="localhost";       /* Your computer address */
    $smtp->direct_delivery=0;           /* Set to 1 to deliver directly to the recepient SMTP server */
    $smtp->timeout=$smtp_settings['timeout'];    /* Set to the number of seconds wait for a successful connection to the SMTP server */
    $smtp->data_timeout=0;              /* Set to the number seconds wait for sending or retrieving data from the SMTP server.
                                           Set to 0 to use the same defined in the timeout variable */
    $smtp->debug=$smtp_settings['debug'];                     /* Set to 1 to output the communication with the SMTP server */
    $smtp->html_debug=1;                /* Set to 1 to format the debug output as HTML */

    if($smtp_settings['username'] && $smtp_settings['password']){
    $smtp->pop3_auth_host=$smtp_settings['host_name'];           /* Set to the POP3 authentication host if your SMTP server requires prior POP3 authentication */
    $smtp->user=$smtp_settings['username'];                     /* Set to the user name if the server requires authetication */
     $smtp->password=$smtp_settings['password'];                 /* Set to the authetication password */
    $smtp->realm="";                    /* Set to the authetication realm, usually the authentication user e-mail domain */
    }

    $smtp->workstation="";              /* Workstation name for NTLM authentication */
    $smtp->authentication_mechanism=""; /* Specify a SASL authentication method like LOGIN, PLAIN, CRAM-MD5, NTLM, etc..
                                           Leave it empty to make the class negotiate if necessary */

   $mailResult =  $smtp->SendMessage(
        $from_email,
        array(
            $to_email
        ),
        array(
            $mailHeader
        ),
        $msg,0);

        if($mailResult){
              return true ;
                }else{
                    if($smtp_settings['show_errors']){
                    print "<b>SMTP Error: </b> ".$smtp->error ."<br>";
                    }
               return false;
               }

    }else{
    $mailResult = @mail($to_email,$subject,$msg,$mailHeader);

               if($mailResult){
              return true ;
                }else{
               return false;
               }
    }
        }

//----------- Get Hooks ------------
function get_plugins_hooks(){

$hooklocations = array();
    require_once(CWD . '/includes/class_xml.php');
    $handle = opendir(CWD . '/xml/');
    while (($file = readdir($handle)) !== false)
    {
        if (!preg_match('#^hooks_(.*).xml$#i', $file, $matches))
        {
            continue;
        }
        $product = $matches[1];

        $phrased_product = $products[($product ? $product : 'allomani')];
        if (!$phrased_product)
        {
            $phrased_product = $product;
        }

        $xmlobj = new XMLparser(false, CWD . "/xml/$file");
        $xml = $xmlobj->parse();

        if (!is_array($xml['hooktype'][0]))
        {
            // ugly kludge but it works...
            $xml['hooktype'] = array($xml['hooktype']);
        }

        foreach ($xml['hooktype'] AS $key => $hooks)
        {
            if (!is_numeric($key))
            {
                continue;
            }
            //$phrased_type = isset($vbphrase["hooktype_$hooks[type]"]) ? $vbphrase["hooktype_$hooks[type]"] : $hooks['type'];
            $phrased_type =  $hooks['type'];
            $hooktype = $phrased_product . ' : ' . $phrased_type;

            $hooklocations["$hooktype"] = array();

            if (!is_array($hooks['hook']))
            {
                $hooks['hook'] = array($hooks['hook']);
            }

            foreach ($hooks['hook'] AS $hook)
            {
                $hookid = (is_string($hook) ? $hook : $hook['value']);
                $hooklocations["$hooktype"]["$hookid"] = $hookid;
            }
        }
    }
    ksort($hooklocations);
    return $hooklocations ;
    }

//--------- Get used hooks List -----------
$qr = db_query("select hookid from songs_hooks where active='1'");
while($data = db_fetch($qr)){
$used_hooks[] = $data['hookid'];
}
unset($qr,$data);
//-------------- compile hook --------------
function compile_hook($hookid){
global $used_hooks;
if(is_array($used_hooks)){
if(in_array($hookid,$used_hooks)){
$qr = db_query("select code from songs_hooks where hookid='".db_clean_string($hookid,"text","read")."' and active='1' order by ord asc");
if(db_num($qr)){
while($data=db_fetch($qr)){
run_php($data['code']);
    }
}else{
 return false;
 }
 }else{
     return false;
     }
     }else{
         return false;
         }
}

//--------- iif expression ------------
function iif($expression, $returntrue, $returnfalse = '')
{
    return ($expression ? $returntrue : $returnfalse);
}

//------- set cookies function -----------
function set_cookie($name,$value=""){
global $cookies_prefix,$cookies_timemout,$cookies_path,$cookies_domain;
$name = $cookies_prefix . $name;
$k_timeout = time() + (60 * 60 * 24 * intval($cookies_timemout));
setcookie($name, $value, $k_timeout,$cookies_path,$cookies_domain);
}
//--------- get cookies funtion ---------
function get_cookie($name){
global $cookies_prefix,$HTTP_COOKIE_VARS;
$name = $cookies_prefix . $name;
return $HTTP_COOKIE_VARS[$name];
}


//--------- array replace --------
if(!function_exists('array_replace')){
function array_replace($tofind, $toreplace,$a){

if(!is_array($a)){$a = array($a);}

for($i=0;$i<count($a);$i++){
$a[$i] = str_replace($tofind,$toreplace,$a[$i]);
}

return $a ;
}
}

//---------- Flush Function -------------
function data_flush()
{
    static $output_handler = null;
    if ($output_handler === null)
    {
        $output_handler = @ini_get('output_handler');
    }

    if ($output_handler == 'ob_gzhandler')
    {
        // forcing a flush with this is very bad
        return;
    }

    flush();
    if (PHP_VERSION  >= '4.2.0' AND function_exists('ob_flush') AND function_exists('ob_get_length') AND ob_get_length() !== false)
    {
        @ob_flush();
    }
    else if (function_exists('ob_end_flush') AND function_exists('ob_start') AND function_exists('ob_get_length') AND ob_get_length() !== FALSE)
    {
        @ob_end_flush();
        @ob_start();
    }
}

//----------- select row ------------
function print_select_row($name, $array, $selected = '', $options="" , $size = 0, $multiple = false,$same_values=false)
{
    global $vbulletin;

    $select = "<select name=\"$name\" id=\"sel_$name\"" . iif($size, " size=\"$size\"") . iif($multiple, ' multiple="multiple"') . iif($options , " $options").">\n";
    $select .= construct_select_options($array, $selected,$same_values);
    $select .= "</select>\n";

    print $select;
}


function construct_select_options($array, $selectedid = '',$same_values=false)
{
    if (is_array($array))
    {
        $options = '';
        foreach($array AS $key => $val)
        {
            if (is_array($val))
            {
                $options .= "\t\t<optgroup label=\"" . $key . "\">\n";
                $options .= construct_select_options($val, $selectedid, $tabindex, $htmlise);
                $options .= "\t\t</optgroup>\n";
            }
            else
            {
                if (is_array($selectedid))
                {
                    $selected = iif(in_array($key, $selectedid), ' selected="selected"', '');
                }
                else
                {
                    $selected = iif($key == $selectedid, ' selected="selected"', '');
                }
                $options .= "\t\t<option value=\"".($same_values ? $val : $key). "\"$selected>" . $val . "</option>\n";
            }
        }
    }
    return $options;
}
//---------- print text row ----------
function print_text_row($name,$value="",$size="",$dir="",$options=""){
print "<input type=text name=\"$name\"".iif($value," value=\"$value\"").iif($size," size=\"$size\"").iif($dir," dir=\"$dir\"").iif($options," $options").">";
}

//--------- print admin table -------------
function print_admin_table($content,$width="50%",$align="center"){
    print "<center><table class=grid width='$width'><tr><td align='$align'>$content</td></tr></table></center>";
    }

 //-------------- Get Remote filesize --------
function fetch_remote_filesize($url)
    {
        // since cURL supports any protocol we should check its http(s)
        preg_match('#^((http|ftp)s?):\/\/#i', $url, $check);
        if (ini_get('allow_url_fopen') != 0 AND $check[1] == 'http')
        {
            $urlinfo = @parse_url($url);

            if (empty($urlinfo['port']))
            {
                $urlinfo['port'] = 80;
            }

            if ($fp = @fsockopen($urlinfo['host'], $urlinfo['port'], $errno, $errstr, 30))
            {
                fwrite($fp, 'HEAD ' . $url . " HTTP/1.1\r\n");
                fwrite($fp, 'HOST: ' . $urlinfo['host'] . "\r\n");
                fwrite($fp, "Connection: close\r\n\r\n");

                while (!feof($fp))
                {
                    $headers .= fgets($fp, 4096);
                }
                fclose ($fp);

                $headersarray = explode("\n", $headers);
                foreach($headersarray as $header)
                {
                    if (stristr($header, 'Content-Length') !== false)
                    {
                        $matches = array();
                        preg_match('#(\d+)#', $header, $matches);
                        return sprintf('%u', $matches[0]);
                    }
                }
            }
        }
        else if (false AND !empty($check) AND function_exists('curl_init') AND $ch = curl_init())
        {
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_NOBODY, true);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'HEAD');
            /* Need to enable this for self signed certs, do we want to do that?
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            */

            $header = curl_exec($ch);
            curl_close($ch);

            if ($header !== false)
            {
                preg_match('#Content-Length: (\d+)#i', $header, $matches);
                return sprintf('%u', $matches[1]);
            }
        }
        return false;
    }
 //--------------- Get file Extension ----------
 function file_extension($filename)
{
    return substr(strrchr($filename, '.'), 1);
}

//-------------- Get Dir Files List ----------
function get_files($dir,$allowed_types="",$subdirs_search=1) {
      $dir = (substr($dir,-1,1)=="/" ? substr($dir,0,strlen($dir)-1) : $dir);

    if($dh = opendir($dir)) {

        $files = Array();
        $inner_files = Array();

        while($file = readdir($dh)) {
            if($file != "." && $file != ".." && $file[0] != '.') {
                if(is_dir($dir . "/" . $file) && $subdirs_search) {
                    $inner_files = get_files($dir . "/" . $file,$allowed_types);
                    if(is_array($inner_files)) $files = array_merge($files, $inner_files);
                }else{
                  $fileinfo= pathinfo($dir . "/" . $file);
                $imtype = $fileinfo["extension"];
          if(is_array($allowed_types)){
          if(in_array($imtype,$allowed_types)){
               $files[] =  $dir . "/" . $file;
           }
          }else{
               $files[] =  $dir . "/" . $file;
          }
                }
            }
        }

        closedir($dh);
        return $files;
    }
}

//----------- Number Format --------------------
function convert_number_format($number, $decimals = 0, $bytesize = false, $decimalsep = null, $thousandsep = null)
{

    $type = '';

    if (empty($number))
    {
        return 0;
    }
    else if (preg_match('#^(\d+(?:\.\d+)?)(?>\s*)([mkg])b?$#i', trim($number), $matches))
    {
        switch(strtolower($matches[2]))
        {
            case 'g':
                $number = $matches[1] * 1073741824;
                break;
            case 'm':
                $number = $matches[1] * 1048576;
                break;
            case 'k':
                $number = $matches[1] * 1024;
                break;
            default:
                $number = $matches[1] * 1;
        }
    }

    if ($bytesize)
    {
        if ($number >= 1073741824)
        {
            $number = $number / 1073741824;
            $decimals = 2;
            $type = " GB";
        }
        else if ($number >= 1048576)
        {
            $number = $number / 1048576;
            $decimals = 2;
            $type = " MB";
        }
        else if ($number >= 1024)
        {
            $number = $number / 1024;
            $decimals = 1;
            $type = " KB";
        }
        else
        {
            $decimals = 0;
            $type = " Byte";
        }
    }

    if ($decimalsep === null)
    {
     //   $decimalsep = ".";
    }
    if ($thousandsep === null)
    {
    //    $thousandsep = ",";
    }

    if($decimalsep && $thousandsep){
    return str_replace('_', '&nbsp;', number_format($number, $decimals, $decimalsep, $thousandsep)) . $type;
    }else{
         return str_replace('_', '&nbsp;', round($number,$decimals)) . $type;
    }
}

//--------------------- preview Text ------------------------------------
function getPreviewText($text) {
             global $preview_text_limit ;
    // Strip all tags
    $desc = strip_tags(html_entity_decode($text), "<a><em>");
    $charlen = 0; $crs = 0;
    if(strlen_HTML($desc) == 0)
        $preview = substr($desc, 0, $preview_text_limit);
    else
    {
        $i = 0;
        while($charlen < 80)
        {
            $crs = strpos($desc, " ", $crs)+1;
            $lastopen = strrpos(substr($desc, 0, $crs), "<");
            $lastclose = strrpos(substr($desc, 0, $crs), ">");
            if($lastclose > $lastopen)
            {
                // we are not in a tag
                $preview = substr($desc, 0, $crs);
                $charlen = strlen_noHTML($preview);
            }
            $i++;
        }
    }
    return trim($preview)  ;

}


function strlen_noHtml($string){
    $crs = 0;
    $charlen = 0;
    $len = strlen($string);
    while($crs < $len)
    {
        $offset = $crs;
        $crs = strpos($string, "<", $offset);
        if($crs === false)
        {
           $crs = $len;
           $charlen += $crs - $offset;
        }
        else
        {
            $charlen += $crs - $offset;
            $crs = strpos($string, ">", $crs)+1;
        }
    }
    return $charlen;
}


function strlen_Html($string){
    $crs = 0;
    $charlen = 0;
    $len = strlen($string);
    while($crs < $len)
    {
        $scrs = strpos($string, "<", $crs);
        if($scrs === false)
        {
           $crs = $len;
        }
        else
        {
            $crs = strpos($string, ">", $scrs)+1;
            if($crs === false)
                $crs = $len;
            $charlen += $crs - $scrs;
        }
    }
    return $charlen;
}


//------------- convert ar 2 en ------------------

function convert2en($filename){
    
if(!ereg("^([-a-zA-Z0-9_.!@#$&*+=|~%^()/\'])*$", $filename)){
$filename= str_replace("'","",$filename);
$filename= str_replace(" ","_",$filename);
$filename= str_replace("«","a",$filename);
$filename= str_replace("√","a",$filename);
$filename= str_replace("¬","a",$filename); 
$filename= str_replace("‹","",$filename);   
$filename= str_replace("‹","",$filename);  
$filename= str_replace("≈","i",$filename);
$filename= str_replace("»","b",$filename);
$filename= str_replace(" ","t",$filename);
$filename= str_replace("À","th",$filename);
$filename= str_replace("Ã","g",$filename);
$filename= str_replace("Õ","7",$filename);
$filename= str_replace("Œ","k",$filename);
$filename= str_replace("œ","d",$filename);
$filename= str_replace("–","d",$filename);
$filename= str_replace("—","r",$filename);
$filename= str_replace("“","z",$filename);
$filename= str_replace("”","s",$filename);
$filename= str_replace("‘","sh",$filename);
$filename= str_replace("’","s",$filename);
$filename= str_replace("÷","5",$filename);
$filename= str_replace("⁄","a",$filename);
$filename= str_replace("€","gh",$filename);
$filename= str_replace("›","f",$filename);
$filename= str_replace("ﬁ","k",$filename);
$filename= str_replace("ﬂ","k",$filename);
$filename= str_replace("·","l",$filename);
$filename= str_replace("‰","n",$filename);
$filename= str_replace("Â","h",$filename);
$filename= str_replace("Ì","y",$filename);
$filename= str_replace("ÿ","6",$filename);
$filename= str_replace("Ÿ","d",$filename);
$filename= str_replace("Ê","w",$filename);
$filename= str_replace("ƒ","o",$filename);
$filename= str_replace("∆","i",$filename);
$filename= str_replace("·«","la",$filename);
$filename= str_replace("·√","la",$filename);
$filename= str_replace("Ï","a",$filename);
$filename= str_replace("…","t",$filename);
$filename= str_replace("„","m",$filename);
}


return $filename ;

}

 
//--------------------------- Create Thumb ----------------------------
function create_thumb($filename , $width , $height,$fixed,$suffix=''){
    
require_once(CWD .'/includes/class_thumb.php');
if(function_exists("ImageCreateTrueColor")){
 if(file_exists(CWD . "/$filename")){ 
 $img_info = @getimagesize(CWD . "/$filename"); 
 $thumb=new thumbnail(CWD . "/$filename");

 if($fixed){
 $thumb->size_fixed($width,$height);
 }else{    
 if($img_info[0] < $width){
 $width = $img_info[0];
 }
 if($img_info[1] < $height){
$height = $img_info[1];
  }
  
 if($height > $width){
  $thumb->size_height($height); 
 }else{
 $thumb->size_width($width);
 } 
 }        
           

   $imtype = file_extension(CWD . "/$filename");


$thumb->jpeg_quality(100); 

$save_name  =  basename($filename);
$save_path = str_replace("/".$save_name,'',$filename);

$imtype = file_extension($save_name);
$save_name = convert2en($save_name);
$save_name = strtolower($save_name);
$save_name= str_replace(" ","_",$save_name);

if($suffix){
$save_name = str_replace(".$imtype","",$save_name)."_".$suffix.".$imtype";
}

    
while(file_exists(CWD . "/" .$save_path."/".$save_name)){
$save_name = str_replace(".$imtype","",$save_name)."_".rand(0,999).".$imtype";    
}
    
    
$thumb->save(CWD . "/" .$save_path."/".$save_name);           
return ($save_path."/".$save_name) ;
 }else{
     return false;
 }
 }else{
return $false;     
 }
        }
//---------- Delete song -------//
function delete_song($id){
$id=intval($id);
db_query("delete from songs_urls_data where song_id='$id'");
db_query("delete from songs_custom_fields where song_id='$id'");
db_query("delete from songs_songs where id='$id'");
} 

//-------- Delete Singer -------//
function delete_singer($id){
    
$qr = db_query("select id from songs_songs where album='$id'");
while($data = db_fetch($qr)){
delete_song($data['id']); 
}

db_query("delete from songs_singers where id='$id'");
db_query("delete from songs_albums where cat='$id'");
}   

//---------- Pages Links ---------//
function print_pages_links($start,$items_count,$items_perpage,$page_string){
  
 global $phrases ;
   
$previous_page=$start - $songs_perpage;
$next_page=$start + $songs_perpage;


if ($items_count>$items_perpage){
echo "<p align=center>$phrases[pages] : ";
if($start >0){
$previouspage = $start - $items_perpage;
echo "<a href='".str_replace("{start}",$previouspage,$page_string)."'><</a>\n";
}


$pages=intval($items_count/$items_perpage);
if ($items_count%$items_perpage){$pages++;}
for ($i = 1; $i <= $pages; $i++) {
$nextpag = $items_perpage*($i-1);
if ($nextpag == $start){
echo "<font size=2 face=tahoma><b>$i</b></font>&nbsp;\n";
}else{
echo "<a href='".str_replace("{start}",$nextpag,$page_string)."'>[$i]</a>&nbsp;\n";}
}
if (! ( ($start/$items_perpage) == ($pages - 1) ) && ($pages != 1) )
{$nextpag = $start+$items_perpage;
echo "<a href='".str_replace("{start}",$nextpag,$page_string)."'>></a>\n";}
echo "</p>";}
}  

//------------- if Videos Cat Admin ---------
function if_videos_cat_admin($cat,$skip_zero_id=true){
 global $user_info,$phrases ;

 if($user_info['groupid'] != 1){
     $prm_data = db_qr_fetch("select permisions_videos from songs_user where id='$user_info[id]'");


  if($cat){

  $cats_permisions = explode(",",$prm_data['permisions_videos']);
         if(!in_array($cat,$cats_permisions)){
              print_admin_table("<center>$phrases[err_cat_access_denied]</center>");
         die();
    }
    }else{
        if(!$skip_zero_id){
          print_admin_table("<center>$phrases[err_cat_access_denied]</center>");
         die();
        }
    }
      }
}

//---------- Get Videos Cats --------//
function get_videos_cats($id){
  $cats_arr = array();
   $cats_arr[]=$id;

         $qr1 = db_query("select id from songs_videos_cats where cat='$id'");
         while($data1 = db_fetch($qr1)){
          $nxx = get_videos_cats($data1['id']);
          if(is_array($nxx)){
              $cats_arr = array_merge($nxx,$cats_arr);
          }
           unset($nxx);
          }

          return  $cats_arr ;
         }
         
//-------- Songs Custom Fields ----------

function get_song_field($name,$data,$action="add",$song_id=0,$default_value=""){
    global $phrases;
      $cntx = "" ;

if($data['type']=="text"){

if($action=="edit"){
    $dtsx  = db_qr_fetch("select value from songs_custom_fields where song_id='$song_id' and cat='$data[id]'");

 $cntx .= "<input type=text name=\"$name\" value=\"$dtsx[value]\" $data[style]>";
        }elseif($action=="add"){

        if($default_value){ $data['value'] =  $default_value;}

        $cntx .= "<input type=text name=\"$name\" value=\"$data[value]\" $data[style]>";
            }else{
            $cntx .= "<input type=text name=\"$name\" value=\"\" $data[style]>";
                }

//---------- text area -------------
}elseif($data['type']=="textarea"){

if($action=="edit"){
    $dtsx  = db_qr_fetch("select value from songs_custom_fields where song_id='$song_id' and cat='$data[id]'");

$cntx .= "<textarea name=\"$name\" $data[style]>$dtsx[value]</textarea>";
   }elseif($action=="add"){
        if($default_value){ $data['value'] =  $default_value;}
$cntx .= "<textarea name=\"$name\" $data[style]>$data[value]</textarea>";
   }else{
$cntx .= "<textarea name=\"$name\" $data[style]></textarea>";
    }

//-------- select -----------------
}elseif($data['type']=="select"){

        if($action=="edit"){
        $dtsx  = db_qr_fetch("select value from songs_custom_fields where song_id='$song_id' and cat='$data[id]'");
        }

        $cntx .= "<select name=\"$name\" $data[style]>";
        if($action=="search"){ $cntx .= "<option value=\"\">$phrases[without_selection]</option>";}

        $vx  = explode("\n",$data['value']);
        foreach($vx as $value){

        if($action=="edit" && $value==$dtsx['value']){$chk="selected";}else{$chk="";}

        $cntx .= "<option value=\"$value\" $chk>$value</option>";
            }
        $cntx .= "</select>";

//--------- radio ------------
}elseif($data['type']=="radio"){

        if($action=="search"){ $cntx .= "<input type=\"radio\" name=\"$name\" value=\"\" $data[style] checked>$phrases[without_selection]<br>";}

        if($action=="edit"){
        $dtsx  = db_qr_fetch("select value from songs_custom_fields where song_id='$song_id' and cat='$data[id]'");
        }

        $vx  = explode("\n",$data['value']);
        foreach($vx as $value){
        if($action=="edit" && $value==$dtsx['value']){$chk="checked";}else{$chk="";}
        $cntx .= "<input type=\"radio\" name=\"$name\" value=\"$value\" $data[style] $chk> $value<br>";
            }

//-------- checkbox -------------
}elseif($data['type']=="checkbox"){

if($action=="edit"){
        $dtsx  = db_qr_fetch("select value from songs_custom_fields where song_id='$song_id' and cat='$data[id]'");
        }

        $vx  = explode("\n",$data['value']);
        foreach($vx as $value){
        if($action=="edit" && $value==$dtsx['value']){$chk="checked";}else{$chk="";}
        $cntx .= "<input type=\"checkbox\" name=\"$name\" value=\"$value\"  $chk> $value<br>";
            }

}
return $cntx;
}
//-------- Songs Custom Fields Value ----------
function get_song_field_value($cat,$song_id){
$dtsx  = db_qr_fetch("select value from songs_custom_fields where song_id='$song_id' and cat='$cat'");
return "$dtsx[value]";
}    
//--------- Sync URLs Sets ---------
function sync_urls_sets(){
$qr_links = db_query("select * from songs_urls_fields where active=1 order by ord");
$x = 0;
while($data_links = db_fetch($qr_links)){ 
$urls_sets[$x]['id'] = $data_links['id'] ;
$urls_sets[$x]['show_listen'] = $data_links['show_listen'];
$urls_sets[$x]['show_download'] = $data_links['show_download'];
$urls_sets[$x]['download_icon'] = $data_links['download_icon'];
$urls_sets[$x]['listen_icon'] = $data_links['listen_icon'];
$urls_sets[$x]['download_alt'] = $data_links['download_alt'];  
$urls_sets[$x]['listen_alt'] = $data_links['listen_alt']; 
$x++;  
} 
unset($qr_links,$data_links,$x);
return $urls_sets;
}
//------------ sync urls data  ------------//
function sync_urls_data($id){
         $qr_urls_data = db_query("select * from songs_urls_data where song_id='$id'");
         while($data_urls_data = db_fetch($qr_urls_data)){
         $urls_data[$data_urls_data['cat']]['url'] = $data_urls_data['url'];
         $urls_data[$data_urls_data['cat']]['downloads'] = intval($data_urls_data['downloads']); 
         $urls_data[$data_urls_data['cat']]['listens'] = intval($data_urls_data['listens']);   
         }
unset($qr_urls_data,$data_urls_data);
return $urls_data;
}
//--------- Sync Songs Fields Sets ---------
function sync_songs_fields_sets(){
$qr_fields = db_query("select * from songs_custom_sets where active=1 order by ord");
$x = 0;
while($data_fields = db_fetch($qr_fields)){ 
$songs_fields_sets[$x]['id'] = $data_fields['id'] ;
$songs_fields_sets[$x]['name'] = $data_fields['name'];
$x++;  
} 
unset($data_fields,$qr_fields,$x);
return $songs_fields_sets;
}

//----------- Song download permission -----------
function song_download_permission($id){
global $settings ;

if($settings['member_download_only']==1){
if(check_member_login()){
return true;
}else{
return false;
}
}elseif($settings['member_download_only']==2){

$data_singer = db_qr_fetch("select songs_songs.album as cat from songs_songs where songs_songs.id='$id'");
$data_user = db_qr_fetch("select songs_cats.download_limit from songs_cats,songs_singers where songs_cats.id=songs_singers.cat and songs_singers.id='$data_singer[cat]'");
  
if($data_user['download_limit']){

if(check_member_login()){
return true;
}else{
return false;
}

    }else{
    return true;
        }
}else{
return true;
}
//---------------------------------
}
//----------- Video download permission -----------
function video_download_permission($id){
global $settings ;

if($settings['videos_member_download_only']==1){
if(check_member_login()){
return true;
}else{
return false;
}
}elseif($settings['videos_member_download_only']==2){

$data_user = db_qr_fetch("select songs_videos_cats.download_limit from songs_videos_cats,songs_videos_data where songs_videos_cats.id=songs_videos_data.cat and songs_videos_data.id='$id'");

if($data_user['download_limit']){

if(check_member_login()){
return true;
}else{
return false;
}

    }else{
    return true;
        }
}else{
return true;
}
//---------------------------------
}
//--------- Login Redirection -----------
function login_redirect($head=false){
    global $global_lang,$settings ;

if($head){
    compile_template(get_template('page_head'));
}


print "<form action=index.php method=post name=lg_form>
<input type=hidden name=action value='login'>
 <input type=hidden name='re_link' value=\"http://$_SERVER[HTTP_HOST]"."$_SERVER[REQUEST_URI]\">
  ";
  if($global_lang=="arabic"){
             print "Ã«—Ì  ÕÊÌ·ﬂ «·Ï ’›Õ… «Œ—Ï ... «–« ·„ Ì „ «· ÕÊÌ·  <input type=submit value='«÷€ÿ Â‰«'>";  
      
      }else{
           print "Redirecting ... if your browser not supporting redirecting <input type=submit value='Click Here'>";  
      } 
 print "</form>
 
 <script>
 document.forms['lg_form'].submit();
 </script>";
 }  
 //----------- get playlist Item --------------//
 function get_playlist_item($id,$song_id=0,$with_div=1){
     global $member_data;
 if(check_member_login()){
       $id = intval($id);
       $song_id = intval($song_id);
       
 if(!$song_id){
 $data = db_qr_fetch("select song_id from songs_playlists_data where id='$id' and member_id='$member_data[id]'");
 $song_id = intval($data['song_id']);
 unset($data);
 } 
      
 $data = db_qr_fetch("select name from songs_songs where id='$song_id'");
 
if($with_div){
    print "<div id=\"playlist_item_$id\">";
}

print "
<input type=hidden name=\"song_id[]\" value=\"$song_id\">
<table width=100%>
<tr><td width=10><span style=\"cursor: move;\" class=\"handle\"><img src='images/move_small.gif'></span></td>
<td>".iif($data['name'],$data['name'],"-")."</td>
<td width=10><a href=\"javascript:playlist_delete_song($id);\"><img src='images/del_small.gif' border=0></a></td>
</tr></table>";

if($with_div){
print "</div>";
}
 }
 } 
 //--------------- Load Global Plugins --------------------------
$dhx = opendir(CWD ."/plugins");
while ($rdx = readdir($dhx)){
         if($rdx != "." && $rdx != "..") {
                 $cur_fl = CWD ."/plugins/" . $rdx . "/global.php" ;
        if(file_exists($cur_fl)){
                include $cur_fl ;

                }
          }

    }
closedir($dhx);

?>