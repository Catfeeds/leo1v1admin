interface GargsStatic {
	sid:	number;
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
	userid	:any;
	create_time	:any;
	create_adminid	:any;
	subject	:any;
	stu_score_type	:any;
	stu_score_time	:any;
	score	:any;
	rank	:any;
	file_url	:any;
	semester	:any;
	total_score	:any;
	grade	:any;
	grade_rank	:any;
	status	:any;
	reason	:any;
	month	:any;
	rank_up	:any;
	rank_down	:any;
	num	:any;
	subject_str	:any;
	semester_str	:any;
	grade_str	:any;
	stu_score_type_str	:any;
	create_admin_nick	:any;
}

/*

tofile: 
	 mkdir -p ../stu_manage; vi  ../stu_manage/score_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-score_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			sid:	$('#id_sid').val()
        });
    }


	$('#id_sid').val(g_args.sid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">sid</span>
                <input class="opt-change form-control" id="id_sid" />
            </div>
        </div>
*/
