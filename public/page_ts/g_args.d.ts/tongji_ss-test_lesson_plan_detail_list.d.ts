interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	page_num:	number;
	page_count:	number;
	set_lesson_adminid:	number;
	subject:	number;//App\Enums\Esubject
	grade:	number;//App\Enums\Egrade
	success_flag:	number;//App\Enums\Eset_boolean
	test_lesson_fail_flag:	number;//App\Enums\Etest_lesson_fail_flag
	userid:	number;
	require_admin_type:	number;//App\Enums\Eaccount_role
	require_adminid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	set_lesson_adminid	:any;
	require_adminid	:any;
	lesson_start	:any;
	userid	:any;
	teacherid	:any;
	subject	:any;
	phone	:any;
	nick	:any;
	grade	:any;
	success_flag	:any;
	test_lesson_fail_flag	:any;
	fail_reason	:any;
	teacher_nick	:any;
	require_admin_nick	:any;
	set_lesson_admin_nick	:any;
	test_lesson_fail_flag_str	:any;
	subject_str	:any;
	grade_str	:any;
	success_flag_str	:any;
}

/*

tofile: 
	 mkdir -p ../tongji_ss; vi  ../tongji_ss/test_lesson_plan_detail_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-test_lesson_plan_detail_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			set_lesson_adminid:	$('#id_set_lesson_adminid').val(),
			subject:	$('#id_subject').val(),
			grade:	$('#id_grade').val(),
			success_flag:	$('#id_success_flag').val(),
			test_lesson_fail_flag:	$('#id_test_lesson_fail_flag').val(),
			userid:	$('#id_userid').val(),
			require_admin_type:	$('#id_require_admin_type').val(),
			require_adminid:	$('#id_require_adminid').val()
        });
    }

	Enum_map.append_option_list("subject",$("#id_subject"));
	Enum_map.append_option_list("grade",$("#id_grade"));
	Enum_map.append_option_list("set_boolean",$("#id_success_flag"));
	Enum_map.append_option_list("test_lesson_fail_flag",$("#id_test_lesson_fail_flag"));
	Enum_map.append_option_list("account_role",$("#id_require_admin_type"));

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
	$('#id_set_lesson_adminid').val(g_args.set_lesson_adminid);
	$('#id_subject').val(g_args.subject);
	$('#id_grade').val(g_args.grade);
	$('#id_success_flag').val(g_args.success_flag);
	$('#id_test_lesson_fail_flag').val(g_args.test_lesson_fail_flag);
	$('#id_userid').val(g_args.userid);
	$('#id_require_admin_type').val(g_args.require_admin_type);
	$('#id_require_adminid').val(g_args.require_adminid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">set_lesson_adminid</span>
                <input class="opt-change form-control" id="id_set_lesson_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">科目</span>
                <select class="opt-change form-control" id="id_subject" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">年级</span>
                <select class="opt-change form-control" id="id_grade" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">set_boolean</span>
                <select class="opt-change form-control" id="id_success_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">出错类型</span>
                <select class="opt-change form-control" id="id_test_lesson_fail_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">userid</span>
                <input class="opt-change form-control" id="id_userid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account_role</span>
                <select class="opt-change form-control" id="id_require_admin_type" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">require_adminid</span>
                <input class="opt-change form-control" id="id_require_adminid" />
            </div>
        </div>
*/
