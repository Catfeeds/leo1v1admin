interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	error_type:	number;
	sub_error_type:	number;
	use_type:	number;
	resource_type:	number;
	subject:	number;
	grade:	number;
	tag_one:	number;
	tag_two:	number;
	tag_three:	number;
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
	file_title	:any;
	file_size	:any;
	file_type	:any;
	ex_num	:any;
	file_hash	:any;
	file_link	:any;
	file_id	:any;
	file_use_type	:any;
	use_type	:any;
	resource_id	:any;
	resource_type	:any;
	subject	:any;
	grade	:any;
	tag_one	:any;
	tag_two	:any;
	tag_three	:any;
	tag_four	:any;
	tag_five	:any;
	tag_four_str	:any;
	create_time	:any;
	visitor_id	:any;
	c_time	:any;
	id	:any;
	teacherid	:any;
	add_time	:any;
	error_type	:any;
	sub_error_type	:any;
	detail_error	:any;
	error_picture	:any;
	detail_question	:any;
	train_error_type	:any;
	phone	:any;
	error_nick	:any;
	etype	:any;
	estatus	:any;
	file_use_type_str	:any;
	nick	:any;
	error_type_str	:any;
	sub_error_type_str	:any;
	tag_one_name	:any;
	tag_two_name	:any;
	tag_three_name	:any;
	tag_four_name	:any;
	tag_five_name	:any;
	file_size_str	:any;
	picture_one	:any;
	picture_two	:any;
	picture_three	:any;
	picture_four	:any;
	picture_five	:any;
	subject_str	:any;
	grade_str	:any;
	resource_type_str	:any;
	use_type_str	:any;
	tag_one_str	:any;
	tag_two_str	:any;
	tag_five_str	:any;
}

/*

tofile: 
	 mkdir -p ../resource_new; vi  ../resource_new/get_error.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/resource_new-get_error.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		error_type:	$('#id_error_type').val(),
		sub_error_type:	$('#id_sub_error_type').val(),
		use_type:	$('#id_use_type').val(),
		resource_type:	$('#id_resource_type').val(),
		subject:	$('#id_subject').val(),
		grade:	$('#id_grade').val(),
		tag_one:	$('#id_tag_one').val(),
		tag_two:	$('#id_tag_two').val(),
		tag_three:	$('#id_tag_three').val(),
		tag_four:	$('#id_tag_four').val(),
		tag_five:	$('#id_tag_five').val(),
		file_title:	$('#id_file_title').val(),
		file_id:	$('#id_file_id').val()
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
	$('#id_error_type').val(g_args.error_type);
	$('#id_sub_error_type').val(g_args.sub_error_type);
	$('#id_use_type').val(g_args.use_type);
	$('#id_resource_type').val(g_args.resource_type);
	$('#id_subject').val(g_args.subject);
	$('#id_grade').val(g_args.grade);
	$('#id_tag_one').val(g_args.tag_one);
	$('#id_tag_two').val(g_args.tag_two);
	$('#id_tag_three').val(g_args.tag_three);
	$('#id_tag_four').val(g_args.tag_four);
	$('#id_tag_five').val(g_args.tag_five);
	$('#id_file_title').val(g_args.file_title);
	$('#id_file_id').val(g_args.file_id);


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
                <span class="input-group-addon">error_type</span>
                <input class="opt-change form-control" id="id_error_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["error_type title", "error_type", "th_error_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">sub_error_type</span>
                <input class="opt-change form-control" id="id_sub_error_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["sub_error_type title", "sub_error_type", "th_sub_error_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">use_type</span>
                <input class="opt-change form-control" id="id_use_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["use_type title", "use_type", "th_use_type" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">resource_type</span>
                <input class="opt-change form-control" id="id_resource_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["resource_type title", "resource_type", "th_resource_type" ]])!!}

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
                <span class="input-group-addon">tag_one</span>
                <input class="opt-change form-control" id="id_tag_one" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["tag_one title", "tag_one", "th_tag_one" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">tag_two</span>
                <input class="opt-change form-control" id="id_tag_two" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["tag_two title", "tag_two", "th_tag_two" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">tag_three</span>
                <input class="opt-change form-control" id="id_tag_three" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["tag_three title", "tag_three", "th_tag_three" ]])!!}

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
