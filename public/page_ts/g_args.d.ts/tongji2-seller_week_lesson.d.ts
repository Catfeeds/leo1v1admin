interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	group_adminid:	number;
	seller_groupid_ex:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	v_week_lesson_count	:any;
	v_week_require_lesson_count	:any;
	v_week_all_lesson_count	:any;
	v_week_need_lesson_count	:any;
	v_0_lesson_count	:any;
	v_0_require_lesson_count	:any;
	v_0_all_lesson_count	:any;
	v_0_need_lesson_count	:any;
	v_1_lesson_count	:any;
	v_1_require_lesson_count	:any;
	v_1_all_lesson_count	:any;
	v_1_need_lesson_count	:any;
	v_2_lesson_count	:any;
	v_2_require_lesson_count	:any;
	v_2_all_lesson_count	:any;
	v_2_need_lesson_count	:any;
	v_3_lesson_count	:any;
	v_3_require_lesson_count	:any;
	v_3_all_lesson_count	:any;
	v_3_need_lesson_count	:any;
	v_4_lesson_count	:any;
	v_4_require_lesson_count	:any;
	v_4_all_lesson_count	:any;
	v_4_need_lesson_count	:any;
	v_5_lesson_count	:any;
	v_5_require_lesson_count	:any;
	v_5_all_lesson_count	:any;
	v_5_need_lesson_count	:any;
	v_6_lesson_count	:any;
	v_6_require_lesson_count	:any;
	v_6_all_lesson_count	:any;
	v_6_need_lesson_count	:any;
	main_type	:any;
	up_group_name	:any;
	group_name	:any;
	account	:any;
	main_type_class	:any;
	up_group_name_class	:any;
	group_name_class	:any;
	account_class	:any;
	level	:any;
	main_type_str	:any;
}

/*

tofile: 
	 mkdir -p ../tongji2; vi  ../tongji2/seller_week_lesson.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-seller_week_lesson.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			group_adminid:	$('#id_group_adminid').val(),
			seller_groupid_ex:	$('#id_seller_groupid_ex').val()
        });
    }


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
	$('#id_group_adminid').val(g_args.group_adminid);
	$('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">group_adminid</span>
                <input class="opt-change form-control" id="id_group_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_groupid_ex</span>
                <input class="opt-change form-control" id="id_seller_groupid_ex" />
            </div>
        </div>
*/
