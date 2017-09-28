interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	teacherid:	number;
	teacher_money_type:	number;
	level:	number;
	is_test_user:	number;
	page_num:	number;
	page_count:	number;
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
	teacher_money_type	:any;
	level	:any;
	test_transfor_per	:any;
	create_time	:any;
	realname	:any;
	all_grade	:any;
	all_subject	:any;
	stu_num	:any;
	trial_lesson_count	:any;
	normal_lesson_count	:any;
	teacher_money_type_str	:any;
	level_str	:any;
	create_time_str	:any;
	all_lesson_count	:any;
	grade_str	:any;
	subject_str	:any;
}

/*

tofile: 
	 mkdir -p ../human_resource; vi  ../human_resource/teacher_total_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-teacher_total_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			teacherid:	$('#id_teacherid').val(),
			teacher_money_type:	$('#id_teacher_money_type').val(),
			level:	$('#id_level').val(),
			is_test_user:	$('#id_is_test_user').val()
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
	$('#id_teacher_money_type').val(g_args.teacher_money_type);
	$('#id_level').val(g_args.level);
	$('#id_is_test_user').val(g_args.is_test_user);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher_money_type</span>
                <input class="opt-change form-control" id="id_teacher_money_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">level</span>
                <input class="opt-change form-control" id="id_level" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_test_user</span>
                <input class="opt-change form-control" id="id_is_test_user" />
            </div>
        </div>
*/
