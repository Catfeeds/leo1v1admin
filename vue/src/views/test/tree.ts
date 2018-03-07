import Vue from 'vue'
import Component from 'vue-class-component'
import vtable from "../../components/vtable"
import {self_RowData, self_Args } from "../page.d.ts/test-get_user_list"

// @Component 修饰符注明了此类为一个 Vue 组件
@Component({
  // 所有的组件选项都可以放在这里
  template:  require("./tree.html" ),
})

export default class extends vtable {

  data_ex() {
    return {
      "message"          : "xx",
    }
  }

  do_created_ex(call_func) {
    this.load_admin_js_list([
      "/js/jquery.treetable.js",
    ], call_func);
  }
  //

  get_opt_data(obj):self_RowData {return this.get_opt_data_base(obj );}
  get_args() :self_Args  {return  this.get_args_base();}


  query_init( $header_query_info): void{
    console.log("init_query");
    var me =this;


    var action=  this.get_action_str();

    $.admin_enum_select({
      'join_header'       : $header_query_info,
      "enum_type"         : "grade",
      "field_name"        : "grade",
      "title"             : "年级",
      "select_value"      : this.get_args().grade,
      "multi_select_flag" : true,
      "btn_id_config"     : {},
    });


    $.admin_enum_select({
      'join_header'       : $header_query_info,
      "enum_type"         : "gender",
      "field_name"        : "gender",
      "title"             : "性别",
      "select_value"      : this.get_args().gender,
      "multi_select_flag" : true,
      "btn_id_config"     : {},
    });


    $.admin_ajax_select_user({
      'join_header'  : $header_query_info,
      "user_type"    : "student",
      "field_name"    : "userid" ,
      "title"        :  "userid",
      "select_value" : this.get_args().userid,
    });
    $.admin_query_input({
      'join_header'  : $header_query_info,
      "field_name"  :"query_text",
      "title"  :  "学生" ,
      "placeholder" : "回车查询",
      "length_css" : "col-xs-12 col-md-3",
      "select_value" : this.get_args().query_text,
      "as_header_query" :true,
    });


    //JQuery 写法
    var jquery_body = $("<div> <button class=\"btn btn-primary do-add\">增加</button> <a href=\"javascript:;\"class=\"btn btn-warning  do-test \">xx</a> </div>");

    jquery_body.find(".do-add").on( "click" ,function(e) {
      BootstrapDialog.alert("asdfa");
    });

    jquery_body.find(".do-test").on( "click" ,function(e) {
      BootstrapDialog.alert(" test 2");
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

  do_edit(e:MouseEvent) {
    var opt_data = this.get_opt_data(e.target);
    var $nick= $("<input/>");
    $nick.val( opt_data.nick );
    var arr=[
      ["userid",opt_data.userid],
      ["昵称",$nick],
    ];
    $.show_key_value_table("编辑",arr,{
      label: '确认',
      cssClass: 'btn-warning',
      action: function(dialog) {
        $.do_ajax("/test/set_student_nick",{
          "userid" :opt_data.userid,
          "nick" :$nick.val(),
        });
      }
    });
  }
  js_xx_loaded ( e  ) {

  }



  base_init_ex (){
    $("#id-example-advanced").table_head_static(400);
    $("#id-example-advanced").treetable({
      expandable: true,
      clickableNodeNames: true,
    });
  }

}
