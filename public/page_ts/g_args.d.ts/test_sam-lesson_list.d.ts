interface GargsStatic {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	studentid:	number;
	teacherid:	number;
	confirm_flag:	string;//枚举列表: App\Enums\Econfirm_flag
 	seller_adminid:	number;
	lesson_status:	number;
	assistantid:	number;
	grade:	string;//枚举列表: App\Enums\Egrade
 	test_seller_id:	number;
	has_performance:	number;
	fulltime_flag:	number;
	lesson_user_online_status:	number;//\App\Enums\Eset_boolean
	lesson_type:	number;
	subject:	number;
	lesson_count:	number;
	lesson_cancel_reason_type:	number;
	lesson_del_flag:	number;
	has_video_flag:	number;//\App\Enums\Eboolean
	is_with_test_user:	number;
	lessonid:	number;
	origin:	string;
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
	 mkdir -p ../test_sam; vi  ../test_sam/lesson_list.ts

/// <reference path="../common.d.ts" />
/// <reference path="../g_args.d.ts/test_sam-lesson_list.d.ts" />

$(function(){
    function load_data(){
        $.reload_self_page ( {
			date_type_config:	$('#id_date_type_config').val(),
			date_type:	$('#id_date_type').val(),
			opt_date_type:	$('#id_opt_date_type').val(),
			start_time:	$('#id_start_time').val(),
			end_time:	$('#id_end_time').val(),
			studentid:	$('#id_studentid').val(),
			teacherid:	$('#id_teacherid').val(),
			confirm_flag:	$('#id_confirm_flag').val(),
			seller_adminid:	$('#id_seller_adminid').val(),
			lesson_status:	$('#id_lesson_status').val(),
			assistantid:	$('#id_assistantid').val(),
			grade:	$('#id_grade').val(),
			test_seller_id:	$('#id_test_seller_id').val(),
			has_performance:	$('#id_has_performance').val(),
			fulltime_flag:	$('#id_fulltime_flag').val(),
			lesson_user_online_status:	$('#id_lesson_user_online_status').val(),
			lesson_type:	$('#id_lesson_type').val(),
			subject:	$('#id_subject').val(),
			lesson_count:	$('#id_lesson_count').val(),
			lesson_cancel_reason_type:	$('#id_lesson_cancel_reason_type').val(),
			lesson_del_flag:	$('#id_lesson_del_flag').val(),
			has_video_flag:	$('#id_has_video_flag').val(),
			is_with_test_user:	$('#id_is_with_test_user').val(),
			lessonid:	$('#id_lessonid').val(),
			origin:	$('#id_origin').val()
        });
    }

	Enum_map.append_option_list("set_boolean",$("#id_lesson_user_online_status"));
	Enum_map.append_option_list("boolean",$("#id_has_video_flag"));

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
	$('#id_studentid').val(g_args.studentid);
	$('#id_teacherid').val(g_args.teacherid);
	$('#id_confirm_flag').val(g_args.confirm_flag);
	$.enum_multi_select( $('#id_confirm_flag'), 'confirm_flag', function(){load_data();} )
	$('#id_seller_adminid').val(g_args.seller_adminid);
	$('#id_lesson_status').val(g_args.lesson_status);
	$('#id_assistantid').val(g_args.assistantid);
	$('#id_grade').val(g_args.grade);
	$.enum_multi_select( $('#id_grade'), 'grade', function(){load_data();} )
	$('#id_test_seller_id').val(g_args.test_seller_id);
	$('#id_has_performance').val(g_args.has_performance);
	$('#id_fulltime_flag').val(g_args.fulltime_flag);
	$('#id_lesson_user_online_status').val(g_args.lesson_user_online_status);
	$('#id_lesson_type').val(g_args.lesson_type);
	$('#id_subject').val(g_args.subject);
	$('#id_lesson_count').val(g_args.lesson_count);
	$('#id_lesson_cancel_reason_type').val(g_args.lesson_cancel_reason_type);
	$('#id_lesson_del_flag').val(g_args.lesson_del_flag);
	$('#id_has_video_flag').val(g_args.has_video_flag);
	$('#id_is_with_test_user').val(g_args.is_with_test_user);
	$('#id_lessonid').val(g_args.lessonid);
	$('#id_origin').val(g_args.origin);


	$('.opt-change').set_input_change_event(load_data);
});



*/
/* HTML ...

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">studentid</span>
                <input class="opt-change form-control" id="id_studentid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">teacherid</span>
                <input class="opt-change form-control" id="id_teacherid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">confirm_flag</span>
                <input class="opt-change form-control" id="id_confirm_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">seller_adminid</span>
                <input class="opt-change form-control" id="id_seller_adminid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_status</span>
                <input class="opt-change form-control" id="id_lesson_status" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">assistantid</span>
                <input class="opt-change form-control" id="id_assistantid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">grade</span>
                <input class="opt-change form-control" id="id_grade" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">test_seller_id</span>
                <input class="opt-change form-control" id="id_test_seller_id" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">has_performance</span>
                <input class="opt-change form-control" id="id_has_performance" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">fulltime_flag</span>
                <input class="opt-change form-control" id="id_fulltime_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">set_boolean</span>
                <select class="opt-change form-control" id="id_lesson_user_online_status" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_type</span>
                <input class="opt-change form-control" id="id_lesson_type" />
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
                <span class="input-group-addon">lesson_count</span>
                <input class="opt-change form-control" id="id_lesson_count" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_cancel_reason_type</span>
                <input class="opt-change form-control" id="id_lesson_cancel_reason_type" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lesson_del_flag</span>
                <input class="opt-change form-control" id="id_lesson_del_flag" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">boolean</span>
                <select class="opt-change form-control" id="id_has_video_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">is_with_test_user</span>
                <input class="opt-change form-control" id="id_is_with_test_user" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">lessonid</span>
                <input class="opt-change form-control" id="id_lessonid" />
            </div>
        </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">origin</span>
                <input class="opt-change form-control" id="id_origin" />
            </div>
        </div>
*/
