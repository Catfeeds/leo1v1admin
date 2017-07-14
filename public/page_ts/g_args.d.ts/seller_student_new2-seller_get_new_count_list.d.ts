interface GargsStatic {
	adminid:	number;
	seller_new_count_type:	number;//App\Enums\Eseller_new_count_type
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
	adminid	:any;
	add_time	:any;
	start_time	:any;
	end_time	:any;
	seller_new_count_type	:any;
	value_ex	:any;
	count	:any;
	get_count	:any;
	admin_nick	:any;
	seller_new_count_type_str	:any;
	value_ex_str	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student_new2; vi  ../seller_student_new2/seller_get_new_count_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-seller_get_new_count_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			adminid:	$('#id_adminid').val(),
			seller_new_count_type:	$('#id_seller_new_count_type').val()
        });
    }

	Enum_map.append_option_list("seller_new_count_type",$("#id_seller_new_count_type"));

	$('#id_adminid').val(g_args.adminid);
	$('#id_seller_new_count_type').val(g_args.seller_new_count_type);


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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">例子赠送类型</span>
                <select class="opt-change form-control" id="id_seller_new_count_type" >
                </select>
            </div>
        </div>
*/
