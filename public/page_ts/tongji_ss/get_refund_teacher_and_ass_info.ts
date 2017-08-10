/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-get_refund_teacher_and_ass_info.d.ts" />

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


    $(".tea_num").on("click",function(){
        var teacherid = $(this).data("teacherid");
        var num = $(this).data("num");
        //alert(teacherid);
        var title = "被换老师详情";
        var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>助教</td><td>学生</td><tr></table></div>");

        $.do_ajax('/ss_deal2/get_refund_teacher_detail_info',{
            "teacherid"       :teacherid,
            "start_time"      : g_args.start_time,
            "end_time"        : g_args.end_time
        },function(resp) {
            var userid_list = resp.data;
            $.each(userid_list,function(i,item){
                html_node.find("table").append("<tr><td>"+item["account"]+"</td><td>"+item["nick"]+"</td></tr>");
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

    $(".ass_num").on("click",function(){
        var adminid = $(this).data("adminid");
        var num= $(this).data("num");
        if(num>=10){
            var title = "助教换老师详情";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>老师</td><td>学生</td><tr></table></div>");

            $.do_ajax('/tongji_ss/get_change_teacher_detail_info_ass',{
                "adminid"         :adminid,
                "start_time"      : g_args.start_time,
                "end_time"        : g_args.end_time
            },function(resp) {
                var userid_list = resp.data;
                $.each(userid_list,function(i,item){
                    html_node.find("table").append("<tr><td>"+item["realname"]+"</td><td>"+item["nick"]+"</td></tr>");
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
        }else{
            alert("10次以上可查看!");
        }


    });

    $('.opt-change').set_input_change_event(load_data);
});
