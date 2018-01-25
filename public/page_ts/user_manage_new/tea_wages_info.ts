/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-tea_wages_info.d.ts" />

$(function(){
    var notify_cur_playpostion =null;
    function load_data(){
        $.reload_self_page ( {
			      date_type_config : $('#id_date_type_config').val(),
			      date_type        : $('#id_date_type').val(),
			      opt_date_type    : $('#id_opt_date_type').val(),
			      start_time       : $('#id_start_time').val(),
			      end_time         : $('#id_end_time').val(),
			      teacherid        : $('#id_teacherid').val(),
			      studentid        : $('#id_studentid').val(),
			      show_type        : $('#id_show_type').val(),
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

	  $('#id_teacherid').val(g_args.teacherid);
	  $('#id_studentid').val(g_args.studentid);
	  $('#id_show_type').val(g_args.show_type);
	  $('.opt-change').set_input_change_event(load_data);
    $('#id_teacherid').val(g_args.teacherid);
    $('#id_studentid').val(g_args.studentid);

    $(".opt-goto-lesson").on("click",function(){
        var opt_data=$(this).get_opt_data();
        $.wopen("/tea_manage/lesson_list?lessonid=" + opt_data.lessonid);
    });

    var link_css = {
        color  : "#3c8dbc",
        cursor : "pointer"
    };

    $(".l-1 .key1").css(link_css);
    $(".l-2 .key2").css(link_css);
    $(".l-1 .key1").on("click",function(){
        var $this      = $(this);
        var class_name = $this.data("class_name");
        if ($this.data("show") ==true) {
            $(".key2."+class_name ).parent().hide();
        }else{
            var $opt_item=$(".key2."+class_name ).parent(".l-2");
            $opt_item.show();
        }
        $this.parent().show();
        $this.data("show", !$this.data("show") );
    });

    $(".l-2 .key2").on("click",function(){
        var $this      = $(this);
        var class_name = $this.data("class_name");
        if ($this.data("show") ==true) {
            $(".key3."+class_name ).parent().hide();
        }else{
            var $opt_item=$(".key3."+class_name ).parent(".l-3");
            $opt_item.show();
        }
        $this.parent().show();
        $this.data("show", !$this.data("show") );
    });

    $(".l-3 .key3").on("click",function(){
        var $this      = $(this);
        var class_name = $this.data("class_name");
        if ($this.data("show") ==true) {
            $(".key4."+class_name ).parent().hide();
        }else{
            var $opt_item=$(".key4."+class_name ).parent(".l-4");
            $opt_item.show();
        }
        $this.parent().show();
        $this.data("show", !$this.data("show") );
    });

	//时间控件
	$('#id_start_time').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
	    onChangeDateTime :function(){
		    load_data();
        }
	});
    
	$('#id_end_time').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
		onChangeDateTime :function(){
		    load_data();
        }
	});
    
	$(".opt-change").on("change",function(){
		load_data();
	});

    $.admin_select_user( $("#id_teacherid"), "teacher",  load_data, true) ;
    $.admin_select_user( $("#id_studentid"), "student",  load_data, false) ;

    $("#id_reset_already_lesson_count").on("click",function(){
        $.do_ajax("/user_deal/reset_already_lesson_count",{
            "teacherid"  : $("#id_teacherid").val(),
            "start_time" : $("#id_start_time").val(),
            "end_time"   : $("#id_end_time").val()
        });
    });

    $(".opt-div").each(function() {
        var $this=$(this) ;
        if (!$this.data("lessonid")) {
            $(this).hide();
        }
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

    $(".opt-add_reward").on("click",function(){
	      var data            = $(this).get_opt_data();
        var id_reward_type  = $("<select/>");
        var id_reward_money = $("<input/>");

        Enum_map.append_option_list("reward_type",id_reward_type,true,[2,3]);
        var arr = [
            ["奖励类型",id_reward_type],
            ["奖励金额",id_reward_money],
        ];
        $.show_key_value_table("添加奖励",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/teacher_money/add_teacher_reward",{
                    "money_info" : data.lessonid,
                    "type"       : id_reward_type.val(),
                    "teacherid"  : teacherid,
                    "money"      : id_reward_money.val()*100
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                });
            }
        });
    });


    $(".opt-reset_lesson").on("click",function(){
	      var data = $(this).get_opt_data();

        BootstrapDialog.show({
	          title   : "重置本节课的老师工资类型和等级",
	          message : "确认重置本节课的老师工资？",
	          buttons : [{
		            label  : "返回",
		            action : function(dialog) {
			              dialog.close();
		            }
	          }, {
		            label    : "确认",
		            cssClass : "btn-warning",
		            action   : function(dialog) {
                    $.do_ajax("/teacher_money/reset_lesson_reward",{
                        "lessonid" : data.lessonid,
                    },function(result){
                        if(result.ret==0){
                            window.location.reload();
                        }else{
                            BootstrapDialog.alert(result.info);
                        }
                    })
		            }
	          }]
        });
    });

    $(".teacher_reward_list").on("click",function(){
        var teacherid  = g_args.teacherid;
        var start_time = g_args.start_time;
        var end_time   = g_args.end_time;
        var url = "/user_manage_new/teacher_trial_reward_list?opt_date_type=3&teacherid="+teacherid
            +"&start_time="+start_time+"&end_time="+end_time;

        window.open(url,"_blank");
    });

    $(".opt-show-log").on("click",function(){
        var data = $(this).get_opt_data();

        $.do_ajax("/lesson_manage/get_lesson_operate_info",{
            "lessonid":data.lessonid
        },function(result){
            if(result.ret==0){
                window.location.reload();
            }else{
                BootstrapDialog.alert(result.info);
            }
        })
    });

    $(".opt-update-log").on("click",function(){
        var data                  = $(this).get_opt_data();
        var id_level              = $("<select>");
        var id_grade              = $("<select>");
        var id_teacher_money_type = $("<select>");
        var id_teacher_type       = $("<select>");
        var id_lesson_count       = $("<input>");
        if(data.teacher_money_type==6){
            Enum_map.append_option_list("new_level",id_level,true);
        }else{
            Enum_map.append_option_list("level",id_level,true);
        }
        Enum_map.append_option_list_by_not_id("grade",id_grade,true,[0,100,200,300]);
        Enum_map.append_option_list("teacher_money_type",id_teacher_money_type,true,[0,6,7]);
        Enum_map.append_option_list("teacher_type",id_teacher_type,true);
        id_level.val(data.tea_level_num);
        id_grade.val(data.grade);
        id_teacher_money_type.val(data.teacher_money_type);
        id_teacher_type.val(data.teacher_type);
        id_lesson_count.val(data.lesson_count);

        var arr = [
            ["----","更改老师工资类型后，需要重新设置课程的等级"],
            ["课程上的老师工资类型",id_teacher_money_type],
            ["课程等级",id_level],
            ["课程上的老师类型",id_teacher_type],
            ["课程年级",id_grade],
            ["课程课时",id_lesson_count],
        ];

        $.show_key_value_table("修改课程信息",arr,{
            label    : "确认",
            cssClass : "btn-warning",
            action   : function(dialog) {
                $.do_ajax("/lesson_manage/change_lesson_info",{
                    "lessonid"           : data.lessonid,
                    "teacher_money_type" : id_teacher_money_type.val(),
                    "level"              : id_level.val(),
                    "teacher_type"       : id_teacher_type.val(),
                    "grade"              : id_grade.val(),
                    "lesson_count"       : id_lesson_count.val(),
                },function(result){
                    if(result.ret==0){
                        window.location.reload();
                    }else{
                        BootstrapDialog.alert(result.info);
                    }
                })
            }
        },function(){
            var check_teacher_money_type = function(){
                var teacher_money_type = id_teacher_money_type.val();
                id_level.empty();
                if(teacher_money_type==6){
                    Enum_map.append_option_list("new_level",id_level,true);
                }else{
                    Enum_map.append_option_list("level",id_level,true);
                }
                if(teacher_money_type==data.teacher_money_type){
                    id_level.val(data.tea_level_num);
                }
            }

            id_teacher_money_type.on("click",function(){
                check_teacher_money_type();
            });

        });

    });


});
