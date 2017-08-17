/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-tongji_teaching_and_research_teacher_test_lesson_info.d.ts" />

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


    show_top( $("#id_order_num_body > tr")) ;
    show_top( $("#id_order_reward_body > tr")) ;
    show_top( $("#id_order_per_body > tr") ) ;
    show_top( $("#id_subject_order_num_body > tr") ) ;
    show_top( $("#id_subject_order_per_body > tr") ) ;
    show_top( $("#id_person_kpi > tr") ) ;
    show_top( $("#id_subject_kpi > tr") ) ;

    $(".all_lesson").on("click",function(){
        var teacherid = $(this).data("teacherid");
        var subject = $(this).data("subject");
        var grade = $(this).data("grade");
        var all_realname = $(this).data("realname");
        var all_lesson = $(this).data("lesson");
        var all_num = $(this).data("num");
        var all_per = $(this).data("per");
        if(teacherid =="" && subject != -2){
            var title = "试听课详情";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>年级</td><td>试听课数</td><td>签单数</td><td>签单率</td><td>操作</td><tr></table></div>");

            $.do_ajax('/tongji_ss/get_research_teacher_grade_info',{
                "subject"         : subject,
                "grade"           : grade,
                "start_time"      : g_args.start_time,
                "end_time"        : g_args.end_time
            },function(resp) {
                var userid_list = resp.data;
                $.each(userid_list,function(i,item){
                    var grade_str = item["grade_str"];
                    var all_lesson = item["all_lesson"]
                    var order_num = item["order_num"];
                    var per = item["per"];
                    html_node.find("table").append("<tr><td>"+grade_str+"</td><td>"+all_lesson+"</td><td>"+order_num+"</td><td>"+per+"%</td><td><a href=\"/tongji_ss/research_teacher_lesson_detail_info?subject="+subject+"&grade="+item["grade"]+"&start_time="+g_args.start_time+"&end_time="+g_args.end_time+"\" target=\"_blank\" >点击查看详情</a></td></tr>");
                });
                html_node.find("table").append("<tr><td>总计</td><td>"+all_lesson+"</td><td>"+all_num+"</td><td>"+all_per+"%</td><td><a href=\"/tongji_ss/research_teacher_lesson_detail_info?subject="+subject+"&grade="+grade+"&start_time="+g_args.start_time+"&end_time="+g_args.end_time+"\" target=\"_blank\" >点击查看详情</a></td></tr>");

               
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

    $(".reward_num").on("click",function(){
        var adminid = $(this).data("adminid");
        var title = "签单老师详情";
        var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>老师</td><td>签单数</td><td>签单奖</td><tr></table></div>");
        $.do_ajax('/tongji_ss/get_research_teacher_reward_info',{
            "adminid"         : adminid,
            "start_time"      : g_args.start_time,
            "end_time"        : g_args.end_time
        },function(resp) {
            var userid_list = resp.data;
            $.each(userid_list,function(i,item){
                var reward = item["reward_count"]/100;
                var num = item["num"];
                var realname = item["realname"];
                html_node.find("table").append("<tr><td>"+realname+"</td><td>"+num+"</td><td>"+reward+"</td></tr>");
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

    $(".first_reward_num").on("click",function(){
        var adminid = $(this).data("adminid");
        var title = "首签老师名单";
        var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>老师</td><td>签单奖</td><tr></table></div>");
        $.do_ajax('/tongji_ss/get_research_teacher_first_reward_info',{
            "adminid"         : adminid,
            "start_time"      : g_args.start_time,
            "end_time"        : g_args.end_time
        },function(resp) {
            var userid_list = resp.data;
            $.each(userid_list,function(i,item){
                var reward = item["first_reward_count"]/100;
                var realname = item["realname"];
                html_node.find("table").append("<tr><td>"+realname+"</td><td>"+reward+"</td></tr>");
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

    $(".test_lesson").on("click",function(){
        var adminid = $(this).data("adminid");
        var title = "试听详情";
        var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>老师</td><td>试听课数</td><td>签单数</td><td>签单率</td><tr></table></div>");
        $.do_ajax('/tongji_ss/get_interview_test_lesson_info',{
            "adminid"         : adminid,
            "start_time"      : g_args.start_time,
            "end_time"        : g_args.end_time
        },function(resp) {
            var list = resp.data;
            $.each(list,function(i,item){
                html_node.find("table").append("<tr><td>"+item["realname"]+"</td><td>"+item["person_num"]+"</td><td>"+item["order_num"]+"</td><td>"+item["order_per"]+"%</td></tr>");
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

        
    });


    $("#id_read_reward_rule").on("click",function(){
        var title = "签单率/签单奖相关规则";
        var html_node= $("<div ><div>一、签单奖定义:<br>1.近两个月面试的新老师在本月产生的签单<br>2.以老师近一个月的签单率为衡量标准,签单率15%-25%:奖金5元/位;签单率25%-35%:奖金10元/位;签单率35%以上:奖金20元/位;</div><div>二、首次试听签单奖定义:第一次试听课就签约,奖金:50元/位</div><div>三、面试签单率定义:<br>1.1月5日到今天面试的所有老师在本月的试听课产生的签单/试听课数<br>2.签单数暂时需要达到10个以上才能获得此奖项<br>2)签单率15%-25%:奖金100元;签单率25%-35%:奖金300元/位;签单率35%以上:奖金500元/位;</div></div>");
        
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

    $(".subject_order_per").on("click",function(){
        var subject = $(this).data("subject");
        if(subject==-3){
            var title = "文科组详情";
            var html_node= $("<div  id=\"div_table\"><table   class=\"table table-bordered \"><tr><td>科目</td><td>签单数</td><td>签单率</td><tr></table></div>");
            $.do_ajax('/tongji_ss/get_subject_order_per_info',{
                "start_time"      : g_args.start_time,
                "end_time"        : g_args.end_time
            },function(resp) {
                var userid_list = resp.data;
                $.each(userid_list,function(i,item){
                    html_node.find("table").append("<tr><td>"+item["subject_str"]+"</td><td>"+item["order_num"]+"</td><td>"+item["order_per"]+"%</td></tr>");
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

    if(g_args.master_flag==0){
        $("#team_info").hide();
    }

	$('.opt-change').set_input_change_event(load_data);
});




