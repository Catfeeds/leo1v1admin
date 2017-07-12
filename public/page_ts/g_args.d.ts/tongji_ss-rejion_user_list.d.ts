interface GargsStatic {
	origin_ex:	string;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	need_count:	number;
	seller_student_status:	number;//\App\Enums\Eseller_student_status 
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
	origin	:any;
	add_time	:any;
	userid	:any;
	nick	:any;
	phone	:any;
	has_pad	:any;
	grade	:any;
	admin_revisiterid	:any;
	count	:any;
	last_revisit_time	:any;
	grade_str	:any;
	has_pad_str	:any;
	admin_revisiter_nick	:any;
}

/*

tofile: 
	 mkdir -p ../tongji_ss; vi  ../tongji_ss/rejion_user_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-rejion_user_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			origin_ex:	$('#id_origin_ex').val(),
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			need_count:	$('#id_need_count').val(),
			seller_student_status:	$('#id_seller_student_status').val()
        });
    }

	Enum_map.append_option_list("seller_student_status",$("#id_seller_student_status"));

    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });
	$('#id_origin_ex').val(g_args.origin_ex);
	$('#id_need_count').val(g_args.need_count);
	$('#id_seller_student_status').val(g_args.seller_student_status);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin_ex</span>
                <input class="opt-change form-control" id="id_origin_ex" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">need_count</span>
                <input class="opt-change form-control" id="id_need_count" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_student_status</span>
                <select class="opt-change form-control" id="id_seller_student_status" >
                </select>
            </div>
        </div>
*/
