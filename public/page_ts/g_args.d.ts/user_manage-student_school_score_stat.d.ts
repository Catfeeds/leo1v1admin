interface GargsStatic {
	order_by_str:	string;
	username:	string;
	grade:	number;
	semester:	number;
	stu_score_type:	number;
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
	create_time	:any;
	create_adminid	:any;
	subject	:any;
	stu_score_type	:any;
	stu_score_time	:any;
	score	:any;
	total_score	:any;
	rank	:any;
	semester	:any;
	grade	:any;
	grade_rank	:any;
	status	:any;
	month	:any;
	rank_up	:any;
	rank_down	:any;
	realname	:any;
	school	:any;
	name	:any;
	nick	:any;
	num	:any;
	semester_str	:any;
	grade_str	:any;
	stu_score_type_str	:any;
	create_admin_nick	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage; vi  ../user_manage/student_school_score_stat.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-student_school_score_stat.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			order_by_str:	$('#id_order_by_str').val(),
			username:	$('#id_username').val(),
			grade:	$('#id_grade').val(),
			semester:	$('#id_semester').val(),
			stu_score_type:	$('#id_stu_score_type').val()
        });
    }


	$('#id_order_by_str').val(g_args.order_by_str);
	$('#id_username').val(g_args.username);
	$('#id_grade').val(g_args.grade);
	$('#id_semester').val(g_args.semester);
	$('#id_stu_score_type').val(g_args.stu_score_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_by_str</span>
                <input class="opt-change form-control" id="id_order_by_str" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">username</span>
                <input class="opt-change form-control" id="id_username" />
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
                <span class="input-group-addon">semester</span>
                <input class="opt-change form-control" id="id_semester" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">stu_score_type</span>
                <input class="opt-change form-control" id="id_stu_score_type" />
            </div>
        </div>
*/
