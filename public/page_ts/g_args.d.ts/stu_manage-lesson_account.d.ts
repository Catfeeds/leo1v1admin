interface GargsStatic {
	sid:	number;
	page_num:	number;
	page_count:	number;
	lesson_account_id:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
}

/*

tofile: 
	 mkdir -p ../stu_manage; vi  ../stu_manage/lesson_account.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/stu_manage-lesson_account.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			sid:	$('#id_sid').val(),
			lesson_account_id:	$('#id_lesson_account_id').val()
        });
    }


	$('#id_sid').val(g_args.sid);
	$('#id_lesson_account_id').val(g_args.lesson_account_id);


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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_account_id</span>
                <input class="opt-change form-control" id="id_lesson_account_id" />
            </div>
        </div>
*/
