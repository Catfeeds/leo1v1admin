interface GargsStatic {
	order_by_str:	string;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	st_application_nick	:any;
	all_count	:any;
	bad_count	:any;
	before_4_bad_count	:any;
	succ_count	:any;
	after_4_bak_count	:any;
}

/*

tofile: 
	 mkdir -p ../tongji; vi  ../tongji/test_lesson_tongi.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji-test_lesson_tongi.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			order_by_str:	$('#id_order_by_str').val(),
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val()
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
	$('#id_order_by_str').val(g_args.order_by_str);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_by_str</span>
                <input class="opt-change form-control" id="id_order_by_str" />
            </div>
        </div>
*/
