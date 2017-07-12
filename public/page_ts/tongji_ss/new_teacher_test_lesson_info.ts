/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-new_teacher_test_lesson_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			have_test_lesson_flag:	$('#id_have_test_lesson_flag').val(),
		    subject:	$('#id_subject').val(),
			grade_part_ex:	$('#id_grade_part_ex').val(),
            train_through_new:	$('#id_train_through_new').val(),
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
    Enum_map.append_option_list("subject", $("#id_subject") );
    Enum_map.append_option_list("grade_part_ex", $("#id_grade_part_ex") );
	$('#id_have_test_lesson_flag').val(g_args.have_test_lesson_flag);
	$('#id_subject').val(g_args.subject);
	$('#id_grade_part_ex').val(g_args.grade_part_ex);
	$('#id_train_through_new').val(g_args.train_through_new);


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

    init_noit_btn("id_have_lesson",    "有试听课老师人数" );
    init_noit_btn("id_no_lesson",    "无试听课老师人数" );
    init_noit_btn("id_all_tea",    "老师总量" );
    init_noit_btn("id_all_lesson",    "总试听数" );
    init_noit_btn("id_train_through_count",    "培训通过人数" );
    init_noit_btn("id_all_interview_count",    "面试通过人数" );
    $("#id_have_lesson").on("click",function(){
       
        $('#id_have_test_lesson_flag').val(1);
        load_data();
    });
    $("#id_no_lesson").on("click",function(){
        $('#id_have_test_lesson_flag').val(0);
        load_data();
    });


	$('.opt-change').set_input_change_event(load_data);
});
