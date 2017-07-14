interface GargsStatic {
	start_date:	string;
	end_date:	string;
	origin:	string;
	grade:	number;//App\Enums\Egrade 
	subject:	number;//App\Enums\Esubject 
	phone:	string;
	page_num:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
interface RowData {
	id	:any;
	phone	:any;
	phone_location	:any;
	origin	:any;
	add_time	:any;
	subject	:any;
	grade	:any;
	has_pad	:any;
	trial_type	:any;
	nick	:any;
	qq	:any;
	admin_revisiterid	:any;
	grade_str	:any;
	subject_str	:any;
	has_pad_str	:any;
}

/*

tofile: 
	 mkdir -p ../seller_student; vi  ../seller_student/student_sub_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/seller_student-student_sub_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			start_date:	$('#id_start_date').val(),
			end_date:	$('#id_end_date').val(),
			origin:	$('#id_origin').val(),
			grade:	$('#id_grade').val(),
			subject:	$('#id_subject').val(),
			phone:	$('#id_phone').val()
        });
    }

	Enum_map.append_option_list("grade",$("#id_grade")); 
	Enum_map.append_option_list("subject",$("#id_subject")); 

	$('#id_start_date').val(g_args.start_date);
	$('#id_end_date').val(g_args.end_date);
	$('#id_origin').val(g_args.origin);
	$('#id_grade').val(g_args.grade);
	$('#id_subject').val(g_args.subject);
	$('#id_phone').val(g_args.phone);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">start_date</span>
                <input class="opt-change form-control" id="id_start_date" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">end_date</span>
                <input class="opt-change form-control" id="id_end_date" />
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
                <span class="input-group-addon">年级</span>
                <select class="opt-change form-control" id="id_grade" >
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
                <span class="input-group-addon">phone</span>
                <input class="opt-change form-control" id="id_phone" />
            </div>
        </div>
*/
