interface GargsStatic {
	page_num:	number;
	page_count:	number;
	cc_id:	number;
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
	teacherid	:any;
	cc_id	:any;
	lessonid	:any;
	question_type	:any;
	question_content	:any;
	teacher_flag	:any;
	teacher_time	:any;
	cc_flag	:any;
	cc_time	:any;
	create_time	:any;
	realname	:any;
	phone	:any;
	lesson_name	:any;
	lesson_type	:any;
}

/*

tofile: 
	 mkdir -p ../teacher_apply; vi  ../teacher_apply/teacher_apply_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_apply-teacher_apply_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			cc_id:	$('#id_cc_id').val()
        });
    }


	$('#id_cc_id').val(g_args.cc_id);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">cc_id</span>
                <input class="opt-change form-control" id="id_cc_id" />
            </div>
        </div>
*/
