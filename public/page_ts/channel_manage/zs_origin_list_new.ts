/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/channel_manage-zs_origin_list_new.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }


 	$(".common-table" ).table_admin_level_4_init();

	$('.opt-change').set_input_change_event(load_data);
});
