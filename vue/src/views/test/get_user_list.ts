import Vue from 'vue'
import Component from 'vue-class-component'
import vtable from "../../components/vtable"
import {self_RowData, self_Args } from "../page.d.ts/test-get_user_list"

// @Component 修饰符注明了此类为一个 Vue 组件
@Component({
  // 所有的组件选项都可以放在这里
  template:  require("./get_user_list.html" ),
})

export default class extends vtable {

  data_ex() {
    return {
      "message" : "xx",
    }
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

    var action=  this.get_action_str();


    $.admin_enum_select( {
      "join_header"  : $header_query_info,
      "enum_type" : null,
      "field_name" : "test_select",
      "option_map" : {
        1: "xx",
        2:"kkk 2 ",
        3:"nnn3  ",
      },
      "title" : "自定义列表",
      "select_value" :this.get_args().test_select,

    }) ;


    $.admin_enum_select({
      'join_header'       : $header_query_info,
      "enum_type"         : "grade",
      "field_name"        : "grade",
      "title"             : "年级",
      "need_power"        :  function(html_power_list ){
        return !(html_power_list.grade);
      } ,
      "select_value"      : this.get_args().grade,
      "multi_select_flag" : false,
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



    $.admin_ajax_select_dlg_ajax({
      'join_header'  : $header_query_info,
      "field_name"    : "xmpp_server_id" ,
      "title"        :  "xmpp_server_id ",
      "length_css" :" col-md-3  ",
      "select_value" : this.get_args().xmpp_server_id,



      "opt_type" :  "select", // or "list"
      "url"          : "/user_deal/get_xmpp_server_list_js",
      select_primary_field   : "server_name",
      select_display         : "server_name",
      select_no_select_value : "",
      //select_no_select_title : "[全部]",
      select_no_select_title : "xmpp服务器",
      "th_input_id"  : null,

      //其他参数
      "args_ex" : {
      },
      //字段列表
      'field_list' :[
        {
        title:"ip",
        render:function(val,item) {return item.ip;}
      },{
        title:"权重",
        render:function(val,item) {return item.weights ;}
      },{
        title:"名称",
        render:function(val,item) {return item.server_name;}
      },{

        title:"说明",
        render:function(val,item) {return item.server_desc;}
      }
      ] ,
      filter_list: [],

      "auto_close"       : true,
      //选择
      "onChange"         : function(v) {
        $("id_xmpp_server_name").val(v);
        load_data();
      },
      //加载数据后，其它的设置
      "onLoadData"       : null,
    });
    $.admin_ajax_select_user({
      'join_header'  : $header_query_info,
      "user_type"    : "student",
      "field_name"    : "userid" ,
      "title"        :  "userid",
      "select_value" : this.get_args().userid,
    });
    $.admin_query_input({
      'join_header'     : $header_query_info,
      "field_name"      : "query_text",
      "title"           : "学生" ,
      "placeholder"     : "回车查询",
      "length_css"      : "col-xs-12 col-md-3",
      "select_value"    : this.get_args().query_text,
      "as_header_query" : true,
    });


    $.admin_query_admin_group ({
      'join_header'     : $header_query_info,
      "field_name"      : "group_admin_ex",
      "title"           : "成员" ,
      "length_css"      : "col-xs-12 col-md-3",
      "select_value"    : this.get_args().group_admin_ex,
    });


    $.admin_query_origin({
      'join_header'     : $header_query_info,
      "field_name"      : "origin_ex",
      "title"           : "渠道" ,
      "length_css"      : "col-xs-12 col-md-3",
      "select_value"    : this.get_args().origin_ex,
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
  doOpt(  e  : MouseEvent ,opt_data: self_RowData  ) {
    BootstrapDialog.alert(JSON.stringify(opt_data));
  };

  opt_edit(e:MouseEvent ,opt_data: self_RowData) {
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
  gen_test_field (row_data: self_RowData ) {
    return "KKKK "  +  row_data.realname;
  }
}
