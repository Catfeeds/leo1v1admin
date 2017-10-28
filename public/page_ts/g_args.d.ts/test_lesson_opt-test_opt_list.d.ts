interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	test_lesson_type:	number;
	action:	number;
	test_opt_type:	number;
	adminid:	number;
	user_name:	string;
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
}

/*

tofile: 
	 mkdir -p ../test_lesson_opt; vi  ../test_lesson_opt/test_opt_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test_lesson_opt-test_opt_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		test_lesson_type:	$('#id_test_lesson_type').val(),
		action:	$('#id_action').val(),
		test_opt_type:	$('#id_test_opt_type').val(),
		adminid:	$('#id_adminid').val(),
		user_name:	$('#id_user_name').val()
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
	$('#id_test_lesson_type').val(g_args.test_lesson_type);
	$('#id_action').val(g_args.action);
	$('#id_test_opt_type').val(g_args.test_opt_type);
	$('#id_adminid').val(g_args.adminid);
	$('#id_user_name').val(g_args.user_name);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_lesson_type</span>
                <input class="opt-change form-control" id="id_test_lesson_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">action</span>
                <input class="opt-change form-control" id="id_action" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_opt_type</span>
                <input class="opt-change form-control" id="id_test_opt_type" />
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
                <span class="input-group-addon">user_name</span>
                <input class="opt-change form-control" id="id_user_name" />
            </div>
        </div>
*/
