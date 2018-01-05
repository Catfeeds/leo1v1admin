api 文档
## Table of Contents

* [Install](#install)
* [Controller  控制器](#controller)
* [DB 数据库](#db)
* [power 权限设计 ](#power)

* [vue整合](#vue)

##  Install 

## Controller  控制器

```php
    public function get_user_list(){
        #分页信息
        $page_info= $this->get_in_page_info();
        #排序信息
        list($order_in_db_flag, $order_by_str, $order_field_name,$order_type )
            =$this->get_in_order_by_str([],"userid desc");

        #输入参数
        list($start_time, $end_time)=$this->get_in_date_range_day(0);
        $grade=$this->get_in_el_grade();

        $ret_info=$this->t_student_info->get_test_list($page_info, $order_by_str, $start_time,$end_time ,  $grade );

        foreach($ret_info["list"] as &$item) {
            E\Egrade::set_item_value_str($item);
        }

        return $this->pageView(__METHOD__, $ret_info,[
            "message" =>  "cur usrid:".$userid,
        ]);
    }
```


### input


```php
$xxx=$this->get_in_el_xxx();
//如:
$grade=$this->get_in_el_grade();
```

#### 整数 
```php
$age=$this->get_in_intval("age", -1);
```


#### 字符串 
```php
$name=$this->get_in_strval("name");
```

### 切换数据库
目前线上 有  主库, 只读1, 只读2(用统计) 
对 后台而言 , select 默认使用只读1

如果要 切到统计数据库
```php
    public function tt() {
        $this->check_and_switch_tongji_domain();
        ...
        dd( $_SERVER );
    }
```

## DB 数据库

### migration 

```php
        Schema::create('db_weiyi_admin.t_url_desc_power', function( Blueprint $table)
        {
            //表注释
            t_comment($table, "角色的每个页面的详细权限" );
            //字段以及注释
            t_field($table->integer("id",true) ,"自增id");
            t_field($table->integer("role_groupid") ,"角色组id");
            t_field($table->string("url") ,"页面地址:/test/get_user_list1");
            t_field($table->string("opt_key") ,"权限识别:grade,opt_grade,input_grade..");
            t_field($table->integer("open_flag") ,"是否开放权限");
            //唯一索引
            $table->unique(["role_groupid", "url","opt_key"],"role_url_opt_key");
        });

```


### 自动生成 表 的对应代码


### 操作 mysql 核心函数 
#### 核心函数

```php
#insert 
        return $this->row_insert([
            "logtime" =>$logtime,
            "online_count" => $online_count,
        ],true );
# 主键更新 
        $this->field_update_list($id, [
            "online_count" => 100
        ]);
# 主键 得到记录
        $row=$this->field_get_list($id, "*");
# 主键 得到value
        $this->get_xxxx($id);
        $this->get_online_count($id);

#普通的更新 
        $this->gen_sql_new("update %s  set online_count=%u  where id=%u ",
                           self::DB_TABLE_NAME, $online_count, $id);
        $this->main_update($sql);

#普通的得到记录
        $this->main_get_row($sql);

#普通的得到一个字段值
        $this->main_get_value($sql);

#普通的得到列表
        $this->main_get_list$sql);

```


#### 通常 分页
```php
    public function get_test_list( $page_info, $order_by_str,  $grade ) {
        $where_arr=[] ;
        // int , 枚举, 枚举列表 ,都用这个
        $this->where_arr_add_int_or_idlist($where_arr,"grade", $grade );

        $sql = $this->gen_sql_new("select  userid, nick,realname,  phone, grade "
                              ." from %s ".
                                  "  where  %s  $order_by_str ",
                              self::DB_TABLE_NAME,
                                  $where_arr
        );

        return $this->main_get_list_by_page($sql, $page_info);
    }
```

## power 权限设计

通过配置 将会自动实现 以下代码 
```php
    public function get_user_list1(){
        $this->set_in_value("grade", 101);
        $this->set_in_value("sys_operid",  $this->get_account_id() );
        //
        $this->html_power_list_add([ "grade","opt_grade", "input_grade" ]);
        return $this->get_user_list();
    }
```

blade  代码整合
```html
            @if (  isset($html_power_list["input_account_role"] )  )
            <div class="col-md-2 col-xs-0">
                <div class="input-group ">
                    <span>角色</span>
                    <select class="opt-change" id="id_account_role">
                    </select>
                </div>
            </div>
            @endif

```

```html
表头
@if ( isset($html_power_list["account_role"] )  )
    <td>角色</td>
@endif

每一行
@if (  isset($html_power_list["account_role"] )  )
    <td>{{$var["account_role_str"]}}</td>
@endif

```



## vue整合

**单页应用 无刷新**

**支持typescript**



### 运行vue 开发环境
```bash
cdad
cd vue
npm run dev 
```

目录结构
```bash
localhost:~/admin_yb1v1/vue/src$ tree components/ views/
components/
└── vtable.ts
views/
├── 404.vue
├── common
├── page.d.ts
│   ├── test-get_user_list1.ts
│   └── test-get_user_list.ts
└── test
    ├── get_user_list1.ts
    ├── get_user_list.html
    └── get_user_list.ts
```

```bash
localhost:~/admin_yb1v1/vue$ tree static/
static/
├── css
│   └── new_header.css
└── js
    ├── jquery.admin.js
    ├── new_header.js
    └── vue_header.js
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
        list($start_time, $end_time)=$this->get_in_date_range_day(0);
        $userid=$this->get_in_userid(-1);
        $grade=$this->get_in_el_grade();
        $gender=$this->get_in_el_gender();
        $query_text=$this->get_in_query_text();

        $ret_info=$this->t_student_info->get_test_list($page_info, $order_by_str,  $grade );

        foreach($ret_info["list"] as &$item) {
            E\Egrade::set_item_value_str($item);
        }

        return $this->pageOutJson(__METHOD__, $ret_info,[
            "message" =>  "cur usrid:".$userid,
        ]);
    }
    public function get_user_list1(){
        $this->set_in_value("grade", 101);
        //
        $this->html_power_list_add([ "grade","opt_grade", "input_grade" ]);
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
  opt_xxx(e  : MouseEvent ) {
    var opt_data = this.get_opt_data(e.target);
    BootstrapDialog.alert(JSON.stringify(opt_data));

  };

  js_xx_loaded ( e  ) {

  }
}



```
```typescript 
// g_args  =>  this->get_args()

// var arr=[]; =>  arr:Array<any>=[] ;

 $.each(arr, function(){
    var v = this.value;
})

=>

$.each(arr, function(i,item){
    var v = item.value;
})
 
```
```typescript 
$(".opt-xxx").on("click",function(){
  var opt_data=$(this).get_opt_data();
	
});

=>
  opt_xxx(e  : MouseEvent ) {
    var opt_data = this.get_opt_data(e.target);
    BootstrapDialog.alert(JSON.stringify(opt_data));

  };
``

**重新实现** 
```typescript 

//vue_domain_flag 是否是静态页面,还是用来的页面
//$.wopen( url:string,open_self_window?:boolean ,vue_domain_flag?:boolean ):void;

$.wopen('/seller_student_new/no_lesson_call_end_time_list?adminid='+ opt_data.uid) ;


// 自动调用到 php 服务

$.do_ajax

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
            <a class="fa-times " title="xxxxx" @click="opt_xxx" v-if="check_show('opt_grade')"  > </a> </div>
        </td>
      </tr>
    </tbody>
  </table>
</section>
```

