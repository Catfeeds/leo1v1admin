interface GargsStatic {
	tag_four:	number;
	tag_five:	number;
	file_title:	string;
	file_id:	string;
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
}

/*

tofile: 
	 mkdir -p ../test_paper; vi  ../test_paper/input_paper.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test_paper-input_paper.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		tag_four:	$('#id_tag_four').val(),
		tag_five:	$('#id_tag_five').val(),
		file_title:	$('#id_file_title').val(),
		file_id:	$('#id_file_id').val()
		});
}
$(function(){


	$('#id_tag_four').val(g_args.tag_four);
	$('#id_tag_five').val(g_args.tag_five);
	$('#id_file_title').val(g_args.file_title);
	$('#id_file_id').val(g_args.file_id);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">tag_four</span>
                <input class="opt-change form-control" id="id_tag_four" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["tag_four title", "tag_four", "th_tag_four" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">tag_five</span>
                <input class="opt-change form-control" id="id_tag_five" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["tag_five title", "tag_five", "th_tag_five" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">file_title</span>
                <input class="opt-change form-control" id="id_file_title" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["file_title title", "file_title", "th_file_title" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">file_id</span>
                <input class="opt-change form-control" id="id_file_id" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["file_id title", "file_id", "th_file_id" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
