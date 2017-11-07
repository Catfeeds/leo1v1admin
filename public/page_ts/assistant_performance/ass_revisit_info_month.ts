/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/assistant_performance-ass_revisit_info_month.d.ts" />

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

    $.do_ajax("/user_manage_new/get_last_change_type_info",{
        "userid" : userid
    },function(result){
        var data = result.data;
        if(data==false){
        }else{
            lesson_stop_reason = data.reason;
            student_type = data.type_cur;
            recover_time = data.recover_time;
            wx_remind_time = data.wx_remind_time;
            stop_duration = data.stop_duration;
        }

        var id_auto_set_flag = $("<select ><option value=\"0\">系统自动更新</option><option value=\"1\">手动修改</option></select>");
        var id_student_type = $("<select />");
        var id_lesson_stop_reason = $("<textarea />");
        var id_recover_time = $("<input />");
        var id_wx_remind_time = $("<input />");
        var id_stop_duration = $("<input />");
        Enum_map.append_option_list( "student_type",  id_student_type,true,[0,1,2,3,4]);
        id_recover_time.datetimepicker({
            datepicker:true,
            timepicker:false,
            format:'Y-m-d ',
            step:30,
            onChangeDateTime :function(){

            }
        });

        id_wx_remind_time.datetimepicker({
            datepicker:true,
            timepicker:false,
            format:'Y-m-d',
            step:30,
            onChangeDateTime :function(){

            }
        });


        var arr = [
            // [ "是否系统自动更新：",  id_auto_set_flag] ,
            [ "学员类型",  id_student_type] ,
            [ "原因",  id_lesson_stop_reason] ,
            ["时长",  id_stop_duration ],
            ["预计复课时间",  id_recover_time ],
            ["微信提醒时间",  id_wx_remind_time ],
        ];
        id_auto_set_flag.val(is_auto_set_type_flag);
        id_student_type.val(student_type);
        id_lesson_stop_reason.val(lesson_stop_reason);
        id_stop_duration.val(stop_duration);
        id_recover_time.val(recover_time);
        id_wx_remind_time.val(wx_remind_time);

        var show_field=function (jobj,show_flag) {
            if ( show_flag ) {
                jobj.parent().parent().show();
            }else{
                jobj.parent().parent().hide();
            }
        };

        var reset_ui=function() {
            var val=id_student_type.val();
            if (val>1) {
                show_field( id_recover_time ,true);
                show_field( id_stop_duration,true);
                show_field( id_wx_remind_time,true);
            }else{
                show_field( id_recover_time ,false );
                show_field( id_stop_duration,false );
                show_field( id_wx_remind_time,false);
            }



        };

        id_student_type.on("change",function(){
            reset_ui();
        });


        $.show_key_value_table("修改类型", arr ,{
            label    : '确认',
            cssClass : 'btn-warning',
            action   : function(dialog) {
                $.ajax({
                    type     :"post",
                    url      :"/user_manage/set_stu_type",
                    dataType :"json",
                    data     :{
                        "userid":userid,
                        "type":id_student_type.val(),
                        "is_auto_set_type_flag":1,
                        "lesson_stop_reason":id_lesson_stop_reason.val(),
                        "recover_time"  :id_recover_time.val(),
                        "wx_remind_time"  :id_wx_remind_time.val(),
                        "stop_duration" :id_stop_duration.val()
                    },
                    success  : function(result){
                        if(result['ret'] != 0){
                            alert(result['info']);
                        }else{
                            window.location.reload();
                        }
                    }
                });

            }
        },function(){
            reset_ui();
        });

    });



	$('.opt-change').set_input_change_event(load_data);
});


