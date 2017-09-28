interface GargsStatic {
	teacherid:	number;
	subject:	number;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
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
	quit_time	:any;
	quit_info	:any;
	quit_set_adminid	:any;
	account	:any;
	realname	:any;
	subject	:any;
	grade_part_ex	:any;
	grade_start	:any;
	grade_end	:any;
	create_time	:any;
	teacherid	:any;
	create_time_str	:any;
	quit_time_str	:any;
	grade_part_ex_str	:any;
	grade_start_str	:any;
	grade_end_str	:any;
	subject_str	:any;
}

/*

tofile: 
	 mkdir -p ../human_resource; vi  ../human_resource/quit_teacher_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-quit_teacher_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			teacherid:	$('#id_teacherid').val(),
			subject:	$('#id_subject').val(),
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
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_subject').val(g_args.subject);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">subject</span>
                <input class="opt-change form-control" id="id_subject" />
            </div>
        </div>
*/
