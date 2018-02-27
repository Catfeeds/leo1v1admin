interface GargsStatic {
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
	has_comment:	number;
	has_error:	number;
	id_order:	number;
	paper_assort:	number;
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
	 mkdir -p ../resource; vi  ../resource/get_all.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/resource-get_all.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
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
		has_comment:	$('#id_has_comment').val(),
		has_error:	$('#id_has_error').val(),
		id_order:	$('#id_id_order').val(),
		paper_assort:	$('#id_paper_assort').val()
		});
}
$(function(){


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
	$('#id_has_comment').val(g_args.has_comment);
	$('#id_has_error').val(g_args.has_error);
	$('#id_id_order').val(g_args.id_order);
	$('#id_paper_assort').val(g_args.paper_assort);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

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
                <span class="input-group-addon">has_comment</span>
                <input class="opt-change form-control" id="id_has_comment" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["has_comment title", "has_comment", "th_has_comment" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">has_error</span>
                <input class="opt-change form-control" id="id_has_error" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["has_error title", "has_error", "th_has_error" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">id_order</span>
                <input class="opt-change form-control" id="id_id_order" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["id_order title", "id_order", "th_id_order" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">paper_assort</span>
                <input class="opt-change form-control" id="id_paper_assort" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["paper_assort title", "paper_assort", "th_paper_assort" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
