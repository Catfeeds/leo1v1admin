interface GargsStatic {
	teacher_money_type:	number;
	level:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: string;
declare var g_adminid: string;
interface RowData {
	lesson_count	:any;
	f_101	:any;
	f_102	:any;
	f_103	:any;
	f_104	:any;
	f_105	:any;
	f_106	:any;
	f_201	:any;
	f_202	:any;
	f_203	:any;
	f_301	:any;
	f_302	:any;
	f_303	:any;
}

/*

tofile: 
	 mkdir -p ../user_manage_new; vi  ../user_manage_new/get_teacher_money_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/user_manage_new-get_teacher_money_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			teacher_money_type:	$('#id_teacher_money_type').val(),
			level:	$('#id_level').val()
        });
    }


	$('#id_teacher_money_type').val(g_args.teacher_money_type);
	$('#id_level').val(g_args.level);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacher_money_type</span>
                <input class="opt-change form-control" id="id_teacher_money_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">level</span>
                <input class="opt-change form-control" id="id_level" />
            </div>
        </div>
*/
