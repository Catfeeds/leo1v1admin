interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	confirm_flag:	number;//App\Enums\Eboolean 
	pay_flag:	number;//App\Enums\Eboolean 
	show_add_money_flag:	number;
	check_adminid:	number;
	has_check_adminid_flag:	number;//App\Enums\Eboolean 
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
interface RowData {
	check_adminid	:any;
	teacherid	:any;
	realname	:any;
	nick	:any;
	teacher_money_type	:any;
	level	:any;
	l1v1_lesson_count	:any;
	test_lesson_count	:any;
	all_lesson_money	:any;
	index	:any;
	teacher_money_type_str	:any;
	level_str	:any;
	real_all_count	:any;
	real_l1v1_count	:any;
	real_test_count	:any;
	real_money_all_count	:any;
	real_money_l1v1_count	:any;
	real_money_test_count	:any;
	confirm_flag	:any;
	confirm_time	:any;
	confirm_adminid	:any;
	pay_flag	:any;
	pay_time	:any;
	pay_adminid	:any;
	confirm_flag_str	:any;
	pay_flag_str	:any;
	confirm_admin_nick	:any;
	pay_admin_nick	:any;
	check_admin_nick	:any;
	all_count	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new ; vi  ../user_manage_new/tea_lesson_count_total_list_tea.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-tea_lesson_count_total_list_tea.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			confirm_flag:	$('#id_confirm_flag').val(),
			pay_flag:	$('#id_pay_flag').val(),
			show_add_money_flag:	$('#id_show_add_money_flag').val(),
			check_adminid:	$('#id_check_adminid').val(),
			has_check_adminid_flag:	$('#id_has_check_adminid_flag').val()
        });
    }

	Enum_map.append_option_list("boolean",$("#id_confirm_flag")); 
	Enum_map.append_option_list("boolean",$("#id_pay_flag")); 
	Enum_map.append_option_list("boolean",$("#id_has_check_adminid_flag")); 

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
	$('#id_confirm_flag').val(g_args.confirm_flag);
	$('#id_pay_flag').val(g_args.pay_flag);
	$('#id_show_add_money_flag').val(g_args.show_add_money_flag);
	$('#id_check_adminid').val(g_args.check_adminid);
	$('#id_has_check_adminid_flag').val(g_args.has_check_adminid_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_confirm_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_pay_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">show_add_money_flag</span>
                <input class="opt-change form-control" id="id_show_add_money_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">check_adminid</span>
                <input class="opt-change form-control" id="id_check_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_has_check_adminid_flag" >
                </select>
            </div>
        </div>
*/
