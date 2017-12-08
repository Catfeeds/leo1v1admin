/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-get_fulltime_teacher_attendance_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			attendance_type:	$('#id_attendance_type').val(),
			teacherid:	$('#id_teacherid').val(),
			adminid:	$('#id_adminid').val(),
			account_role:	$('#id_account_role').val(),
			fulltime_teacher_type:	$('#id_fulltime_teacher_type').val()
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
    
    Enum_map.append_option_list("attendance_type", $("#id_attendance_type"));
    Enum_map.append_option_list("fulltime_teacher_type", $("#id_fulltime_teacher_type"),false,[1,2]);
    Enum_map.append_option_list("account_role", $("#id_account_role"),false,[4,5]);
   

 

	$('#id_attendance_type').val(g_args.attendance_type);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_account_role').val(g_args.account_role);
	$('#id_fulltime_teacher_type').val(g_args.fulltime_teacher_type);
	$('#id_adminid').val(g_args.adminid);
       $.admin_select_user($('#id_teacherid'),
                        "teacher", load_data);
    $.admin_select_user(
        $('#id_adminid'),
        "admin", load_data,false,{"main_type":g_args.account_role});

    
    $.each( $(".opt-show-lessons-new"), function(i,item ){
        $(item).admin_select_teacher_free_time_new({
            "teacherid" : $(item).get_opt_data("teacherid")
        });
    });




    $(".opt-del").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var id = opt_data.id;

        BootstrapDialog.confirm("确定要删除？", function(val){
            if (val) {
                $.do_ajax( '/user_deal/del_fulltime_teacher_attendance_info', {
                    'id' : id
                });
            } 
        });

    });

    $(".lesson_info").on("click",function(){
        var teacherid = $(this).data("teacherid");
        var time = $(this).data("time");
        if(teacherid > 0){
            var title = "当天课程详情";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>lessonid</td><td>类型</td><td>开始时间</td><td>结束时间</td><td>学生</td><td>年级</td><td>科目</td><td>折算课时</td><tr></table></div>");

            $.do_ajax('/ajax_deal2/get_attendance_lesson_info',{
                "teacherid" : teacherid,
                "time"      : time
            },function(resp) {
                var userid_list = resp.data;
                $.each(userid_list,function(i,item){
                    var lessonid = item["lessonid"];
                    var nick = item["nick"]
                    var start = item["lesson_start_str"];
                    var end = item["lesson_end_str"];
                    var lesson_type = item["lesson_type_str"];
                    var subject = item["subject_str"];
                    var grade = item["grade_str"];
                    html_node.find("table").append("<tr><td>"+lessonid+"</td><td>"+lesson_type+"</td><td>"+start+"</td><td>"+end+"</td><td>"+nick+"</td><td>"+grade+"</td><td>"+subject+"</td><td>"+item["lesson_cout"]+"</td></tr>");
                });
            });

            var dlg=BootstrapDialog.show({
                title:title, 
                message :  html_node   ,
                closable: true, 
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

        }
        
    });



	$('.opt-change').set_input_change_event(load_data);
});








