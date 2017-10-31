interface GargsStatic {
	phone:	number;
	open_flag:	number;
	userid:	number;
	test_lesson_subject_id:	number;
	account_seller_level:	number;
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
	 mkdir -p ../seller_student_new; vi  ../seller_student_new/deal_new_user.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-deal_new_user.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		phone:	$('#id_phone').val(),
		open_flag:	$('#id_open_flag').val(),
		userid:	$('#id_userid').val(),
		test_lesson_subject_id:	$('#id_test_lesson_subject_id').val(),
		account_seller_level:	$('#id_account_seller_level').val()
    });
}
$(function(){


	$('#id_phone').val(g_args.phone);
	$('#id_open_flag').val(g_args.open_flag);
	$('#id_userid').val(g_args.userid);
	$('#id_test_lesson_subject_id').val(g_args.test_lesson_subject_id);
	$('#id_account_seller_level').val(g_args.account_seller_level);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone</span>
                <input class="opt-change form-control" id="id_phone" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">open_flag</span>
                <input class="opt-change form-control" id="id_open_flag" />
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
                <span class="input-group-addon">test_lesson_subject_id</span>
                <input class="opt-change form-control" id="id_test_lesson_subject_id" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">account_seller_level</span>
                <input class="opt-change form-control" id="id_account_seller_level" />
            </div>
        </div>
*/
