import Vue from 'vue'
import Component from 'vue-class-component'
import vtable from "../../components/vtable"
import {self_RowData, self_Args } from "../page.d.ts/month_def_type-def_list"
// @Component 修饰符注明了此类为一个 Vue 组件.
@Component({
  // 所有的组件选项都可以放在这里.
  template:  require("./def_list.html" ),
})
export default class extends vtable {

  get_args() :self_Args  {return  this.get_args_base();}
  data_ex() {
    //扩展的 data  数据
    var me=this;
    var field_list=[{
      field_name: "id",
      "title": "id",
    },{
      field_name: "month_def_type_str",
      "title": "月定义类型",
    },{
      field_name: "def_time",
      "title": "定义时间",
    },{
      field_name: "start_time",
      "title": "开始时间",
    },{
      field_name: "end_time",
      "title": "结束时间",
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
	$.admin_enum_select({
		'join_header'  : $header_query_info,
    "enum_type"    : "month_def_type",
		"field_name"    : "month_def_type" ,
		"length_css" : "col-xs-6 col-md-2",
		"show_title_flag":true,
		"title"        :  "类型",
		"select_value" : this.get_args().month_def_type,
	});

  }
}
