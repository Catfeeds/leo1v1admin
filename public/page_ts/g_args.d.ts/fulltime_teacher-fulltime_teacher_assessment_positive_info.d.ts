interface GargsStatic {
	adminid:	number;
	main_flag:	number;
	become_full_member_flag:	number;
	fulltime_teacher_type:	number;
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
	create_time	:any;
	uid	:any;
	account	:any;
	become_full_member_flag	:any;
	become_full_member_time	:any;
	id	:any;
	assess_time	:any;
	positive_id	:any;
	master_deal_flag	:any;
	main_master_deal_flag	:any;
	name	:any;
	assess_adminid	:any;
	mater_adminid	:any;
	master_assess_time	:any;
	main_mater_adminid	:any;
	main_master_assess_time	:any;
	positive_type	:any;
	create_time_str	:any;
	become_full_member_time_str	:any;
	assess_time_str	:any;
	master_assess_time_str	:any;
	main_master_assess_time_str	:any;
	assess_admin_nick	:any;
	mater_admin_nick	:any;
	main_mater_admin_nick	:any;
	master_deal_flag_str	:any;
	main_master_deal_flag_str	:any;
	become_full_member_flag_str	:any;
	positive_type_str	:any;
}

/*

tofile: 
	 mkdir -p ../fulltime_teacher; vi  ../fulltime_teacher/fulltime_teacher_assessment_positive_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/fulltime_teacher-fulltime_teacher_assessment_positive_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			adminid:	$('#id_adminid').val(),
			main_flag:	$('#id_main_flag').val(),
			become_full_member_flag:	$('#id_become_full_member_flag').val(),
			fulltime_teacher_type:	$('#id_fulltime_teacher_type').val()
        });
    }


	$('#id_adminid').val(g_args.adminid);
	$('#id_main_flag').val(g_args.main_flag);
	$('#id_become_full_member_flag').val(g_args.become_full_member_flag);
	$('#id_fulltime_teacher_type').val(g_args.fulltime_teacher_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">adminid</span>
                <input class="opt-change form-control" id="id_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">main_flag</span>
                <input class="opt-change form-control" id="id_main_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">become_full_member_flag</span>
                <input class="opt-change form-control" id="id_become_full_member_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">fulltime_teacher_type</span>
                <input class="opt-change form-control" id="id_fulltime_teacher_type" />
            </div>
        </div>
*/
