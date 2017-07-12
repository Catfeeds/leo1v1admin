/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-seller_week_lesson_call.d.ts" />

function load_data(){
    $.reload_self_page ( {
        order_by_str: g_args.order_by_str,
        origin_ex : $("#id_origin_ex").val(),
        date_type:	$('#id_date_type').val(),
        opt_date_type:	$('#id_opt_date_type').val(),
        start_time:	$('#id_start_time').val(),
        end_time:	$('#id_end_time').val(),
        grade:	$('#id_grade').val(),
        groupid:	$('#id_groupid').val(),
        main_type_str:g_args.main_type_str,
        up_group_name:g_args.up_group_name,
        group_name:g_args.group_name,
        account:g_args.account,
        seller_groupid_ex:$('#id_seller_groupid_ex').val(),
    });
}

$(function(){
    $(".common-table" ).tbody_scroll_table();
    $(".common-table" ).table_admin_level_4_init();

    $('#id_date_range').select_date_range({
        'date_type'      : g_args.date_type,
        'opt_date_type'  : g_args.opt_date_type,
        'start_time'     : g_args.start_time,
        'end_time'       : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery          : function() {
            load_data();
        }
    });
    $('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);
    $("#id_seller_groupid_ex").init_seller_groupid_ex();
    var jump_url_1="/tongji2/lesson_call_list";
    $(".td-call_count").on("click",function(){
        var opt_data= $(this).get_row_opt_data();
        var start_time=$(this).attr('value');
        var end_time=$(this).attr('value');
        if(opt_data.main_type_str == '全部'){
            opt_data.main_type_str = '';
        }
        var seller_groupid_ex = opt_data.main_type_str+"%2C"+opt_data.up_group_name+"%2C"+opt_data.group_name+"%2C"+opt_data.account;
        $.wopen(jump_url_1+"?"
                +"opt_date_type=1"
                +"&start_time="+start_time
                +"&end_time="+end_time
                +"&seller_groupid_ex="+seller_groupid_ex
               );
    });

    $('.opt-change').set_input_change_event(load_data);
});
