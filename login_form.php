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

 open_table();
   $re_link = htmlspecialchars($re_link) ;  
print "
<script type=\"text/javascript\" src=\"js/md5.js\"></script>

<form method=\"POST\" action=\"login.php\" onsubmit=\"md5hash(password, md5pwd, md5pwd_utf, 1)\">

<input type=hidden name='md5pwd' value=''>
<input type=hidden name='md5pwd_utf' value=''>


<input type=hidden name=action value=login>
<input type=hidden name=re_link value=\"$re_link\">


<table border=\"0\" width=\"200\">
        <tr>
                <td height=\"15\"><span>$phrases[username] :</span></td>
                <td height=\"15\"><input type=\"text\" name=\"username\" size=\"10\"></td>
        </tr>
        <tr>
                <td height=\"12\"><span>$phrases[password]:</span></td>
                <td height=\"12\" ><input type=\"password\" name=\"password\" size=\"10\"></td>
        </tr>
        <tr>
                <td height=\"23\" colspan=2>
                <p align=\"center\"><input type=\"submit\" value=\"$phrases[login]\"></td>
        </tr>
        <tr>
                <td height=\"38\" colspan=2><span>
                <a href=\"index.php?action=register\">$phrases[newuser]</a><br>
                <a href=\"index.php?action=forget_pass\">$phrases[forgot_pass]</a></span></td>
        </tr>
</table>
</form>\n";
close_table();
?>