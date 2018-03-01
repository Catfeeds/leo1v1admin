interface GargsStatic {
	grade:	string;//枚举列表: \App\Enums\Egrade
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
	lesson_num	:any;
	is_auto_set_type_flag	:any;
	stu_lesson_stop_reason	:any;
	phone	:any;
	is_test_user	:any;
	originid	:any;
	grade	:any;
	praise	:any;
	assistantid	:any;
	parent_name	:any;
	parent_type	:any;
	last_login_ip	:any;
	last_login_time	:any;
	lesson_count_all	:any;
	lesson_count_left	:any;
	user_agent	:any;
	type	:any;
	ass_revisit_last_month_time	:any;
	ass_revisit_last_week_time	:any;
	ass_assign_time	:any;
	phone_location	:any;
	nick	:any;
	lesson_total	:any;
	type_str	:any;
	user_agent_simple	:any;
	ass_assign_time_str	:any;
	lesson_count_done	:any;
	assistant_nick	:any;
	ass_revisit_week_flag	:any;
	ass_revisit_month_flag	:any;
	ass_revisit_week_flag_str	:any;
	ass_revisit_month_flag_str	:any;
	status	:any;
	status_str	:any;
	cur	:any;
	last	:any;
	cur_str	:any;
	last_str	:any;
	location	:any;
	course_list_total	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage; vi  ../user_manage/ass_random_revisit.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-ass_random_revisit.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		grade:	$('#id_grade').val()
		});
}
$(function(){


	$('#id_grade').admin_set_select_field({
		"enum_type"    : "grade",
		"field_name" : "grade",
		"select_value" : g_args.grade,
		"multi_select_flag"     : true,
		"onChange"     : load_data,
		"th_input_id"  : "th_grade",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade</span>
                <input class="opt-change form-control" id="id_grade" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["grade title", "grade", "th_grade" ]])!!}
*/
