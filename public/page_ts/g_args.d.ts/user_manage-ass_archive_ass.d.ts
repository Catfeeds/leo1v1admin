interface GargsStatic {
	test_user:	number;
	originid:	number;
	grade:	number;//App\Enums\Egrade 
	user_name:	string;
	phone:	string;
	teacherid:	number;
	student_type:	number;
	assistantid:	number;
	page_num:	number;
	userid:	number;
	revisit_flag:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
interface RowData {
	userid	:any;
	nick	:any;
	phone	:any;
	is_test_user	:any;
	originid	:any;
	grade	:any;
	praise	:any;
	assistantid	:any;
	parent_name	:any;
	parent_type	:any;
	last_login_ip	:any;
	last_login_time	:any;
	lesson_count_all	:any;
	lesson_count_left	:any;
	user_agent	:any;
	type	:any;
	ass_revisit_last_month_time	:any;
	ass_revisit_last_week_time	:any;
	user_agent_simple	:any;
	assistant_nick	:any;
	ass_revisit_week_flag	:any;
	ass_revisit_month_flag	:any;
	ass_revisit_week_flag_str	:any;
	ass_revisit_month_flag_str	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage ; vi  ../user_manage/ass_archive_ass.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-ass_archive_ass.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			test_user:	$('#id_test_user').val(),
			originid:	$('#id_originid').val(),
			grade:	$('#id_grade').val(),
			user_name:	$('#id_user_name').val(),
			phone:	$('#id_phone').val(),
			teacherid:	$('#id_teacherid').val(),
			student_type:	$('#id_student_type').val(),
			assistantid:	$('#id_assistantid').val(),
			userid:	$('#id_userid').val(),
			revisit_flag:	$('#id_revisit_flag').val()
        });
    }

	Enum_map.append_option_list("grade",$("#id_grade")); 

	$('#id_test_user').val(g_args.test_user);
	$('#id_originid').val(g_args.originid);
	$('#id_grade').val(g_args.grade);
	$('#id_user_name').val(g_args.user_name);
	$('#id_phone').val(g_args.phone);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_student_type').val(g_args.student_type);
	$('#id_assistantid').val(g_args.assistantid);
	$('#id_userid').val(g_args.userid);
	$('#id_revisit_flag').val(g_args.revisit_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_user</span>
                <input class="opt-change form-control" id="id_test_user" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">originid</span>
                <input class="opt-change form-control" id="id_originid" />
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
                <span class="input-group-addon">user_name</span>
                <input class="opt-change form-control" id="id_user_name" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone</span>
                <input class="opt-change form-control" id="id_phone" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">student_type</span>
                <input class="opt-change form-control" id="id_student_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
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
                <span class="input-group-addon">revisit_flag</span>
                <input class="opt-change form-control" id="id_revisit_flag" />
            </div>
        </div>
*/
