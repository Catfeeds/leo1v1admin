/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-teacher_first_test_lesson_week.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			subject:	$('#id_subject').val(),
			record_flag:	$('#id_record_flag').val(),
			record_adminid:	$('#id_record_adminid').val()
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

    Enum_map.append_option_list("subject",$("#id_subject")); 
    Enum_map.append_option_list("boolean",$("#id_record_flag")); 
	$('#id_subject').val(g_args.subject);
	$('#id_record_flag').val(g_args.record_flag);
    $('#id_record_adminid').val(g_args.record_adminid);
    $.admin_select_user(
        $('#id_record_adminid'),
        "admin", load_data,false,{"main_type":4});


    $(".opt-get-teacher-class-abnormal").on("click",function(){
       // alert("研发中!");
      //  return;
        var opt_data=$(this).get_opt_data();
        var teacherid = opt_data.teacherid;
        $.do_ajax("/tongji_ss/get_week_test_lesson_list",{
            "teacherid" : teacherid
        },function(response){
            //console.log(response.data);return;
            var data_list   = [];
            var select_list = [];
            $.each( response.data,function(){
                data_list.push([this["lessonid"], this["nick"],this["lesson_start_str"],this["subject_str"],this["grade_str"]]);               
            });

            $(this).admin_select_dlg({
                header_list     : [ "lessonid","学生","时间","科目","年级"],
                data_list       : data_list,
                multi_selection : true,
                select_list     : select_list,
                onChange        : function( select_list,dlg) {
                    console.log(teacherid);
                    dlg.close();
                    $.do_ajax('/human_resource/set_class_abnormal_record',{
                        "teacherid" : teacherid,
                        "lessonid_list":JSON.stringify(select_list)
                    });

                }
            });
        }) ;

    });
             
    $(".test_lesson").on("click",function(){
        var teacherid = $(this).data("teacherid");
        console.log(teacherid);
        if(teacherid > 0){
            var title = "试听详情";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>lessonid</td><td>时间</td><td>学生</td><td>年级</td><td>科目</td><td>试听需求</td><td>视频回放</td><td>咨询师回访记录</td><td>合同</td><td width=\"120px\">签约失败说明</td></tr></table></div>");

            $.do_ajax('/tongji_ss/get_first_test_lesson_info',{
                "teacherid" : teacherid
            },function(resp) {
                var userid_list = resp.data;
                console.log(userid_list);
                $.each(userid_list,function(i,item){
                    var lessonid = item["lessonid"];
                    var nick = item["nick"]
                    var time = item["lesson_start_str"];
                    var subject = item["subject_str"];
                    var grade = item["grade_str"];
                    var rev = item["rev"];
                    console.log(rev);
                    html_node.find("table").append("<tr><td>"+lessonid+"</td><td>"+time+"</td><td>"+nick+"</td><td>"+grade+"</td><td>"+subject+"</td><td>期待时间:"+item["stu_request_test_lesson_time"]+"<br>试听内容:"+item["stu_test_lesson_level_str"]+"<br>试听需求:"+item["stu_request_test_lesson_demand"]+"<br>教材:"+item["editionid_str"]+"<br>学生成绩:"+item["stu_score_info"]+"<br>学生性格:"+item["stu_character_info"]+"<br>试卷:"+item["stu_test_paper_flag_str"]+"</td><td><a href=\"http://admin.yb1v1.com/player/playback.html?draw="+encodeURIComponent(item["draw_url"])+"&audio="+encodeURIComponent(item["audio_url"])+"&start="+item["real_begin_time"]+" \" target=\"_blank\">点击回放</a></td><td>"+rev+"</td><td>"+item["have_order"]+"</td><td>"+item["test_lesson_order_fail_desc"]+"</td></tr>");
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



