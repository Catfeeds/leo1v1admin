interface GargsStatic {
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
	app_num	:any;
	interview_num	:any;
	interview_pass_num	:any;
	interview_trial_num	:any;
	interview_trial_pass_num	:any;
	teacher_ref_type	:any;
	reference	:any;
	level	:any;
	teacher_ref_type_class	:any;
	realname	:any;
	teacher_ref_type_str	:any;
	interview_per	:any;
	interview_pass_per	:any;
	interview_trial_pass_per	:any;
}

/*

tofile: 
	 mkdir -p ../human_resource; vi  ../human_resource/zs_origin_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-zs_origin_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val()
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


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
*/
