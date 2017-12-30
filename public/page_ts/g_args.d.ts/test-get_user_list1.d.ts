interface GargsStatic {
	page_num:	number;
	page_count:	number;
	grade:	string;//枚举列表: \App\Enums\Egrade
 	query_text:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	userid	:any;
	nick	:any;
	realname	:any;
	phone	:any;
	grade	:any;
	grade_str	:any;
}

/*

tofile: 
	 mkdir -p ../test; vi  ../test/get_user_list1.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test-get_user_list1.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		grade:	$('#id_grade').val(),
		query_text:	$('#id_query_text').val()
		});
}
$(function(){


	$('#id_grade').admin_set_select_field({
		"enum_type"    : "grade",
		"field_name" : "grade",
		"select_value" : g_args.grade,
		"multi_select_flag"     : true,
		"onChange"     : load_data,
		"th_input_id"  : "th_grade",
		"only_show_in_th_input"     : false,
		"btn_id_config"     : {},
	});
	$('#id_query_text').val(g_args.query_text);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade</span>
                <input class="opt-change form-control" id="id_grade" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["grade title", "grade", "th_grade" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">query_text</span>
                <input class="opt-change form-control" id="id_query_text" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["query_text title", "query_text", "th_query_text" ]])!!}
*/
