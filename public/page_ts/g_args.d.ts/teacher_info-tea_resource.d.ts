interface GargsStatic {
	dir_id:	number;
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
	dir_id	:any;
	name	:any;
	create_time	:any;
	file_title	:any;
	file_size	:any;
	file_id	:any;
	tea_res_id	:any;
}

/*

tofile: 
	 mkdir -p ../teacher_info; vi  ../teacher_info/tea_resource.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_info-tea_resource.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		dir_id:	$('#id_dir_id').val()
		});
}
$(function(){


	$('#id_dir_id').val(g_args.dir_id);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">dir_id</span>
                <input class="opt-change form-control" id="id_dir_id" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["dir_id title", "dir_id", "th_dir_id" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
