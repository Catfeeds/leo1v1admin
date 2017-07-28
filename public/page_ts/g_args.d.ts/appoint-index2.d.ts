interface GargsStatic {
	package_type:	string;
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
	packageid	:any;
	package_pic	:any;
	package_name	:any;
	grade	:any;
	subject	:any;
	lesson_total	:any;
	effect_start	:any;
	effect_end	:any;
	tag_type	:any;
	current_price	:any;
	package_deadline	:any;
	package_type	:any;
	user_total	:any;
	package_tags	:any;
	package_type_str	:any;
}

/*

tofile: 
	 mkdir -p ../appoint; vi  ../appoint/index2.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/appoint-index2.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			package_type:	$('#id_package_type').val()
        });
    }


	$('#id_package_type').val(g_args.package_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">package_type</span>
                <input class="opt-change form-control" id="id_package_type" />
            </div>
        </div>
*/
