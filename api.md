api 文档
## Table of Contents

* [Install](#install)
* [vue整合](#vue)

##  Install 

## vue整合

### 运行vue 开发环境
```bash
cdad
cd vue
npm run dev 
```

### Controller 改造 


```php
    public function get_user_list(){
        #分页信息
        $page_info= $this->get_in_page_info();
        #排序信息
        list($order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str([],"userid desc");

        #输入参数
        $grade=$this->get_in_el_grade();
        list($start_time, $end_time)=$this->get_in_date_range_day(0);
        $ret_info=$this->t_student_info->get_test_list($page_info, $order_by_str,  $grade );
        $gender=$this->get_in_el_gender();

        $this->get_in_query_text();
        $userid=$this->get_in_userid(-1);

        #得到当前action :get_user_list 或  get_user_list1 
        $action=$this->get_action_str();
        \App\Helper\Utils::logger("action:$action");

        foreach($ret_info["list"] as &$item) {
            E\Egrade::set_item_value_str($item);
        }

        #哪些数据不需要显示
        $html_hide_list=[];
        if ($action=="get_user_list1"){
            $html_hide_list[]="grade"; //不显示 grade 列
            $html_hide_list[]="opt_grade"; //不显示 操作 grade
            $html_hide_list[]="input_grade"; //不显示 grade输入
        }

        return $this->pageOutJson(__METHOD__, $ret_info,[
            "html_hide_list" =>  $html_hide_list,
            #其他数据
            "message" =>  "cur usrid:".$userid,
        ]);
    }

    //公用ctrl
    public function get_user_list1(){
        $this->set_in_value("grade", 101);
        return $this->get_user_list();
    }

```
### ts 代码 

```typescript
import Vue from 'vue'
import Component from 'vue-class-component'
import vtable from "../../components/vtable"
import {self_RowData, self_Args } from "../page.d.ts/test-get_user_list"

// @Component 修饰符注明了此类为一个 Vue 组件
@Component({
  // 所有的组件选项都可以放在这里
  template:  require("./get_user_list.html" ),
  data: {
    message: "" ,
  }
})

export default class extends vtable {

  data_ex() {
    return {
      "message"          : "xx",

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

  js_xx_loaded ( e  ) {

  }
}

```

### html 模板
```html
<section class="content ">
  <admin-remote-script  @load=js_xx_loaded  src="/js/xx.js" />
  <admin-remote-css  href="/css/test.css" />

  <div id="id_header_query_info" > </div>

  <hr/>
  <div  > test :{{message}} </div>
  <table class=" common-table " >
    <thead>
      <tr> <td data-field_name="userid"> userid </td>
        <td >姓名</td>
        <td data-field_name="grade"  v-if="check_show('grade')"  >年级</td>
        <td>操作</td>
      </tr>
    </thead>
    <tbody>
      <tr v-for="(item, index) in table_data" >
        <td> {{ index }} {{ item.userid }} </td>
        <td> {{ item.phone}} </td>
        <td v-if="check_show('grade')" > {{ item.grade_str}} </td>
        <td >
          <div v-bind:data-index="index" >
            <a class="fa-times " title="xxxxx" @click="doOpt" v-if="check_show('opt_grade')"  > </a> </div>
        </td>
      </tr>
    </tbody>
  </table>
</section>
```
