import Vue from 'vue'
import Component from 'vue-class-component'
import vtable from "../../components/vtable"
import {self_RowData, self_Args } from "../page.d.ts/tea_manage_new-lesson_record_server_list"
// @Component 修饰符注明了此类为一个 Vue 组件.
@Component({
  // 所有的组件选项都可以放在这里.
  template:  require("./lesson_record_server_list.html" ),
})
export default class extends vtable {

  get_args() :self_Args  {return  this.get_args_base();}
  data_ex() {
    //扩展的 data  数据
    var me=this;
    var field_list=[{
      field_name: "index",
      "title": "全/反",
    },{
      field_name: "subject_str",
      "title": "科目",
    },{
      field_name: "record_audio_server1",
      "title": "声音服务器",
    },{
      field_name: 'xmpp_server_name',
      "title": "xmpp服务器"
    },{
      field_name: "lesson_type_str",
      "title": "课程类型"
    },{
      field_name: "lesson_time",
      "title": "上课时间"
    },{
      field_name: "student_nick",
      "title": "学生"
    },{
      field_name: "teacher_nick",
      "title": "老师"
    }];
    var  row_opt_list =[{
      //face_icon: "fa-edit",
      on_click: me.opt_edit ,
      "title": "<a>课程</a>",
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
		$.admin_date_select ({
		'join_header'  : $header_query_info,
		'title' : "时间",
		'date_type' : this.get_args().date_type,
		'opt_date_type' : this.get_args().opt_date_type,
		'start_time'    : this.get_args().start_time,
		'end_time'      : this.get_args().end_time,
		date_type_config : JSON.parse(this.get_args().date_type_config),
		as_header_query :true,
		});

   $.admin_enum_select({
		'join_header'  : $header_query_info,
		"enum_type"    : "contract_type" ,
    "field_name"   : "lesson_type",
		"length_css" : "col-xs-6 col-md-2",
		"show_title_flag":true,
		"title"        :  "课程类型",
		"select_value" : this.get_args().lesson_type,
	});

  $.admin_enum_select({
		'join_header'  : $header_query_info,
		"enum_type"    : "subject" ,
    "field_name"   : "subject",
		"length_css" : "col-xs-6 col-md-2",
		"show_title_flag":true,
		"title"        :  "科目",
    "multi_select_flag"     : true,
		"select_value" : this.get_args().subject,
	});

	// $.admin_query_input({
	// 	'join_header'  : $header_query_info,
	// 	"field_name"    : "record_audio_server1" ,
	// 	"placeholder" : "回车查询",
	// 	"length_css" : "col-xs-12 col-md-3",
	// 	"title"        :  "record_audio_server1",
	// 	"select_value" : this.get_args().record_audio_server1,
	// });

	// $.admin_query_input({
	// 	'join_header'  : $header_query_info,
	// 	"field_name"    : "xmpp_server_name" ,
	// 	"placeholder" : "回车查询",
	// 	"length_css" : "col-xs-12 col-md-3",
	// 	"title"        :  "xmpp_server_name",
	// 	"select_value" : this.get_args().xmpp_server_name,
	// });

	$.admin_ajax_select_user({
		'join_header'  : $header_query_info,
		"user_type"    : "student",
		"field_name"    : "userid",
		"title"        :  "学生id",
		"select_value" : this.get_args().userid,
	});

  }
}
