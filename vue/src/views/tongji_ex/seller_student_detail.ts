import Vue from 'vue'
import Component from 'vue-class-component'
import vtable from "../../components/vtable"
import {self_RowData, self_Args } from "../page.d.ts/tongji_ex-seller_student_detail"

// @Component 修饰符注明了此类为一个 Vue 组件.
@Component({
  // 所有的组件选项都可以放在这里.
  template:  require("./seller_student_detail.html" ),
})

export default class extends vtable {

  get_args() :self_Args  {return  this.get_args_base();}
  data_ex() {
    //扩展的 data  数据
    var me=this;
    var field_list=[{
      title: "userid",
      field_name: "userid",
      default_display: "1",
    },{
      title: "例子进入时间",
      field_name: "add_time",
      default_display: "1",
    },{
      title: "渠道",
      field_name: "origin",
      default_display: "1",
    },{
      title: "回访状态",
      field_name: "seller_student_status",
      default_display: "1",
    },{
      title: "全局tq状态",
      field_name: "",
      default_display: "1",
    },{
      title: "负责人",
      field_name: "",
      default_display: "1",
    },{
      title: "首个拨打人",
      field_name: "first_called_adminid",
      default_display: "1",
    },{
      title: "首个认领人",
      field_name: "first_get_adminid",
      default_display: "1",
    },{
      title: "试听排课",
      field_name: "test_lessonid",
      default_display: "1",
    },{
      title: "试听成功",
      field_name: "suc_test_lesson",
      default_display: "1",
    },{
      title: "是否签单",
      field_name: "order",
      default_display: "1",
    },{
      title: "签单金额",
      field_name: "price",
      default_display: "1",
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

  initPicker(obj)
  {
    obj.datetimepicker({
      lang       : 'ch',
      datepicker : true,
      timepicker : false,
      format     : 'Y-m-d',
      step       : 30,
      onChangeDateTime :function(){
        $(this).hide();
      }
    });
  }

  opt_edit( e:MouseEvent, opt_data: self_RowData ){
    alert(JSON.stringify( opt_data));
  }

  query_init($header_query_info): void{
    console.log("init_query");
    var me =this;

    // $('#id_date_range').select_date_range({
    //   'date_type'      : me.get_args().date_type,
    //   'opt_date_type'  : me.get_args().opt_date_type,
    //   'start_time'     : me.get_args().start_time,
    //   'end_time'       : me.get_args().end_time,
    //   date_type_config : JSON.parse(me.get_args().date_type_config),
    //   onQuery :function() {
    //     load_data();
    //   }
    // });

    $.admin_enum_select({
      'join_header'       : $header_query_info,
      "enum_type"         : "boolean",
      "field_name"        : "",
      "title"             : "",
      "select_value"      : '',
      "multi_select_flag" : true,
      "btn_id_config"     : {},
    });

  }
}
