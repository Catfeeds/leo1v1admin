/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-no_return_call_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            adminid : $("#id_seller_adminid").val(),
        });
    }

    $("#id_seller_adminid").val(g_args.adminid);

    $.admin_select_user($("#id_seller_adminid"), "admin", load_data);

    $('.opt-change').set_input_change_event(load_data);

});
