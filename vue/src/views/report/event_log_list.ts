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
    return {
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
    "field_name"    : "sub_project" ,
    "placeholder" : "回车查询",
    "length_css" : "col-xs-12 col-md-3",
    "title"        :  "sub_project",
    "select_value" : this.get_args().sub_project,
  });

  }
}
