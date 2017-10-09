interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	teacher_ref_type:	number;
	teacher_money_type:	number;
	level:	number;
	show_data:	number;
	show_type:	string;
	reference:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	teacherid	:any;
	tea_nick	:any;
	subject	:any;
	create_time	:any;
	teacher_money_type	:any;
	level	:any;
	teacher_money_flag	:any;
	teacher_ref_type	:any;
	test_transfor_per	:any;
	lesson_1v1	:any;
	lesson_trial	:any;
	lesson_total	:any;
	teacher_money_type_str	:any;
	level_str	:any;
	subject_str	:any;
	create_time_str	:any;
	id	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/tea_wages_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-tea_wages_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			teacher_ref_type:	$('#id_teacher_ref_type').val(),
			teacher_money_type:	$('#id_teacher_money_type').val(),
			level:	$('#id_level').val(),
			show_data:	$('#id_show_data').val(),
			show_type:	$('#id_show_type').val(),
			reference:	$('#id_reference').val()
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
	$('#id_teacher_ref_type').val(g_args.teacher_ref_type);
	$('#id_teacher_money_type').val(g_args.teacher_money_type);
	$('#id_level').val(g_args.level);
	$('#id_show_data').val(g_args.show_data);
	$('#id_show_type').val(g_args.show_type);
	$('#id_reference').val(g_args.reference);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher_ref_type</span>
                <input class="opt-change form-control" id="id_teacher_ref_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher_money_type</span>
                <input class="opt-change form-control" id="id_teacher_money_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">level</span>
                <input class="opt-change form-control" id="id_level" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">show_data</span>
                <input class="opt-change form-control" id="id_show_data" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">show_type</span>
                <input class="opt-change form-control" id="id_show_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">reference</span>
                <input class="opt-change form-control" id="id_reference" />
            </div>
        </div>
*/
