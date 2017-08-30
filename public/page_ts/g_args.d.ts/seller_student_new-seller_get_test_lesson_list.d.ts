interface GargsStatic {
	adminid:	number;
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
	phone	:any;
	lesson_start	:any;
	student_nick	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student_new; vi  ../seller_student_new/seller_get_test_lesson_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-seller_get_test_lesson_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			adminid:	$('#id_adminid').val()
        });
    }


	$('#id_adminid').val(g_args.adminid);


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
*/
