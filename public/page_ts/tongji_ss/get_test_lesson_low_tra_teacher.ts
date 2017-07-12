/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-get_test_lesson_low_tra_teacher.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			subject:	$('#id_subject').val(),
            is_record_flag:	$('#id_is_record_flag').val(),
            is_do_sth:	$('#id_is_do_sth').val(),
			limit_plan_lesson_type : $('#id_limit_plan_lesson_type').val()

        });
    }

    
	Enum_map.append_option_list("subject",$("#id_subject")); 
    Enum_map.append_option_list("boolean", $("#id_is_record_flag") );
    Enum_map.append_option_list("boolean", $("#id_is_do_sth") );
	$('#id_is_record_flag').val(g_args.is_record_flag);
	$('#id_is_do_sth').val(g_args.is_do_sth);
	$('#id_limit_plan_lesson_type').val(g_args.limit_plan_lesson_type);

	$('#id_subject').val(g_args.subject);

    
    if(tea_right==0){
        $(".opt-teacher-freeze").hide();
        $(".opt-limit-plan-lesson").hide();
        $(".opt-set-teacher-record-new").hide();
        
    }

        

    $(".ten_lesson").on("click",function(){
        var teacherid = $(this).data("teacherid");
        var test_lesson_num = $(this).text();
        console.log(test_lesson_num);
        if(teacherid > 0){
            

            var title = "试听详情";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td class=\"ll \">lessonid</td><td>时间</td><td>学生</td><td>年级</td><td>科目</td><td>试听需求</td><td>视频回放</td><td>咨询师回访记录</td><td>合同</td><td width=\"120px\">签约失败说明</td></tr></table></div>");

            $.do_ajax('/tongji_ss/get_ten_test_lesson_info',{
                "teacherid" : teacherid,
                "test_lesson_num": test_lesson_num
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
               /* html_node.find("table").find(".ll").on("click",function(){
                    alert(1111); 
                });*/
            });

            var dlg=BootstrapDialog.show({
                title:title, 
                message :  html_node   ,
                closable: true, 
                buttons:[{
                    label: '推荐视频',
                    cssClass: 'btn-danger',
                    action: function(dialog) {
                        //dialog.close();
                        var select_userid_list=[];

                        html_node.find("table").find(".opt-select-item").each(function(){
                            var $item=$(this) ;
                            if($item.iCheckValue()) {
                                select_userid_list.push( $item.data("url") ) ;
                            }
                        } ) ;
                        console.log(select_userid_list);

                    }
 
                },{
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



