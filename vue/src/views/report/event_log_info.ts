import Vue from 'vue'
import Component from 'vue-class-component'
import vtable from "../../components/vtable"
import {self_RowData, self_Args } from "../page.d.ts/report-event_log_info"
// @Component 修饰符注明了此类为一个 Vue 组件.
@Component({
  // 所有的组件选项都可以放在这里.
  template:  require("./event_log_info.html" ),
})
export default class extends vtable {

  get_args() :self_Args  {return  this.get_args_base();}
  data_ex() {
    //扩展的 data  数据
    var me=this;

    return {
    }
  }
  opt_show_list ( e:MouseEvent, opt_data: self_RowData ){
    $.wopen("/#/report/event_log_list?sub_project="+opt_data.sub_project, false,  true  );
  }

  opt_show_desc ( e:MouseEvent, opt_data: self_RowData ){

        $("<div></div>").admin_select_dlg_ajax({
            "opt_type" : "list", // or "list"
            "url"      : "/report/event_log_sub_project_event_info_js",
            //其他参数
            "args_ex" : {
                "sub_project": opt_data.sub_project
            },

            //字段列表
            'field_list' :[
                {
                    title:"事件名",
                    field_name:"event_name"
                },{
                    title:"总个数",
                    field_name:"count"
                },{
                    title:"ip独立个数",
                    field_name:"ip_count"
                }
            ] ,
            //查询列表
            filter_list:[
            ],
            "auto_close" : true,
        });
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


  }
}
