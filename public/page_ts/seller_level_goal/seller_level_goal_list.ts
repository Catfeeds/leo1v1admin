/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_level_goal-seller_level_goal_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
            orderid:    $('#id_orderid').val(),
            start_time:    $('#id_start_time').val(),
            end_time:    $('#id_end_time').val(),
            aid:    $('#id_aid').val(),
            pid:    $('#id_pid').val(),
            p_price:    $('#id_p_price').val(),
            ppid:    $('#id_ppid').val(),
            pp_price:    $('#id_pp_price').val(),
            userid:    $('#id_userid').val()
        });
    }


    $('#id_orderid').val(g_args.orderid);
    $('#id_start_time').val(g_args.start_time);
    $('#id_end_time').val(g_args.end_time);
    $('#id_aid').val(g_args.aid);
    $('#id_pid').val(g_args.pid);
    $('#id_p_price').val(g_args.p_price);
    $('#id_ppid').val(g_args.ppid);
    $('#id_pp_price').val(g_args.pp_price);
    $('#id_userid').val(g_args.userid);


    $('.opt-change').set_input_change_event(load_data);
});
