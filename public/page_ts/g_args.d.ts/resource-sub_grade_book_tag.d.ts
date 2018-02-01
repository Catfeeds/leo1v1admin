interface GargsStatic {
	subject:	number;
	grade:	number;
	bookid:	number;
	season_id:	number;
	resource_type:	number;
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
	subject	:any;
	grade	:any;
	bookid	:any;
	tag	:any;
	del_flag	:any;
	resource_type	:any;
	season_id	:any;
	subject_str	:any;
	grade_str	:any;
	book_str	:any;
	resource_str	:any;
	season_str	:any;
}

/*

tofile: 
	 mkdir -p ../resource; vi  ../resource/sub_grade_book_tag.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/resource-sub_grade_book_tag.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		subject:	$('#id_subject').val(),
		grade:	$('#id_grade').val(),
		bookid:	$('#id_bookid').val(),
		season_id:	$('#id_season_id').val(),
		resource_type:	$('#id_resource_type').val()
		});
}
$(function(){


	$('#id_subject').val(g_args.subject);
	$('#id_grade').val(g_args.grade);
	$('#id_bookid').val(g_args.bookid);
	$('#id_season_id').val(g_args.season_id);
	$('#id_resource_type').val(g_args.resource_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

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
                <span class="input-group-addon">bookid</span>
                <input class="opt-change form-control" id="id_bookid" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["bookid title", "bookid", "th_bookid" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">season_id</span>
                <input class="opt-change form-control" id="id_season_id" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["season_id title", "season_id", "th_season_id" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">resource_type</span>
                <input class="opt-change form-control" id="id_resource_type" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["resource_type title", "resource_type", "th_resource_type" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_num title", "page_num", "th_page_num" ]])!!}
{!!\App\Helper\Utils::th_order_gen([["page_count title", "page_count", "th_page_count" ]])!!}
*/
