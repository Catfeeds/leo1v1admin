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
      field_name: "id",
      "title": "id",
    },{
      field_name: "created_at",
      "title": "创建时间",
    },{
      field_name: "",
      "title": "内容",
      render:function(value, item:self_RowData ,index){
        return item.payload.substr(0, 200);
      }
    }];
    var  row_opt_list =[];
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

  opt_multi_delete  ( e:MouseEvent  )  {

    this.do_select_list( "id",function(select_list){
      BootstrapDialog.confirm("要删除:("+ select_list.length +")个"  , function(val){
        if (val) {
          $.each( select_list, function( i, item ) {
            $.do_ajax("/admin_manage/job_del_list",{
              id_list:  select_list.join(",")
            });
          });
        }
      });
    });
  }
}
