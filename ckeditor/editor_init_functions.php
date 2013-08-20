<?

function encodeHTML($sHTML)
		{
		$sHTML=ereg_replace("&","&amp;",$sHTML);
		$sHTML=ereg_replace("<","&lt;",$sHTML);
		$sHTML=ereg_replace(">","&gt;",$sHTML);
		return $sHTML;
		}

function editor_html_init(){
global $scripturl ;

print "<script language=JavaScript src='".$scripturl."/ckeditor/ckeditor.js'></script>" ;

}

function editor_init() {

}

function editor_print_form($name,$width,$height,$content){
    global $global_dir,$global_lang;
	print "<textarea id=\"$name\" name=\"$name\" rows=4 cols=30>\n";



if($content){
	print encodeHTML($content);
	}else{
        print encodeHTML("<div dir=$global_dir></div>");
    }

print "</textarea>

	<script>
		var editor  = CKEDITOR.replace('$name',{language:'".iif($global_lang=="arabic","ar","en")."'});
	</script>";

	}
