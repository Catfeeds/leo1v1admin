/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-course_plan.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			studentid:	$('#id_studentid').val(),
			plan_course:	$('#id_plan_course').val()
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
	$('#id_studentid').val(g_args.studentid);
    $.admin_select_user( $("#id_studentid"), "student", load_data );

    $(".opt-confirm").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var lessonid = $(this).get_opt_data("lessonid");

        var $confirm_flag                          = $("<select> </select>");
        var $lesson_cancel_reason_type             = $("<select> </select>");
        var $lesson_cancel_reason_next_lesson_time = $("<input/>");
        var $lesson_cancel_reason_next_lesson_end_time = $("<input/>");
        var $lesson_count = $("<input/>");
        var $confirm_reason                        = $("<textarea/> ");

        Enum_map.append_option_list( "confirm_flag", $confirm_flag,true);
        Enum_map.append_option_list( "lesson_cancel_reason_type", $lesson_cancel_reason_type,true);
        
        var arr=[
            ["上课完成", $confirm_flag ] ,
            ["无效类型", $lesson_cancel_reason_type   ] ,
            ["调课-上课时间",$lesson_cancel_reason_next_lesson_time  ],
            ["调课-下课时间",$lesson_cancel_reason_next_lesson_end_time  ],
            ["课时数",$lesson_count  ],
            ["无效说明", $confirm_reason ] 

        ];

        $confirm_flag.val( opt_data.confirm_flag )  ;
        $confirm_reason.val( opt_data.confirm_reason )  ;
        $lesson_cancel_reason_next_lesson_time.val( opt_data.lesson_cancel_reason_next_lesson_time_str )  ;
        $lesson_count.val( opt_data.lesson_count/100 )  ;
        $lesson_cancel_reason_type.val( opt_data.lesson_cancel_reason_type )  ;

        var show_field=function (jobj,show_flag) {
            if ( show_flag ) {
                jobj.parent().parent().show();
            }else{
                jobj.parent().parent().hide();
            }
        };

        var reset_ui=function() {
            var val=$confirm_flag.val();
            if (val==1 || val==0) {
                show_field( $confirm_reason ,false );
                show_field( $lesson_cancel_reason_type,false );
                show_field( $lesson_cancel_reason_next_lesson_time,false );
                show_field( $lesson_cancel_reason_next_lesson_end_time ,false);
                show_field( $lesson_count ,false);

            }else{
                show_field( $confirm_reason ,true);
                show_field( $lesson_cancel_reason_type,true);
                var reason_type= $lesson_cancel_reason_type.val();
                if ( reason_type >0  && reason_type <10 ) {
                    show_field( $lesson_cancel_reason_next_lesson_time,true);
                    show_field( $lesson_cancel_reason_next_lesson_end_time,true);
                    show_field( $lesson_count,true);
                }else{
                    show_field( $lesson_cancel_reason_next_lesson_time ,false);
                    show_field( $lesson_cancel_reason_next_lesson_end_time ,false);
                    show_field( $lesson_count ,false);
                }
            }
        };

        $confirm_flag.on("change",function(){
            reset_ui();
        });
        $lesson_cancel_reason_type.on("change",function(){
            reset_ui();
        });
        
        $.show_key_value_table("确认课时", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.do_ajax("/user_deal/lesson_set_confirm", {
                    "lessonid":lessonid,
                    "confirm_flag":$confirm_flag.val(),
                    "confirm_reason":$confirm_reason.val(),
                    "lesson_cancel_reason_next_lesson_time":$lesson_cancel_reason_next_lesson_time.val(),
                    "lesson_cancel_reason_next_lesson_end_time":$lesson_cancel_reason_next_lesson_end_time.val(),
                    "lesson_count":$lesson_count.val(),
                    "lesson_cancel_reason_type":$lesson_cancel_reason_type.val(),
                    "courseid":opt_data.courseid,
                    "lesson_type":opt_data.lesson_type,
                    "subject":opt_data.subject,
                    "grade":opt_data.grade,
                    "teacherid":opt_data.teacherid,
                    "userid":opt_data.userid,
                    "phone":opt_data.phone
                });
            }
        },function(){
            reset_ui();
            $lesson_cancel_reason_next_lesson_time.datetimepicker({
                datepicker:true,
                timepicker:true,
                format:'Y-m-d H:i',
                step:30,
                onChangeDateTime :function(){
                    var end_time= parseInt(
                        $.strtotime($lesson_cancel_reason_next_lesson_time.val()+':00')) + opt_data.lesson_diff;
                    $lesson_cancel_reason_next_lesson_end_time.val(  $.DateFormat(end_time, "hh:mm"));
                }

            });
            $lesson_cancel_reason_next_lesson_end_time.datetimepicker({
                datepicker:false,
                timepicker:true,
                format:'H:i',
                step:30
            });

		});
        
	});

	$(".cancel_lesson").on("click",function(){
        var lessonid = $(this).get_opt_data('lessonid');
        var courseid = $(this).get_opt_data('courseid');
        
        BootstrapDialog.show({
            title: '删除',
            message : "确认删除吗？" ,
            closable: false, 
            buttons: [{
                label: '返回',
                action: function(dialog) {
                    dialog.close();
                }
            }, {
                label: '确认',
                cssClass: 'btn-warning',
                action: function(dialog) {
                    dialog.close();

                    $.ajax({
                        url: '/user_deal/cancel_lesson',
                        type: 'POST',
                        data: {
				            'lessonid':  lessonid
			            },
                        dataType: 'json',
                        success: function(data) {
                            window.location.reload();
                        }
                    });

                }
            }]
        }); 
    });   
 
    

    $("#id_plan_course").on("click",function(){
        if($("#id_studentid").val() == -1) {
            alert("请先选择学生");
        }else{
            $("<div></div>").admin_select_dlg_ajax({
                "opt_type" : "select", // or "list"
                "url"      : "/tea_manage/get_course_list",
                //其他参数
                "args_ex" : {
                    "userid"  :  $("#id_studentid").val()
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
            
        }
    });

    $("#id_plan_regular_course").on("click",function(){
        if($("#id_studentid").val() == -1) {
            alert("请先选择学生");
        }else{
            if(g_args.opt_date_type != 2){
                alert("请按周选择时间");
                return;
            }
            $.do_ajax("/user_deal/regular_lesson_plan",{
                "userid"     : $("#id_studentid").val(),
                "start_time" : g_args.start_time,
                "end_time"   : g_args.end_time
            });
        }
    });


	$('.opt-change').set_input_change_event(load_data);
    

});
