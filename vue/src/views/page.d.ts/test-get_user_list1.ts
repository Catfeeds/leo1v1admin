interface self_Args {
	page_num:	number;
	page_count:	number;
	grade:	string;//枚举列表: \App\Enums\Egrade
 	query_text:	string;
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
	 mkdir -p ../../../vue/src/views/test; vi  ../../../vue/src/views/test/get_user_list1.ts


import Vue from 'vue'
import Component from 'vue-class-component'
import vbase from "../layout/vbase"
import {self_RowData, self_Args } from "../page.d.ts/test-get_user_list1"

// @Component 修饰符注明了此类为一个 Vue 组件
@Component({
  // 所有的组件选项都可以放在这里
  template:  require("./get_user_list1.html" ),
})

export default class extends vbase {

  get_opt_data(obj):self_RowData {return this.get_opt_data_base(obj );}
  get_args() :self_Args  {return  this.get_args_base();}

  query_init(): void{
    console.log("init_query");
    var me =this;

    var $header_query_info= $("#id_header_query_info").admin_header_query ({
    });
	$.admin_enum_select({
		'join_header'  : $header_query_info,
"enum_type"    : "grade",
"field_name" : "grade",
"select_value" : this.get_args().grade,
		"multi_select_flag"     : true,
		"onChange"     : load_data,
		"th_input_id"  : "th_grade",
		"btn_id_config"     : {},
	});


  }
}
*/
