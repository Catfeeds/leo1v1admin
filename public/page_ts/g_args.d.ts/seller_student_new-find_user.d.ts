interface GargsStatic {
	phone:	string;
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
	add_time	:any;
	phone	:any;
	userid	:any;
	require_adminid	:any;
	admin_revisiterid	:any;
	nick	:any;
	subject	:any;
	seller_resource_type	:any;
	sub_assign_adminid_2	:any;
	subject_str	:any;
	seller_resource_type_str	:any;
	sub_assign_admin_2_nick	:any;
	admin_revisiter_nick	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student_new; vi  ../seller_student_new/find_user.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-find_user.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			phone:	$('#id_phone').val()
        });
    }


	$('#id_phone').val(g_args.phone);


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
*/
