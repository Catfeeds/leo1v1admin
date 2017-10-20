interface GargsStatic {
	id:	number;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	page_num:	number;
	page_count:	number;
	adminid:	number;
	accept_adminid:	number;
	accept_adminid_flag:	string;
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
	id	:any;
	add_time	:any;
	adminid	:any;
	userid	:any;
	teacherid	:any;
	subject	:any;
	grade	:any;
	textbook	:any;
	stu_score_info	:any;
	stu_character_info	:any;
	type	:any;
	lessonid	:any;
	record_info	:any;
	record_info_url	:any;
	stu_request_test_lesson_demand	:any;
	record_scheme	:any;
	accept_adminid	:any;
	accept_time	:any;
	realname	:any;
	nick	:any;
	account	:any;
	record_scheme_url	:any;
	is_change_teacher	:any;
	tea_time	:any;
	is_done_flag	:any;
	done_time	:any;
	is_resubmit_flag	:any;
	accept_account	:any;
	id_index	:any;
	add_time_str	:any;
	accept_time_str	:any;
	done_time_str	:any;
	subject_str	:any;
	grade_str	:any;
	is_change_teacher_str	:any;
	rurl	:any;
	surl	:any;
	is_done_flag_str	:any;
}

/*

tofile: 
	 mkdir -p ../tea_manage_new; vi  ../tea_manage_new/get_seller_ass_record_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tea_manage_new-get_seller_ass_record_info.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		id:	$('#id_id').val(),
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		adminid:	$('#id_adminid').val(),
		accept_adminid:	$('#id_accept_adminid').val(),
		accept_adminid_flag:	$('#id_accept_adminid_flag').val(),
		require_adminid:	$('#id_require_adminid').val()
    });
}
$(function(){


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
	$('#id_id').val(g_args.id);
	$('#id_adminid').val(g_args.adminid);
	$('#id_accept_adminid').val(g_args.accept_adminid);
	$('#id_accept_adminid_flag').val(g_args.accept_adminid_flag);
	$('#id_require_adminid').val(g_args.require_adminid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">id</span>
                <input class="opt-change form-control" id="id_id" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">adminid</span>
                <input class="opt-change form-control" id="id_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">accept_adminid</span>
                <input class="opt-change form-control" id="id_accept_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">accept_adminid_flag</span>
                <input class="opt-change form-control" id="id_accept_adminid_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">require_adminid</span>
                <input class="opt-change form-control" id="id_require_adminid" />
            </div>
        </div>
*/
