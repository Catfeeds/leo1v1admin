interface self_Args {
	page_num:	number;
	page_count:	number;
	order_by_str:	string;
	ip:	string;
	event_type_id:	number;
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	sub_project:	string;

}
interface self_RowData {
	id	:any;
	logtime	:any;
	event_type_id	:any;
	value	:any;
	ip	:any;
	event_name	:any;

}
export  {self_RowData , self_Args  }
/*
tofile:
	 mkdir -p ../report; vi  ../report/event_log_list.ts

import Vue from 'vue'
import Component from 'vue-class-component'
import vtable from "../../components/vtable"
import {self_RowData, self_Args } from "../page.d.ts/report-event_log_list"
// @Component 修饰符注明了此类为一个 Vue 组件.
@Component({
  // 所有的组件选项都可以放在这里.
  template:  require("./event_log_list.html" ),
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

	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "ip" ,
		"placeholder" : "回车查询", 
		"length_css" : "col-xs-12 col-md-3", 
		"title"        :  "ip",
		"select_value" : this.get_args().ip,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "event_type_id" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "event_type_id",
		"select_value" : this.get_args().event_type_id,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "sub_project" ,
		"placeholder" : "回车查询", 
		"length_css" : "col-xs-12 col-md-3", 
		"title"        :  "sub_project",
		"select_value" : this.get_args().sub_project,
	});

  }
}
*/