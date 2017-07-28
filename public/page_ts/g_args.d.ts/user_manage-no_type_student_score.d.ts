interface GargsStatic {
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
	id	:any;
	stu_score_type	:any;
	grade	:any;
	create_time	:any;
	userid	:any;
	subject	:any;
	status	:any;
	month	:any;
	num	:any;
	subject_str	:any;
	student_nick	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage; vi  ../user_manage/no_type_student_score.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-no_type_student_score.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {

        });
    }




	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
*/
