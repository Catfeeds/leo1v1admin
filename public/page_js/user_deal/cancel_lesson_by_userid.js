/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_deal-cancel_lesson_by_userid.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
            assistantid:	$('#id_assistantid').val(),
            userid:	$('#id_userid').val(),
            is_done:	$('#id_is_done').val()
        });
    }

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

    $(".regular-info").on("click",function(){
        var opt_data=$(this).parent().parent().find(".row-data").get_self_opt_data();
        var userid = opt_data.userid;
        $.wopen("../human_resource/regular_course?teacherid=-1&userid="+userid);
        
    });

    $(".opt-info").on("click",function(){
        var start = g_args.start_time;
        var end = g_args.end_time;
        var opt_data=$(this).parent().parent().find(".row-data").get_self_opt_data();
        var userid = opt_data.userid;
        $.wopen("../tea_manage/course_plan?date_type=null&opt_date_type=2&start_time="+start+"&end_time="+end+"&studentid="+userid+"&plan_course=");
        
    });
    
    $(".course_plan").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var userid = opt_data.userid;
        $("<div></div>").admin_select_dlg_ajax({
            "opt_type" : "select", // or "list"
            "url"      : "/tea_manage/get_course_list",
            //其他参数
            "args_ex" : {
                "userid"  :  userid
            },

            select_primary_field   : "courseid",
            select_display         : "package_name",
            select_no_select_value : 0,
            select_no_select_title : "[未设置]",

            //字段列表
            'field_list' :[
                {
                    title:"courseid",
                    width :50,
                    field_name:"courseid"
                },{
                    title:"类型",
                    field_name:"course_type"
                },{
                    title:"老师",
                    field_name:"teacher_nick"
                },{
                    title:"科目",
                    field_name:"subject"
                },{
                    title:"年级",
                    field_name:"grade"
                },{
                    title:"状态",
                    field_name:"course_status"
                },{
                    title:"课次总数",
                    field_name:"lesson_total"

                },{
                    title:"剩余课时数",
                    field_name:"lesson_left"

                },{
                    title:"默认课时数",
                    field_name:"default_lesson_count"
                }
            ] ,
            //查询列表
            filter_list:[
            ],
            "auto_close" : true,
            "onChange"   : function( val) {
                var courseid = val ;
                var me=this;
                if (courseid>0) {
                    $.do_ajax("/user_deal/lesson_add_lesson",{
                        "courseid":courseid
                    },function(resp){
                        var  $item=$("<div></div> ");
                        $item.admin_set_lesson_time({
                            "lessonid" : resp.lessonid  
                        });
                        $item.click();
                    });
                }else{
                    alert( "还没有课程" );
                }                
            },
            "onLoadData" : null
        });
            
        
    });

    $(".plan_regular_course").on("click",function(){
        var opt_data=$(this).get_opt_data();
        var userid = opt_data.userid;

        $.do_ajax("/user_deal/regular_lesson_plan",{
            "userid": userid,
            "start_time": g_args.start_time,
            "end_time": g_args.end_time
        });

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
                    $.do_ajax("/user_deal/regular_lesson_plan_count",{
                        "userid"  : opt_data.userid,
                        "start_time" : g_args.start_time,
                        "end_time"   : g_args.end_time,
                        "lesson_start" : JSON.stringify(lesson_start)
                    },function(resp){
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
            }
        };
        do_one();

    });

    if ( window.location.pathname =="/tea_manage/course_plan_stu_ass" ) {
        $("#id_assistantid").parent().parent().hide();
    }

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

