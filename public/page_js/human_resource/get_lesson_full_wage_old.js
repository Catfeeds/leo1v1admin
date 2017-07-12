/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-get_lesson_full_wage_old.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type     :	$('#id_date_type').val(),
			opt_date_type :	$('#id_opt_date_type').val(),
			start_time    :	$('#id_start_time').val(),
			end_time      :	$('#id_end_time').val(),
            order_type    : $("#id_order_type").val(),
            normal_money  : $("#id_normal_money").val(),
            lesson_num    : $("#id_lesson_num").val(),
        });
    }


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

    $("#id_order_type").val(g_args.order_type);
    $("#id_normal_money").val(g_args.normal_money);
    $("#id_lesson_num").val(g_args.lesson_num);

    $("#id_submit").on("click",function(){
        load_data();
    });

});
