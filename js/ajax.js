function ajax_check_register_username(str)
{
var url="ajax.php";
url=url+"?action=check_register_username&str="+str;
url=url+"&sid="+Math.random();

new Ajax.Request(url, {   
method: 'get',   
onSuccess: function(t){ $('register_username_area').innerHTML=t.responseText;}
 }); 

}

function ajax_check_register_email(str)
{
var url="ajax.php";
url=url+"?action=check_register_email&str="+str;
url=url+"&sid="+Math.random();

new Ajax.Request(url, {   
method: 'get',   
onSuccess: function(t){$('register_email_area').innerHTML=t.responseText;}
 }); 

}

function init_playlist_sortlist(){
Sortable.create
(
    'playlist_div',{
tag:'div',

        constraint: false,
        onUpdate: function()
        {
      new Ajax.Updater
            (
                'result', 'ajax.php',
                { postBody: Sortable.serialize('playlist_div',{name:'sort_list'}) +'&action=set_playlist_sort'}
            );
        }
    }
);
}

function playlist_add_song(song_id){

var url="ajax.php";
url=url+"?action=playlist_add_song&song_id="+song_id;
url=url+"&sid="+Math.random();

new Ajax.Request(url, {   
method: 'get',   
onSuccess: function(t){

var new_id =  t.responseText;

var url="ajax.php";
url=url+"?action=playlist_get_item&id="+ new_id;
url=url+"&sid="+Math.random();

new Ajax.Request(url, {   
method: 'get',   
onSuccess: function(t){

if($('playlist_div').innerHTML=='---'){
$('playlist_div').innerHTML='';
}
var new_element = document.createElement('div');
new_element.id = 'playlist_item_'+new_id;
new_element.innerHTML =  t.responseText;
$('playlist_div').insertBefore(new_element, $('playlist_div').firstChild);
init_playlist_sortlist();
}
 }); 

}
 }); 
}

function playlist_delete_song(id){

var url="ajax.php";
url=url+"?action=playlist_delete_song&id="+id;
url=url+"&sid="+Math.random();

new Ajax.Request(url, {   
method: 'get',   
onSuccess: function(t){
var x=$('playlist_item_'+id).parentNode.childNodes.length;
$('playlist_item_'+id).parentNode.removeChild($('playlist_item_'+id));
if(x <=1){
$('playlist_div').innerHTML = '---';
}
}
 }); 
}


function get_playlist_items(id){
var url="ajax.php";
url=url+"?action=get_playlist_items&id="+id;
url=url+"&sid="+Math.random();

new Ajax.Request(url, {   
method: 'get',   
onSuccess: function(t){
$('playlist_div').innerHTML =  t.responseText;
init_playlist_sortlist();
}
 }); 
}

function playlists_add(){
if($('playlists_add_div').style.display == "inline"){
$('playlists_add_div').style.display = "none";
}else{
$('playlists_add_div').style.display = "inline";
}
}

function playlists_del(id){
var url="ajax.php";
url=url+"?action=playlists_del&id="+id;
url=url+"&sid="+Math.random();

new Ajax.Request(url, {   
method: 'get',   
onSuccess: function(t){
get_playlists();
get_playlist_items(t.responseText);
}
 });
}


function get_playlists(){
var url="ajax.php";
url=url+"?action=get_playlists&name="+name;
url=url+"&sid="+Math.random();

new Ajax.Request(url, {   
method: 'get',   
onSuccess: function(t){

$('playlists_select_div').innerHTML =  t.responseText;
}
 });
}

function playlists_submit(name){
if(name){
var url="ajax.php";
url=url+"?action=playlists_add&name="+name;
url=url+"&sid="+Math.random();

new Ajax.Request(url, {   
method: 'get',   
onSuccess: function(t){
get_playlists();
get_playlist_items(t.responseText);
$('playlists_add_div').style.display = "none";
$('playlist_name').value='';
}
 }); 
}
}