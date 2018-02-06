interface self_Args {
	page_num:	number;
	page_count:	number;
	query_text:	string;

}
interface self_RowData {
	id	:any;
	queue	:any;
	payload	:any;
	attempts	:any;
	reserved	:any;
	reserved_at	:any;
	available_at	:any;
	created_at	:any;

}
export  {self_RowData , self_Args  }
/*
tofile:
	 mkdir -p ../admin_manage; vi  ../admin_manage/job_list.ts

import Vue from 'vue'
import Component from 'vue-class-component'
import vtable from "../../components/vtable"
import {self_RowData, self_Args } from "../page.d.ts/admin_manage-job_list"
// @Component 修饰符注明了此类为一个 Vue 组件.
@Component({
  // 所有的组件选项都可以放在这里.
  template:  require("./job_list.html" ),
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
		"field_name"    : "query_text" ,
		"placeholder" : "回车查询", 
		"length_css" : "col-xs-12 col-md-3", 
		"title"        :  "query_text",
		"select_value" : this.get_args().query_text,
	});

  }
}
*/