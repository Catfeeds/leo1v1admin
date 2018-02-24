<?php
namespace App\Models;
use \App\Enums as E;
class t_admin_self_menu extends \App\Models\Zgen\z_t_admin_self_menu
{

    public function __construct()
    {
        parent::__construct();
    }
    public function get_new_order_index($adminid ) {
        $sql=$this->gen_sql_new("select max(order_index)  from  %s where adminid=%u ",
                                self::DB_TABLE_NAME, $adminid);

        return $this->main_get_value($sql)+1;
    }

    public function  switch_order_index( $id1, $id2 )  {
        $order_index1= $this->get_order_index($id1);
        $order_index2= $this->get_order_index($id2);

        $this->set_order_index($id1,NULL);
        $this->set_order_index($id2, $order_index1 );
        $this->set_order_index($id1, $order_index2 );

    }
    public function add( $adminid,$title,  $url, $icon ) {
        $order_index=$this->get_new_order_index($adminid);
        $this->row_insert([
            "adminid" => $adminid,
            "title" => $title,
            "url" => $url,
            "icon" => $icon,
            "order_index" => $order_index,
        ]);
    }
    public function del($adminid, $id  ) {
        if ($this->get_adminid($id) ==$adminid) {
            $this->row_delete($id);
        }
        return true;
    }
    public function get_id_by_admin_order_index( $adminid,$order_index ) {

        $sql=$this->gen_sql_new(
            "select id from  %s where adminid=%u and  order_index= %u ",
            self::DB_TABLE_NAME, $adminid, $order_index);
        return $this->main_get_value($sql);
    }

    public function get_next_order_index( $adminid, $order_index ,$next_flag  ) {
        if ($next_flag) {
            $sql=$this->gen_sql_new(
                "select min(order_index)  from  %s where adminid=%u and  order_index> %u ",
                self::DB_TABLE_NAME, $adminid, $order_index);
        }else{
            $sql=$this->gen_sql_new(
                "select max(order_index)  from  %s where adminid=%u and  order_index< %u ",
                self::DB_TABLE_NAME, $adminid, $order_index);
        }
        return $this->main_get_value($sql);
    }

    public function set_order_index( $id, $order_index  ) {
        $this->field_update_list($id,[
            "order_index" => $order_index,
        ]);
    }
    public function get_list( $adminid ) {
        $sql=$this->gen_sql_new("select * from %s where adminid=%u order by order_index asc",
                                self::DB_TABLE_NAME,
                                $adminid);
        return $this->main_get_list($sql);
    }
    public function get_url_info( $adminid, $url ) {
        $where_arr=[
            "adminid" => $adminid,
            "url" => $url,
        ];
        $sql= $this->gen_sql_new(
            "select  * from %s where %s  ",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_row($sql);
    }
    public function get_menu_config($adminid) {
        $list=$this->get_list($adminid);
        $data=[];
        foreach ($list as $item )  {
            $url = $item["url"];
            $url = $this->handle_url($url);

            $data[]= ["name"=>$item["title"], "url" => $url ];
        }
        $arr=[[
            "name"=>"我的收藏",
            "icon" => "fa-star",
            "list"=> $data,
        ]];
        return $arr;
    }

    // 处理地址
    public function handle_url($burl) {
        $url = $burl;
        // 测试 http://self.admin.leo1v1.com/user_deal/reload_account_power 控制器中可以直接把数据打印到此页面用于测试
        if(\App\Helper\Utils::check_env_is_testing())
            $curr_host = 'http://dev.admin.leo1v1.com';
        else
           $curr_host = $_SERVER['HTTP_HOST']; // 当前域名
        $http_curr_host = "http://".$curr_host;

        // 1. 处理以前数据库中的短地址 /controller/action
        $pattern = '/^http:\/\//'; // 匹配http://
        preg_match($pattern, $burl, $http);
        if (isset($http[0])) { // 完整路由
            $pattern = '/^http:\/\/p\.[\w+]/'; // 匹配冒烟环境
            preg_match($pattern, $http_curr_host, $domain);
            if (isset($domain[0])) { // 当前环境为冒烟环境
                $pattern = '/^http:\/\/p\.[\w+]/'; // 匹配冒烟环境
                preg_match($pattern, $burl, $pdomain);
                if (isset($pdomain[0])) {
                    return $burl;
                } else {
                    $url = str_replace("http://", "http://p.", $burl);
                }
            } else { // 生产环境
                $pattern = '/^http:\/\/p\.[\w+]/'; // 匹配冒烟环境
                preg_match($pattern, $burl, $pdomain);
                if (isset($pdomain[0])) {
                    $url = "http://".substr($burl, 9);
                } else {
                    return $burl;
                }

            }

            // $pattern = '/^http:\/\/[\w\.]+/'; // 匹配域名
            // preg_match($pattern, $burl, $domain);

            // if (isset($domain[0]) && $http_curr_host != $domain[0]) {
            //     // 2. 处理冒烟环境添加到收藏 正式环境显示 http://p.admin.leo1v1.com
            //     $pattern = '/^http:\/\/p\.[\w+]/'; // 匹配冒烟环境
            //     preg_match($pattern, $burl, $pdomain);
            //     if (isset($pdomain[0])) {
            //         $url = "http://".substr($burl, 9);
            //     } else {
            //         // 3. 处理正式环境添加到收藏 冒烟环境显示
            //         $url = str_replace("http://", "http://p.", $burl);
            //     }
            // }
        } else { // 短路由 
            $url = $http_curr_host.$burl;
        }
        
        return $url;
    }

}
