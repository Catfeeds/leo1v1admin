interface self_Args {
	assign_groupid:	number;
	assign_account_role:	number;
	creater_adminid:	number;
	adminid:	number;
	uid:	number;
	user_info:	string;
	has_question_user:	number;//枚举: \App\Enums\Eboolean
	del_flag:	string;//枚举列表: \App\Enums\Eboolean
 	page_num:	number;
	page_count:	number;
	account_role:	string;//枚举列表: \App\Enums\Eaccount_role
 	cardid:	number;
	day_new_user_flag:	string;//枚举列表: \App\Enums\Eboolean
 	tquin:	number;
	fulltime_teacher_type:	string;//枚举列表: \App\Enums\Efulltime_teacher_type
 	call_phone_type:	string;//枚举列表: \App\Enums\Ecall_phone_type
 	seller_groupid_ex:	string;
	seller_level:	string;//枚举列表: \App\Enums\Eseller_level
 
}
interface self_RowData {
	no_update_seller_level_flag	:any;
	create_time	:any;
	leave_member_time	:any;
	become_member_time	:any;
	call_phone_type	:any;
	call_phone_passwd	:any;
	fingerprint1	:any;
	ytx_phone	:any;
	wx_id	:any;
	up_adminid	:any;
	day_new_user_flag	:any;
	account_role	:any;
	creater_adminid	:any;
	uid	:any;
	del_flag	:any;
	account	:any;
	seller_level	:any;
	name	:any;
	nickname	:any;
	email	:any;
	phone	:any;
	password	:any;
	permission	:any;
	tquin	:any;
	wx_openid	:any;
	cardid	:any;
	become_full_member_flag	:any;
	main_department	:any;
	fulltime_teacher_type	:any;
	seller_student_assign_type	:any;
	old_permission	:any;
	reset_passwd_flag	:any;
	creater_admin_nick	:any;
	up_admin_nick	:any;
	account_role_str	:any;
	seller_level_str	:any;
	department_str	:any;
	become_full_member_flag_str	:any;
	no_update_seller_level_flag_str	:any;
	del_flag_str	:any;
	leave_time	:any;
	become_time	:any;
	day_new_user_flag_str	:any;

}
export  {self_RowData , self_Args  }
/*
tofile:
	 mkdir -p ../authority; vi  ../authority/manager_list.ts

import Vue from 'vue'
import Component from 'vue-class-component'
import vtable from "../../components/vtable"
import {self_RowData, self_Args } from "../page.d.ts/authority-manager_list"
// @Component 修饰符注明了此类为一个 Vue 组件.
@Component({
  // 所有的组件选项都可以放在这里.
  template:  require("./manager_list.html" ),
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
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "assign_groupid" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "assign_groupid",
		"select_value" : this.get_args().assign_groupid,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "assign_account_role" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "assign_account_role",
		"select_value" : this.get_args().assign_account_role,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "creater_adminid" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "creater_adminid",
		"select_value" : this.get_args().creater_adminid,
	});
	$.admin_ajax_select_user({
		'join_header'  : $header_query_info,
		"user_type"    : "account",
		"field_name"    : "adminid",
		"title"        :  "adminid",
		"select_value" : this.get_args().adminid,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "uid" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "uid",
		"select_value" : this.get_args().uid,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "user_info" ,
		"placeholder" : "回车查询", 
		"length_css" : "col-xs-12 col-md-3", 
		"title"        :  "user_info",
		"select_value" : this.get_args().user_info,
	});
	$.admin_enum_select({
		'join_header'  : $header_query_info,
		"enum_type"    : "boolean",
		"field_name" : "has_question_user",
"title" : "has_question_user",
		"multi_select_flag"     : false ,
		"btn_id_config"     : {},
	});

	$.admin_enum_select({
		'join_header'  : $header_query_info,
"enum_type"    : "boolean",
"field_name" : "del_flag",
"title" : "del_flag",
"select_value" : this.get_args().del_flag,
		"multi_select_flag"     : true,
		"btn_id_config"     : {},
	});

	$.admin_enum_select({
		'join_header'  : $header_query_info,
"enum_type"    : "account_role",
"field_name" : "account_role",
"title" : "account_role",
"select_value" : this.get_args().account_role,
		"multi_select_flag"     : true,
		"btn_id_config"     : {},
	});

	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "cardid" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "cardid",
		"select_value" : this.get_args().cardid,
	});
	$.admin_enum_select({
		'join_header'  : $header_query_info,
"enum_type"    : "boolean",
"field_name" : "day_new_user_flag",
"title" : "day_new_user_flag",
"select_value" : this.get_args().day_new_user_flag,
		"multi_select_flag"     : true,
		"btn_id_config"     : {},
	});

	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "tquin" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "tquin",
		"select_value" : this.get_args().tquin,
	});
	$.admin_enum_select({
		'join_header'  : $header_query_info,
"enum_type"    : "fulltime_teacher_type",
"field_name" : "fulltime_teacher_type",
"title" : "fulltime_teacher_type",
"select_value" : this.get_args().fulltime_teacher_type,
		"multi_select_flag"     : true,
		"btn_id_config"     : {},
	});

	$.admin_enum_select({
		'join_header'  : $header_query_info,
"enum_type"    : "call_phone_type",
"field_name" : "call_phone_type",
"title" : "call_phone_type",
"select_value" : this.get_args().call_phone_type,
		"multi_select_flag"     : true,
		"btn_id_config"     : {},
	});

	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "seller_groupid_ex" ,
		"placeholder" : "回车查询", 
		"length_css" : "col-xs-12 col-md-3", 
		"title"        :  "seller_groupid_ex",
		"select_value" : this.get_args().seller_groupid_ex,
	});
	$.admin_enum_select({
		'join_header'  : $header_query_info,
"enum_type"    : "seller_level",
"field_name" : "seller_level",
"title" : "seller_level",
"select_value" : this.get_args().seller_level,
		"multi_select_flag"     : true,
		"btn_id_config"     : {},
	});


  }
}
*/