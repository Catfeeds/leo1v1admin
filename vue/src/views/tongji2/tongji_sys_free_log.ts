import Vue from 'vue'
import Component from 'vue-class-component'
import vtable from "../../components/vtable"
import {self_RowData, self_Args } from "../page.d.ts/tongji2-tongji_sys_free_log"
// @Component 修饰符注明了此类为一个 Vue 组件.

@Component({
  // 所有的组件选项都可以放在这里.
  template:  require("./tongji_sys_free_log.html" ),
})
export default class extends vtable {

  get_args() :self_Args  {return  this.get_args_base();}
  data_ex() {
    //扩展的 data  数据
    var me=this;
    var field_list=[{
      field_name: "adminid",
      "title": "adminid",

    },{
      field_name: "account",
      "title": "cc名称",

    },{
      field_name: "userid",
      "title": "userid",
    },{
      field_name: "phone",
      "title": "用户电话",

    },{
      field_name: "phone",
      "title": "用户电话",

    },{
      field_name: "admin_assign_time",
      "title": "分配时间",

    },{

      "title": "释放时机",
      field_name: "release_reason_flag_str",
    }];

    return {
      "table_config":  {
        "field_list": field_list,
      }
    }
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
      "title"        :  "cc",
      "select_value" : this.get_args().adminid,
    });
    $.admin_ajax_select_user({
      'join_header'  : $header_query_info,
      "user_type"    : "student",
      "field_name"    : "userid",
      "title"        :  "学生",
      "select_value" : this.get_args().userid,
    });

  }
}
