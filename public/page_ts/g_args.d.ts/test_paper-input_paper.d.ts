interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	subject:	number;
	grade:	number;
	paper_id:	number;
	book:	number;
	volume:	number;
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
	paper_id	:any;
	paper_name	:any;
	subject	:any;
	grade	:any;
	book	:any;
	volume	:any;
	status	:any;
	adminid	:any;
	modify_time	:any;
	answer	:any;
	dimension	:any;
	question_bind	:any;
	suggestion	:any;
	use_arr	:any;
	subject_str	:any;
	grade_str	:any;
	volume_str	:any;
	book_str	:any;
	operator	:any;
	edit_time	:any;
	use_number	:any;
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
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		subject:	$('#id_subject').val(),
		grade:	$('#id_grade').val(),
		paper_id:	$('#id_paper_id').val(),
		book:	$('#id_book').val(),
		volume:	$('#id_volume').val()
		});
}
$(function(){


	$('#id_date_range').select_date_range({
		'date_type' : g_args.date_type,
		'opt_date_type' : g_args.opt_date_type,
		'start_time'    : g_args.start_time,
		'end_time'      : g_args.end_time,
		date_type_config : JSON.parse( g_args.date_type_config),
		onQuery :function() {
			load_data();
		});
	$('#id_subject').val(g_args.subject);
	$('#id_grade').val(g_args.grade);
	$('#id_paper_id').val(g_args.paper_id);
	$('#id_book').val(g_args.book);
	$('#id_volume').val(g_args.volume);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...
{!!\App\Helper\Utils::th_order_gen([["date_type_config title", "date_type_config", "th_date_type_config" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["date_type title", "date_type", "th_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["opt_date_type title", "opt_date_type", "th_opt_date_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["start_time title", "start_time", "th_start_time" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["end_time title", "end_time", "th_end_time" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">subject</span>
                <input class="opt-change form-control" id="id_subject" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["subject title", "subject", "th_subject" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade</span>
                <input class="opt-change form-control" id="id_grade" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["grade title", "grade", "th_grade" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">paper_id</span>
                <input class="opt-change form-control" id="id_paper_id" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["paper_id title", "paper_id", "th_paper_id" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">book</span>
                <input class="opt-change form-control" id="id_book" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["book title", "book", "th_book" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">volume</span>
                <input class="opt-change form-control" id="id_volume" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["volume title", "volume", "th_volume" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
