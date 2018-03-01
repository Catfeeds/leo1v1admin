import Vue from 'vue'
import Component from 'vue-class-component'
import vtable from "../../components/vtable"
import {self_RowData, self_Args } from "../page.d.ts/tongji2-tongji_sys_assign_call_info"
// @Component 修饰符注明了此类为一个 Vue 组件.

@Component({
  // 所有的组件选项都可以放在这里.
  template:  require("./tongji_sys_assign_call_info.html" ),
})
export default class extends vtable {

  get_args() :self_Args  {return  this.get_args_base();}
  data_ex() {
    //扩展的 data  数据
    var me=this;
    var field_list=[{
      field_name: "logtime",
      "order_field_name": "logtime",
      "title": "分配时间",

    },{
      field_name: "seller_student_assign_from_type_str",
      "order_field_name": "seller_student_assign_from_type",
      "title": "来源",
    },{
      field_name: "userid",
      "title": "userid",

    },{
      field_name: "student_nick",
      "title": "学生",

    },{

      "title": "电话号码",
      field_name: "phone",
    },{
      field_name: "add_time",
      "order_field_name": "add_time",
      "title": "进库时间",

    },{
      field_name: "origin",
      "order_field_name": "origin",
      "title": "渠道",
    },{
      field_name: "origin_level_str",
      "order_field_name": "origin_level",
      "title": "渠道等级",
    },{
      field_name: "admin_nick",
      "title": "分配给cc",
    },{
      field_name: "seller_level_str",
      "title": "cc等级",
    },{
      field_name: "admin_revisiter_nick",
      "title": "当前cc",
    },{
      field_name: "call_count",
      "order_field_name": "call_count",
      "title": "拨打次数",
    },{
      field_name: "called_flag_str",
      "order_field_name": "called_flag",
      "title": "拨通",

    },{
      field_name: "call_time",
      "title": "拨打时长",
      "order_field_name": "call_time",
    },{
      field_name: "check_hold_flag_str",
      "title": "是否不占名额",
      "order_field_name": "check_hold_flag",
    }];
    var  row_opt_list =[{
      face_icon: "fa-list",
      on_click: me.opt_list,
      "title": "拨打记录",
    },{
      face_icon: "fa-comments",
      display: function(row_data:self_RowData){
        return row_data.origin_level>2;
      },
      on_click: me.opt_user_return_back_list,
      "title": "拨打记录",
    }];

    return {
      "table_config":  {
        "field_list": field_list,
        "row_opt_list": row_opt_list,
      }
    }
  }
  opt_user_return_back_list (  e:MouseEvent, opt_data: self_RowData){
    $.show_user_return_back_list( opt_data.userid, opt_data.phone);
  }

  opt_list( e:MouseEvent, opt_data: self_RowData ){
    //alert(JSON.stringify( opt_data));
    $.wopen("/tq/get_list?uid="+opt_data.adminid+"&" +"phone=" + opt_data.phone  );
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


    $.admin_enum_select({
      'join_header'  : $header_query_info,
      "enum_type"    : "seller_student_assign_from_type",
      "field_name" : "seller_student_assign_from_type",
      "title" : "来源",
      "select_value" : this.get_args().seller_student_assign_from_type,
      "multi_select_flag"     : true,
      "btn_id_config"     : {},
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
    $.admin_enum_select({
      'join_header'  : $header_query_info,
      "enum_type"    : "boolean",
      "field_name" : "called_flag",
      "title" : "拨通",
      "select_value" : this.get_args().called_flag,
      "multi_select_flag"     : false,
      "btn_id_config"     : {},
    });

	  $.admin_enum_select({
		  'join_header'  : $header_query_info,
      "enum_type"    : "boolean",
      "field_name" : "same_admin_flag",
      "title" : "cc一致",
      "select_value" : this.get_args().same_admin_flag,
		  "multi_select_flag"     : false,
		  "btn_id_config"     : {},
	  });


	  $.admin_enum_select({
		  'join_header'  : $header_query_info,
      "enum_type"    : "boolean",
      "field_name" : "check_hold_flag",
      "title" : "不占配额",
      "select_value" : this.get_args().check_hold_flag,
		  "multi_select_flag"     : false,
		  "btn_id_config"     : {},
	  });


  }
  opt_multi_set_check_hold_flag () {
    this.do_select_list("id",function(select_list){

      var $check_hold_flag=$("<select/>");
      Enum_map.append_option_list( "boolean",$check_hold_flag,true  );
      var arr =[
        ["是否不占名额", $check_hold_flag ],
      ];

        $.show_key_value_table("批量设置:(" + select_list.length + ")个" , arr ,{
            label: '确认',
            cssClass: 'btn-warning',
            action: function(dialog) {

              $.do_ajax("/ss_deal2/multi_set_assign_check_hold_flag",{
                "id_list" :  select_list.join(","),
                "check_hold_flag" :  $check_hold_flag.val()
              });
            }
        });

    });
  }
}
