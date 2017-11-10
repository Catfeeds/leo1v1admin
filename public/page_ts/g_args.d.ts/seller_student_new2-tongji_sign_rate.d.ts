interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	flag:	number;
	is_green_flag:	number;
	is_down:	number;
	subject:	string;//枚举列表: \App\Enums\Esubject
 	phone_location:	string;
	grade:	string;//枚举列表: \App\Enums\Egrade
 	has_pad:	number;//App\Enums\Epad_type
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
	 mkdir -p ../seller_student_new2; vi  ../seller_student_new2/tongji_sign_rate.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-tongji_sign_rate.d.ts" />

function load_data(){
    if ( window["g_load_data_flag"]) {return;}
    $.reload_self_page ( {
		date_type_config:	$('#id_date_type_config').val(),
		date_type:	$('#id_date_type').val(),
		opt_date_type:	$('#id_opt_date_type').val(),
		start_time:	$('#id_start_time').val(),
		end_time:	$('#id_end_time').val(),
		flag:	$('#id_flag').val(),
		is_green_flag:	$('#id_is_green_flag').val(),
		is_down:	$('#id_is_down').val(),
		subject:	$('#id_subject').val(),
		phone_location:	$('#id_phone_location').val(),
		grade:	$('#id_grade').val(),
		has_pad:	$('#id_has_pad').val()
    });
}
$(function(){

	Enum_map.append_option_list("pad_type",$("#id_has_pad"));

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
	$('#id_flag').val(g_args.flag);
	$('#id_is_green_flag').val(g_args.is_green_flag);
	$('#id_is_down').val(g_args.is_down);
	$('#id_subject').val(g_args.subject);
	$.enum_multi_select( $('#id_subject'), 'subject', function(){load_data();} )
	$('#id_phone_location').val(g_args.phone_location);
	$('#id_grade').val(g_args.grade);
	$.enum_multi_select( $('#id_grade'), 'grade', function(){load_data();} )
	$('#id_has_pad').val(g_args.has_pad);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">flag</span>
                <input class="opt-change form-control" id="id_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_green_flag</span>
                <input class="opt-change form-control" id="id_is_green_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_down</span>
                <input class="opt-change form-control" id="id_is_down" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">subject</span>
                <input class="opt-change form-control" id="id_subject" />
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
                <span class="input-group-addon">grade</span>
                <input class="opt-change form-control" id="id_grade" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">Pad</span>
                <select class="opt-change form-control" id="id_has_pad" >
                </select>
            </div>
        </div>
*/
