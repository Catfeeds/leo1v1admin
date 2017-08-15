/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-get_from_ass_tran_lesson_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			assistantid:	$('#id_assistantid').val(),
			success_flag:	$('#id_success_flag').val(),
			order_flag:	$('#id_order_flag').val()
        });
    }


    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });

    Enum_map.append_option_list("success_flag", $("#id_success_flag"));
    Enum_map.append_option_list("boolean", $("#id_order_flag"));
	$('#id_assistantid').val(g_args.assistantid);
	$('#id_success_flag').val(g_args.success_flag);
	$('#id_order_flag').val(g_args.order_flag);
    $.admin_select_user($("#id_assistantid"), "assistant",function(){
        load_data();
    });


    $(".opt-success-info-list").on("click",function(){
        var lessonid = $(this).data("lessonid");
        console.log(lessonid); 
        $.do_ajax( "/ss_deal/get_test_lesson_confirm_info", {
            "lessonid" : lessonid,
        },function(res){
            var data= res.data;
            var arr=[
                ["是否课前4小时取消",  data.fail_greater_4_hour_flag_str ],
                ["失败类型", data.test_lesson_fail_flag_str ],
                ["失败说明", data.fail_reason ],
                ["设置时间", data.confirm_time_str ],
                ["设置人", data.confirm_adminid_account ],
            ];

            $.show_key_value_table("试听失败详情", arr, "");

        });

    });

	$('.opt-change').set_input_change_event(load_data);
});



