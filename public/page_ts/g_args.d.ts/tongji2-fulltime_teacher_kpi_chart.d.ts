interface GargsStatic {
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
	title	:any;
	cc_transfer_all	:any;
	cc_transfer_sh	:any;
	cc_transfer_wh	:any;
	student_num_all	:any;
	student_num_sh	:any;
	student_num_wh	:any;
	lesson_count_all	:any;
	lesson_count_sh	:any;
	lesson_count_wh	:any;
}

/*

tofile: 
	 mkdir -p ../tongji2; vi  ../tongji2/fulltime_teacher_kpi_chart.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-fulltime_teacher_kpi_chart.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val()
    });
}
$(function(){


	$('#id_start_time').val(g_args.start_time);
	$('#id_end_time').val(g_args.end_time);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">start_time</span>
                <input class="opt-change form-control" id="id_start_time" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">end_time</span>
                <input class="opt-change form-control" id="id_end_time" />
            </div>
        </div>
*/
