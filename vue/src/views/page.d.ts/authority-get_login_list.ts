interface self_Args {
	account:	number;
	flag:	number;
	start_date:	string;
	end_date:	string;
	login_info:	string;
	page_num:	number;
	page_count:	number;
}
interface self_RowData {
	account	:any;
	all_count	:any;
	succ	:any;
	fail	:any;
}

export  {self_RowData , self_Args  }
/*

tofile:
	 mkdir -p ../authority; vi  ../authority/get_login_list.ts


import Vue from 'vue'
import Component from 'vue-class-component'
import vtable from "../../components/vtable"
import {self_RowData, self_Args } from "../page.d.ts/authority-get_login_list"

// @Component 修饰符注明了此类为一个 Vue 组件
@Component({
  // 所有的组件选项都可以放在这里
  template:  require("./get_login_list.html" ),
})

export default class extends vtable {

  get_opt_data(obj):self_RowData {return this.get_opt_data_base(obj );}
  get_args() :self_Args  {return  this.get_args_base();}

  data_ex() {
    //扩展的 data  数据
     return {"message": "xx" }
   }
  query_init( $header_query_info): void{
    console.log("init_query");
    var me =this;

	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "account" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "account",
		"select_value" : this.get_args().account,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "flag" ,
		"length_css" : "col-xs-6 col-md-2", 
		"show_title_flag":true, 
		"title"        :  "flag",
		"select_value" : this.get_args().flag,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "start_date" ,
		"placeholder" : "回车查询", 
		"length_css" : "col-xs-12 col-md-3", 
		"title"        :  "start_date",
		"select_value" : this.get_args().start_date,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "end_date" ,
		"placeholder" : "回车查询", 
		"length_css" : "col-xs-12 col-md-3", 
		"title"        :  "end_date",
		"select_value" : this.get_args().end_date,
	});
	$.admin_query_input({
		'join_header'  : $header_query_info,
		"field_name"    : "login_info" ,
		"placeholder" : "回车查询", 
		"length_css" : "col-xs-12 col-md-3", 
		"title"        :  "login_info",
		"select_value" : this.get_args().login_info,
	});

  }
}
*/
