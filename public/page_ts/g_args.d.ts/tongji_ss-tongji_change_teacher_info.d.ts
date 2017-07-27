interface GargsStatic {
	change_teacher_reason_type:	number;
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
	userid	:any;
	old_teacherid	:any;
	is_done_flag	:any;
	change_teacher_reason_type	:any;
	subject	:any;
	grade	:any;
	order_confirm_flag	:any;
	confirm_adminid	:any;
	lesson_start	:any;
	teacherid	:any;
	lesson_end	:any;
	assistantid	:any;
	stu_nick	:any;
	teacher_nick	:any;
	old_teacher_nick	:any;
	change_teacher_reason_type_str	:any;
	grade_str	:any;
	subject_str	:any;
	order_confirm_flag_str	:any;
	ass_nick	:any;
	test_lesson_time	:any;
	confirm_adminid_nick	:any;
	is_done_flag_str	:any;
}

/*

tofile: 
	 mkdir -p ../tongji_ss; vi  ../tongji_ss/tongji_change_teacher_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-tongji_change_teacher_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			change_teacher_reason_type:	$('#id_change_teacher_reason_type').val(),
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
	$('#id_change_teacher_reason_type').val(g_args.change_teacher_reason_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">change_teacher_reason_type</span>
                <input class="opt-change form-control" id="id_change_teacher_reason_type" />
            </div>
        </div>
*/
