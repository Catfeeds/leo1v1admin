interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	todo_type:	string;//枚举列表: \App\Enums\Etodo_type
 	todo_status:	string;//枚举列表: \App\Enums\Etodo_status
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
	 mkdir -p ../self_manage; vi  ../self_manage/todo_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/self_manage-todo_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			todo_type:	$('#id_todo_type').val(),
			todo_status:	$('#id_todo_status').val()
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
	$('#id_todo_type').val(g_args.todo_type);
	$.enum_multi_select( $('#id_todo_type'), 'todo_type', function(){load_data();} )
	$('#id_todo_status').val(g_args.todo_status);
	$.enum_multi_select( $('#id_todo_status'), 'todo_status', function(){load_data();} )


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">todo_type</span>
                <input class="opt-change form-control" id="id_todo_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">todo_status</span>
                <input class="opt-change form-control" id="id_todo_status" />
            </div>
        </div>
*/
