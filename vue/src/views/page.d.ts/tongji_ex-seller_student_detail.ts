interface self_Args {
	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	page_num:	number;
	page_count:	number;

}
interface self_RowData {
	key0	:any;
	seller_resource_type	:any;
	first_call_time	:any;
	first_contact_time	:any;
	test_lesson_count	:any;
	first_revisit_time	:any;
	last_revisit_time	:any;
	tmk_assign_time	:any;
	last_contact_time	:any;
	last_contact_cc	:any;
	competition_call_adminid	:any;
	competition_call_time	:any;
	sys_invaild_flag	:any;
	wx_invaild_flag	:any;
	return_publish_count	:any;
	tmk_adminid	:any;
	test_lesson_subject_id	:any;
	seller_student_sub_status	:any;
	add_time	:any;
	global_tq_called_flag	:any;
	seller_student_status	:any;
	userid	:any;
	nick	:any;
	origin	:any;
	origin_level	:any;
	phone_location	:any;
	phone	:any;
	sub_assign_adminid_2	:any;
	admin_revisiterid	:any;
	admin_assign_time	:any;
	sub_assign_time_2	:any;
	origin_assistantid	:any;
	origin_userid	:any;
	subject	:any;
	grade	:any;
	user_desc	:any;
	has_pad	:any;
	require_adminid	:any;
	tmk_student_status	:any;
	first_tmk_set_valid_admind	:any;
	first_tmk_set_valid_time	:any;
	tmk_set_seller_adminid	:any;
	first_tmk_set_seller_time	:any;
	first_admin_master_adminid	:any;
	first_admin_master_time	:any;
	first_admin_revisiterid	:any;
	first_admin_revisiterid_time	:any;
	first_seller_status	:any;
	call_count	:any;
	auto_allot_adminid	:any;
	first_called_cc	:any;
	first_get_cc	:any;
	test_lesson_flag	:any;
	orderid	:any;
	price	:any;
	seller_student_status_str	:any;
	global_tq_called_flag_str	:any;
	cc_nick	:any;
	suc_test_flag	:any;
	order_flag	:any;

}
export  {self_RowData , self_Args  }
/*
tofile:
	 mkdir -p ../tongji_ex; vi  ../tongji_ex/seller_student_detail.ts

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
      field_name: "title",
      "title": "说明",
    },{
      field_name: "admin_nick",
      "order_field_name": "admin_nick",
      "title": "昵称",
      "default_display":  false,
      render:function(value, item:self_RowData ,index){
        return "<a class=\"fa btn\" >"+item.admin_nick+"</a>" ;
      }
    },{
      field_name: "auth_flag_str",
      "title": "管理员",
      "order_field_name": "auth_flag",
      need_power: "auth_flag",
      render:function(value, item:self_RowData ,index){
        return "<a class=\"fa btn\" >"+value+"</a>" ;
      }
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


  }
}
*/