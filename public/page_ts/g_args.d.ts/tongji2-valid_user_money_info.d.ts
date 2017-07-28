interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	origin_ex:	string;
	grade:	number;//\App\Enums\Egrade
	phone_location:	string;
	origin_from_user_flag:	number;//\App\Enums\Eboolean
	competition_flag:	number;//\App\Enums\Eboolean
	subject:	number;//\App\Enums\Esubject
	date_type_config_1:	string;
	date_type_1:	number;
	opt_date_type_1:	number;
	start_time_1:	string;
	end_time_1:	string;
	check_field_id:	number;
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
}

/*

tofile: 
	 mkdir -p ../tongji2; vi  ../tongji2/valid_user_money_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji2-valid_user_money_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			origin_ex:	$('#id_origin_ex').val(),
			grade:	$('#id_grade').val(),
			phone_location:	$('#id_phone_location').val(),
			origin_from_user_flag:	$('#id_origin_from_user_flag').val(),
			competition_flag:	$('#id_competition_flag').val(),
			subject:	$('#id_subject').val(),
			date_type_config_1:	$('#id_date_type_config_1').val(),
			date_type_1:	$('#id_date_type_1').val(),
			opt_date_type_1:	$('#id_opt_date_type_1').val(),
			start_time_1:	$('#id_start_time_1').val(),
			end_time_1:	$('#id_end_time_1').val(),
			check_field_id:	$('#id_check_field_id').val()
        });
    }

	Enum_map.append_option_list("grade",$("#id_grade"));
	Enum_map.append_option_list("boolean",$("#id_origin_from_user_flag"));
	Enum_map.append_option_list("boolean",$("#id_competition_flag"));
	Enum_map.append_option_list("subject",$("#id_subject"));

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
	$('#id_grade').val(g_args.grade);
	$('#id_phone_location').val(g_args.phone_location);
	$('#id_origin_from_user_flag').val(g_args.origin_from_user_flag);
	$('#id_competition_flag').val(g_args.competition_flag);
	$('#id_subject').val(g_args.subject);
	$('#id_date_type_config_1').val(g_args.date_type_config_1);
	$('#id_date_type_1').val(g_args.date_type_1);
	$('#id_opt_date_type_1').val(g_args.opt_date_type_1);
	$('#id_start_time_1').val(g_args.start_time_1);
	$('#id_end_time_1').val(g_args.end_time_1);
	$('#id_check_field_id').val(g_args.check_field_id);


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
                <span class="input-group-addon">年级</span>
                <select class="opt-change form-control" id="id_grade" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone_location</span>
                <input class="opt-change form-control" id="id_phone_location" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_origin_from_user_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_competition_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">科目</span>
                <select class="opt-change form-control" id="id_subject" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">date_type_config_1</span>
                <input class="opt-change form-control" id="id_date_type_config_1" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">date_type_1</span>
                <input class="opt-change form-control" id="id_date_type_1" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">opt_date_type_1</span>
                <input class="opt-change form-control" id="id_opt_date_type_1" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">start_time_1</span>
                <input class="opt-change form-control" id="id_start_time_1" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">end_time_1</span>
                <input class="opt-change form-control" id="id_end_time_1" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">check_field_id</span>
                <input class="opt-change form-control" id="id_check_field_id" />
            </div>
        </div>
*/
