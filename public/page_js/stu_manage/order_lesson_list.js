/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-order_lesson_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			sid              : g_sid,
			competition_flag : $('#id_competition_flag').val()
        });
    }
	$('#id_competition_flag').val(g_args.competition_flag);
	$('.opt-change').set_input_change_event(load_data);
    
    $(".opt-confirm").on("click",function(){
        var opt_data = $(this).get_opt_data();
        var lessonid = $(this).get_opt_data("lessonid");
        var $confirm_flag = $("<select> </select>");
        var $lesson_cancel_reason_type = $("<select> </select>");
        var $lesson_cancel_reason_next_lesson_time = $("<input/>");
        var $confirm_reason = $("<textarea/> ");

        Enum_map.append_option_list( "confirm_flag", $confirm_flag,true);
        Enum_map.append_option_list( "lesson_cancel_reason_type", $lesson_cancel_reason_type,true);
        
        var arr=[
            ["上课完成", $confirm_flag ] ,
            ["无效类型", $lesson_cancel_reason_type   ] ,
            ["调课时间",$lesson_cancel_reason_next_lesson_time  ],
            ["无效说明", $confirm_reason ] 
        ];

        $confirm_flag.val( opt_data.confirm_flag )  ;
        $confirm_reason.val( opt_data.confirm_reason )  ;
        $lesson_cancel_reason_next_lesson_time.val( opt_data.lesson_cancel_reason_next_lesson_time )  ;
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
            }else{
                show_field( $confirm_reason ,true);
                show_field( $lesson_cancel_reason_type,true);
                var reason_type= $lesson_cancel_reason_type.val();
                if ( reason_type >0  && reason_type <10 ) {
                    show_field( $lesson_cancel_reason_next_lesson_time,true);
                }else{
                    show_field( $lesson_cancel_reason_next_lesson_time ,false);
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
                    "lesson_cancel_reason_type":$lesson_cancel_reason_type.val()
                });
            }
        } ,function(){
            reset_ui();
            $lesson_cancel_reason_next_lesson_time.datetimepicker({
                datepicker:true,
                timepicker:true,
                format:'Y-m-d H:i',
                step:30,
            });
		});
    });

});


