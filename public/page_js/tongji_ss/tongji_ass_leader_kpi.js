/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-tongji_ass_leader_kpi.d.ts" />

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

    $(".un_revisit").on("click",function(){
        var adminid = $(this).data("adminid");
       // alert(adminid);
        if(adminid > 0){
            var title     = "未回访详情";
            var html_node = $("<div id=\"div_table\"><table class=\"table table-bordered \"><tr><td>助教</td><td>学生</td><td>分配时间</td><td>首度回访时间</td><tr></table></div>");

            $.do_ajax('/tongji_ss/get_ass_un_revisit_info',{
                "adminid" : adminid,
                "start_time":g_args.start_time,
                "end_time":g_args.end_time
            },function(resp) {
                var list = resp.data;
                $.each(list,function(i,item){
                    var account = item["account"];
                    var nick = item["nick"];
                    var ass_assign_time = item["ass_assign_time_str"];
                    var revisit_time = item["revisit_time_str"];
                    html_node.find("table").append("<tr><td>"+account+"</td><td>"+nick+"</td><td>"+ass_assign_time+"</td><td>"+revisit_time+"</td>></tr>");
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








