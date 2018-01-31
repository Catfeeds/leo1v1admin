import Vue from 'vue'
import Component from 'vue-class-component'
import vtable from "../../components/vtable"
import {self_RowData, self_Args } from "../page.d.ts/admin_manage-kaoqin_machine_adminid"
// @Component 修饰符注明了此类为一个 Vue 组件.
@Component({
  // 所有的组件选项都可以放在这里.
  template:  require("./kaoqin_machine_adminid.html" ),
})
export default class extends vtable {

  get_args() :self_Args  {return  this.get_args_base();}
  data_ex() {
    //扩展的 data  数据
    var me=this;
    var field_list=[{
      field_name: "title",
      "title": "id",
    },{
      field_name: "admin_nick",
      "order_field_name": "admin_nick",
      "title": "昵称",
      "default_display":  false,
      render:function(value, item:self_RowData ,index){
        return item.admin_nick;
      }
    },{
      field_name: "auth_flag_str",
      "title": "管理员",
      "order_field_name": "auth_flag",
      render:function(value, item:self_RowData ,index){
        return value ;
      }
    }];
    var  row_opt_list =[{
      face_icon: "fa-edit",
      on_click: me.opt_edit ,
      "title": "编辑",
    },{
      face_icon: "fa-times",
      "title": "删除",
      on_click: me.opt_del,
    }];

    return {
      "table_config":  {
        "field_list": field_list,
        "row_opt_list": row_opt_list,
      }
    }
  }
  opt_edit( e:MouseEvent, opt_data: self_RowData ){

    var $auth_flag =$("<select/>");
    Enum_map.append_option_list( "boolean", $auth_flag, true );

    var arr=[
      ["机器" ,  opt_data.title ],
      ["账号" ,  opt_data.admin_nick ],
      ["管理员" ,  $auth_flag ],
    ];
    $auth_flag.val( opt_data.auth_flag );


    $.show_key_value_table("修改", arr ,{
      label: '确认',
      cssClass: 'btn-warning',
      action: function(dialog) {
        $.do_ajax("/user_deal/set_kaoqin_machine_adminid",{
          "machine_id" : opt_data.machine_id ,
          "adminid" :  opt_data.adminid,
          "auth_flag"  :  $auth_flag.val()
        });
      }
    },function(){

    });


  }

  opt_del( e:MouseEvent, opt_data: self_RowData ){
    BootstrapDialog.confirm("要删除:"+ opt_data.title  + "-" + opt_data.admin_nick , function(val){
      if (val) {
        $.do_ajax("/user_deal/del_kaoqin_machine_adminid",{
          "machine_id" : opt_data.machine_id ,
          "adminid" :  opt_data.adminid,
        });
      }
    });
  }
  opt_multi_delete  ( e:MouseEvent  )  {

    this.do_select_list(["machine_id", "adminid"],function(select_list){
      BootstrapDialog.confirm("要删除:("+ select_list.length +")个"  , function(val){
        if (val) {
          $.each( select_list, function( i, item ) {
            $.do_ajax("/user_deal/del_kaoqin_machine_adminid",{
              "machine_id" : item.machine_id ,
              "adminid" :  item.adminid,
            });
          });
        }
      });
    });
  }

  opt_add ( e:MouseEvent ) {

    if ( this.get_args() .machine_id== -1  ) {
      alert("请选择考勤机");
      return;
    }

    var $adminid=$("<input/>");
    var arr=[
      ["账号" ,  $adminid],
    ];
    var me=this;


    $.show_key_value_table("修改", arr ,{
      label: '确认',
      cssClass: 'btn-warning',
      action: function(dialog) {
        $.do_ajax("/user_deal/add_kaoqin_machine_adminid",{
          "machine_id" : me.get_args().machine_id ,
          "adminid" : $adminid.val()
        });
      }
    },function(){
      $.admin_select_user( $adminid, "admin");
    });
  }


  query_init( $header_query_info): void{
    console.log("init_query");
    var me =this;

    $.admin_ajax_select_dlg_ajax({
      'join_header'  : $header_query_info,
      "field_name"    : "machine_id" ,
      "title"        :  "machine_id",
      "length_css" :" col-md-3  ",
      "select_value" : this.get_args().machine_id ,

      "opt_type" :  "select", // or "list"
      "url"          : "/admin_manage/get_kaoqin_list_js",
      select_primary_field   : "machine_id",
      select_display         : "title",
      select_no_select_value : "",
      select_no_select_title : "考勤机[全部]",
      "th_input_id"  : null,

      //其他参数
      "args_ex" : {
      },
      //字段列表
      'field_list' :[
        {
        title:"machine_id",
        render:function(val,item) {return item.machine_id;}
      },{
        title:"sn",
        render:function(val,item) {return item.sn ;}
      },{
        title:"title",
        render:function(val,item) {return item.title;}
      },{

        title:"说明",
        render:function(val,item) {return item.desc;}
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
      "user_type"    : "account",
      "field_name"    : "adminid",
      "title"        :  "adminid",
      "select_value" : this.get_args().adminid,
    });
    $.admin_enum_select({
      'join_header'  : $header_query_info,
      "enum_type"    : "boolean",
      "field_name" : "auth_flag",
      "title" : "管理员",
      "select_value" : this.get_args().auth_flag,
      "multi_select_flag"     : false ,
      "btn_id_config"     : {},
    });


  }
}
