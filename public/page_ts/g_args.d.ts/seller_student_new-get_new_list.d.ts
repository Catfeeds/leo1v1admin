interface GargsStatic {
	t_flag:	number;
	page_num:	number;
	page_count:	number;
	grade:	number;//App\Enums\Egrade
	has_pad:	number;//App\Enums\Epad_type
	subject:	number;//App\Enums\Esubject
	origin:	string;
	phone:	string;
	seller_level:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
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
	origin_level	:any;
	has_pad_str	:any;
	subject_str	:any;
	grade_str	:any;
	phone_hide	:any;
	origin_level_str	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student_new; vi  ../seller_student_new/get_new_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student_new-get_new_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			t_flag:	$('#id_t_flag').val(),
			grade:	$('#id_grade').val(),
			has_pad:	$('#id_has_pad').val(),
			subject:	$('#id_subject').val(),
			origin:	$('#id_origin').val(),
			phone:	$('#id_phone').val(),
			seller_level:	$('#id_seller_level').val()
        });
    }

	Enum_map.append_option_list("grade",$("#id_grade"));
	Enum_map.append_option_list("pad_type",$("#id_has_pad"));
	Enum_map.append_option_list("subject",$("#id_subject"));

	$('#id_t_flag').val(g_args.t_flag);
	$('#id_grade').val(g_args.grade);
	$('#id_has_pad').val(g_args.has_pad);
	$('#id_subject').val(g_args.subject);
	$('#id_origin').val(g_args.origin);
	$('#id_phone').val(g_args.phone);
	$('#id_seller_level').val(g_args.seller_level);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">t_flag</span>
                <input class="opt-change form-control" id="id_t_flag" />
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

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">phone</span>
                <input class="opt-change form-control" id="id_phone" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_level</span>
                <input class="opt-change form-control" id="id_seller_level" />
            </div>
        </div>
*/
