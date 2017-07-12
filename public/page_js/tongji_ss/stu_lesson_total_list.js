/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-stu_lesson_total_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type     :	$('#id_date_type').val(),
			opt_date_type :	$('#id_opt_date_type').val(),
			start_time    :	$('#id_start_time').val(),
			end_time      :	$('#id_end_time').val(),
			renewal_rate  :	$('#id_renewal_rate').val(),
			select_type   :	$('#id_select_type').val(),
			student_type  :	$('#id_student_type').val(),
			month_cost    :	$('#id_month_cost').val(),
			month_cost_ex :	$('#id_month_cost_ex').val(),
			order_str     :	$('#id_order_str').val()
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

    Enum_map.append_option_list("student_type",$("#id_student_type"),true,[0,2,3]);
    $("#id_month_cost").val(g_args.month_cost);
    $("#id_month_cost_ex").val(g_args.month_cost_ex);
    $("#id_renewal_rate").val(g_args.renewal_rate);
    $("#id_select_type").val(g_args.select_type);
    $("#id_student_type").val(g_args.student_type);
    $("#id_order_str").val(g_args.order_str);

    $("#id_submit").on("click",function(){
        load_data();
    });

    $("#id_change").on("click",function(){
        var data=$(this).data("show");
        console.log(data);
        if(data==1){
            $("#time_count_list").show();
            $("#stu_lesson_total_info").hide();
            $(this).data("show",2);
            $(this).text("显示课时信息");
        }else{
            $("#time_count_list").hide();
            $("#stu_lesson_total_info").show();
            $(this).data("show",1);
            $(this).text("显示人数分布");
        }
    });
});
