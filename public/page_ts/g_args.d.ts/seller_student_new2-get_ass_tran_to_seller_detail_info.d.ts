interface GargsStatic {
	page_num:	number;
	page_count:	number;
	assistantid:	number;
	campus_id:	number;
	groupid:	number;
	leader_flag:	number;
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
	 mkdir -p ../seller_student_new2; vi  ../seller_student_new2/get_ass_tran_to_seller_detail_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new2-get_ass_tran_to_seller_detail_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			assistantid:	$('#id_assistantid').val(),
			campus_id:	$('#id_campus_id').val(),
			groupid:	$('#id_groupid').val(),
			leader_flag:	$('#id_leader_flag').val()
        });
    }


	$('#id_assistantid').val(g_args.assistantid);
	$('#id_campus_id').val(g_args.campus_id);
	$('#id_groupid').val(g_args.groupid);
	$('#id_leader_flag').val(g_args.leader_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">campus_id</span>
                <input class="opt-change form-control" id="id_campus_id" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">groupid</span>
                <input class="opt-change form-control" id="id_groupid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">leader_flag</span>
                <input class="opt-change form-control" id="id_leader_flag" />
            </div>
        </div>
*/
