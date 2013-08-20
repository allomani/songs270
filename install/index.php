<?
chdir('./../');
define('CWD', (($getcwd = str_replace("\\","/",getcwd())) ? $getcwd : '.'));


require (CWD . "/config.php");
require ("ClassSQLimport.php");

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
if (!empty($HTTP_POST_VARS)) {extract($HTTP_POST_VARS);}
if (!empty($HTTP_GET_VARS)) {extract($HTTP_GET_VARS);}
//------------------------------------



$last_step = 1;

mysql_connect($db_host,$db_username,$db_password) or die("Connect Error");
mysql_select_db($db_name) or die("Database Name Error"); 


if(!$step){
print "<h1> Songs & Clips v2.7 Installation</h1>"; 

print "<form action=index.php method=get>
Language : <select name=lang>
<option value='ar'>Arabic</option>
<option value='en'>English</option>
</select>
<input type=hidden name=step value='".(intval($step)+1)."'>
<input type=submit value='Next'>
</form>";

$next_printed = 1; 
$import["exito"]  = 1;

}elseif($step==1){
print "<h1> Import Tables </h1>";



$qr = mysql_query("show tables");
if(!mysql_num_rows($qr)){

if($lang=="ar"){
$sqlFile = CWD . "/install/songs270_ar.sql.gz";
}else{
$sqlFile = CWD . "/install/songs270_en.sql.gz"; 
}

$newImport = new sqlImport ($db_host, $db_username, $db_password,$db_name, $sqlFile);
$newImport -> import ();
}else{

if($action=="empty_database"){
while($data = mysql_fetch_array($qr)){
mysql_query("drop table `$data[0]`");
}
print "
<br>
<b>Database is Clean Now. </b><br><br>
<form action=\"index.php\">
<input type=hidden name=lang value='".htmlspecialchars($lang)."'>
<input type=hidden name=step value='1'>
<input type=submit value='Next'>
</form><br>";
$next_printed = 1;
$import["exito"]  = 1;    
}else{

$import["exito"]  = 0;
$import ["errorCode"] = "1" ;
$import ["errorText"] = "Database is not Empty , Please use new database";

print "<form action=\"index.php\">
<input type=hidden name=action value='empty_database'>
<input type=hidden name=lang value='".htmlspecialchars($lang)."'> 
<input type=hidden name=step value='1'>
<input type=submit value='Empty Database'>
</form><br>";
}
}
}


//------------------ Show Messages !!! ---------------------------
if(!is_array($import)){
$import = $newImport -> ShowErr ();
}

if ($import["exito"] == 1)
{
if($step==$last_step && !$next_printed){
print "<center><b> Install Complete</b> </center>";
}else{
if(!$next_printed){

if($step){
print "... DONE ... <br><br>";
}

echo "<form action=index.php method=get>
<input type=hidden name=lang value='".htmlspecialchars($lang)."'>
<input type=hidden name=step value='".(intval($step)+1)."'>
<input type=submit value='Next'>
</form>";
}
}
} else {
echo "Error : " . $import ["errorCode"].": ".$import ["errorText"];
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

//--------- iif expression ------------
function iif($expression, $returntrue, $returnfalse = '')
{
    return ($expression ? $returntrue : $returnfalse);
}
?> 