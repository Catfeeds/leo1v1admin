/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage-robot_open_class.d.ts" />
$(function(){
    function load_data(){
        $.reload_self_page ( {
			start_date    :	$('#id_start_date').val(),
			end_date      :	$('#id_end_date').val(),
			teacherid     :	$('#id_search_teacher').val(),
			lesson_status :	$('#id_lesson_status').val()
        });
    }

	$('#id_start_date').val(g_args.start_date);
	$('#id_end_date').val(g_args.end_date);
	$('#id_search_teacher').val(g_args.teacherid);
	$('#id_lesson_status').val(g_args.lesson_status);

    $("#id_search_teacher").admin_select_user({
        "type"     : "teacher",
        "onChange" : function(){
            load_data();
        }
    });

	//时间控件
	$('#id_date_start').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
		onChangeDateTime :function(){
            load_data();
		}
	});
	$('#id_date_end').datetimepicker({
		lang:'ch',
		timepicker:false,
		format:'Y-m-d',
        onChangeDateTime :function(){
            load_data();
		}
	});

	$('.opt-change').set_input_change_event(load_data);
});
