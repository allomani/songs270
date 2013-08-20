function CheckAll()
{
count = document.submit_form.elements.length;
    for (i=0; i < count; i++) 
	{
    if((document.submit_form.elements[i].checked == 1) ||(document.submit_form.elements[i].checked == 0))
    	{document.submit_form.elements[i].checked = 1; }
  
	}
}
function UncheckAll(){
count = document.submit_form.elements.length;
    for (i=0; i < count; i++) 
	{
    if((document.submit_form.elements[i].checked == 1) || (document.submit_form.elements[i].checked == 0))
    	{document.submit_form.elements[i].checked = 0; }

	}
}

function show_options(box){

nms = box.options[box.selectedIndex].value;

if (nms == 'song_album_set') {
document.getElementById("albums_set_div").style.display = "inline";
document.getElementById("comments_set_div").style.display = "none";
}else if(nms == 'song_comment_set'){
document.getElementById("albums_set_div").style.display = "none";
document.getElementById("comments_set_div").style.display = "inline";
}else{
document.getElementById("albums_set_div").style.display = "none";
document.getElementById("comments_set_div").style.display = "none";
}

}

function show_adv_options(box){

	nms = box.options[box.selectedIndex].value;

if (nms == 'menu') {
document.getElementById("add_after_menu").style.display = "inline";
document.getElementById("banners_pages_area").style.display = "inline";
}else if(nms == 'listen') {
document.getElementById("add_after_menu").style.display = "none";
document.getElementById("banners_pages_area").style.display = "none";
}else{
document.getElementById("add_after_menu").style.display = "none";
document.getElementById("banners_pages_area").style.display = "inline";
}

}

function show_banner_code(){

document.getElementById("banners_code_area").style.display = "inline";

document.getElementById("banners_img_area").style.display = "none";
document.getElementById("banners_url_area").style.display = "none"
}

function show_banner_img(){

document.getElementById("banners_code_area").style.display = "none";

document.getElementById("banners_img_area").style.display = "inline";
document.getElementById("banners_url_area").style.display = "inline"
}

function set_checked_color(id,box){
if(box.checked == true){
document.getElementById(id).style.backgroundColor='#EFEFEF';
}else{
document.getElementById(id).style.backgroundColor='#FFFFFF';
}
}

function set_tr_color(tr,color){

if(tr.style.backgroundColor !='#efefef'){
tr.style.backgroundColor=color;
}
}


function set_menu_pages(box){

nms = box.options[box.selectedIndex].value;

if (nms == 'c') {
count = document.submit_form.elements.length;
    for (i=0; i < count; i++) 
	{
    if((document.submit_form.elements[i].checked == 1) ||(document.submit_form.elements[i].checked == 0))
    	{
if(document.submit_form.elements[i].name == 'pages[0]'){
document.submit_form.elements[i].checked = 1; 
}else{
document.submit_form.elements[i].checked = 0; 
}
}

  
	}
}else{
count = document.submit_form.elements.length;
    for (i=0; i < count; i++) 
	{
    if((document.submit_form.elements[i].checked == 1) ||(document.submit_form.elements[i].checked == 0))
    	{document.submit_form.elements[i].checked = 1; }
  
	}
}

}

function uploader(folder,f_name,id)
{
if ( id === undefined ) {
      id = 'win0';
   }


msgwindow=window.open("uploader.php?folder="+folder+"&f_name="+f_name+"&win_name="+id,id,"toolbar=no,scrollbars=no,width=520,height=220,top=200,left=200")
}

function uploader2(folder,f_name,frm)
{

msgwindow=window.open("uploader.php?folder="+folder+"&f_name="+f_name+"&frm="+frm,"popup","toolbar=no,scrollbars=no,width=520,height=220,top=200,left=200")
}

function singers_list()
{

msgwindow=window.open("singers_list.php","displaywindow","toolbar=no,scrollbars=yes,resizable=yes,width=600,height=500,top=200,left=200")
}

function show_hide_preview_text(box){
if(box.checked == true){
document.getElementById('preview_text_tr').style.display = "none";
}else{
document.getElementById('preview_text_tr').style.display = "inline";
}
}


   function show_snd_mail_options(box){
           nms = box.options[box.selectedIndex].value;

if (nms == 'all') {
   document.getElementById("when_one_user_email").style.display = "none";
           }else{
   document.getElementById("when_one_user_email").style.display = "inline";
  }
  }

   function show_snd_mail_options2(box){
           nms = box.options[box.selectedIndex].value;

if (nms == 'msg') {
   document.getElementById("sender_email_tr").style.display = "none";
    document.getElementById("msg_type_tr").style.display = "none";
document.getElementById("msg_encoding_tr").style.display = "none";
           }else{
   document.getElementById("sender_email_tr").style.display = "inline";
document.getElementById("msg_type_tr").style.display = "inline";
document.getElementById("msg_encoding_tr").style.display = "inline";
 
  }
  }

function show_uploader_options(box){

if (box == '1') {
   document.getElementById("file_field").style.display = "none";
    document.getElementById("url_field").style.display ="inline";

           }else{
   document.getElementById("file_field").style.display = "inline";
document.getElementById("url_field").style.display =  "none";
 
  }
}


function select_singer(id){
opener.sender.elements['cat'].value = id;
opener.sender.elements['type'].selectedIndex = 0;
window.close();
}

function select_album(id){
opener.sender.elements['cat'].value = id;
opener.sender.elements['type'].selectedIndex =1;
window.close();
}
