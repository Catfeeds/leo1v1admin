/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-product_info.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		    deal_flag:	$('#id_deal_flag').val(),
		    feedback_adminid:	$('#id_feedback_adminid').val(),
		    date_type_config:	$('#id_date_type_config').val(),
		    date_type:	$('#id_date_type').val(),
		    opt_date_type:	$('#id_opt_date_type').val(),
		    start_time:	$('#id_start_time').val(),
		    end_time:	$('#id_end_time').val()
    });
}
$(function(){


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

    Enum_map.append_option_list( "boolean", $("#id_deal_flag"));
    $.admin_select_user($("#id_feedback_adminid"),"admin", load_data);

	  $('#id_deal_flag').val(g_args.deal_flag);
	  $('#id_feedback_adminid').val(g_args.feedback_adminid);

    $('#id_submit').on("click",function(){
        var data         = $(this).get_opt_data();
        var $feedback_id = $("<input/>");
        var $describe    = $("<textarea>");
        var $lesson_url  = $("<input/>");
        var $reason      = $("<textarea>");
        var $solution    = $("<textarea>");
        var $student     = $("<input/>");
        var $teacher     = $("<input/>");
        var $deal_flag   = $('<select><option value="-1">未设置</option><option value="0">否</option><option value="1">是</option> </select>');
        var $remark      = $("<textarea/>");
        var arr = [
            ["反馈人",$feedback_id],
            ["问题描述",$describe],
            ["上课链接",$lesson_url],
            ["原因",$reason],
            ["解决方案",$solution],
            ["学生",$student],
            ["老师",$teacher],
            ["解决状态",$deal_flag],
            ["备注",$remark],
        ];

        $.show_key_value_table("录入反馈信息",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/ss_deal2/add_product_info",{
                    "feedback_id" : $feedback_id.val(),
                    "describe"    : $describe.val(),
                    "lesson_url"  : $lesson_url.val(),
                    "reason"      : $reason.val(),
                    "solution"    : $solution.val(),
                    "student_id"  : $student.val(),
                    "teacher_id"  : $teacher.val(),
                    "deal_flag"   : $deal_flag.val(),
                    "remark"      : $remark.val()
                },function(result){
                    BootstrapDialog.alert(result.info);
                    dialog.close();
                    load_data();
                });
            }
        },function(){
            $.admin_select_user($feedback_id,"admin");
            $.admin_select_user($student,"student");
            $.admin_select_user($teacher,"teacher");
        });
    });


	  $('.opt-change').set_input_change_event(load_data);
});

