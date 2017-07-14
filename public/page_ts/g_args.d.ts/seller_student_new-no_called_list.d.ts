interface GargsStatic {
	page_num:	number;
	tmk_flag:	number;
	grade:	number;//App\Enums\Egrade 
	has_pad:	number;//App\Enums\Epad_type 
	subject:	number;//App\Enums\Esubject 
	origin:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: string;
declare var g_adminid: string;
interface RowData {
	test_lesson_subject_id	:any;
	add_time	:any;
	userid	:any;
	phone	:any;
	phone_location	:any;
	grade	:any;
	subject	:any;
	has_pad	:any;
	origin	:any;
	has_pad_str	:any;
	subject_str	:any;
	grade_str	:any;
	phone_hide	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student_new; vi  ../seller_student_new/no_called_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-no_called_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			tmk_flag:	$('#id_tmk_flag').val(),
			grade:	$('#id_grade').val(),
			has_pad:	$('#id_has_pad').val(),
			subject:	$('#id_subject').val(),
			origin:	$('#id_origin').val()
        });
    }

	Enum_map.append_option_list("grade",$("#id_grade")); 
	Enum_map.append_option_list("pad_type",$("#id_has_pad")); 
	Enum_map.append_option_list("subject",$("#id_subject")); 

	$('#id_tmk_flag').val(g_args.tmk_flag);
	$('#id_grade').val(g_args.grade);
	$('#id_has_pad').val(g_args.has_pad);
	$('#id_subject').val(g_args.subject);
	$('#id_origin').val(g_args.origin);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">tmk_flag</span>
                <input class="opt-change form-control" id="id_tmk_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">年级</span>
                <select class="opt-change form-control" id="id_grade" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">Pad</span>
                <select class="opt-change form-control" id="id_has_pad" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">科目</span>
                <select class="opt-change form-control" id="id_subject" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin</span>
                <input class="opt-change form-control" id="id_origin" />
            </div>
        </div>
*/
