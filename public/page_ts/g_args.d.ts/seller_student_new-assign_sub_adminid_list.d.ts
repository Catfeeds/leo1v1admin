interface GargsStatic {
	majordomo_groupid:	number;
	admin_main_groupid:	number;
	self_groupid:	number;
	button_show_flag:	number;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	order_by_str:	string;
	userid:	number;
	origin:	string;
	origin_ex:	string;
	grade:	string;//枚举列表: \App\Enums\Egrade
 	subject:	number;//App\Enums\Esubject
	phone_location:	string;
	admin_revisiterid:	number;
	tq_called_flag:	number;//App\Enums\Etq_called_flag
	global_tq_called_flag:	number;//App\Enums\Etq_called_flag
	seller_student_status:	string;//枚举列表: \App\Enums\Eseller_student_status
 	page_num:	number;
	page_count:	number;
	has_pad:	number;//App\Enums\Epad_type
	sub_assign_adminid_2:	number;
	origin_assistantid:	number;
	tmk_adminid:	number;
	account_role:	string;//枚举列表: App\Enums\Eaccount_role
 	origin_level:	string;//枚举列表: \App\Enums\Eorigin_level
 	seller_student_sub_status:	number;//App\Enums\Eseller_student_sub_status
	tmk_student_status:	number;//App\Enums\Etmk_student_status
	seller_resource_type:	number;//App\Enums\Eseller_resource_type
	sys_invaild_flag:	number;//\App\Enums\Eboolean
	publish_flag:	number;//\App\Enums\Eboolean
	show_list_flag:	number;
	seller_level:	string;//枚举列表: \App\Enums\Eseller_level
 	admin_del_flag:	number;//\App\Enums\Eboolean
	wx_invaild_flag:	number;//\App\Enums\Eboolean
	filter_flag:	number;//\App\Enums\Eboolean
	first_seller_adminid:	number;
	call_phone_count:	string;
	call_count:	string;
	suc_test_count:	string;
	main_master_flag:	number;
	origin_count:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	nickname	:any;
	seller_resource_type	:any;
	first_call_time	:any;
	first_contact_time	:any;
	first_revisit_time	:any;
	last_revisit_time	:any;
	tmk_assign_time	:any;
	last_contact_time	:any;
	competition_call_adminid	:any;
	competition_call_time	:any;
	sys_invaild_flag	:any;
	wx_invaild_flag	:any;
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
	require_adminid	:any;
	tmk_student_status	:any;
	first_tmk_set_valid_admind	:any;
	first_tmk_set_valid_time	:any;
	tmk_set_seller_adminid	:any;
	first_tmk_set_seller_time	:any;
	first_admin_master_adminid	:any;
	first_admin_master_time	:any;
	first_admin_revisiterid	:any;
	first_admin_revisiterid_time	:any;
	first_seller_status	:any;
	call_count	:any;
	auto_allot_adminid	:any;
	last_call_time_space	:any;
	opt_time	:any;
	index	:any;
	seller_student_status_str	:any;
	seller_student_sub_status_str	:any;
	tmk_student_status_str	:any;
	grade_str	:any;
	seller_resource_type_str	:any;
	sys_invaild_flag_str	:any;
	subject_str	:any;
	has_pad_str	:any;
	global_tq_called_flag_str	:any;
	origin_level_str	:any;
	sub_assign_admin_2_nick	:any;
	admin_revisiter_nick	:any;
	origin_assistant_nick	:any;
	tmk_admin_nick	:any;
	competition_call_admin_nick	:any;
	require_admin_nick	:any;
	first_tmk_valid_desc	:any;
	first_tmk_set_cc_desc	:any;
	first_set_master_desc	:any;
	first_set_cc_desc	:any;
	first_seller_status_str	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student_new; vi  ../seller_student_new/assign_sub_adminid_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-assign_sub_adminid_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		majordomo_groupid:	$('#id_majordomo_groupid').val(),
		admin_main_groupid:	$('#id_admin_main_groupid').val(),
		self_groupid:	$('#id_self_groupid').val(),
		button_show_flag:	$('#id_button_show_flag').val(),
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		order_by_str:	$('#id_order_by_str').val(),
		userid:	$('#id_userid').val(),
		origin:	$('#id_origin').val(),
		origin_ex:	$('#id_origin_ex').val(),
		grade:	$('#id_grade').val(),
		subject:	$('#id_subject').val(),
		phone_location:	$('#id_phone_location').val(),
		admin_revisiterid:	$('#id_admin_revisiterid').val(),
		tq_called_flag:	$('#id_tq_called_flag').val(),
		global_tq_called_flag:	$('#id_global_tq_called_flag').val(),
		seller_student_status:	$('#id_seller_student_status').val(),
		has_pad:	$('#id_has_pad').val(),
		sub_assign_adminid_2:	$('#id_sub_assign_adminid_2').val(),
		origin_assistantid:	$('#id_origin_assistantid').val(),
		tmk_adminid:	$('#id_tmk_adminid').val(),
		account_role:	$('#id_account_role').val(),
		origin_level:	$('#id_origin_level').val(),
		seller_student_sub_status:	$('#id_seller_student_sub_status').val(),
		tmk_student_status:	$('#id_tmk_student_status').val(),
		seller_resource_type:	$('#id_seller_resource_type').val(),
		sys_invaild_flag:	$('#id_sys_invaild_flag').val(),
		publish_flag:	$('#id_publish_flag').val(),
		show_list_flag:	$('#id_show_list_flag').val(),
		seller_level:	$('#id_seller_level').val(),
		admin_del_flag:	$('#id_admin_del_flag').val(),
		wx_invaild_flag:	$('#id_wx_invaild_flag').val(),
		filter_flag:	$('#id_filter_flag').val(),
		first_seller_adminid:	$('#id_first_seller_adminid').val(),
		call_phone_count:	$('#id_call_phone_count').val(),
		call_count:	$('#id_call_count').val(),
		suc_test_count:	$('#id_suc_test_count').val(),
		main_master_flag:	$('#id_main_master_flag').val(),
		origin_count:	$('#id_origin_count').val()
    });
}
$(function(){

	Enum_map.append_option_list("subject",$("#id_subject"));
	Enum_map.append_option_list("tq_called_flag",$("#id_tq_called_flag"));
	Enum_map.append_option_list("tq_called_flag",$("#id_global_tq_called_flag"));
	Enum_map.append_option_list("pad_type",$("#id_has_pad"));
	Enum_map.append_option_list("seller_student_sub_status",$("#id_seller_student_sub_status"));
	Enum_map.append_option_list("tmk_student_status",$("#id_tmk_student_status"));
	Enum_map.append_option_list("seller_resource_type",$("#id_seller_resource_type"));
	Enum_map.append_option_list("boolean",$("#id_sys_invaild_flag"));
	Enum_map.append_option_list("boolean",$("#id_publish_flag"));
	Enum_map.append_option_list("boolean",$("#id_admin_del_flag"));
	Enum_map.append_option_list("boolean",$("#id_wx_invaild_flag"));
	Enum_map.append_option_list("boolean",$("#id_filter_flag"));

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
	$('#id_majordomo_groupid').val(g_args.majordomo_groupid);
	$('#id_admin_main_groupid').val(g_args.admin_main_groupid);
	$('#id_self_groupid').val(g_args.self_groupid);
	$('#id_button_show_flag').val(g_args.button_show_flag);
	$('#id_order_by_str').val(g_args.order_by_str);
	$('#id_userid').val(g_args.userid);
	$('#id_origin').val(g_args.origin);
	$('#id_origin_ex').val(g_args.origin_ex);
	$('#id_grade').val(g_args.grade);
	$.enum_multi_select( $('#id_grade'), 'grade', function(){load_data();} )
	$('#id_subject').val(g_args.subject);
	$('#id_phone_location').val(g_args.phone_location);
	$('#id_admin_revisiterid').val(g_args.admin_revisiterid);
	$('#id_tq_called_flag').val(g_args.tq_called_flag);
	$('#id_global_tq_called_flag').val(g_args.global_tq_called_flag);
	$('#id_seller_student_status').val(g_args.seller_student_status);
	$.enum_multi_select( $('#id_seller_student_status'), 'seller_student_status', function(){load_data();} )
	$('#id_has_pad').val(g_args.has_pad);
	$('#id_sub_assign_adminid_2').val(g_args.sub_assign_adminid_2);
	$('#id_origin_assistantid').val(g_args.origin_assistantid);
	$('#id_tmk_adminid').val(g_args.tmk_adminid);
	$('#id_account_role').val(g_args.account_role);
	$.enum_multi_select( $('#id_account_role'), 'account_role', function(){load_data();} )
	$('#id_origin_level').val(g_args.origin_level);
	$.enum_multi_select( $('#id_origin_level'), 'origin_level', function(){load_data();} )
	$('#id_seller_student_sub_status').val(g_args.seller_student_sub_status);
	$('#id_tmk_student_status').val(g_args.tmk_student_status);
	$('#id_seller_resource_type').val(g_args.seller_resource_type);
	$('#id_sys_invaild_flag').val(g_args.sys_invaild_flag);
	$('#id_publish_flag').val(g_args.publish_flag);
	$('#id_show_list_flag').val(g_args.show_list_flag);
	$('#id_seller_level').val(g_args.seller_level);
	$.enum_multi_select( $('#id_seller_level'), 'seller_level', function(){load_data();} )
	$('#id_admin_del_flag').val(g_args.admin_del_flag);
	$('#id_wx_invaild_flag').val(g_args.wx_invaild_flag);
	$('#id_filter_flag').val(g_args.filter_flag);
	$('#id_first_seller_adminid').val(g_args.first_seller_adminid);
	$('#id_call_phone_count').val(g_args.call_phone_count);
	$('#id_call_count').val(g_args.call_count);
	$('#id_suc_test_count').val(g_args.suc_test_count);
	$('#id_main_master_flag').val(g_args.main_master_flag);
	$('#id_origin_count').val(g_args.origin_count);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">majordomo_groupid</span>
                <input class="opt-change form-control" id="id_majordomo_groupid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">admin_main_groupid</span>
                <input class="opt-change form-control" id="id_admin_main_groupid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">self_groupid</span>
                <input class="opt-change form-control" id="id_self_groupid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">button_show_flag</span>
                <input class="opt-change form-control" id="id_button_show_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_by_str</span>
                <input class="opt-change form-control" id="id_order_by_str" />
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
                <span class="input-group-addon">origin</span>
                <input class="opt-change form-control" id="id_origin" />
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
                <span class="input-group-addon">grade</span>
                <input class="opt-change form-control" id="id_grade" />
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
                <span class="input-group-addon">TQ</span>
                <select class="opt-change form-control" id="id_tq_called_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">TQ</span>
                <select class="opt-change form-control" id="id_global_tq_called_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_student_status</span>
                <input class="opt-change form-control" id="id_seller_student_status" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">Pad</span>
                <select class="opt-change form-control" id="id_has_pad" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">sub_assign_adminid_2</span>
                <input class="opt-change form-control" id="id_sub_assign_adminid_2" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin_assistantid</span>
                <input class="opt-change form-control" id="id_origin_assistantid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">tmk_adminid</span>
                <input class="opt-change form-control" id="id_tmk_adminid" />
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
                <span class="input-group-addon">origin_level</span>
                <input class="opt-change form-control" id="id_origin_level" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">子状态</span>
                <select class="opt-change form-control" id="id_seller_student_sub_status" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">tmk_student_status</span>
                <select class="opt-change form-control" id="id_tmk_student_status" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">资源</span>
                <select class="opt-change form-control" id="id_seller_resource_type" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_sys_invaild_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_publish_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">show_list_flag</span>
                <input class="opt-change form-control" id="id_show_list_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_level</span>
                <input class="opt-change form-control" id="id_seller_level" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_admin_del_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_wx_invaild_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_filter_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">first_seller_adminid</span>
                <input class="opt-change form-control" id="id_first_seller_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">call_phone_count</span>
                <input class="opt-change form-control" id="id_call_phone_count" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">call_count</span>
                <input class="opt-change form-control" id="id_call_count" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">suc_test_count</span>
                <input class="opt-change form-control" id="id_suc_test_count" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">main_master_flag</span>
                <input class="opt-change form-control" id="id_main_master_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin_count</span>
                <input class="opt-change form-control" id="id_origin_count" />
            </div>
        </div>
*/
