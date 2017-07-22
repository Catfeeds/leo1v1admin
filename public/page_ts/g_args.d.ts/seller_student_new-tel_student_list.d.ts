interface GargsStatic {
	self_groupid:	number;
	userid:	number;
	page_num:	number;
	page_count:	number;
	global_tq_called_flag:	string;//枚举列表: \App\Enums\Etq_called_flag
 	grade:	string;//枚举列表: \App\Enums\Egrade
 	subject:	string;//枚举列表: \App\Enums\Esubject
 	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	seller_student_status:	number;//App\Enums\Eseller_student_status
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	tmk_student_status	:any;
	tmk_next_revisit_time	:any;
	tmk_desc	:any;
	return_publish_count	:any;
	tmk_adminid	:any;
	test_lesson_subject_id	:any;
	seller_student_sub_status	:any;
	add_time	:any;
	global_tq_called_flag	:any;
	seller_student_status	:any;
	userid	:any;
	nick	:any;
	origin	:any;
	origin_level	:any;
	phone_location	:any;
	phone	:any;
	sub_assign_adminid_2	:any;
	admin_revisiterid	:any;
	admin_assign_time	:any;
	sub_assign_time_2	:any;
	origin_assistantid	:any;
	origin_userid	:any;
	subject	:any;
	grade	:any;
	user_desc	:any;
	has_pad	:any;
	index	:any;
	seller_student_status_str	:any;
	seller_student_sub_status_str	:any;
	grade_str	:any;
	subject_str	:any;
	has_pad_str	:any;
	global_tq_called_flag_str	:any;
	origin_level_str	:any;
	sub_assign_admin_2_nick	:any;
	admin_revisiter_nick	:any;
	origin_assistant_nick	:any;
	tmk_admin_nick	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student_new; vi  ../seller_student_new/tel_student_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-tel_student_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			self_groupid:	$('#id_self_groupid').val(),
			userid:	$('#id_userid').val(),
			global_tq_called_flag:	$('#id_global_tq_called_flag').val(),
			grade:	$('#id_grade').val(),
			subject:	$('#id_subject').val(),
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			seller_student_status:	$('#id_seller_student_status').val()
        });
    }

	Enum_map.append_option_list("seller_student_status",$("#id_seller_student_status"));

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
	$('#id_self_groupid').val(g_args.self_groupid);
	$('#id_userid').val(g_args.userid);
	$('#id_global_tq_called_flag').val(g_args.global_tq_called_flag);
	$.enum_multi_select( $('#id_global_tq_called_flag'), 'tq_called_flag', function(){load_data();} )
	$('#id_grade').val(g_args.grade);
	$.enum_multi_select( $('#id_grade'), 'grade', function(){load_data();} )
	$('#id_subject').val(g_args.subject);
	$.enum_multi_select( $('#id_subject'), 'subject', function(){load_data();} )
	$('#id_seller_student_status').val(g_args.seller_student_status);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">self_groupid</span>
                <input class="opt-change form-control" id="id_self_groupid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">userid</span>
                <input class="opt-change form-control" id="id_userid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">global_tq_called_flag</span>
                <input class="opt-change form-control" id="id_global_tq_called_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade</span>
                <input class="opt-change form-control" id="id_grade" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">subject</span>
                <input class="opt-change form-control" id="id_subject" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_student_status</span>
                <select class="opt-change form-control" id="id_seller_student_status" >
                </select>
            </div>
        </div>
*/
