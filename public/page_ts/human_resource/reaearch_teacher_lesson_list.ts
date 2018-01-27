/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-reaearch_teacher_lesson_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ({
			      teacherid:	$('#id_teacherid').val()
        });
    }


	  $('#id_teacherid').val(g_args.teacherid);
    $.admin_select_user( $("#id_teacherid"), "research_teacher", load_data);
    $.each( $(".opt-show-lessons-new"), function(i,item ){
        $(item).admin_select_teacher_free_time_new({
            "teacherid" : $(item).get_opt_data("teacherid")
        });
        $(item).hide();
    });
    $(".show_lesson_info").each(function(){
        $(this).admin_select_teacher_free_time_new({
            "teacherid" : $(this).data("teacherid")
        });

    });

	  $('.opt-change').set_input_change_event(load_data);

    $("#id_add_closest").on("click", function(){

        var id_realname           = $("<input>");
        var id_phone              = $("<input>");

        var id_teacher_money_type = $("<input readonly>");
        var id_teacher_type       = $("<input readonly>");
        var id_grade_start        = $("<select/>");
        var id_grade_end          = $("<select/>");
        var id_subject            = $("<select/>");
        
        id_teacher_money_type.val("在职老师");
        id_teacher_type.val("公司教研老师");

        Enum_map.append_option_list("grade", id_grade_start,true);
        Enum_map.append_option_list("grade", id_grade_end,true);
        Enum_map.append_option_list("subject", id_subject,true);

        var arr = [
            [ "老师姓名",  id_realname] ,
            [ "手机号",  id_phone] ,
            [ "科目",  id_subject] ,
            [ "年级开始",  id_grade_start] ,
            [ "年级结束",  id_grade_end] ,
            [ "工资类型",  id_teacher_money_type] ,
            [ "老师类型",  id_teacher_type] ,
        ];

        $.show_key_value_table("添加教研老师", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                var realname            = id_realname.val();
                var phone               = id_phone.val();
                var teacher_money_type  = id_teacher_money_type.val();
                var teacher_type        = id_teacher_type.val();
                var grade_start         = id_grade_start.val();
                var grade_end           = id_grade_end.val();
                var subject             = id_subject.val();
                if (!realname) {
                    BootstrapDialog.alert("教研老师名字不能为空");
                    return;
                }
                if (!phone) {
                    BootstrapDialog.alert('教研老师手机号码不能为空');
                    return;
                }
                if (grade_start == 0) {
                    BootstrapDialog.alert('年级开始不能为空');
                    return;
                }
                if (grade_end == 0) {
                    BootstrapDialog.alert('年级结束不能为空');
                    return;
                }

                if (subject == 0) {
                    BootstrapDialog.alert('科目不能为空');
                    return;
                }

                if(grade_end<grade_start){
                    BootstrapDialog.alert("年级结束不能小于年级开始");
                    return;
                }
                

                $.do_ajax('/human_resource/add_research_teacher',{
                    'realname'           : realname,
                    'phone'              : phone,
                    'subject'            : subject,
                    'grade_start'        : grade_start,
                    'grade_end'          : grade_end,
                    'teacher_money_type' : 0,
                    'teacher_type'       : 4
                });

            }
        });
   });

    $(".opt-set-grade-range").on("click",function(){
        var data = $(this).get_opt_data();
        var id_subject            = $("<select>");
        var id_grade_start        = $("<select>");
        var id_grade_end          = $("<select>");
        var id_second_subject     = $("<select>");
        var id_second_grade_start = $("<select>");
        var id_second_grade_end   = $("<select>");

        Enum_map.append_option_list("subject",id_subject,true);
        Enum_map.append_option_list("subject",id_second_subject,true);
        Enum_map.append_option_list("grade_range",id_grade_start,true);
        Enum_map.append_option_list("grade_range",id_grade_end,true);
        Enum_map.append_option_list("grade_range",id_second_grade_start,true);
        Enum_map.append_option_list("grade_range",id_second_grade_end,true);

        var arr = [
            ["第一科目",id_subject],
            ["开始年级",id_grade_start],
            ["结束年级",id_grade_end],
            ["第二科目",id_second_subject],
            ["开始年级",id_second_grade_start],
            ["结束年级",id_second_grade_end],
        ];
        id_subject.val(data.subject);
        id_second_subject.val(data.second_subject);
        id_grade_start.val(data.grade_start);
        id_grade_end.val(data.grade_end);
        id_second_grade_start.val(data.second_grade_start);
        id_second_grade_end.val(data.second_grade_end);

        $.show_key_value_table("更新老师的科目和年级信息",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/teacher_info_admin/update_teacher_subject_info",{
                    "teacherid"          : data.teacherid,
                    "subject"            : id_subject.val(),
                    "second_subject"     : id_second_subject.val(),
                    "grade_start"        : id_grade_start.val(),
                    "grade_end"          : id_grade_end.val(),
                    "second_grade_start" : id_second_grade_start.val(),
                    "second_grade_end"   : id_second_grade_end.val(),
                });
            }
        });

    });

    $(".opt-change-lesson-num").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var id_limit_day_lesson_num =$("<input/>");
        var id_limit_week_lesson_num =$("<input/>");
        var id_limit_month_lesson_num =$("<input/>");
        var id_saturday_lesson_num =$("<input/>");
        var id_week_lesson_count =$("<input/>");
        var id_seller_require_flag=$("<select/>");
        var id_limit_time_info =$("<button class='btn btn-primary show_time_info'>点击编辑</button>");
        Enum_map.append_option_list("boolean", id_seller_require_flag, true );
        id_seller_require_flag.val(0);

        id_limit_day_lesson_num.val(opt_data.limit_day_lesson_num);
        id_limit_week_lesson_num.val(opt_data.limit_week_lesson_num);
        id_limit_month_lesson_num.val(opt_data.limit_month_lesson_num);
        id_saturday_lesson_num.val(opt_data.saturday_lesson_num);
        id_week_lesson_count.val(opt_data.week_lesson_count);

        var arr=[
            ["每日最大排课数", id_limit_day_lesson_num],
            ["每周最大排课数", id_limit_week_lesson_num],
            ["每月最大排课数", id_limit_month_lesson_num],
            ["教研老师周六可排课时", id_saturday_lesson_num],
            ["教研周课时上限", id_week_lesson_count],
            ["是否CC要求",id_seller_require_flag],
            ["排课限制",id_limit_time_info]
        ];
        id_limit_time_info.data("time_info",opt_data.week_limit_time_info);
      //  alert(id_limit_time_info.data("haha"));
        id_limit_time_info.on("click",function(){
            var lessonid = $(this).data("lessonid");
            var userid = $(this).data("userid");
            var role = $(this).data("role");
            var title = "排课限制时间";
            var html_node= $("<div  id=\"div_table\"><div class=\"col-xs-6 col-md-12\"  ><a id='add_data' class=\"btn btn-danger add_data\" href=\"javascript:;\" style=\"float:right\">增加</a></div><div class=\"col-xs-12 col-md-12  \"><table   class=\"table table-bordered \"><tr><td>周</td><td>开始时间</td><td>结束时间</td><td>操作</td></tr></table></div>");

            var list= id_limit_time_info.data("time_info");
            console.log(list);
            $.each(list,function(i,item){
                // html_node.find("table").append("<tr><td>"+role+"</td><td>"+item["opt_type_str"]+"</td><td>"+item["opt_time_str"]+"</td><td><button class='btn btn-primary update_data'>修改</button><button class='btn btn-primary delete_data' style='margin-left:20px'>删除</button></td></tr>");
                html_node.find("table").append("<tr data-week='"+item["week_num"]+"' data-start='"+item["start"]+"' data-end='"+item["end"]+"' data-week_name='"+item["week_name"]+"' ><td class='week_name'>"+item["week_name"]+"</td><td class='week_start'>"+item["start"]+"</td><td class='week_end'>"+item["end"]+"</td><td><button class='btn btn-primary update_data'>修改</button><button class='btn btn-primary delete_data' style='margin-left:20px'>删除</button></td></tr>");

            });
           
            html_node.find("#add_data").on("click",function(){
                var id_week        = $("<select><option value='1'>周一</option><option value='2'>周二</option><option value='3'>周三</option><option value='4'>周四</option><option value='5'>周五</option><option value='1'>周六</option><option value='7'>周日</option></select>");
                var id_start =$("<input/>");
                var id_end =$("<input/>");

                var arr1 = [
                    ["周",id_week],
                    ["开始时间",id_start],
                    ["结束时间",id_end],                  
                ];
                
                id_end.datetimepicker({
                    datepicker:false,
                    timepicker:true,
                    format:'H:i',
                    step:30
                });
                id_start.datetimepicker({
                    datepicker:false,
                    timepicker:true,
                    format:'H:i',
                    step:30
                });



                $.show_key_value_table("增加排课限制",arr1,{
                    label    : "确认",
                    cssClass : "btn-warning",
                    action   : function(dialog1) {
                        var week_num=id_week.val();
                        var week = id_week.find("option:selected").text();
                        var start = id_start.val();
                        var end = id_end.val();
                        html_node.find("table").append("<tr data-week='"+week_num+"' data-start='"+start+"' data-end='"+end+"' data-week_name='"+week+"' ><td class='week_name'>"+week+"</td><td class='week_start'>"+start+"</td><td class='week_end'>"+end+"</td><td><button class='btn btn-primary update_data'>修改</button><button class='btn btn-primary delete_data' style='margin-left:20px'>删除</button></td></tr>");
                        dialog1.close();
                    }
                });

                
            });
            html_node.find("table").on("click", ".update_data",function(){
                var me = $(this);
                console.log(me.parent().parent());
                var id_week        = $("<select><option value='1'>周一</option><option value='2'>周二</option><option value='3'>周三</option><option value='4'>周四</option><option value='5'>周五</option><option value='1'>周六</option><option value='7'>周日</option></select>");
                var id_start =$("<input/>");
                var id_end =$("<input/>");

                var arr1 = [
                    ["周",id_week],
                    ["开始时间",id_start],
                    ["结束时间",id_end],                  
                ];
                var week_num = me.parent().parent().data("week");
                var start = me.parent().parent().data("start");
                var end = me.parent().parent().data("end");
                id_week.val(week_num);
                id_start.val(start);
                id_end.val(end);
                
                id_end.datetimepicker({
                    datepicker:false,
                    timepicker:true,
                    format:'H:i',
                    step:30
                });
                id_start.datetimepicker({
                    datepicker:false,
                    timepicker:true,
                    format:'H:i',
                    step:30
                });



                $.show_key_value_table("修改排课限制",arr1,{
                    label    : "确认",
                    cssClass : "btn-warning",
                    action   : function(dialog1) {
                        var week_num=id_week.val();
                        var week = id_week.find("option:selected").text();
                        var start = id_start.val();
                        var end = id_end.val();
                        me.parent().parent().after("<tr data-week='"+week_num+"' data-start='"+start+"' data-end='"+end+"' data-week_name='"+week+"' ><td class='week_name'>"+week+"</td><td class='week_start'>"+start+"</td><td class='week_end'>"+end+"</td><td><button class='btn btn-primary update_data'>修改</button><button class='btn btn-primary delete_data' style='margin-left:20px'>删除</button></td></tr>");
                        me.parent().parent().remove();


                        // html_node.find("table").append("<tr data-week='"+week_num+"'><td class='week_name'>"+week+"</td><td class='week_start'>"+start+"</td><td class='week_end'>"+end+"</td><td><button class='btn btn-primary update_data'>修改</button><button class='btn btn-primary delete_data' style='margin-left:20px'>删除</button></td></tr>");
                        dialog1.close();
                    }
                });

            });

            html_node.find("table").on("click", ".delete_data",function(){
                var me = $(this);
                me.parent().parent().remove();
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
                },{
                    label: '确认',
                    cssClass: 'btn-warning',
                    action: function(dialog) {
                        console.log( html_node.find("table").find("tr"));
                        var data =  html_node.find("table").find("tr");
                        var arr=[];
                        $.each(data,function(i,item){
                            console.log(i);
                            console.log(item);
                            if(i>0){
                                arr.push({"week_num":$(item).data("week"),"week_name":$(item).data("week_name"),"start":$(item).data("start"),"end":$(item).data("end")});

                            }
                        });
                        console.log(arr);
                        id_limit_time_info.data("time_info",arr);


                       dialog.close();

                    }
                }],
                onshown:function(){

                }

            });

            dlg.getModalDialog().css("width","600px");
  
        });
        $.show_key_value_table("修改排课数量", arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action : function(dialog) {

                $.do_ajax( '/tea_manage_new/update_teacher_lesson_num',
                           {
                    "teacherid"          : opt_data.teacherid,
                    "limit_day_lesson_num" :id_limit_day_lesson_num.val(),
                    "limit_week_lesson_num" :id_limit_week_lesson_num.val(),
                    "limit_month_lesson_num" :id_limit_month_lesson_num.val(),
                    "saturday_lesson_num" :id_saturday_lesson_num.val(),
                    "week_lesson_count" :id_week_lesson_count.val(),
                    "seller_require_flag" :id_seller_require_flag.val(),
                    "week_limit_time_info": JSON.stringify(id_limit_time_info.data("time_info")),
                    "type" :2
                });
            }
        });


    });


});



