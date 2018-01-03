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

        #哪些数据不需要
        $html_hide_list=[];
        if ($action=="get_user_list1"){
            $html_hide_list[]="grade";
            $html_hide_list[]="opt_grade";
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
