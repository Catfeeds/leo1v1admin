interface GargsStatic {
	type:	number;
	usage_type:	number;
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
	type	:any;
	name	:any;
	time_type	:any;
	created_at	:any;
	updated_at	:any;
	order_by	:any;
	usage_type	:any;
	img_tags_url	:any;
	img_url	:any;
	status	:any;
	subject	:any;
	grade	:any;
	start_time	:any;
	end_time	:any;
	jump_url	:any;
	title_share	:any;
	info_share	:any;
	jump_type	:any;
	type_str	:any;
	time_type_str	:any;
	usage_type_str	:any;
}

/*

tofile: 
	 mkdir -p ../pic_manage; vi  ../pic_manage/pic_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/pic_manage-pic_info.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		type:	$('#id_type').val(),
		usage_type:	$('#id_usage_type').val()
		});
}
$(function(){


	$('#id_type').val(g_args.type);
	$('#id_usage_type').val(g_args.usage_type);


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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">usage_type</span>
                <input class="opt-change form-control" id="id_usage_type" />
            </div>
        </div>
*/
