interface GargsStatic {
	page_num:	number;
	page_count:	number;
	teacherid:	number;
	jw_adminid:	number;
	grade_part_ex:	number;
	subject:	number;
	second_subject:	number;
	identity:	number;
	class_will_type:	number;
	have_lesson:	number;
	revisit_flag:	number;
	textbook_flag:	number;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	grade_part_ex	:any;
	second_subject	:any;
	realname	:any;
	teacherid	:any;
	assign_jw_adminid	:any;
	subject	:any;
	account	:any;
	assign_jw_time	:any;
	train_through_new_time	:any;
	identity	:any;
	phone	:any;
	record_info	:any;
	add_time	:any;
	acc	:any;
	class_will_type	:any;
	class_will_sub_type	:any;
	recover_class_time	:any;
	lesson_start	:any;
	l_subject	:any;
	grade_start	:any;
	grade_end	:any;
	teacher_textbook	:any;
	work_day	:any;
	identity_str	:any;
	subject_str	:any;
	l_subject_str	:any;
	second_subject_str	:any;
	grade_start_str	:any;
	grade_end_str	:any;
	grade_part_ex_str	:any;
	class_will_type_str	:any;
	class_will_sub_type_str	:any;
	assign_jw_time_str	:any;
	add_time_str	:any;
	recover_class_time_str	:any;
	lesson_start_str	:any;
	textbook	:any;
}

/*

tofile: 
	 mkdir -p ../human_resource; vi  ../human_resource/get_assign_jw_adminid_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-get_assign_jw_adminid_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			teacherid:	$('#id_teacherid').val(),
			jw_adminid:	$('#id_jw_adminid').val(),
			grade_part_ex:	$('#id_grade_part_ex').val(),
			subject:	$('#id_subject').val(),
			second_subject:	$('#id_second_subject').val(),
			identity:	$('#id_identity').val(),
			class_will_type:	$('#id_class_will_type').val(),
			have_lesson:	$('#id_have_lesson').val(),
			revisit_flag:	$('#id_revisit_flag').val(),
			textbook_flag:	$('#id_textbook_flag').val()
        });
    }


	$('#id_teacherid').val(g_args.teacherid);
	$('#id_jw_adminid').val(g_args.jw_adminid);
	$('#id_grade_part_ex').val(g_args.grade_part_ex);
	$('#id_subject').val(g_args.subject);
	$('#id_second_subject').val(g_args.second_subject);
	$('#id_identity').val(g_args.identity);
	$('#id_class_will_type').val(g_args.class_will_type);
	$('#id_have_lesson').val(g_args.have_lesson);
	$('#id_revisit_flag').val(g_args.revisit_flag);
	$('#id_textbook_flag').val(g_args.textbook_flag);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">jw_adminid</span>
                <input class="opt-change form-control" id="id_jw_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade_part_ex</span>
                <input class="opt-change form-control" id="id_grade_part_ex" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">subject</span>
                <input class="opt-change form-control" id="id_subject" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">second_subject</span>
                <input class="opt-change form-control" id="id_second_subject" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">identity</span>
                <input class="opt-change form-control" id="id_identity" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">class_will_type</span>
                <input class="opt-change form-control" id="id_class_will_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">have_lesson</span>
                <input class="opt-change form-control" id="id_have_lesson" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">revisit_flag</span>
                <input class="opt-change form-control" id="id_revisit_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">textbook_flag</span>
                <input class="opt-change form-control" id="id_textbook_flag" />
            </div>
        </div>
*/
