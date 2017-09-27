interface GargsStatic {
	grade:	number;
	subject:	number;
	test_type:	number;
	page_num:	number;
	page_count:	number;
	order_by_str:	string;
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
	test_title	:any;
	test_des	:any;
	grade	:any;
	subject	:any;
	visit_num	:any;
	share_num	:any;
	custom_type	:any;
	test_type	:any;
	poster	:any;
	create_time	:any;
	account	:any;
	grade_str	:any;
	subject_str	:any;
	test_type_str	:any;
	new_arr	:any;
}

/*

tofile: 
	 mkdir -p ../t_yxyx_test_pic_info; vi  ../t_yxyx_test_pic_info/get_all_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/t_yxyx_test_pic_info-get_all_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			grade:	$('#id_grade').val(),
			subject:	$('#id_subject').val(),
			test_type:	$('#id_test_type').val(),
			order_by_str:	$('#id_order_by_str').val()
        });
    }


	$('#id_grade').val(g_args.grade);
	$('#id_subject').val(g_args.subject);
	$('#id_test_type').val(g_args.test_type);
	$('#id_order_by_str').val(g_args.order_by_str);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade</span>
                <input class="opt-change form-control" id="id_grade" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">subject</span>
                <input class="opt-change form-control" id="id_subject" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_type</span>
                <input class="opt-change form-control" id="id_test_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_by_str</span>
                <input class="opt-change form-control" id="id_order_by_str" />
            </div>
        </div>
*/
