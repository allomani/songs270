function init_blocks_sortlist(){
Sortable.create
(
    'blocks_list_r',{
tag:'div',
containment:["blocks_list_r","blocks_list_c","blocks_list_l"],
        constraint: false,
        onUpdate: function()
        {
      new Ajax.Updater
            (
                'result', 'ajax.php',
                { postBody: Sortable.serialize('blocks_list_r') +'&action=set_blocks_sort'}
            );
        }
    }
);
Sortable.create
(
    'blocks_list_c',{
tag:'div',
containment:["blocks_list_r","blocks_list_c","blocks_list_l"],
        constraint: false,
        onUpdate: function()
        {
            new Ajax.Updater
            (
                'result', 'ajax.php',
                { postBody: Sortable.serialize('blocks_list_c') +'&action=set_blocks_sort'}
            );
        }
    }
);

Sortable.create
(
    'blocks_list_l',{
tag:'div',
containment:["blocks_list_r","blocks_list_c","blocks_list_l"],
        constraint: false,
             onUpdate: function()
        {
            new Ajax.Updater
            (
                'result', 'ajax.php',
                { postBody: Sortable.serialize('blocks_list_l') +'&action=set_blocks_sort'}
            );
        }
    }
);

}



function init_cats_sortlist(){
Sortable.create
(
    'cats_list',{
tag:'div',
handle:'handle',
        constraint: false,
        onUpdate: function()
        {
       
      new Ajax.Updater
            (
                'result', 'ajax.php',
                { postBody: Sortable.serialize('cats_list',{name:'sort_list'}) +'&action=set_cats_sort'}
            );
        }
    }
);
}


function init_new_stores_sortlist(){
Sortable.create
(
    'new_stores_list',{
tag:'div',
        constraint: false,
        onUpdate: function()
        {
      new Ajax.Updater
            (
                'result', 'ajax.php',
                { postBody: Sortable.serialize('new_stores_list',{name:'sort_list'}) +'&action=set_new_stores_sort'}
            );
        }
    }
);
}


function init_new_songs_sortlist(){
Sortable.create
(
    'new_songs_list',{
tag:'div',
        constraint: false,
        onUpdate: function()
        {
      new Ajax.Updater
            (
                'result', 'ajax.php',
                { postBody: Sortable.serialize('new_songs_list',{name:'sort_list'}) +'&action=set_new_songs_sort'}
            );
        }
    }
);
}

function init_videos_cats_sortlist(){
Sortable.create
(
    'videos_cats_list',{
tag:'div',
handle:'handle',
        constraint: false,
        onUpdate: function()
        {
      new Ajax.Updater
            (
                'result', 'ajax.php',
                { postBody: Sortable.serialize('videos_cats_list',{name:'sort_list'}) +'&action=set_videos_cats_sort'}
            );
        }
    }
);
}


function init_songs_custom_fields_sortlist(){
Sortable.create
(
    'songs_custom_fields_list',{
tag:'div',
handle:'handle',
        constraint: false,
        onUpdate: function()
        {
      new Ajax.Updater
            (
                'result', 'ajax.php',
                { postBody: Sortable.serialize('songs_custom_fields_list',{name:'sort_list'}) +'&action=set_songs_custom_fields_sort'}
            );
        }
    }
);
}

function init_urls_fields_sortlist(){
Sortable.create
(
    'urls_fields_list',{
tag:'div',
handle:'handle',
        constraint: false,
        onUpdate: function()
        {
      new Ajax.Updater
            (
                'result', 'ajax.php',
                { postBody: Sortable.serialize('urls_fields_list',{name:'sort_list'}) +'&action=set_urls_fields_sort'}
            );
        }
    }
);
}