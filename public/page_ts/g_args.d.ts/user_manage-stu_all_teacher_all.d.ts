interface GargsStatic {
	assistantid:	number;
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
	assistantid	:any;
	teacherid	:any;
	phone	:any;
	grade_part_ex	:any;
	subject	:any;
	num	:any;
	subject_str	:any;
	grade_part_ex_str	:any;
	teacher_nick	:any;
	assistant_nick	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage; vi  ../user_manage/stu_all_teacher_all.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage-stu_all_teacher_all.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			assistantid:	$('#id_assistantid').val()
        });
    }


	$('#id_assistantid').val(g_args.assistantid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>
*/
