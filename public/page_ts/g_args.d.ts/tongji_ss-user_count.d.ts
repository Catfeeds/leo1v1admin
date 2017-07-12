interface GargsStatic {
	order_by_str:	string;
	seller_groupid_ex:	string;
	check_add_time_count:	number;
	check_call_old_count:	number;
	grade:	string;//枚举列表: App\Enums\Egrade
 	stu_test_paper_flag:	number;//App\Enums\Eboolean
	check_first_revisit_time_count:	number;
	check_test_lesson_count:	number;
	check_order_count:	number;
	admin_revisiterid:	number;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	title	:any;
	add_time_count	:any;
	call_count	:any;
	call_old_count	:any;
	first_revisit_time_count	:any;
	after_24_first_revisit_time_count	:any;
	test_lesson_count	:any;
	test_lesson_count_succ	:any;
	seller_require_test_lesson_count	:any;
	test_lesson_count_succ_new	:any;
	seller_test_lesson_count	:any;
	seller_test_lesson_count_succ	:any;
	order_count	:any;
	order_count_new	:any;
	order_count_next	:any;
	test_lesson_count_fail_need_money	:any;
	test_lesson_count_fail_need_money_new	:any;
	seller_test_lesson_count_fail_need_money	:any;
	seller_test_lesson_count_fail_not_need_money	:any;
	test_lesson_count_change_time	:any;
	seller_test_lesson_count_fail_need_money_new	:any;
	seller_test_lesson_count_stu_tea_join_count	:any;
}

/*

tofile: 
	 mkdir -p ../tongji_ss; vi  ../tongji_ss/user_count.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-user_count.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			order_by_str:	$('#id_order_by_str').val(),
			seller_groupid_ex:	$('#id_seller_groupid_ex').val(),
			check_add_time_count:	$('#id_check_add_time_count').val(),
			check_call_old_count:	$('#id_check_call_old_count').val(),
			grade:	$('#id_grade').val(),
			stu_test_paper_flag:	$('#id_stu_test_paper_flag').val(),
			check_first_revisit_time_count:	$('#id_check_first_revisit_time_count').val(),
			check_test_lesson_count:	$('#id_check_test_lesson_count').val(),
			check_order_count:	$('#id_check_order_count').val(),
			admin_revisiterid:	$('#id_admin_revisiterid').val(),
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val()
        });
    }

	Enum_map.append_option_list("boolean",$("#id_stu_test_paper_flag"));

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
	$('#id_order_by_str').val(g_args.order_by_str);
	$('#id_seller_groupid_ex').val(g_args.seller_groupid_ex);
	$('#id_check_add_time_count').val(g_args.check_add_time_count);
	$('#id_check_call_old_count').val(g_args.check_call_old_count);
	$('#id_grade').val(g_args.grade);
	$.enum_multi_select( $('#id_grade'), 'grade', function(){load_data();} )
	$('#id_stu_test_paper_flag').val(g_args.stu_test_paper_flag);
	$('#id_check_first_revisit_time_count').val(g_args.check_first_revisit_time_count);
	$('#id_check_test_lesson_count').val(g_args.check_test_lesson_count);
	$('#id_check_order_count').val(g_args.check_order_count);
	$('#id_admin_revisiterid').val(g_args.admin_revisiterid);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">order_by_str</span>
                <input class="opt-change form-control" id="id_order_by_str" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_groupid_ex</span>
                <input class="opt-change form-control" id="id_seller_groupid_ex" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">check_add_time_count</span>
                <input class="opt-change form-control" id="id_check_add_time_count" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">check_call_old_count</span>
                <input class="opt-change form-control" id="id_check_call_old_count" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade</span>
                <input class="opt-change form-control" id="id_grade" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_stu_test_paper_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">check_first_revisit_time_count</span>
                <input class="opt-change form-control" id="id_check_first_revisit_time_count" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">check_test_lesson_count</span>
                <input class="opt-change form-control" id="id_check_test_lesson_count" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">check_order_count</span>
                <input class="opt-change form-control" id="id_check_order_count" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">admin_revisiterid</span>
                <input class="opt-change form-control" id="id_admin_revisiterid" />
            </div>
        </div>
*/
