interface GargsStatic {
	subject:	number;
	province:	number;
	city:	number;
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
	province	:any;
	province_name	:any;
	city	:any;
	city_name	:any;
	book	:any;
	book_name	:any;
	is_del	:any;
	subject_str	:any;
	book_arr	:any;
}

/*

tofile: 
	 mkdir -p ../info_support; vi  ../info_support/get_books.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/info_support-get_books.d.ts" />

function load_data(){
	if ( window["g_load_data_flag"]) {return;}
		$.reload_self_page ( {
		order_by_str : g_args.order_by_str,
		subject:	$('#id_subject').val(),
		province:	$('#id_province').val(),
		city:	$('#id_city').val()
		});
}
$(function(){


	$('#id_subject').val(g_args.subject);
	$('#id_province').val(g_args.province);
	$('#id_city').val(g_args.city);


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
                <span class="input-group-addon">province</span>
                <input class="opt-change form-control" id="id_province" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["province title", "province", "th_province" ]])!!}

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">city</span>
                <input class="opt-change form-control" id="id_city" />
            </div>
        </div>
{!!\App\Helper\Utils::th_order_gen([["city title", "city", "th_city" ]])!!}
*/
