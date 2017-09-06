interface GargsStatic {
	textbook_check_flag:	number;
	user_name:	string;
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
	 mkdir -p ../human_resource; vi  ../human_resource/get_check_textbook_tea_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-get_check_textbook_tea_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			textbook_check_flag:	$('#id_textbook_check_flag').val(),
			user_name:	$('#id_user_name').val()
        });
    }


	$('#id_textbook_check_flag').val(g_args.textbook_check_flag);
	$('#id_user_name').val(g_args.user_name);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">textbook_check_flag</span>
                <input class="opt-change form-control" id="id_textbook_check_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">user_name</span>
                <input class="opt-change form-control" id="id_user_name" />
            </div>
        </div>
*/
