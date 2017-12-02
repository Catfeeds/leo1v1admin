interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	subject:	number;
	account_role:	number;
	kpi_flag:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	name	:any;
	test_first_per_str	:any;
	test_five_per_str	:any;
	regular_first_per_str	:any;
	regular_five_per_str	:any;
	real_num	:any;
	suc_count	:any;
	train_first_all	:any;
	train_first_pass	:any;
	train_second_all	:any;
	test_first	:any;
	test_five	:any;
	regular_first	:any;
	regular_five	:any;
	all_num	:any;
	per	:any;
	all_target_num	:any;
}

/*

tofile: 
	 mkdir -p ../main_page; vi  ../main_page/quality_control.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/main_page-quality_control.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			subject:	$('#id_subject').val(),
			account_role:	$('#id_account_role').val(),
			kpi_flag:	$('#id_kpi_flag').val()
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
	$('#id_subject').val(g_args.subject);
	$('#id_account_role').val(g_args.account_role);
	$('#id_kpi_flag').val(g_args.kpi_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">subject</span>
                <input class="opt-change form-control" id="id_subject" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account_role</span>
                <input class="opt-change form-control" id="id_account_role" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">kpi_flag</span>
                <input class="opt-change form-control" id="id_kpi_flag" />
            </div>
        </div>
*/
