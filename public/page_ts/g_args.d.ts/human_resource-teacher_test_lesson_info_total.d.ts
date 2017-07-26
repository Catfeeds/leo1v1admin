interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	teacherid:	number;
	subject:	number;
	teacher_subject:	number;
	identity:	number;
	grade_part_ex:	number;
	tea_status:	number;
	teacher_account:	number;
	fulltime_flag:	number;
	fulltime_teacher_type:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
}

/*

tofile: 
	 mkdir -p ../human_resource; vi  ../human_resource/teacher_test_lesson_info_total.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-teacher_test_lesson_info_total.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			teacherid:	$('#id_teacherid').val(),
			subject:	$('#id_subject').val(),
			teacher_subject:	$('#id_teacher_subject').val(),
			identity:	$('#id_identity').val(),
			grade_part_ex:	$('#id_grade_part_ex').val(),
			tea_status:	$('#id_tea_status').val(),
			teacher_account:	$('#id_teacher_account').val(),
			fulltime_flag:	$('#id_fulltime_flag').val(),
			fulltime_teacher_type:	$('#id_fulltime_teacher_type').val()
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
	$('#id_subject').val(g_args.subject);
	$('#id_teacher_subject').val(g_args.teacher_subject);
	$('#id_identity').val(g_args.identity);
	$('#id_grade_part_ex').val(g_args.grade_part_ex);
	$('#id_tea_status').val(g_args.tea_status);
	$('#id_teacher_account').val(g_args.teacher_account);
	$('#id_fulltime_flag').val(g_args.fulltime_flag);
	$('#id_fulltime_teacher_type').val(g_args.fulltime_teacher_type);


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
                <span class="input-group-addon">subject</span>
                <input class="opt-change form-control" id="id_subject" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher_subject</span>
                <input class="opt-change form-control" id="id_teacher_subject" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">identity</span>
                <input class="opt-change form-control" id="id_identity" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade_part_ex</span>
                <input class="opt-change form-control" id="id_grade_part_ex" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">tea_status</span>
                <input class="opt-change form-control" id="id_tea_status" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher_account</span>
                <input class="opt-change form-control" id="id_teacher_account" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">fulltime_flag</span>
                <input class="opt-change form-control" id="id_fulltime_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">fulltime_teacher_type</span>
                <input class="opt-change form-control" id="id_fulltime_teacher_type" />
            </div>
        </div>
*/
