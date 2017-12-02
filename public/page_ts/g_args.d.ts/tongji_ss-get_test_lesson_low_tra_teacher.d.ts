interface GargsStatic {
	subject:	number;
	limit_plan_lesson_type:	number;
	is_record_flag:	number;
	is_do_sth:	number;
	wx_type:	number;
	start_time_ex:	number;
	end_time_ex:	number;
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
	 mkdir -p ../tongji_ss; vi  ../tongji_ss/get_test_lesson_low_tra_teacher.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/tongji_ss-get_test_lesson_low_tra_teacher.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			subject:	$('#id_subject').val(),
			limit_plan_lesson_type:	$('#id_limit_plan_lesson_type').val(),
			is_record_flag:	$('#id_is_record_flag').val(),
			is_do_sth:	$('#id_is_do_sth').val(),
			wx_type:	$('#id_wx_type').val(),
			start_time_ex:	$('#id_start_time_ex').val(),
			end_time_ex:	$('#id_end_time_ex').val()
        });
    }


	$('#id_subject').val(g_args.subject);
	$('#id_limit_plan_lesson_type').val(g_args.limit_plan_lesson_type);
	$('#id_is_record_flag').val(g_args.is_record_flag);
	$('#id_is_do_sth').val(g_args.is_do_sth);
	$('#id_wx_type').val(g_args.wx_type);
	$('#id_start_time_ex').val(g_args.start_time_ex);
	$('#id_end_time_ex').val(g_args.end_time_ex);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">subject</span>
                <input class="opt-change form-control" id="id_subject" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">limit_plan_lesson_type</span>
                <input class="opt-change form-control" id="id_limit_plan_lesson_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_record_flag</span>
                <input class="opt-change form-control" id="id_is_record_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_do_sth</span>
                <input class="opt-change form-control" id="id_is_do_sth" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">wx_type</span>
                <input class="opt-change form-control" id="id_wx_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">start_time_ex</span>
                <input class="opt-change form-control" id="id_start_time_ex" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">end_time_ex</span>
                <input class="opt-change form-control" id="id_end_time_ex" />
            </div>
        </div>
*/
