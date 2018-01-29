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
      field_name: "logtime",
      "order_field_name": "logtime",
      "title": "分配时间",

    },{
      field_name: "seller_student_assign_from_type_str",
      "title": "来源",
    },{
      field_name: "student_nick",
      "title": "学生",

    },{
      "title": "电话号码",
      field_name: "phone",
    },{
      field_name: "admin_nick",
      "title": "cc",

    },{
      field_name: "call_count",
      "order_field_name": "call_count",
      "title": "拨打次数",

    },{
      field_name: "called_flag_str",
      "order_field_name": "called_flag",
      "title": "拨通",
    },{
      field_name: "call_count",
      "order_field_name": "call_count",
      "title": "昵称",
      //"default_display":  false,
      render:function(value, item:self_RowData ,index){
        return value;
        //return "<a class=\"fa btn\" >"+item.admin_nick+"</a>" ;
      }
    },{
      field_name: "call_time",
      "title": "拨打时长",
      "order_field_name": "call_time",
    }];
    var  row_opt_list =[{
      face_icon: "fa-list",
      on_click: me.opt_edit ,
      "title": "拨打记录",
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
      "multi_select_flag"     : false,
      "btn_id_config"     : {},
    });

  }
}
