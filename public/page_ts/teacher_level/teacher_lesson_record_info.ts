/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_level-teacher_lesson_record_info.d.ts" />
var Cwhiteboard=null;
var notify_cur_playpostion =null;
$(function(){
    function load_data(){
        $.reload_self_page ( {
			teacherid:	$('#id_teacherid').val()
        });
    }


	$('#id_teacherid').val(g_args.teacherid);
    $.admin_select_user($("#id_teacherid"), "teacher", load_data);
    $(".opt-test-lesson").on("click",function(){
        var opt_data = $(this).get_opt_data();
        $.do_ajax( "/teacher_level/get_teacher_first_five_info",{
            "teacherid" :opt_data.teacherid
        },function(resp){
            var title = "试听课详情";
            var list = resp.data;
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>lessonid</td><td>时间</td><td>类型</td><td>学生</td><td>操作</td><td>反馈详情</td></tr></table></div>");
            $.each(list,function(i,item){
                html_node.find("table").append("<tr><td>"+item['lessonid']+"</td><td>"+item['lesson_start_str']+"</td><td>"+item['num']+"</td><td>"+item['nick']+"</td><td></td><td></td></tr>");

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

            dlg.getModalDialog().css("width","800px");
            
        });

    });


	$('.opt-change').set_input_change_event(load_data);
});










