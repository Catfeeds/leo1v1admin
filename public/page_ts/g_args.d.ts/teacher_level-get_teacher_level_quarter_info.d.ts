interface GargsStatic {
	teacher_money_type:	number;
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
	 mkdir -p ../teacher_level; vi  ../teacher_level/get_teacher_level_quarter_info.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/teacher_level-get_teacher_level_quarter_info.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			teacher_money_type:	$('#id_teacher_money_type').val()
        });
    }


	$('#id_teacher_money_type').val(g_args.teacher_money_type);


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
*/
