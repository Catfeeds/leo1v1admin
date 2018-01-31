interface self_Args {
	page_num:	number;
	page_count:	number;
	order_by_str:	string;
	machine_id:	number;
	adminid:	number;
	auth_flag:	string;//枚举列表: \App\Enums\Eboolean
 
}
interface self_RowData {
	title	:any;
	machine_id	:any;
	adminid	:any;
	auth_flag	:any;
	open_door_flag	:any;
	sn	:any;
	del_flag	:any;
	auth_flag_str	:any;
	admin_nick	:any;

}
export  {self_RowData , self_Args  }
/*
tofile:
	 mkdir -p ../admin_manage; vi  ../admin_manage/kaoqin_machine_adminid.ts

import Vue from 'vue'
import Component from 'vue-class-component'
import vtable from "../../components/vtable"
import {self_RowData, self_Args } from "../page.d.ts/admin_manage-kaoqin_machine_adminid"
// @Component 修饰符注明了此类为一个 Vue 组件.
@Component({
  // 所有的组件选项都可以放在这里.
  template:  require("./kaoqin_machine_adminid.html" ),
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
		"field_name"    : "machine_id" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "machine_id",
		"select_value" : this.get_args().machine_id,
	});
	$.admin_ajax_select_user({
		'join_header'  : $header_query_info,
		"user_type"    : "account",
		"field_name"    : "adminid",
		"title"        :  "adminid",
		"select_value" : this.get_args().adminid,
	});
	$.admin_enum_select({
		'join_header'  : $header_query_info,
"enum_type"    : "boolean",
"field_name" : "auth_flag",
"title" : "auth_flag",
"select_value" : this.get_args().auth_flag,
		"multi_select_flag"     : true,
		"btn_id_config"     : {},
	});


  }
}
*/