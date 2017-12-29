interface self_Args {
	page_num:	number;
	page_count:	number;
	grade:	string;//枚举列表: \App\Enums\Egrade
 	date_type_config:	string;
	date_type:	number;
	opt_date_type:	number;
	start_time:	string;
	end_time:	string;
	order_by_str:	string;
	gender:	string;//枚举列表: \App\Enums\Egender
 	query_text:	string;
	userid:	number;
}
interface self_RowData {
	userid	:any;
	nick	:any;
	realname	:any;
	phone	:any;
	grade	:any;
	grade_str	:any;
}

export  {self_RowData , self_Args  }
/*

tofile: 
	 mkdir -p ../test; vi  ../test/get_user_list.ts


import Vue from 'vue'
import Component from 'vue-class-component'
import vbase from "../layout/vbase"
import {self_RowData, self_Args } from "../page.d.ts/test-get_user_list"

// @Component 修饰符注明了此类为一个 Vue 组件
@Component({
  // 所有的组件选项都可以放在这里
  template:  require("./get_user_list.html" ),
})

export default class extends vbase {

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
"title" : "grade",
"select_value" : this.get_args().grade,
		"multi_select_flag"     : true,
		"btn_id_config"     : {},
	});

	$.admin_enum_select({
		'join_header'  : $header_query_info,
"enum_type"    : "gender",
"field_name" : "gender",
"title" : "gender",
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

  }
}
*/
