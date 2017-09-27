interface GargsStatic {
	page_num:	number;
	page_count:	number;
	leader_flag:	number;
	assistantid:	number;
	ass_renw_flag:	number;
	master_renw_flag:	number;
	renw_week:	number;
	end_week:	number;
	done_flag:	number;
	id:	number;
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
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/ass_warning_stu_info_new.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-ass_warning_stu_info_new.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			leader_flag:	$('#id_leader_flag').val(),
			assistantid:	$('#id_assistantid').val(),
			ass_renw_flag:	$('#id_ass_renw_flag').val(),
			master_renw_flag:	$('#id_master_renw_flag').val(),
			renw_week:	$('#id_renw_week').val(),
			end_week:	$('#id_end_week').val(),
			done_flag:	$('#id_done_flag').val(),
			id:	$('#id_id').val()
        });
    }


	$('#id_leader_flag').val(g_args.leader_flag);
	$('#id_assistantid').val(g_args.assistantid);
	$('#id_ass_renw_flag').val(g_args.ass_renw_flag);
	$('#id_master_renw_flag').val(g_args.master_renw_flag);
	$('#id_renw_week').val(g_args.renw_week);
	$('#id_end_week').val(g_args.end_week);
	$('#id_done_flag').val(g_args.done_flag);
	$('#id_id').val(g_args.id);


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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">done_flag</span>
                <input class="opt-change form-control" id="id_done_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">id</span>
                <input class="opt-change form-control" id="id_id" />
            </div>
        </div>
*/
