interface GargsStatic {
	grade:	number;//App\Enums\Egrade
	all_flag:	number;
	originid:	number;
	user_name:	string;
	phone:	string;
	assistantid:	number;
	seller_adminid:	number;
	page_num:	number;
	page_count:	number;
	userid:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	origin_userid	:any;
	userid	:any;
	nick	:any;
	realname	:any;
	spree	:any;
	phone	:any;
	originid	:any;
	origin	:any;
	grade	:any;
	parent_name	:any;
	parent_type	:any;
	last_login_ip	:any;
	last_lesson_time	:any;
	last_login_time	:any;
	assistantid	:any;
	lesson_count_all	:any;
	lesson_count_left	:any;
	user_agent	:any;
	seller_adminid	:any;
	ass_assign_time	:any;
	reg_time	:any;
	phone_location	:any;
	origin_assistantid	:any;
	grade_up	:any;
	user_agent_simple	:any;
	seller_admin_nick	:any;
	assistant_nick	:any;
	origin_ass_nick	:any;
	ss_assign_time	:any;
	cache_nick	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage; vi  ../user_manage/pay_money_stu_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-pay_money_stu_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		grade:	$('#id_grade').val(),
		all_flag:	$('#id_all_flag').val(),
		originid:	$('#id_originid').val(),
		user_name:	$('#id_user_name').val(),
		phone:	$('#id_phone').val(),
		assistantid:	$('#id_assistantid').val(),
		seller_adminid:	$('#id_seller_adminid').val(),
		userid:	$('#id_userid').val()
    });
}
$(function(){

	Enum_map.append_option_list("grade",$("#id_grade"));

	$('#id_grade').val(g_args.grade);
	$('#id_all_flag').val(g_args.all_flag);
	$('#id_originid').val(g_args.originid);
	$('#id_user_name').val(g_args.user_name);
	$('#id_phone').val(g_args.phone);
	$('#id_assistantid').val(g_args.assistantid);
	$('#id_seller_adminid').val(g_args.seller_adminid);
	$('#id_userid').val(g_args.userid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">年级</span>
                <select class="opt-change form-control" id="id_grade" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">all_flag</span>
                <input class="opt-change form-control" id="id_all_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">originid</span>
                <input class="opt-change form-control" id="id_originid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">user_name</span>
                <input class="opt-change form-control" id="id_user_name" />
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
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_adminid</span>
                <input class="opt-change form-control" id="id_seller_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">userid</span>
                <input class="opt-change form-control" id="id_userid" />
            </div>
        </div>
*/
