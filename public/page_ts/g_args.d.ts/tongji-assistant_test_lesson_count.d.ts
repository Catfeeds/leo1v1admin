interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	ass_test_lesson_type:	number;//App\Enums\Eass_test_lesson_type
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
	 mkdir -p ../tongji; vi  ../tongji/assistant_test_lesson_count.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji-assistant_test_lesson_count.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			ass_test_lesson_type:	$('#id_ass_test_lesson_type').val()
        });
    }

	Enum_map.append_option_list("ass_test_lesson_type",$("#id_ass_test_lesson_type"));

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
	$('#id_ass_test_lesson_type').val(g_args.ass_test_lesson_type);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">助教试听分类</span>
                <select class="opt-change form-control" id="id_ass_test_lesson_type" >
                </select>
            </div>
        </div>
*/
