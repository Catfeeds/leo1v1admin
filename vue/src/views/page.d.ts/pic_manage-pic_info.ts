interface self_Args {
	type:	number;
	usage_type:	number;
	active_status:	number;
	page_num:	number;
	page_count:	number;

}
interface self_RowData {
	id	:any;
	type	:any;
	name	:any;
	time_type	:any;
	created_at	:any;
	updated_at	:any;
	order_by	:any;
	usage_type	:any;
	img_tags_url	:any;
	img_url	:any;
	status	:any;
	subject	:any;
	grade	:any;
	start_time	:any;
	end_time	:any;
	jump_url	:any;
	title_share	:any;
	info_share	:any;
	jump_type	:any;
	del_flag	:any;
	type_str	:any;
	time_type_str	:any;
	usage_type_str	:any;
	active_status	:any;
	min_date	:any;

}
export  {self_RowData , self_Args  }
/*
tofile:
	 mkdir -p ../pic_manage; vi  ../pic_manage/pic_info.ts

import Vue from 'vue'
import Component from 'vue-class-component'
import vtable from "../../components/vtable"
import {self_RowData, self_Args } from "../page.d.ts/pic_manage-pic_info"
// @Component 修饰符注明了此类为一个 Vue 组件.
@Component({
  // 所有的组件选项都可以放在这里.
  template:  require("./pic_info.html" ),
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
		"field_name"    : "type" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "type",
		"select_value" : this.get_args().type,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "usage_type" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "usage_type",
		"select_value" : this.get_args().usage_type,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "active_status" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "active_status",
		"select_value" : this.get_args().active_status,
	});

  }
}
*/