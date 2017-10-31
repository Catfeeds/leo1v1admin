/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/admin_manage-web_page_log.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
        web_page_id: g_args.web_page_id	,
        from_adminid:	$('#id_from_adminid').val()
    });
}
$(function(){


  $('#id_from_adminid').val(g_args.from_adminid);

    $.admin_select_user(
        $('#id_from_adminid'),
        "admin", load_data ,false, {
            "main_type": 2, //分配用户
        }
    );


  $('.opt-change').set_input_change_event(load_data);
});
