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
      title: "id",
      field_name: "teacherid",
      default_display: "0",
    },{
      title: "真实姓名",
      field_name: "realname",
      default_display: "1",
    },{
      title: "工资分类",
      field_name: "teacher_money_type_str",
      default_display: "0",
    },{
      title: "推荐渠道",
      field_name: "teacher_ref_type_str",
      default_display: "0",
    },{
      title: "老师身份",
      field_name: "identity_str",
      default_display: "1",
    },{
      title: "培训通过时间",
      field_name: "train_through_new_time_str",
      default_display: "0",
    },{
      title: "入库时间",
      field_name: "create_time_str",
      default_display: "0",
    },{
      title: "入职时长",
      field_name: "work_day",
      default_display: "1",
    },


    ];
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
    console.log(1);
    console.log(this.get_args());
    var me =this;
	  $.admin_ajax_select_user({
		  'join_header'  : $header_query_info,
		  "user_type"    : "teacher",
		  "field_name"    : "teacherid",
		  "title"        :  "老师",
		  "select_value" : this.get_args().teacherid,
	  });

    $.admin_enum_select({
		  'join_header'  : $header_query_info,
      "enum_type"    : "teacher_money_type",
		  "field_name"    : "teacher_money_type" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "工资分类",
      "multi_select_flag"     : false,
		  "select_value" : this.get_args().teacher_money_type,
	  });

	  $.admin_enum_select({
		  'join_header'  : $header_query_info,
      "enum_type"    : "level" ,
		  "field_name"    : "level" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "等级分类",
		  "select_value" : this.get_args().level,
	  });

    $.admin_enum_select({
		  'join_header'  : $header_query_info,
      "enum_type"    : "boolean" ,
		  "field_name"    : "need_test_lesson_flag" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "要试听课",
		  "select_value" : this.get_args().need_test_lesson_flag,
	  });

    $.admin_enum_select({
		  'join_header'  : $header_query_info,
      "enum_type"    : "teacher_ref_type" ,
		  "field_name"    : "teacher_ref_type" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "渠道类型",
		  "select_value" : this.get_args().teacher_ref_type,
	  });

	  $.admin_enum_select({
		  'join_header'  : $header_query_info,
		  "field_name"    : "is_good_flag" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "教师性质",
		  "select_value" : this.get_args().is_good_flag,
	  });

	  $.admin_enum_select({
		  'join_header'  : $header_query_info,
		  "field_name"    : "have_wx" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "绑定微信",
		  "select_value" : this.get_args().have_wx,
	  });

	  $.admin_enum_select({
		  'join_header'  : $header_query_info,
		  "field_name"    : "is_new_teacher" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "新教师筛选",
		  "select_value" : this.get_args().is_new_teacher,
	  });

    $.admin_enum_select({
		  'join_header'  : $header_query_info,
		  "field_name"    : "test_lesson_full_flag" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "近两周试听课数",
		  "select_value" : this.get_args().test_lesson_full_flag,
	  });

    $.admin_enum_select({
		  'join_header'  : $header_query_info,
		  "field_name"    : "month_stu_num" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "近一个月常规学生",
		  "select_value" : this.get_args().month_stu_num,
	  });

	  $.admin_enum_select({
		  'join_header'  : $header_query_info,
		  "field_name"    : "record_score_num" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "第一次试听得分",
		  "select_value" : this.get_args().record_score_num,
	  });

	  $.admin_enum_select({
		  'join_header'  : $header_query_info,
		  "field_name"    : "identity" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "老师类型",
		  "select_value" : this.get_args().identity,
	  });

    $.admin_enum_select({
		  'join_header'  : $header_query_info,
		  "field_name"    : "gender" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "性别",
		  "select_value" : this.get_args().gender,
	  });

    $.admin_enum_select({
		  'join_header'  : $header_query_info,
		  "field_name"    : "subject" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "第一科目",
		  "select_value" : this.get_args().subject,
	  });

	  $.admin_enum_select({
		  'join_header'  : $header_query_info,
		  "field_name"    : "second_subject" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "第二科目",
		  "select_value" : this.get_args().second_subject,
	  });

	  $.admin_enum_select({
		  'join_header'  : $header_query_info,
		  "field_name"    : "is_freeze" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "排课冻结",
		  "select_value" : this.get_args().is_freeze,
	  });

    $.admin_enum_select({
		  'join_header'  : $header_query_info,
		  "field_name"    : "limit_plan_lesson_type" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "排课限制",
		  "select_value" : this.get_args().limit_plan_lesson_type,
	  });

	  $.admin_enum_select({
		  'join_header'  : $header_query_info,
		  "field_name"    : "is_record_flag" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "是否反馈",
		  "select_value" : this.get_args().is_record_flag,
	  });

	  $.admin_enum_select({
		  'join_header'  : $header_query_info,
		  "field_name"    : "train_through_new" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "入职流程完成",
		  "select_value" : this.get_args().train_through_new,
	  });

    $.admin_enum_select({
		  'join_header'  : $header_query_info,
		  "field_name"    : "lesson_hold_flag" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "暂停接试听课",
		  "select_value" : this.get_args().lesson_hold_flag,
	  });

    $.admin_enum_select({
		  'join_header'  : $header_query_info,
		  "field_name"    : "test_transfor_per" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "近两月转化率",
		  "select_value" : this.get_args().test_transfor_per,
	  });

	  $.admin_enum_select({
		  'join_header'  : $header_query_info,
		  "field_name"    : "week_liveness" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "一周活跃度",
		  "select_value" : this.get_args().week_liveness,
	  });

	  $.admin_enum_select({
		  'join_header'  : $header_query_info,
		  "field_name"    : "interview_score" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "第一科目面试得分",
		  "select_value" : this.get_args().interview_score,
	  });

    $.admin_enum_select({
		  'join_header'  : $header_query_info,
		  "field_name"    : "second_interview_score" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "第二科目面试得分",
		  "select_value" : this.get_args().second_interview_score,
	  });

    $.admin_enum_select({
		  'join_header'  : $header_query_info,
		  "field_name"    : "teacher_type" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "全职类型",
		  "select_value" : this.get_args().teacher_type,
	  });

	  $.admin_enum_select({
		  'join_header'  : $header_query_info,
		  "field_name"    : "is_quit" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "是否离职",
		  "select_value" : this.get_args().is_quit,
	  });

    $.admin_query_input({
		  'join_header'  : $header_query_info,
		  "field_name"    : "address" ,
		  "placeholder" : "所在地、学校、姓名等 回车查找",
		  "length_css" : "col-xs-12 col-md-3",
		  "title"        :  "address",
		  "select_value" : this.get_args().address,
	  });

    $.admin_query_input({
		  'join_header'  : $header_query_info,
		  "field_name"    : "free_time" ,
		  "placeholder" : "回车查询",
		  "length_css" : "col-xs-12 col-md-3",
		  "title"        :  "空闲时间筛选",
		  "select_value" : this.get_args().free_time,
	  });

    $.admin_enum_select({
		  'join_header'  : $header_query_info,
		  "field_name"    : "plan_level" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "精排筛选",
		  "select_value" : this.get_args().plan_level,
	  });

	  $.admin_enum_select({
		  'join_header'  : $header_query_info,
		  "field_name"    : "teacher_textbook" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "教材版本",
		  "select_value" : this.get_args().teacher_textbook,
	  });

	  $.admin_enum_select({
		  'join_header'  : $header_query_info,
		  "field_name"    : "is_good_flag" ,
		  "length_css" : "col-xs-6 col-md-2",
		  "show_title_flag":true,
		  "title"        :  "教师性质",
		  "select_value" : this.get_args().is_good_flag,
	  });

  }
}
