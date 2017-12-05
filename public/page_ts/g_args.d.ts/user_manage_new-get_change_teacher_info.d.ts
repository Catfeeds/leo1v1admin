interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	page_num:	number;
	page_count:	number;
	ass_adminid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	add_time	:any;
	ass_adminid	:any;
	userid	:any;
	teacherid	:any;
	change_reason	:any;
	except_teacher	:any;
	subject	:any;
	grade	:any;
	textbook	:any;
	phone_location	:any;
	stu_score_info	:any;
	stu_character_info	:any;
	record_teacher	:any;
	accept_reason	:any;
	accept_flag	:any;
	accept_adminid	:any;
	accept_time	:any;
	realname	:any;
	nick	:any;
	account	:any;
	add_time_str	:any;
	subject_str	:any;
	grade_str	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/get_change_teacher_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-get_change_teacher_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			ass_adminid:	$('#id_ass_adminid').val()
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
	$('#id_ass_adminid').val(g_args.ass_adminid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">ass_adminid</span>
                <input class="opt-change form-control" id="id_ass_adminid" />
            </div>
        </div>
*/
