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

    $(".opt_kk_suc").on("click",function(){
        var uid= $(this).data("adminid");
        if(uid > 0){
            var title = "扩课成功学生详情";
            var html_node= $("<div  id=\"div_table\"><div class=\"col-md-12\" id=\"div_no_lesson\"><div class=\"col-md-4\">未试听扩课:</div></div><br><div class=\"col-md-12\" id=\"div_lesson\"><div class=\"col-md-4\">试听扩课:</div></div><br><div class=\"col-md-12\" id=\"div_all\"><div class=\"col-md-4\">总计:</div></div><br><table   class=\"table table-bordered \"><tr><td>userid</td><td>学生</td><td>科目</td><td>老师</td><td>第一次常规课时间</td><tr></table></div>");

            $.do_ajax('/tongji_ss/get_ass_stu_kk_suc_info',{
                "adminid"  : uid,
                "start_time" : g_args.start_time,
                "end_time"   : g_args.end_time
            },function(resp) {
                var userid_list = resp.data;
                $.each(userid_list,function(i,item){
                    var userid = item["userid"];
                    var nick     = item["nick"]
                    var time     = item["time"];
                    var subject  = item["subject_str"];
                    var realname    = item["realname"];
                    html_node.find("table").append("<tr><td>"+userid+"</td><td>"+nick+"</td><td>"+subject+"</td><td>"+realname+"</td><td>"+time+"</td></tr>");
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


    if(g_account=="sherry" ){
        download_show();
    }



	$('.opt-change').set_input_change_event(load_data);
});

