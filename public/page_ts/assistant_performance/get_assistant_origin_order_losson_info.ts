/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/assistant_performance-get_ass_stu_lesson_month.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		    date_type_config:	$('#id_date_type_config').val(),
		    date_type:	$('#id_date_type').val(),
		    opt_date_type:	$('#id_opt_date_type').val(),
		    start_time:	$('#id_start_time').val(),
		    end_time:	$('#id_end_time').val(),
		    assistantid:	$('#id_assistantid').val(),
        studentid         : $("#id_studentid").val(),
        sys_operator      : $("#id_sys_operator").val(),      
        teacherid         : $('#id_teacherid').val(),
		    adminid            : $('#id_adminid').val(),
        origin_userid     : $('#id_origin_userid').val()

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
       
    $("#id_sys_operator").val(g_args.sys_operator);
    $('#id_assistantid').val(g_args.assistantid);
    $('#id_origin_userid').val(g_args.origin_userid);
    $('#id_teacherid').val(g_args.teacherid);
    $('#id_studentid').val(g_args.studentid);
    $('#id_adminid').val(g_args.adminid);
    $.admin_select_user($('#id_studentid'),"student", load_data);
    $.admin_select_user($('#id_origin_userid'),"student", load_data);
    $.admin_select_user($('#id_teacherid'),"teacher", load_data);
    $.admin_select_user($('#id_adminid'),"admin", load_data);
    $.admin_select_user($('#id_assistantid'),"assistant", load_data);


    $(".opt-get-stu-comment").on("click",function(){
        var lessonid        = $(this).get_opt_data("lessonid");
        console.log(lessonid);

        $.do_ajax('/user_deal/get_train_lesson_comment',{
            "lessonid":lessonid,
            "lesson_type":2
        },function(resp) {
            var title = "课后评价详情";
            var list = resp.data;
            console.log(list);
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>类别</td><td>详情</td><tr></table></div>");
            var html_score=
                "<tr>"
                +"<td>试听情况</td>"
                +"<td>"+list.stu_lesson_content+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>学习态度</td>"
                +"<td>"+list.stu_lesson_status+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>学习基础情况</td>"
                +"<td>"+list.stu_study_status+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>学生优点</td>"
                +"<td>"+list.stu_advantages+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>学生有待提高</td>"
                +"<td>"+list.stu_disadvantages+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>培训计划</td>"
                +"<td>"+list.stu_lesson_plan+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>教学方向</td>"
                +"<td>"+list.stu_teaching_direction+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>教材及内容</td>"
                +"<td>"+list.stu_textbook_info+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>教学目标</td>"
                +"<td>"+list.stu_teaching_aim+"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>大致推荐课时数</td>"
                +"<td>"+list.stu_lesson_count +"</td>"
                +"</tr>"
                +"<tr>"
                +"<td>意见、建议等</td>"
                +"<td><textarea>"+list.stu_advice+"</textarea></td>"
                +"</tr>";



            html_node.find("table").append(html_score);
            var dlg=BootstrapDialog.show({
                title    : title,
                message  : html_node,
                closable : true,
                buttons:[{
                    label: '返回',
                    cssClass: 'btn',
                    action: function(dialog) {
                        dialog.close();

                    }
                }],
                onshown:function(){
                }

            });

            dlg.getModalDialog().css("width","1024px");
        });

    });

    $(".opt-play-new").on("click",function(){
        var lessonid = $(this).get_opt_data("lessonid");
        var id = $(this).get_opt_data("id");
        $.do_ajax( "/common/encode_text",{
            "text" : lessonid
        }, function(ret){
            // BootstrapDialog.alert("对外链接 : http://"+ window.location.hostname + "/tea_manage/show_lesson_video?lessonid=" + ret.text  );
            console.log("http://"+ window.location.hostname + "/tea_manage/show_lesson_video?lessonid=" + ret.text);
            $.wopen("http://"+ window.location.hostname + "/tea_manage/show_lesson_video?lessonid=" + ret.text);
        });
       
    });




	  $('.opt-change').set_input_change_event(load_data);
});


