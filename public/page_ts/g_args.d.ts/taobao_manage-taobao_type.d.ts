interface GargsStatic {
	type:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	cid	:any;
	name	:any;
	sort_order	:any;
	type	:any;
	type_str	:any;
}

/*

tofile: 
	 mkdir -p ../taobao_manage; vi  ../taobao_manage/taobao_type.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/taobao_manage-taobao_type.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			type:	$('#id_type').val()
        });
    }


	$('#id_type').val(g_args.type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">type</span>
                <input class="opt-change form-control" id="id_type" />
            </div>
        </div>
*/
