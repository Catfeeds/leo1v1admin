/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji-seller_personal_money.d.ts" />

$(function(){
    var show_name_key="";
    show_name_key="account_name_"+g_adminid;

    if ($.trim($("#id_user_info").val()) != g_args.user_info ) {
        $.do_ajax("/user_deal/set_item_list_add",{
            "item_key" :show_name_key,
            "item_name":  $.trim($("#id_user_info").val())
        },function(){});
    }
    function load_data(){
        $.reload_self_page ( {
            date_type     : $('#id_date_type').val(),
            opt_date_type : $('#id_opt_date_type').val(),
            start_time    : $('#id_start_time').val(),
            end_time      : $('#id_end_time').val(),
            user_info     : $('#id_user_info').val(),
        });
    }

    $('#id_date_range').select_date_range({
        'date_type'      : g_args.date_type,
        'opt_date_type'  : g_args.opt_date_type,
        'start_time'     : g_args.start_time,
        'end_time'       : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });

    $("#id_user_info").autocomplete({
        source: "/user_deal/get_item_list?list_flag=1&item_key="+show_name_key,
        minLength: 0,
        select: function( event, ui ) {
            load_data();
        }
    });
    $("#id_user_info").val(g_args.user_info);
    $('.opt-change').set_input_change_event(load_data);
})
