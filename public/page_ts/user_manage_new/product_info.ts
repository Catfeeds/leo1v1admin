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
        end_time:	$('#id_end_time').val(),
		    lesson_problem:	$('#id_lesson_problem').val(),
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
    Enum_map.append_option_list( "lesson_problem", $("#id_lesson_problem"));

    $('#id_deal_flag').val(g_args.deal_flag);
    $('#id_feedback_adminid').val(g_args.feedback_adminid);
	  $('#id_lesson_problem').val(g_args.lesson_problem);

    $.admin_select_user($("#id_feedback_adminid"),"admin", load_data);

    $('#id_submit').on("click",function(){
        var data         = $(this).get_opt_data();
        var $feedback_id = $("<input/>");
        var $describe    = $("<textarea>");
        var $lesson_url  = $("<input/>");
        var $lesson_problem = $("<select/>");
        var $reason      = $("<textarea>");
        var $solution    = $("<textarea>");
        var $student     = $("<input/>");
        var $teacher     = $("<input/>");
        var $deal_flag   = $('<select><option value="-1">未设置</option><option value="0">否</option><option value="1">是</option> </select>');
        var $remark      = $("<textarea/>");
        var $id_img_url = $("<div><input class=\"change_reason_url\" id=\"id_img_url\" type=\"text\"readonly ><span ><a class=\"upload_gift_pic\" id=\"id_upload_lesson_img\" href=\"javascript:;\">上传</a></span></div>");
        var $id_video_url = $("<div><input class=\"change_reason_url\" id=\"id_video_url\" type=\"text\"readonly ><span ><a class=\"upload_gift_pic\" id=\"id_upload_lesson_video\" href=\"javascript:;\">上传</a></span></div>");
        var $id_zip_url = $("<div><input class=\"change_reason_url\" id=\"id_zip_url\" type=\"text\"readonly ><span ><a class=\"upload_gift_pic\" id=\"id_upload_lesson_zip\" href=\"javascript:;\">上传</a></span></div>");

        Enum_map.append_option_list("lesson_problem", $lesson_problem, true);

        var arr = [
            ["反馈人",$feedback_id],
            ["问题描述",$describe],
            ["问题原因[图片]",$id_img_url],
            ["问题原因[视频|音频]",$id_video_url],
            ["问题原因[压缩包]",$id_zip_url],
            ["上课链接",$lesson_url],
            ["原因",$reason],
            ["问题类型",$lesson_problem],
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
                    "remark"      : $remark.val(),
                    "img_url"     : $id_img_url.val(),
                    "video_url"   : $id_video_url.val(),
                    "zip_url"     : $id_zip_url.val(),
                    "lesson_problem" : $lesson_problem.val(),

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

            $lesson_url.css('width','90%');
            $feedback_id.next().css('width','20%');
            $student.next().css('width','20%');
            $teacher.next().css('width','20%');
            $deal_flag.css('width','20%');
            $.custom_upload_file('id_upload_lesson_img',true,function (up, info, file) {
                var res = $.parseJSON(info);
                $("#id_img_url").val(res.key);
            }, null,["png", "jpg",'jpeg','bmp','gif']);

            $.custom_upload_file('id_upload_lesson_video',true,function (up, info, file) {
                var res = $.parseJSON(info);
                $("#id_video_url").val(res.key);
            }, null,["mp3","mp4"]);

            $.custom_upload_file('id_upload_lesson_zip',true,function (up, info, file) {
                var res = $.parseJSON(info);
                $("#id_zip_url").val(res.key);
            }, null,['zip','rar']);

        });
    });


    $('.opt-del').on("click",function(){
        var data         = $(this).get_opt_data();
        var $id = data.id;
        if(confirm('确定删除此条信息吗?')){
            $.do_ajax("/ss_deal2/del_product_info",{
                "id":$id,
            },function(result){
                load_data();
            });
        }
    });

    $('.opt-edit').on("click",function(){
        var data = $(this).get_opt_data();
        var $id  = data.id;
        $.do_ajax("/ss_deal2/get_product_info",{
            "id":$id,
        },function(result){
            var $feedback_id = $("<input/>");
            var $describe    = $("<textarea>");
            var $lesson_url  = $("<input />");


            var $reason      = $("<textarea>");
            var $solution    = $("<textarea>");
            var $student     = $("<input/>");
            var $teacher     = $("<input/>");
            var $lesson_problem = $("<select/>");
            var $deal_flag   = $('<select><option value="-1">未设置</option><option value="0">否</option><option value="1">是</option> </select>');
            var $remark      = $("<textarea/>");
            var $id_img_url = $("<div><input class=\"change_reason_url\" id=\"id_img_url\" type=\"text\"readonly ><span ><a class=\"upload_gift_pic\" id=\"id_upload_lesson_img\" href=\"javascript:;\">上传</a></span></div>");
            var $id_video_url = $("<div><input class=\"change_reason_url\" id=\"id_video_url\" type=\"text\"readonly ><span ><a class=\"upload_gift_pic\" id=\"id_upload_lesson_video\" href=\"javascript:;\">上传</a></span></div>");
            var $id_zip_url = $("<div><input class=\"change_reason_url\" id=\"id_zip_url\" type=\"text\"readonly ><span ><a class=\"upload_gift_pic\" id=\"id_upload_lesson_zip\" href=\"javascript:;\">上传</a></span></div>");

            Enum_map.append_option_list("lesson_problem", $lesson_problem, true);

            var arr = [
                ["反馈人",$feedback_id],
                ["问题描述",$describe],
                ["问题原因[图片]",$id_img_url],
                ["问题原因[视频|音频]",$id_video_url],
                ["问题原因[压缩包]",$id_zip_url],
                ["上课链接",$lesson_url],
                ["原因",$reason],
                ["问题类型",$lesson_problem],
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
                    $.do_ajax("/ss_deal2/update_product_info",{
                        "feedback_id" : $feedback_id.val(),
                        "describe"    : $describe.val(),
                        "lesson_url"  : $lesson_url.val(),
                        "reason"      : $reason.val(),
                        "solution"    : $solution.val(),
                        "student_id"  : $student.val(),
                        "teacher_id"  : $teacher.val(),
                        "deal_flag"   : $deal_flag.val(),
                        "remark"      : $remark.val(),
                        "id"          : $id,
                        "img_url"     : $("#id_img_url").val(),
                        "video_url"   : $("#id_video_url").val(),
                        "zip_url"     : $("#id_zip_url").val(),
                        "lesson_problem" : $lesson_problem.val(),
                    },function(result){
                        BootstrapDialog.alert(result.info);
                        dialog.close();
                        load_data();
                    });
                }
            },function(){
                var data = result.data;
                $feedback_id.val(data.feedback_adminid);
                $student.val(data.sid);
                $teacher.val(data.tid);
                $describe.val(data.describe_msg);
                $lesson_url.val(data.lesson_url);
                $reason.val(data.reason);
                $remark.val(data.remark);
                $solution.val(data.solution);
                $deal_flag.val(data.deal_flag);
                $('#id_zip_url').val(data.zip_url);
                $('#id_img_url').val(data.img_url);
                $('#id_video_url').val(data.video_url);
                $lesson_problem.val(data.lesson_problem);


                $.admin_select_user($feedback_id,"admin");
                $.admin_select_user($student,"student");
                $.admin_select_user($teacher,"teacher");

                $lesson_problem.css('width','90%');
                $lesson_url.css('width','90%');
                $feedback_id.next().css('width','20%');
                $student.next().css('width','20%');
                $teacher.next().css('width','20%');
                $deal_flag.css('width','20%');
                // $('#id_img_url').css('width','83%');
                // $('#id_video_url').css('width','83%');
                // $('#id_zip_url').css('width','83%');

                $.custom_upload_file('id_upload_lesson_img',true,function (up, info, file) {
                    var res = $.parseJSON(info);
                    $("#id_img_url").val(res.key);
                }, null,["png", "jpg",'jpeg','bmp','gif']);

                $.custom_upload_file('id_upload_lesson_video',true,function (up, info, file) {
                    var res = $.parseJSON(info);
                    $("#id_video_url").val(res.key);
                }, null,["mp3","mp4"]);

                $.custom_upload_file('id_upload_lesson_zip',true,function (up, info, file) {
                    var res = $.parseJSON(info);
                    $("#id_zip_url").val(res.key);
                }, null,['zip','rar']);

            });
        });
    });



    $('.opt-change').set_input_change_event(load_data);
});
