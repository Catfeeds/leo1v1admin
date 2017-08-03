interface GargsStatic {
	del_flag:	number;//\App\Enums\Eboolean
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
	count	:any;
	del_flag	:any;
	account	:any;
	del_flag_str	:any;
}

/*

tofile: 
	 mkdir -p ../tongji2; vi  ../tongji2/seller_student_admin_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-seller_student_admin_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			del_flag:	$('#id_del_flag').val()
        });
    }

	Enum_map.append_option_list("boolean",$("#id_del_flag"));

	$('#id_del_flag').val(g_args.del_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_del_flag" >
                </select>
            </div>
        </div>
*/
