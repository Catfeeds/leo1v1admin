interface self_Args {
	teacherid:	number;
	is_freeze:	number;
	teacher_money_type:	number;
	teacher_ref_type:	number;
	level:	number;
	page_num:	number;
	page_count:	number;
	need_test_lesson_flag:	number;
	textbook_type:	number;
	is_good_flag:	number;
	is_new_teacher:	number;
	gender:	number;
	free_time:	string;
	subject:	number;
	second_subject:	number;
	trial_flag:	number;
	test_flag:	number;
	seller_flag:	number;
	is_test_user:	number;
	is_quit:	number;
	address:	string;
	limit_plan_lesson_type:	number;
	is_record_flag:	number;
	test_lesson_full_flag:	number;
	train_through_new:	number;
	lesson_hold_flag:	number;
	test_transfor_per:	number;
	week_liveness:	number;
	interview_score:	number;
	set_leave_flag:	number;
	second_interview_score:	number;
	lesson_hold_flag_adminid:	number;
	fulltime_flag:	number;
	teacher_type:	number;
	seller_hold_flag:	number;
	have_wx:	number;
	grade_plan:	number;
	subject_plan:	number;
	fulltime_teacher_type:	number;
	month_stu_num:	number;
	record_score_num:	number;
	identity:	number;
	plan_level:	number;
	teacher_textbook:	number;

}
interface self_RowData {
	wx_openid	:any;
	need_test_lesson_flag	:any;
	nick	:any;
	realname	:any;
	teacher_type	:any;
	jianli	:any;
	gender	:any;
	age	:any;
	teacher_money_type	:any;
	identity	:any;
	is_test_user	:any;
	add_acc	:any;
	train_through_new	:any;
	train_through_new_time	:any;
	phone_spare	:any;
	birth	:any;
	phone	:any;
	email	:any;
	rate_score	:any;
	teacherid	:any;
	user_agent	:any;
	teacher_tags	:any;
	teacher_textbook	:any;
	wx_use_flag	:any;
	create_meeting	:any;
	level	:any;
	work_year	:any;
	advantage	:any;
	base_intro	:any;
	textbook_type	:any;
	is_good_flag	:any;
	create_time	:any;
	address	:any;
	subject	:any;
	second_subject	:any;
	third_subject	:any;
	school	:any;
	tea_note	:any;
	is_freeze	:any;
	freeze_reason	:any;
	freeze_adminid	:any;
	freeze_time	:any;
	limit_plan_lesson_type	:any;
	limit_plan_lesson_reason	:any;
	limit_plan_lesson_time	:any;
	limit_plan_lesson_account	:any;
	second_grade	:any;
	third_grade	:any;
	interview_access	:any;
	lesson_hold_flag	:any;
	lesson_hold_flag_acc	:any;
	research_note	:any;
	lesson_hold_flag_time	:any;
	interview_score	:any;
	second_interview_score	:any;
	test_transfor_per	:any;
	week_liveness	:any;
	limit_day_lesson_num	:any;
	limit_week_lesson_num	:any;
	limit_month_lesson_num	:any;
	teacher_ref_type	:any;
	saturday_lesson_num	:any;
	grade_start	:any;
	grade_end	:any;
	second_grade_start	:any;
	second_grade_end	:any;
	month_stu_num	:any;
	not_grade	:any;
	not_grade_limit	:any;
	week_lesson_count	:any;
	trial_lecture_is_pass	:any;
	idcard	:any;
	bankcard	:any;
	bank_address	:any;
	bank_account	:any;
	bank_phone	:any;
	bank_type	:any;
	bank_province	:any;
	bank_city	:any;
	is_quit	:any;
	part_remarks	:any;
	record_score	:any;
	free_time	:any;
	class_will_type	:any;
	class_will_sub_type	:any;
	revisit_add_time	:any;
	recover_class_time	:any;
	revisit_record_info	:any;
	teacher_type_str	:any;
	need_test_lesson_flag_str	:any;
	gender_str	:any;
	subject_str	:any;
	grade_start_str	:any;
	grade_end_str	:any;
	second_subject_str	:any;
	second_grade_start_str	:any;
	second_grade_end_str	:any;
	identity_str	:any;
	level_str	:any;
	teacher_money_type_str	:any;
	teacher_ref_type_str	:any;
	textbook_type_str	:any;
	is_good_flag_str	:any;
	limit_plan_lesson_type_str	:any;
	freeze_time_str	:any;
	create_time_str	:any;
	limit_plan_lesson_time_str	:any;
	train_through_new_time_str	:any;
	lesson_hold_flag_time_str	:any;
	class_will_type_str	:any;
	class_will_sub_type_str	:any;
	revisit_add_time_str	:any;
	recover_class_time_str	:any;
	work_day	:any;
	is_freeze_str	:any;
	lesson_info_week	:any;
	test_user_str	:any;
	train_through_new_str	:any;
	phone_spare_hide	:any;
	freeze_adminid_str	:any;
	week_lesson_num	:any;
	week_left_num	:any;
	not_grade_str	:any;
	interview_acc	:any;
	textbook	:any;
	fine_dimension	:any;
	full_flag	:any;

}
export  {self_RowData , self_Args  }
/*
tofile:
	 mkdir -p ../human_resource; vi  ../human_resource/index.ts

import Vue from 'vue'
import Component from 'vue-class-component'
import vtable from "../../components/vtable"
import {self_RowData, self_Args } from "../page.d.ts/human_resource-index"
// @Component 修饰符注明了此类为一个 Vue 组件.
@Component({
  // 所有的组件选项都可以放在这里.
  template:  require("./index.html" ),
})
export default class extends vtable {

  get_args() :self_Args  {return  this.get_args_base();}
  data_ex() {
    //扩展的 data  数据
    var me=this;
    var field_list=[{
      field_name: "title",
      "title": "说明",
    },{
      field_name: "admin_nick",
      "order_field_name": "admin_nick",
      "title": "昵称",
      "default_display":  false,
      render:function(value, item:self_RowData ,index){
        return "<a class=\"fa btn\" >"+item.admin_nick+"</a>" ;
      }
    },{
      field_name: "auth_flag_str",
      "title": "管理员",
      "order_field_name": "auth_flag",
      need_power: "auth_flag",
      render:function(value, item:self_RowData ,index){
        return "<a class=\"fa btn\" >"+value+"</a>" ;
      }
    }];
    var  row_opt_list =[{
      face_icon: "fa-edit",
      on_click: me.opt_edit ,
      "title": "编辑",
    },{
      face_icon: "fa-times",
      "title": "删除",
    }];

    return {
      "table_config":  {
        "field_list": field_list,
        "row_opt_list": row_opt_list,
      }
    }
  }
  opt_edit( e:MouseEvent, opt_data: self_RowData ){
    alert(JSON.stringify( opt_data));
  }

  query_init( $header_query_info): void{
    console.log("init_query");
    var me =this;
	$.admin_ajax_select_user({
		'join_header'  : $header_query_info,
		"user_type"    : "teacher",
		"field_name"    : "teacherid",
		"title"        :  "teacherid",
		"select_value" : this.get_args().teacherid,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "is_freeze" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "is_freeze",
		"select_value" : this.get_args().is_freeze,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "teacher_money_type" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "teacher_money_type",
		"select_value" : this.get_args().teacher_money_type,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "teacher_ref_type" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "teacher_ref_type",
		"select_value" : this.get_args().teacher_ref_type,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "level" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "level",
		"select_value" : this.get_args().level,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "need_test_lesson_flag" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "need_test_lesson_flag",
		"select_value" : this.get_args().need_test_lesson_flag,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "textbook_type" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "textbook_type",
		"select_value" : this.get_args().textbook_type,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "is_good_flag" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "is_good_flag",
		"select_value" : this.get_args().is_good_flag,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "is_new_teacher" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "is_new_teacher",
		"select_value" : this.get_args().is_new_teacher,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "gender" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "gender",
		"select_value" : this.get_args().gender,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "free_time" ,
		"placeholder" : "回车查询", 
		"length_css" : "col-xs-12 col-md-3", 
		"title"        :  "free_time",
		"select_value" : this.get_args().free_time,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "subject" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "subject",
		"select_value" : this.get_args().subject,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "second_subject" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "second_subject",
		"select_value" : this.get_args().second_subject,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "trial_flag" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "trial_flag",
		"select_value" : this.get_args().trial_flag,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "test_flag" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "test_flag",
		"select_value" : this.get_args().test_flag,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "seller_flag" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "seller_flag",
		"select_value" : this.get_args().seller_flag,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "is_test_user" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "is_test_user",
		"select_value" : this.get_args().is_test_user,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "is_quit" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "is_quit",
		"select_value" : this.get_args().is_quit,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "address" ,
		"placeholder" : "回车查询", 
		"length_css" : "col-xs-12 col-md-3", 
		"title"        :  "address",
		"select_value" : this.get_args().address,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "limit_plan_lesson_type" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "limit_plan_lesson_type",
		"select_value" : this.get_args().limit_plan_lesson_type,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "is_record_flag" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "is_record_flag",
		"select_value" : this.get_args().is_record_flag,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "test_lesson_full_flag" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "test_lesson_full_flag",
		"select_value" : this.get_args().test_lesson_full_flag,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "train_through_new" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "train_through_new",
		"select_value" : this.get_args().train_through_new,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "lesson_hold_flag" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "lesson_hold_flag",
		"select_value" : this.get_args().lesson_hold_flag,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "test_transfor_per" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "test_transfor_per",
		"select_value" : this.get_args().test_transfor_per,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "week_liveness" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "week_liveness",
		"select_value" : this.get_args().week_liveness,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "interview_score" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "interview_score",
		"select_value" : this.get_args().interview_score,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "set_leave_flag" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "set_leave_flag",
		"select_value" : this.get_args().set_leave_flag,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "second_interview_score" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "second_interview_score",
		"select_value" : this.get_args().second_interview_score,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "lesson_hold_flag_adminid" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "lesson_hold_flag_adminid",
		"select_value" : this.get_args().lesson_hold_flag_adminid,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "fulltime_flag" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "fulltime_flag",
		"select_value" : this.get_args().fulltime_flag,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "teacher_type" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "teacher_type",
		"select_value" : this.get_args().teacher_type,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "seller_hold_flag" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "seller_hold_flag",
		"select_value" : this.get_args().seller_hold_flag,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "have_wx" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "have_wx",
		"select_value" : this.get_args().have_wx,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "grade_plan" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "grade_plan",
		"select_value" : this.get_args().grade_plan,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "subject_plan" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "subject_plan",
		"select_value" : this.get_args().subject_plan,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "fulltime_teacher_type" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "fulltime_teacher_type",
		"select_value" : this.get_args().fulltime_teacher_type,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "month_stu_num" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "month_stu_num",
		"select_value" : this.get_args().month_stu_num,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "record_score_num" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "record_score_num",
		"select_value" : this.get_args().record_score_num,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "identity" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "identity",
		"select_value" : this.get_args().identity,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "plan_level" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "plan_level",
		"select_value" : this.get_args().plan_level,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "teacher_textbook" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "teacher_textbook",
		"select_value" : this.get_args().teacher_textbook,
	});

  }
}
*/