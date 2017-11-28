interface GargsStatic {
	tag_l1_sort:	number;
	tag_l2_sort:	number;
	tag_name:	string;
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
	tag_id	:any;
	tag_name	:any;
	tag_l1_sort	:any;
	tag_l2_sort	:any;
	tag_weight	:any;
	tag_object	:any;
	tag_desc	:any;
	create_time	:any;
	manager_id	:any;
	account	:any;
	tag_l1_sort_str	:any;
	tag_l2_sort_str	:any;
	tag_object_str	:any;
}

/*

tofile: 
	 mkdir -p ../product_tag; vi  ../product_tag/tag_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/product_tag-tag_list.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		tag_l1_sort:	$('#id_tag_l1_sort').val(),
		tag_l2_sort:	$('#id_tag_l2_sort').val(),
		tag_name:	$('#id_tag_name').val()
    });
}
$(function(){


	$('#id_tag_l1_sort').val(g_args.tag_l1_sort);
	$('#id_tag_l2_sort').val(g_args.tag_l2_sort);
	$('#id_tag_name').val(g_args.tag_name);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">tag_l1_sort</span>
                <input class="opt-change form-control" id="id_tag_l1_sort" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">tag_l2_sort</span>
                <input class="opt-change form-control" id="id_tag_l2_sort" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">tag_name</span>
                <input class="opt-change form-control" id="id_tag_name" />
            </div>
        </div>
*/
