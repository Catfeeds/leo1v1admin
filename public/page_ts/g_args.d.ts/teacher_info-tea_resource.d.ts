interface GargsStatic {
	cur_dir:	string;
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
	file_title	:any;
	file_type	:any;
	file_size	:any;
	create_time	:any;
	tea_res_id	:any;
	file_id	:any;
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
		cur_dir:	$('#id_cur_dir').val()
		});
}
$(function(){


	$('#id_cur_dir').val(g_args.cur_dir);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">cur_dir</span>
                <input class="opt-change form-control" id="id_cur_dir" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["cur_dir title", "cur_dir", "th_cur_dir" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
