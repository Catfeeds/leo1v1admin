/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/main_page-zs_teacher.d.ts" />

$(function(){

    function load_data(){
        $.reload_self_page ( {
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


    function show_top( $person_body_list) {
        
        $($person_body_list[0]).find("td").css(
            {
                "color" :"red"
            } 
        );
        $($person_body_list[1]).find("td").css(
            {
                "color" :"orange"
            } 
        );

        $($person_body_list[2]).find("td").css(
            {
                "color" :"blue"
            } 
        );

    }

    show_top( $("#id_per_count_list > tr")) ;

    
    $(".video_count").on("click",function(){
        var accept_adminid = $(this).data("adminid");
        if(accept_adminid > 0){
            
            var title = "科目详情";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>科目</td><td>数量</td></tr></table></div>");

            $.do_ajax('/tongji_ss/get_lecture_video_info',{
                "accept_adminid" : accept_adminid,
                "start_time" :    g_args.start_time,
                "end_time" :      g_args.end_time
            },function(resp) {
                var userid_list = resp.data;
                $.each(userid_list,function(i,item){
                    var subject = item["subject_str"];
                    var xx = item["xx_count"];
                    var cz = item["cz_count"];
                    var gz = item["gz_count"];
                    var num = item["all_count"];
                    html_node.find("table").append("<tr><td>"+subject+"</td><td>小学:"+xx+"&nbsp&nbsp&nbsp&nbsp初中:"+cz+"&nbsp&nbsp&nbsp&nbsp高中:"+gz+"&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp合计:"+num+"</td></tr>");
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

            dlg.getModalDialog().css("width","800px");

        }
        
    });
    
    $(".suc_count").on("click",function(){
        var accept_adminid = $(this).data("adminid");
        if(accept_adminid > 0){
            
            var title = "科目详情";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>科目</td><td>数量</td></tr></table></div>");

            $.do_ajax('/tongji_ss/get_lecture_suc_info',{
                "accept_adminid" : accept_adminid,
                "start_time" :    g_args.start_time,
                "end_time" :      g_args.end_time
            },function(resp) {
                var userid_list = resp.data;
                $.each(userid_list,function(i,item){
                    var subject = item["subject_str"];
                    var xx = item["xx_count"];
                    var cz = item["cz_count"];
                    var gz = item["gz_count"];
                    var num = item["all_count"];
                    html_node.find("table").append("<tr><td>"+subject+"</td><td>小学:"+xx+"&nbsp&nbsp&nbsp&nbsp初中:"+cz+"&nbsp&nbsp&nbsp&nbsp高中:"+gz+"&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp合计:"+num+"</td></tr>");

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

            dlg.getModalDialog().css("width","800px");

        }
        
    });


    $(".video_class").on("click",function(){
        
        var title = "科目详情";
        var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>科目</td><td>数量</td></tr></table></div>");

        $.do_ajax('/tongji_ss/get_lecture_all_video_info',{
            "start_time" :    g_args.start_time,
            "end_time" :      g_args.end_time
        },function(resp) {
            var userid_list = resp.data;
            $.each(userid_list,function(i,item){
                var subject = item["subject_str"];
                var xx = item["xx_count"];
                var cz = item["cz_count"];
                var gz = item["gz_count"];
                var num = item["all_count"];
                html_node.find("table").append("<tr><td>"+subject+"</td><td>小学:"+xx+"&nbsp&nbsp&nbsp&nbsp初中:"+cz+"&nbsp&nbsp&nbsp&nbsp高中:"+gz+"&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp合计:"+num+"</td></tr>");

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


    $(".suc_class").on("click",function(){     
        var title = "科目详情";
        var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>科目</td><td>数量</td></tr></table></div>");

        $.do_ajax('/tongji_ss/get_lecture_all_suc_info',{
            "start_time" :    g_args.start_time,
            "end_time" :      g_args.end_time
        },function(resp) {
            var userid_list = resp.data;
            $.each(userid_list,function(i,item){
                var subject = item["subject_str"];
                var xx = item["xx_count"];
                var cz = item["cz_count"];
                var gz = item["gz_count"];
                var num = item["all_count"];
                html_node.find("table").append("<tr><td>"+subject+"</td><td>小学:"+xx+"&nbsp&nbsp&nbsp&nbsp初中:"+cz+"&nbsp&nbsp&nbsp&nbsp高中:"+gz+"&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp合计:"+num+"</td></tr>");

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



