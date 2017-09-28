interface GargsStatic {
	start_date:	string;
	end_date:	string;
	test_user:	number;
	originid:	number;
	grade:	number;//App\Enums\Egrade
	user_name:	string;
	phone:	string;
	teacherid:	number;
	assistantid:	number;
	revisit_assistantid:	number;
	page_num:	number;
	page_count:	number;
	userid:	number;
	revisit_type:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	userid	:any;
	revisit_type	:any;
	assistantid	:any;
	revisit_time	:any;
	operator_note	:any;
	sys_operator	:any;
	nick	:any;
	phone	:any;
	originid	:any;
	grade	:any;
	originid_str	:any;
	assistant_nick	:any;
	revisit_type_str	:any;
	grade_str	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage; vi  ../user_manage/ass_counts.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-ass_counts.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			start_date:	$('#id_start_date').val(),
			end_date:	$('#id_end_date').val(),
			test_user:	$('#id_test_user').val(),
			originid:	$('#id_originid').val(),
			grade:	$('#id_grade').val(),
			user_name:	$('#id_user_name').val(),
			phone:	$('#id_phone').val(),
			teacherid:	$('#id_teacherid').val(),
			assistantid:	$('#id_assistantid').val(),
			revisit_assistantid:	$('#id_revisit_assistantid').val(),
			userid:	$('#id_userid').val(),
			revisit_type:	$('#id_revisit_type').val()
        });
    }

	Enum_map.append_option_list("grade",$("#id_grade"));

	$('#id_start_date').val(g_args.start_date);
	$('#id_end_date').val(g_args.end_date);
	$('#id_test_user').val(g_args.test_user);
	$('#id_originid').val(g_args.originid);
	$('#id_grade').val(g_args.grade);
	$('#id_user_name').val(g_args.user_name);
	$('#id_phone').val(g_args.phone);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_assistantid').val(g_args.assistantid);
	$('#id_revisit_assistantid').val(g_args.revisit_assistantid);
	$('#id_userid').val(g_args.userid);
	$('#id_revisit_type').val(g_args.revisit_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">start_date</span>
                <input class="opt-change form-control" id="id_start_date" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">end_date</span>
                <input class="opt-change form-control" id="id_end_date" />
            </div>
        </div>

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
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">revisit_assistantid</span>
                <input class="opt-change form-control" id="id_revisit_assistantid" />
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
                <span class="input-group-addon">revisit_type</span>
                <input class="opt-change form-control" id="id_revisit_type" />
            </div>
        </div>
*/
