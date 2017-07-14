interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	page_num:	number;
	page_count:	number;
	test_lesson_flag:	number;//App\Enums\Eboolean
	l_1v1_flag:	number;//App\Enums\Eboolean
	tutor_subject:	number;//App\Enums\Esubject
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	teacherid	:any;
	nick	:any;
	create_time	:any;
	tutor_subject	:any;
	test_lesson_count	:any;
	l_1v1_count	:any;
	tutor_subject_str	:any;
}

/*

tofile: 
	 mkdir -p ../tongji_ss; vi  ../tongji_ss/teacher_test_lesson_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-teacher_test_lesson_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			test_lesson_flag:	$('#id_test_lesson_flag').val(),
			l_1v1_flag:	$('#id_l_1v1_flag').val(),
			tutor_subject:	$('#id_tutor_subject').val()
        });
    }

	Enum_map.append_option_list("boolean",$("#id_test_lesson_flag"));
	Enum_map.append_option_list("boolean",$("#id_l_1v1_flag"));
	Enum_map.append_option_list("subject",$("#id_tutor_subject"));

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
	$('#id_test_lesson_flag').val(g_args.test_lesson_flag);
	$('#id_l_1v1_flag').val(g_args.l_1v1_flag);
	$('#id_tutor_subject').val(g_args.tutor_subject);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_test_lesson_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_l_1v1_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">科目</span>
                <select class="opt-change form-control" id="id_tutor_subject" >
                </select>
            </div>
        </div>
*/
