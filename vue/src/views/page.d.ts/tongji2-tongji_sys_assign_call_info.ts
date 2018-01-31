interface self_Args {
	page_num:	number;
	page_count:	number;
	order_by_str:	string;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	adminid:	number;
	userid:	number;
	called_flag:	string;//枚举列表: \App\Enums\Eboolean
 	check_hold_flag:	string;//枚举列表: \App\Enums\Eboolean
 	seller_student_assign_from_type:	string;//枚举列表: \App\Enums\Eseller_student_assign_from_type
 	same_admin_flag:	string;//枚举列表: \App\Enums\Eboolean
 
}
interface self_RowData {
	id	:any;
	logtime	:any;
	userid	:any;
	seller_student_assign_from_type	:any;
	adminid	:any;
	call_count	:any;
	called_flag	:any;
	call_time	:any;
	check_hold_flag	:any;
	phone	:any;
	origin_level	:any;
	origin	:any;
	add_time	:any;
	admin_revisiterid	:any;
	admin_nick	:any;
	admin_revisiter_nick	:any;
	student_nick	:any;
	called_flag_str	:any;
	seller_student_assign_from_type_str	:any;
	check_hold_flag_str	:any;
	origin_level_str	:any;

}
export  {self_RowData , self_Args  }
/*
tofile:
	 mkdir -p ../tongji2; vi  ../tongji2/tongji_sys_assign_call_info.ts

import Vue from 'vue'
import Component from 'vue-class-component'
import vtable from "../../components/vtable"
import {self_RowData, self_Args } from "../page.d.ts/tongji2-tongji_sys_assign_call_info"
// @Component 修饰符注明了此类为一个 Vue 组件.
@Component({
  // 所有的组件选项都可以放在这里.
  template:  require("./tongji_sys_assign_call_info.html" ),
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

	$.admin_ajax_select_user({
		'join_header'  : $header_query_info,
		"user_type"    : "account",
		"field_name"    : "adminid",
		"title"        :  "adminid",
		"select_value" : this.get_args().adminid,
	});
	$.admin_ajax_select_user({
		'join_header'  : $header_query_info,
		"user_type"    : "student",
		"field_name"    : "userid",
		"title"        :  "userid",
		"select_value" : this.get_args().userid,
	});
	$.admin_enum_select({
		'join_header'  : $header_query_info,
"enum_type"    : "boolean",
"field_name" : "called_flag",
"title" : "called_flag",
"select_value" : this.get_args().called_flag,
		"multi_select_flag"     : true,
		"btn_id_config"     : {},
	});

	$.admin_enum_select({
		'join_header'  : $header_query_info,
"enum_type"    : "boolean",
"field_name" : "check_hold_flag",
"title" : "check_hold_flag",
"select_value" : this.get_args().check_hold_flag,
		"multi_select_flag"     : true,
		"btn_id_config"     : {},
	});

	$.admin_enum_select({
		'join_header'  : $header_query_info,
"enum_type"    : "seller_student_assign_from_type",
"field_name" : "seller_student_assign_from_type",
"title" : "seller_student_assign_from_type",
"select_value" : this.get_args().seller_student_assign_from_type,
		"multi_select_flag"     : true,
		"btn_id_config"     : {},
	});

	$.admin_enum_select({
		'join_header'  : $header_query_info,
"enum_type"    : "boolean",
"field_name" : "same_admin_flag",
"title" : "same_admin_flag",
"select_value" : this.get_args().same_admin_flag,
		"multi_select_flag"     : true,
		"btn_id_config"     : {},
	});


  }
}
*/