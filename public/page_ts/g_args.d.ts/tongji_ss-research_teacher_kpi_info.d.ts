interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	type_flag:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	account	:any;
	uid	:any;
	interview_time_avg	:any;
	record_time_avg	:any;
	other_record_time_avg	:any;
	interview_per	:any;
	first_per	:any;
	next_per	:any;
	first_next_per	:any;
	add_per	:any;
	lesson_per	:any;
	lesson_per_other	:any;
	lesson_per_kk	:any;
	lesson_per_change	:any;
	lesson_num_per	:any;
	last_interview_per	:any;
	last_first_per	:any;
	last_add_per	:any;
	last_interview_lesson	:any;
	last_interview_order	:any;
	last_first_lesson	:any;
	last_first_order	:any;
	last_first_next_per	:any;
	last_next_per	:any;
}

/*

tofile: 
	 mkdir -p ../tongji_ss; vi  ../tongji_ss/research_teacher_kpi_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-research_teacher_kpi_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			type_flag:	$('#id_type_flag').val()
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
	$('#id_type_flag').val(g_args.type_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">type_flag</span>
                <input class="opt-change form-control" id="id_type_flag" />
            </div>
        </div>
*/
