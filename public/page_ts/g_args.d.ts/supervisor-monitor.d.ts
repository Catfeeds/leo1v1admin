interface GargsStatic {
	date:	string;
	st_application_nick:	string;
	require_adminid:	number;
	test_seller_id:	number;
	seller_flag:	number;
	userid:	number;
	teacherid:	number;
	run_flag:	number;
	assistantid:	number;
	group_type:	number;
	self_groupid:	number;
	is_group_leader_flag:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	lessonid	:any;
	require_adminid	:any;
	account	:any;
	userid	:any;
	teacherid	:any;
	assistantid	:any;
	lesson_start	:any;
	lesson_end	:any;
	courseid	:any;
	lesson_type	:any;
	lesson_num	:any;
	current_server	:any;
	server_type	:any;
	xmpp_server_name	:any;
	index	:any;
	region	:any;
	ip	:any;
	port	:any;
	room_id	:any;
	lesson_time	:any;
	lesson_type_str	:any;
	assistant_nick	:any;
	teacher_nick	:any;
	student_nick	:any;
	server_type_str	:any;
}

/*

tofile: 
	 mkdir -p ../supervisor; vi  ../supervisor/monitor.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/supervisor-monitor.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date:	$('#id_date').val(),
			st_application_nick:	$('#id_st_application_nick').val(),
			require_adminid:	$('#id_require_adminid').val(),
			test_seller_id:	$('#id_test_seller_id').val(),
			seller_flag:	$('#id_seller_flag').val(),
			userid:	$('#id_userid').val(),
			teacherid:	$('#id_teacherid').val(),
			run_flag:	$('#id_run_flag').val(),
			assistantid:	$('#id_assistantid').val(),
			group_type:	$('#id_group_type').val(),
			self_groupid:	$('#id_self_groupid').val(),
			is_group_leader_flag:	$('#id_is_group_leader_flag').val()
        });
    }


	$('#id_date').val(g_args.date);
	$('#id_st_application_nick').val(g_args.st_application_nick);
	$('#id_require_adminid').val(g_args.require_adminid);
	$('#id_test_seller_id').val(g_args.test_seller_id);
	$('#id_seller_flag').val(g_args.seller_flag);
	$('#id_userid').val(g_args.userid);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_run_flag').val(g_args.run_flag);
	$('#id_assistantid').val(g_args.assistantid);
	$('#id_group_type').val(g_args.group_type);
	$('#id_self_groupid').val(g_args.self_groupid);
	$('#id_is_group_leader_flag').val(g_args.is_group_leader_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">date</span>
                <input class="opt-change form-control" id="id_date" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">st_application_nick</span>
                <input class="opt-change form-control" id="id_st_application_nick" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">require_adminid</span>
                <input class="opt-change form-control" id="id_require_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_seller_id</span>
                <input class="opt-change form-control" id="id_test_seller_id" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_flag</span>
                <input class="opt-change form-control" id="id_seller_flag" />
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
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">run_flag</span>
                <input class="opt-change form-control" id="id_run_flag" />
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
                <span class="input-group-addon">group_type</span>
                <input class="opt-change form-control" id="id_group_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">self_groupid</span>
                <input class="opt-change form-control" id="id_self_groupid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_group_leader_flag</span>
                <input class="opt-change form-control" id="id_is_group_leader_flag" />
            </div>
        </div>
*/
