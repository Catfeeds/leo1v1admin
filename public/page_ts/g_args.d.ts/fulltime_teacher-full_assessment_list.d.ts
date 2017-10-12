interface GargsStatic {
	fulltime_adminid:	number;
	tea_adminid:	number;
	time_flag:	number;
	acc:	string;
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
	 mkdir -p ../fulltime_teacher; vi  ../fulltime_teacher/full_assessment_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/fulltime_teacher-full_assessment_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			fulltime_adminid:	$('#id_fulltime_adminid').val(),
			tea_adminid:	$('#id_tea_adminid').val(),
			time_flag:	$('#id_time_flag').val(),
			acc:	$('#id_acc').val()
        });
    }


	$('#id_fulltime_adminid').val(g_args.fulltime_adminid);
	$('#id_tea_adminid').val(g_args.tea_adminid);
	$('#id_time_flag').val(g_args.time_flag);
	$('#id_acc').val(g_args.acc);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">fulltime_adminid</span>
                <input class="opt-change form-control" id="id_fulltime_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">tea_adminid</span>
                <input class="opt-change form-control" id="id_tea_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">time_flag</span>
                <input class="opt-change form-control" id="id_time_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">acc</span>
                <input class="opt-change form-control" id="id_acc" />
            </div>
        </div>
*/
