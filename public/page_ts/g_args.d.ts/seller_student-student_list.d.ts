interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	self_groupid:	number;
	group_master_flag:	number;
	origin:	string;
	grade:	number;//App\Enums\Egrade
	subject:	number;//App\Enums\Esubject
	phone:	string;
	nick:	string;
	phone_location:	string;
	admin_revisiterid:	number;
	status:	number;
	origin_ex:	string;
	page_num:	number;
	page_count:	number;
	has_pad:	number;
	ass_adminid_flag:	number;//App\Enums\Eboolean
	tq_called_flag:	number;//App\Enums\Etq_called_flag
	admin_assign_time_flag:	number;//App\Enums\Eboolean
	seller_resource_type:	number;
	test_lesson_cancel_flag:	number;//App\Enums\Etest_lesson_cancel_flag
	sub_assign_adminid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	phone	:any;
	ass_adminid	:any;
	tq_called_flag	:any;
	userid	:any;
	notify_lesson_day1	:any;
	notify_lesson_day2	:any;
	money_all	:any;
	last_revisit_time	:any;
	next_revisit_time	:any;
	st_application_time	:any;
	phone_location	:any;
	id	:any;
	add_time	:any;
	origin	:any;
	nick	:any;
	status	:any;
	user_desc	:any;
	grade	:any;
	subject	:any;
	has_pad	:any;
	admin_revisiterid	:any;
	admin_assign_time	:any;
	last_revisit_msg	:any;
	teacherid	:any;
	lesson_start	:any;
	lesson_end	:any;
	first_revisite_time	:any;
	st_arrange_lessonid	:any;
	origin_userid	:any;
	sub_assign_adminid	:any;
	sub_assign_time	:any;
	st_test_paper	:any;
	tea_download_paper_time	:any;
	subject_str	:any;
	has_pad_str	:any;
	lesson_time	:any;
	origin_user_nick	:any;
	status_str	:any;
	grade_str	:any;
	tq_called_flag_str	:any;
	opt_time	:any;
	number	:any;
	admin_revisiterid_nick	:any;
	admin_revisiter_nick	:any;
	sub_assign_admin_nick	:any;
	ass_admin_nick	:any;
	teacher_nick	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student; vi  ../seller_student/student_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student-student_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			self_groupid:	$('#id_self_groupid').val(),
			group_master_flag:	$('#id_group_master_flag').val(),
			origin:	$('#id_origin').val(),
			grade:	$('#id_grade').val(),
			subject:	$('#id_subject').val(),
			phone:	$('#id_phone').val(),
			nick:	$('#id_nick').val(),
			phone_location:	$('#id_phone_location').val(),
			admin_revisiterid:	$('#id_admin_revisiterid').val(),
			status:	$('#id_status').val(),
			origin_ex:	$('#id_origin_ex').val(),
			has_pad:	$('#id_has_pad').val(),
			ass_adminid_flag:	$('#id_ass_adminid_flag').val(),
			tq_called_flag:	$('#id_tq_called_flag').val(),
			admin_assign_time_flag:	$('#id_admin_assign_time_flag').val(),
			seller_resource_type:	$('#id_seller_resource_type').val(),
			test_lesson_cancel_flag:	$('#id_test_lesson_cancel_flag').val(),
			sub_assign_adminid:	$('#id_sub_assign_adminid').val()
        });
    }

	Enum_map.append_option_list("grade",$("#id_grade"));
	Enum_map.append_option_list("subject",$("#id_subject"));
	Enum_map.append_option_list("boolean",$("#id_ass_adminid_flag"));
	Enum_map.append_option_list("tq_called_flag",$("#id_tq_called_flag"));
	Enum_map.append_option_list("boolean",$("#id_admin_assign_time_flag"));
	Enum_map.append_option_list("test_lesson_cancel_flag",$("#id_test_lesson_cancel_flag"));

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
	$('#id_group_master_flag').val(g_args.group_master_flag);
	$('#id_origin').val(g_args.origin);
	$('#id_grade').val(g_args.grade);
	$('#id_subject').val(g_args.subject);
	$('#id_phone').val(g_args.phone);
	$('#id_nick').val(g_args.nick);
	$('#id_phone_location').val(g_args.phone_location);
	$('#id_admin_revisiterid').val(g_args.admin_revisiterid);
	$('#id_status').val(g_args.status);
	$('#id_origin_ex').val(g_args.origin_ex);
	$('#id_has_pad').val(g_args.has_pad);
	$('#id_ass_adminid_flag').val(g_args.ass_adminid_flag);
	$('#id_tq_called_flag').val(g_args.tq_called_flag);
	$('#id_admin_assign_time_flag').val(g_args.admin_assign_time_flag);
	$('#id_seller_resource_type').val(g_args.seller_resource_type);
	$('#id_test_lesson_cancel_flag').val(g_args.test_lesson_cancel_flag);
	$('#id_sub_assign_adminid').val(g_args.sub_assign_adminid);


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
                <span class="input-group-addon">group_master_flag</span>
                <input class="opt-change form-control" id="id_group_master_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin</span>
                <input class="opt-change form-control" id="id_origin" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">年级</span>
                <select class="opt-change form-control" id="id_grade" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">科目</span>
                <select class="opt-change form-control" id="id_subject" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone</span>
                <input class="opt-change form-control" id="id_phone" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">nick</span>
                <input class="opt-change form-control" id="id_nick" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone_location</span>
                <input class="opt-change form-control" id="id_phone_location" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">admin_revisiterid</span>
                <input class="opt-change form-control" id="id_admin_revisiterid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">status</span>
                <input class="opt-change form-control" id="id_status" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin_ex</span>
                <input class="opt-change form-control" id="id_origin_ex" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">has_pad</span>
                <input class="opt-change form-control" id="id_has_pad" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_ass_adminid_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">TQ</span>
                <select class="opt-change form-control" id="id_tq_called_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_admin_assign_time_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_resource_type</span>
                <input class="opt-change form-control" id="id_seller_resource_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">课前取消</span>
                <select class="opt-change form-control" id="id_test_lesson_cancel_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">sub_assign_adminid</span>
                <input class="opt-change form-control" id="id_sub_assign_adminid" />
            </div>
        </div>
*/
