interface GargsStatic {
	parent_cid:	number;
	cid:	number;
	status:	number;
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
	id	:any;
	cid	:any;
	open_iid	:any;
	title	:any;
	pict_url	:any;
	price	:any;
	last_modified	:any;
	product_id	:any;
	status	:any;
	sort_order	:any;
	last_modified_str	:any;
	status_str	:any;
	product_str	:any;
}

/*

tofile: 
	 mkdir -p ../taobao_manage; vi  ../taobao_manage/taobao_item.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/taobao_manage-taobao_item.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			parent_cid:	$('#id_parent_cid').val(),
			cid:	$('#id_cid').val(),
			status:	$('#id_status').val()
        });
    }


	$('#id_parent_cid').val(g_args.parent_cid);
	$('#id_cid').val(g_args.cid);
	$('#id_status').val(g_args.status);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">parent_cid</span>
                <input class="opt-change form-control" id="id_parent_cid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">cid</span>
                <input class="opt-change form-control" id="id_cid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">status</span>
                <input class="opt-change form-control" id="id_status" />
            </div>
        </div>
*/
