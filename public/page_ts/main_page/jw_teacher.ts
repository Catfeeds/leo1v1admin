/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/main_page-jw_teacher.d.ts" />

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
    

    $(".order_lesson").on("click",function(){
        var adminid = $(this).data("adminid");
        var d = new Date();
        var hour = d.getHours();       
        console.log(adminid);
        if(adminid > 0 && (( hour>=9 &&  hour<10) || ( hour>=18 &&  hour<19))){           
            var title = "课程详情";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>lessonid</td><td>时间</td><td>老师</td><td>学生</td><td>年级</td><td>科目</td></tr></table></div>");

            $.do_ajax('/tongji_ss/get_suc_order_lesson_info',{
                "adminid" : adminid,
                "start_time":g_args.start_time,
                "end_time":g_args.end_time,
            },function(resp) {
                var userid_list = resp.data;
                console.log(userid_list);
                $.each(userid_list,function(i,item){
                    var lessonid = item["lessonid"];
                    var nick = item["nick"]
                    var time = item["lesson_start_str"];
                    var subject = item["subject_str"];
                    var grade = item["grade_str"];
                    var tea_name=item["tea_name"];
                    html_node.find("table").append("<tr><td>"+lessonid+"</td><td>"+time+"</td><td>"+tea_name+"</td><td>"+nick+"</td><td>"+grade+"</td><td>"+subject+"</td></tr>");
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
            BootstrapDialog.alert("请在上午9点到10点或者晚上18点到19点查看!");
        }
        
    });

    $(".tra_count_green").on("click",function(){
        var adminid = $(this).data("adminid");
        console.log(adminid);
        if(adminid > 0){           
            var title = "绿色通道课程详情";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>lessonid</td><td>时间</td><td>老师</td><td>学生</td><td>年级</td><td>科目</td></tr></table></div>");

            $.do_ajax('/tongji_ss/get_suc_order_lesson_info',{
                "adminid" : adminid,
                "start_time":g_args.start_time,
                "end_time":g_args.end_time,
                "is_green_flag":1
            },function(resp) {
                var userid_list = resp.data;
                console.log(userid_list);
                $.each(userid_list,function(i,item){
                    var lessonid = item["lessonid"];
                    var nick = item["nick"]
                    var time = item["lesson_start_str"];
                    var subject = item["subject_str"];
                    var grade = item["grade_str"];
                    var tea_name=item["tea_name"];
                    html_node.find("table").append("<tr><td>"+lessonid+"</td><td>"+time+"</td><td>"+tea_name+"</td><td>"+nick+"</td><td>"+grade+"</td><td>"+subject+"</td></tr>");
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

   
    
    $(".tra_count_seller").on("click",function(){
        var adminid = $(this).data("adminid");
        console.log(adminid);
        if(adminid > 0){           
            var title = "销售课程详情";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>lessonid</td><td>时间</td><td>老师</td><td>学生</td><td>年级</td><td>科目</td></tr></table></div>");

            $.do_ajax('/tongji_ss/get_suc_order_lesson_info',{
                "adminid" : adminid,
                "start_time":g_args.start_time,
                "end_time":g_args.end_time,
                "require_admin_type":2
            },function(resp) {
                var userid_list = resp.data;
                console.log(userid_list);
                $.each(userid_list,function(i,item){
                    var lessonid = item["lessonid"];
                    var nick = item["nick"]
                    var time = item["lesson_start_str"];
                    var subject = item["subject_str"];
                    var grade = item["grade_str"];
                    var tea_name=item["tea_name"];
                    html_node.find("table").append("<tr><td>"+lessonid+"</td><td>"+time+"</td><td>"+tea_name+"</td><td>"+nick+"</td><td>"+grade+"</td><td>"+subject+"</td></tr>");
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
    $(".tra_count_ass").on("click",function(){
        var adminid = $(this).data("adminid");
        console.log(adminid);
        if(adminid > 0){           
            var title = "助教课程详情";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>lessonid</td><td>时间</td><td>老师</td><td>学生</td><td>年级</td><td>科目</td></tr></table></div>");

            $.do_ajax('/tongji_ss/get_suc_order_lesson_info',{
                "adminid" : adminid,
                "start_time":g_args.start_time,
                "end_time":g_args.end_time,
                "require_admin_type":1
            },function(resp) {
                var userid_list = resp.data;
                console.log(userid_list);
                $.each(userid_list,function(i,item){
                    var lessonid = item["lessonid"];
                    var nick = item["nick"]
                    var time = item["lesson_start_str"];
                    var subject = item["subject_str"];
                    var grade = item["grade_str"];
                    var tea_name=item["tea_name"];
                    html_node.find("table").append("<tr><td>"+lessonid+"</td><td>"+time+"</td><td>"+tea_name+"</td><td>"+nick+"</td><td>"+grade+"</td><td>"+subject+"</td></tr>");
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

    $(".top_count").on("click",function(){
        var adminid = $(this).data("adminid");
        console.log(adminid);
        if(adminid > 0){           
            var title = "精排详情";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>状态</td><td>lessonid</td><td>时间</td><td>老师</td><td>维度</td><td>学生</td><td>年级</td><td>科目</td></tr></table></div>");

            $.do_ajax('/ajax_deal2/get_seller_top_lesson_info',{
                "adminid" : adminid,
                "start_time":g_args.start_time,
                "end_time":g_args.end_time,
            },function(resp) {
                var userid_list = resp.data;
                console.log(userid_list);
                $.each(userid_list,function(i,item){
                    var lessonid = item["lessonid"];
                    var nick = item["nick"]
                    var time = item["lesson_start_str"];
                    var subject = item["subject_str"];
                    var grade = item["grade_str"];
                    var tea_name=item["realname"];
                    html_node.find("table").append("<tr><td>"+item["test_lesson_student_status_str"]+"</td><td>"+lessonid+"</td><td>"+time+"</td><td>"+tea_name+"</td><td>"+item["teacher_dimension"]+"</td><td>"+nick+"</td><td>"+grade+"</td><td>"+subject+"</td></tr>");
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

    $(".tran_count_seller_top").on("click",function(){
        var adminid = $(this).data("adminid");
        console.log(adminid);
        if(adminid > 0){           
            var title = "精排转化详情";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>状态</td><td>lessonid</td><td>时间</td><td>老师</td><td>维度</td><td>学生</td><td>年级</td><td>科目</td><td>咨询师</td></tr></table></div>");


            $.do_ajax('/tongji_ss/get_suc_seller_top_info',{
                "adminid" : adminid,
                "start_time":g_args.start_time,
                "end_time":g_args.end_time,
            },function(resp) {
                var userid_list = resp.data;
                console.log(userid_list);
                $.each(userid_list,function(i,item){
                    var lessonid = item["lessonid"];
                    var nick = item["nick"]
                    var time = item["lesson_start_str"];
                    var subject = item["subject_str"];
                    var grade = item["grade_str"];
                    var tea_name=item["realname"];
                    html_node.find("table").append("<tr><td>"+item["test_lesson_student_status_str"]+"</td><td>"+lessonid+"</td><td>"+time+"</td><td>"+tea_name+"</td><td>"+item["teacher_dimension"]+"</td><td>"+nick+"</td><td>"+grade+"</td><td>"+subject+"</td><td>"+item["account"]+"</td></tr>");                
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

    $(".order_num").on("click",function(){
        var adminid = $(this).data("adminid");
        console.log(adminid);
        if(adminid > 0){           
            var title = "签单详情";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>状态</td><td>lessonid</td><td>时间</td><td>老师</td><td>维度</td><td>学生</td><td>年级</td><td>科目</td><td>咨询师</td></tr></table></div>");


            $.do_ajax('/ajax_deal2/get_suc_order_lesson_info',{
                "adminid" : adminid,
                "start_time":g_args.start_time,
                "end_time":g_args.end_time,
            },function(resp) {
                var userid_list = resp.data;
                console.log(userid_list);
                $.each(userid_list,function(i,item){
                    var lessonid = item["lessonid"];
                    var nick = item["nick"]
                    var time = item["lesson_start_str"];
                    var subject = item["subject_str"];
                    var grade = item["grade_str"];
                    var tea_name=item["realname"];
                    html_node.find("table").append("<tr><td>"+item["test_lesson_student_status_str"]+"</td><td>"+lessonid+"</td><td>"+time+"</td><td>"+tea_name+"</td><td>"+item["teacher_dimension"]+"</td><td>"+nick+"</td><td>"+grade+"</td><td>"+subject+"</td><td>"+item["account"]+"</td></tr>");                
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







    $("#id_tongji").on("click",function(){
        var row_list=$("#id_per_count_list tr");
        var do_index=0;
	    
        function do_one() {
            if (do_index < row_list.length ) {
                var $tr=$(row_list[do_index]);
                var adminid=$tr.find(".order_lesson").data("adminid");
                var all_count = $tr.find(".all_count").text();
                $.do_ajax("/user_deal/get_jw_tran_info_by_adminid",{
                    "adminid"  : adminid,
                    "all_count": all_count,
                    "start_time" :g_args.start_time,
                    "end_time"  :g_args.end_time
                },function(resp){                    
                    $tr.find(".order_lesson").text(resp.tra_count);
                    $tr.find(".tra_count_seller").text(resp.tra_count_seller);
                    $tr.find(".tra_count_ass").text(resp.tra_count_ass);
                    $tr.find(".tra_per_str").text(resp.tra_per_str);
                    
                    do_index++;
                    do_one();                                     
                });

            }else{
            }
        };
        do_one();

    });


	$('.opt-change').set_input_change_event(load_data);
});



