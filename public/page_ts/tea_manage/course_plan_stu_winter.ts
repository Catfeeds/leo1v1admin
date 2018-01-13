/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-course_plan_stu_winter.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
            assistantid:	$('#id_assistantid').val(),
            userid:	$('#id_userid').val(),
            is_done:	$('#id_is_done').val(),
			student_type:	$('#id_student_type').val()
        });
    }
    Enum_map.append_option_list("student_type", $("#id_student_type"));

	$('#id_student_type').val(g_args.student_type);
	$('#id_assistantid').val(g_args.assistantid);
	$('#id_userid').val(g_args.userid);
	$('#id_is_done').val(g_args.is_done);
    $.admin_select_user($("#id_assistantid"), "assistant",function(){
        load_data();
    });
    $.admin_select_user($("#id_userid"), "student",function(){
        load_data();
    });

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

    $("#id_select_all").on("click", function() {
        $(".opt-select-item").iCheck("check");
    });

    $("#id_select_other").on("click", function() {
        $(".opt-select-item").each(function() {
            var $item = $(this);
            if ($item.iCheckValue()) {
                $item.iCheck("uncheck");
            } else {
                $item.iCheck("check");
            }
        });
    });


   
    $(".course_plan,.plan_regular_course").on("click",function(){
        alert("抱歉,该功能未开发!"); 
    });
    
    $(".is_con").each(function(){
        if($(this).html() == "否"){
            $(this).css("color","red"); 
        }else{
            $(this).css("color","green");             
        } 
    });  

    $(".is_clash_str,.is_col_str").each(function(){
        if($(this).html() == "是"){
            $(this).css("color","red"); 
        }else{
            $(this).css("color","green");             
        } 
    });  

    $(".status").each(function(){
        if($(this).html() == "已完成排课"){
            $(this).css("color","green"); 
        }else{
            $(this).css("color","red");             
        } 
    });  


    $("#id_plan_regular_course_all").on("click",function(){
        var row_list=$("#id_tbody tr");
        var do_index=0;
	    
        function do_one() {
            if (do_index < row_list.length ) {
                var $tr=$(row_list[do_index]);
                if($tr.find(".opt-select-item").iCheckValue()){

                    if($tr.find(".is_col_str").html()=="是"){
                        $tr.find(".status").text("有时间冲突的排课,请确认");
                        $tr.find(".status").css("color","red");
                        do_index++;
                        do_one();                
                    }else{
                        var opt_data=$tr.find(".course_plan").get_opt_data();
                        if(opt_data.lesson_start == "undefined"){
                            var lesson_start = "";
                        }else{
                            var lesson_start = opt_data.lesson_start;
                        }

                        $tr.find(".status").text("开始．．．");
                        $.do_ajax("/user_deal/regular_lesson_plan_count_winter",{
                            "userid"           : opt_data.userid,
                            "old_lesson_total" : opt_data.lesson_total,
                            "start_time"       : g_args.start_time,
                            "end_time"         : g_args.end_time,
                            "lesson_start"     : JSON.stringify(lesson_start)
                        },function(resp){
                            console.log(resp);
                            if(resp.ret == -1){
                                $tr.find(".status").html(resp.info);
                                $tr.find(".status").css("color","red");
                                if(resp.hasOwnProperty('data')){
                                    $tr.find(".opt-info").text(resp.data/100); 
                                }
                                do_index++;
                                do_one();
                            }else{
                                $tr.find(".status").text("已完成排课");
                                $tr.find(".opt-info").text(resp/100);
                                $tr.find(".status").css("color","green");
                                if($tr.find(".regular_total").text() == resp/100 ){
                                    $tr.find(".is_con").text("是"); 
                                    $tr.find(".is_con").css("color","green");
                                }else{
                                    $tr.find(".is_con").text("否"); 
                                    $tr.find(".is_con").css("color","red");
                                }
                                do_index++;
                                do_one();
                            }
                        }); 
                    }
                }else{
                    do_index++;
                    do_one();
                }
            }else{
            }
        };
        do_one();

    });

    $("#id_assistantid").parent().parent().hide();

    var init_noit_btn=function( id_name, title) {
        var btn=$('#'+id_name);
        btn.tooltip({
            "title":title,
            "html":true
        });
        var value =btn.data("value");
        btn.text(value);
        if (value >0 ) {
            btn.addClass("btn-warning");
        }
    };

    init_noit_btn("id_regular_count_all",    "常规课表总课时数" );
    init_noit_btn("id_plan_count_all",    "已排课程总课时数" );

	$('.opt-change').set_input_change_event(load_data);
});

