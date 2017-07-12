/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-ass_weekly_info_master.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val()
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

    $(".end_stu_num").on("click",function(){
        var title = "结课学生详情";
        var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>userid</td><td>学生</td><td>账号</td><td>年级</td><td>助教</td><td>结课原因</td><tr></table></div>");

        $.do_ajax('/tongji_ss/get_ass_end_stu_list',{
            "start_time" : g_args.start_time,
            "end_time"   : g_args.end_time
        },function(resp) {
            var userid_list = resp.data;
            $.each(userid_list,function(i,item){
                var userid = item["userid"];
                var nick     = item["nick"]
                var phone     = item["phone"];
                var grade  = item["grade_str"];
                var name    = item["name"];
                var reason = item["stu_lesson_stop_reason"];
                html_node.find("table").append("<tr><td>"+userid+"</td><td>"+nick+"</td><td>"+phone+"</td><td>"+grade+"</td><td>"+name+"</td><td>"+reason+"</td></tr>");
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
        
    });
    $("#id_opt_date_type").hide();


	$('.opt-change').set_input_change_event(load_data);
});
