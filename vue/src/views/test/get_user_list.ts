
import Vue from 'vue'
import Component from 'vue-class-component'
import vbase from "../layout/vbase"
import {self_RowData, self_Args } from "../page.d.ts/test-get_user_list"


// @Component 修饰符注明了此类为一个 Vue 组件
@Component({
  // 所有的组件选项都可以放在这里
  template:  require("./get_user_list.html" ),
  data: {
    message: "" ,
  }
})

export default class extends vbase {

  data_ex() {
    return {"message": "xx" }
  }

  get_opt_data(obj):self_RowData {return this.get_opt_data_base(obj );}
  get_args() :self_Args  {return  this.get_args_base();}

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

    $.admin_enum_select({
      'join_header'  : $header_query_info,
      "enum_type"    : "grade",
      "field_name" : "grade",
      "title"        :  "年级",
      "select_value" : this.get_args().grade,
      "multi_select_flag"     : true,
      "btn_id_config"     : {},
    });

    $.admin_enum_select({
      'join_header'  : $header_query_info,
      "enum_type"    : "gender",
      "field_name" : "gender",
      "title"        :  "性别",
      "select_value" : this.get_args().gender,
      "multi_select_flag"     : true,
      "btn_id_config"     : {},
    });

    $.admin_ajax_select_user({
      'join_header'  : $header_query_info,
      "user_type"    : "student",
      "field_name"    : "userid" ,
      "title"        :  "userid",
      "select_value" : this.get_args().userid,
    });

    //
    var jquery_body = $("<div> <button class=\"btn  do-add\">增加</button> <a href=\"javascript:;\"class=\"btn btn-warning  do-test \">xx</a> </div>");

    jquery_body.find(".do-add").on( "click" ,function(e) {
      alert("showxx ");
    });

    jquery_body.find(".do-test").on( "click" ,function(e) {
      alert("222");
    });



    $.admin_query_common({
      'join_header'  : $header_query_info,
      "jquery_body" :  jquery_body,
    });

  }
  doOpt(e  : MouseEvent ) {
    var opt_data = this.get_opt_data(e.target);
    BootstrapDialog.alert(JSON.stringify(opt_data));

  };
}
