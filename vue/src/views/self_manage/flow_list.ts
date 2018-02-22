import Vue from 'vue'
import Component from 'vue-class-component'
import vtable from "../../components/vtable"
import {self_RowData, self_Args } from "../page.d.ts/self_manage-flow_list"
// @Component 修饰符注明了此类为一个 Vue 组件.
@Component({
  // 所有的组件选项都可以放在这里.
  template:  require("./flow_list.html" ),
})
export default class extends vtable {


  //不显示全部条件选择器
  show_all_item_limit_item_count=100;

  page_type=1;

  base_init_ex () {
    var me=this;
    $("#id_page_type >li >a" ).on("click",function(e){
      me.page_type= $( e.currentTarget).data("index");
      me.$header_query_info.query();
    });
  }

  //查询用的其他参数
  query_other_args_func (){
    return {
      "page_type" : this.page_type,
    };
  }


  get_args() :self_Args  {return  this.get_args_base();}
  data_ex() {
    //扩展的 data  数据
    var me=this;
    var field_list :Array<any>=[];
    var row_opt_list :Array<any>=[];
      field_list=[{
        field_name: "add_time",
        "title": "时间",
      },{
        field_name: "flow_type_str",
        "order_field_name": "flow_type",
        "title": "flow_type"
      },{
        field_name: "post_admin_nick",
        "title": "管理员",
      },{
        field_name: "line_data",
        "title": "xx",
      }];
      row_opt_list =[{
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
      "auto_hide_flag":  true,
      date_type_config : JSON.parse(this.get_args().date_type_config),
      as_header_query :false,
    });
    $.admin_enum_select({
      'join_header'  : $header_query_info,
      "enum_type"    : "flow_type",
      "field_name" : "flow_type",
      "title" : "分类",
      "select_value" : this.get_args().flow_type,
      "multi_select_flag"     : false ,
      "btn_id_config"   : {},
    });


    $.admin_ajax_select_user ({
      'join_header'  : $header_query_info,
      "field_name"    : "post_adminid" ,
      "length_css" : "col-xs-6 col-md-2",
      "show_title_flag":true,
      "title"        :  "申请人",
      "select_value" : this.get_args().post_adminid,
    });

    $("#id_page_type >li ").removeClass("active");
    $("#id_page_type >li >a[data-index="+this.get_args().page_type +"] ").parent().addClass("active");


  }
  togger_query() {
    $(".header_query_info").toggle();
  }

}
