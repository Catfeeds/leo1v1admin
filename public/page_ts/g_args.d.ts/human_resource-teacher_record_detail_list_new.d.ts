interface GargsStatic {
	teacherid:	number;
	subject:	number;
	page_num:	number;
	page_count:	number;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
}
declare module "g_args" {
    export = g_args;
}
declare var g_args: GargsStatic;
declare var g_account: string;
declare var g_account_role: any;
declare var g_adminid: any;
interface RowData {
	nick	:any;
	subject	:any;
	create_time	:any;
	record_monitor_class	:any;
	record_info	:any;
	acc	:any;
	courseware_flag_score	:any;
	lesson_preparation_content_score	:any;
	courseware_quality_score	:any;
	tea_process_design_score	:any;
	class_atm_score	:any;
	tea_method_score	:any;
	knw_point_score	:any;
	dif_point_score	:any;
	teacher_blackboard_writing_score	:any;
	tea_rhythm_score	:any;
	content_fam_degree_score	:any;
	answer_question_cre_score	:any;
	language_performance_score	:any;
	tea_attitude_score	:any;
	tea_concentration_score	:any;
	tea_accident_score	:any;
	tea_operation_score	:any;
	tea_environment_score	:any;
	class_abnormality_score	:any;
	record_rank	:any;
	record_score	:any;
	record_lesson_list	:any;
	subject_str	:any;
	create_time_str	:any;
	fkqk	:any;
}

/*

tofile: 
	 mkdir -p ../human_resource; vi  ../human_resource/teacher_record_detail_list_new.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/human_resource-teacher_record_detail_list_new.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			teacherid:	$('#id_teacherid').val(),
			subject:	$('#id_subject').val(),
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val()
        });
    }


    $('#id_date_range').select_date_range({
        'date_type' : g_args.date_type,
        'opt_date_type' : g_args.opt_date_type,
        'start_time'    : g_args.start_time,
        'end_time'      : g_args.end_time,
        date_type_config : JSON.parse( g_args.date_type_config),
        onQuery :function() {
            load_data();
        }
    });
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_subject').val(g_args.subject);


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
                <span class="input-group-addon">subject</span>
                <input class="opt-change form-control" id="id_subject" />
            </div>
        </div>
*/
