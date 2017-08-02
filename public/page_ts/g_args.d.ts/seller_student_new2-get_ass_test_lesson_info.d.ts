interface GargsStatic {
	page_num:	number;
	page_count:	number;
	require_adminid:	number;
	master_flag:	number;
	assistantid:	number;
	success_flag:	number;
	order_confirm_flag:	number;
	master_adminid:	number;
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
	 mkdir -p ../seller_student_new2; vi  ../seller_student_new2/get_ass_test_lesson_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-get_ass_test_lesson_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			require_adminid:	$('#id_require_adminid').val(),
			master_flag:	$('#id_master_flag').val(),
			assistantid:	$('#id_assistantid').val(),
			success_flag:	$('#id_success_flag').val(),
			order_confirm_flag:	$('#id_order_confirm_flag').val(),
			master_adminid:	$('#id_master_adminid').val()
        });
    }


	$('#id_require_adminid').val(g_args.require_adminid);
	$('#id_master_flag').val(g_args.master_flag);
	$('#id_assistantid').val(g_args.assistantid);
	$('#id_success_flag').val(g_args.success_flag);
	$('#id_order_confirm_flag').val(g_args.order_confirm_flag);
	$('#id_master_adminid').val(g_args.master_adminid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">require_adminid</span>
                <input class="opt-change form-control" id="id_require_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">master_flag</span>
                <input class="opt-change form-control" id="id_master_flag" />
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
                <span class="input-group-addon">success_flag</span>
                <input class="opt-change form-control" id="id_success_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_confirm_flag</span>
                <input class="opt-change form-control" id="id_order_confirm_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">master_adminid</span>
                <input class="opt-change form-control" id="id_master_adminid" />
            </div>
        </div>
*/
