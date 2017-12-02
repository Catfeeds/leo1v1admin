interface GargsStatic {
	postid:	number;
	is_new_flag:	number;//\App\Enums\Eboolean
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	postid	:any;
	add_time	:any;
	phone	:any;
	phone_location	:any;
	name	:any;
	origin	:any;
	subject	:any;
	grade	:any;
	user_desc	:any;
	has_pad	:any;
	is_new_flag	:any;
	publish_time	:any;
	index	:any;
	grade_str	:any;
	subject_str	:any;
	has_pad_str	:any;
	is_new_flag_str	:any;
}

/*

tofile: 
	 mkdir -p ../upload_tmk; vi  ../upload_tmk/post_student_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/upload_tmk-post_student_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			postid:	$('#id_postid').val(),
			is_new_flag:	$('#id_is_new_flag').val()
        });
    }

	Enum_map.append_option_list("boolean",$("#id_is_new_flag"));

	$('#id_postid').val(g_args.postid);
	$('#id_is_new_flag').val(g_args.is_new_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">postid</span>
                <input class="opt-change form-control" id="id_postid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_is_new_flag" >
                </select>
            </div>
        </div>
*/
