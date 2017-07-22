interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	page_num:	number;
	page_count:	number;
	leader_flag:	number;
	assistantid:	number;
	ass_renw_flag:	number;
	master_renw_flag:	number;
	renw_week:	number;
	end_week:	number;
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
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/ass_warning_stu_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-ass_warning_stu_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			leader_flag:	$('#id_leader_flag').val(),
			assistantid:	$('#id_assistantid').val(),
			ass_renw_flag:	$('#id_ass_renw_flag').val(),
			master_renw_flag:	$('#id_master_renw_flag').val(),
			renw_week:	$('#id_renw_week').val(),
			end_week:	$('#id_end_week').val()
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
	$('#id_leader_flag').val(g_args.leader_flag);
	$('#id_assistantid').val(g_args.assistantid);
	$('#id_ass_renw_flag').val(g_args.ass_renw_flag);
	$('#id_master_renw_flag').val(g_args.master_renw_flag);
	$('#id_renw_week').val(g_args.renw_week);
	$('#id_end_week').val(g_args.end_week);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">leader_flag</span>
                <input class="opt-change form-control" id="id_leader_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">ass_renw_flag</span>
                <input class="opt-change form-control" id="id_ass_renw_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">master_renw_flag</span>
                <input class="opt-change form-control" id="id_master_renw_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">renw_week</span>
                <input class="opt-change form-control" id="id_renw_week" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">end_week</span>
                <input class="opt-change form-control" id="id_end_week" />
            </div>
        </div>
*/
