/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/assistant_performance-performance_info_second.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
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

    $(".seller_week_stu_num_info").on("click",function(){
        var adminid = $(this).data("adminid");
        var title = "每周在册学生详情";
        var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>时间(周)</td><td>学生数</td><td>学生名单</td></tr></table></div>");

        $.do_ajax('/ajax_deal2/get_ass_performance_seller_week_stu_info',{
            "adminid" : adminid,
            "start_time":g_args.start_time
        },function(resp) {
            var userid_list = resp.data;
            console.log(userid_list);
            $.each(userid_list,function(i,item){
                html_node.find("table").append("<tr><td>"+item["time"]+"</td><td>"+item["num"]+"</td><td>"+item["name_list"]+"</td></tr>");
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


	$('.opt-change').set_input_change_event(load_data);
});

