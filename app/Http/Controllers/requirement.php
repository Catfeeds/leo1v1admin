<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use Illuminate\Support\Facades\Redis;


class requirement extends Controller
{
    use CacheNick;
    /**
     * @author    sam
     * @function  需求开发信息
     */
    public function requirement_info () {
        //$userid = 99;
        list($start_time,$end_time) = $this->get_in_date_range( 0,0,0,[],3);
        $name = $this->get_in_int_val('name',"-1");
        $priority = $this->get_in_int_val('priority',"-1");
        $significance = $this->get_in_int_val('significance',"-1");
        $status       = $this->get_in_int_val('status',"-1");
        $product_status = $this->get_in_int_val('product_status',"-1");
        $development_status = $this->get_in_int_val('development_status',"-1");
        $test_status = $this->get_in_int_val('test_status',"-1");
        $now_status = 1;
        $userid = $this->get_account_id();
     
        if($userid==349){
            $userid=-1;
        }
        $page_info=$this->get_in_page_info();
        $ret_info=$this->t_requirement_info->get_list_requirement($page_info,$userid,$name,$priority,$significance,$status,$product_status,$development_status, $test_status,$now_status,$start_time,$end_time);
        
        //        dd($ret_info);
        foreach( $ret_info["list"] as $key => &$item ) {
            $ret_info['list'][$key]['num'] = $key + 1;
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"expect_time");
            $item['name_str']        = E\Erequire_class::get_desc($item["name"]);
            $item['priority_str']    = E\Erequire_priority::get_desc($item["priority"]);
            $item['significance_str']= E\Erequire_significance::get_desc($item["significance"]);
            $item['status_str']      = E\Erequire_status::get_desc($item["status"]);
            $this->cache_set_item_account_nick($item,"create_adminid","create_admin_nick" );
            $item['flag'] = false;
            if(($item['status'] == 2 && $item['product_status'] == 0)){
                $item['flag'] = true;
            }
            if($item['status'] == 1){
                $item['operator_status'] = E\Erequire_product_status::get_desc($item['product_status']);
                $this->cache_set_item_account_nick($item,"create_adminid","operator_nick" );
            }elseif($item['status'] == 2){
                $item['operator_status'] = E\Erequire_product_status::get_desc($item['product_status']);
                $this->cache_set_item_account_nick($item,"product_operator","operator_nick" );
            }elseif($item['status'] == 3){
                $item['operator_status'] = E\Erequire_development_status::get_desc($item['development_status']);
                $this->cache_set_item_account_nick($item,"development_operator","operator_nick" );
            }elseif($item['status'] >= 4){
                $item['operator_status'] = E\Erequire_test_status::get_desc($item['test_status']);
                $this->cache_set_item_account_nick($item,"test_operator","operator_nick" );
            }

        }
        // dd($ret_info);
        return $this->pageView(__METHOD__, $ret_info);
    }
    /**
     * @author    sam
     * @function  需求页面－产品
     */

    public function requirement_info_product()
    {
        list($start_time,$end_time) = $this->get_in_date_range( 0,0,0,[],3);
        $name = $this->get_in_int_val('name',"-1");
        $priority = $this->get_in_int_val('priority',"-1");
        $significance = $this->get_in_int_val('significance',"-1");
        $status       = $this->get_in_int_val('status',"-1");
        $product_status = $this->get_in_int_val('product_status',"-1");
        $development_status = $this->get_in_int_val('development_status',"-1");
        $test_status = $this->get_in_int_val('test_status',"-1");
        $now_status = 2;
        $userid = $this->get_account_id();
        if($userid==349){
            $userid=-1;
        }
        $page_info=$this->get_in_page_info();
        $ret_info=$this->t_requirement_info->get_list_product($page_info,$userid,$name,$priority,$significance,$status,$product_status,$development_status, $test_status,$now_status,$start_time,$end_time);
        foreach( $ret_info["list"] as $key => &$item ) {
            $ret_info['list'][$key]['num'] = $key + 1;
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"expect_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"product_submit_time");
            $item['name_str']        = E\Erequire_class::get_desc($item["name"]);
            $item['priority_str']    = E\Erequire_priority::get_desc($item["priority"]);
            $item['significance_str']= E\Erequire_significance::get_desc($item["significance"]);
            $item['status_str']      = E\Erequire_status::get_desc($item["status"]);
            $this->cache_set_item_account_nick($item,"create_adminid","create_admin_nick" );
            $this->cache_set_item_account_nick($item,"product_operator","product_operator_nick" );
            $item['flag'] = false;
            if(($item['status']==3 && $item['development_status'] == 0)){ //当前用户是操作员或者未处理
                $item['flag'] = true;
            }
            if($item['status'] == 2){
                $item['operator_status'] = E\Erequire_product_status::get_desc($item['product_status']);
                $this->cache_set_item_account_nick($item,"product_operator","operator_nick" );
            }elseif($item['status'] == 3){
                $item['operator_status'] = E\Erequire_development_status::get_desc($item['development_status']);
                $this->cache_set_item_account_nick($item,"development_operator","operator_nick" );
            }elseif($item['status'] >= 4){
                $item['operator_status'] = E\Erequire_test_status::get_desc($item['test_status']);
                $this->cache_set_item_account_nick($item,"test_operator","operator_nick" );
            }

        }
        return $this->pageView(__METHOD__, $ret_info);

    }

    /**
     * @author    sam
     * @function  需求页面－开发
     */
    public function requirement_info_development()
    { list($start_time,$end_time) = $this->get_in_date_range( 0,0,0,[],3);
        $name = $this->get_in_int_val('name',"-1");
        $priority = $this->get_in_int_val('priority',"-1");
        $significance = $this->get_in_int_val('significance',"-1");
        $status       = $this->get_in_int_val('status',"-1");
        $development_status = $this->get_in_int_val('development_status',"-1");
        $test_status = $this->get_in_int_val('test_status',"-1");
        $now_status = 3;


        $userid = $this->get_account_id();
     
        if($userid==349){
            $userid=-1;
        }
        $page_info=$this->get_in_page_info();
        $ret_info=$this->t_requirement_info->get_list_development($page_info,$userid,$name,$priority,$significance,$status,$development_status, $test_status,$now_status,$start_time,$end_time);
        foreach( $ret_info["list"] as $key => &$item ) {
            $ret_info['list'][$key]['num'] = $key + 1;
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"expect_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"product_submit_time");
            $item['name_str']        = E\Erequire_class::get_desc($item["name"]);
            $item['priority_str']    = E\Erequire_priority::get_desc($item["priority"]);
            $item['significance_str']= E\Erequire_significance::get_desc($item["significance"]);
            $item['status_str']      = E\Erequire_status::get_desc($item["status"]);
            $this->cache_set_item_account_nick($item,"create_adminid","create_admin_nick" );
            $this->cache_set_item_account_nick($item,"product_operator","product_operator_nick" );
            $item['flag'] = false;
            if($item['product_operator'] == $userid||
               ($item['status']==2 && $item['product_status'] == 0)){ //当前用户是操作员或者未处理
                $item['flag'] = true;
            }
            if($item['status'] == 3){
                $item['operator_status'] = E\Erequire_development_status::get_desc($item['development_status']);
                $this->cache_set_item_account_nick($item,"development_operator","operator_nick" );
            }elseif($item['status'] >= 4){
                $item['operator_status'] = E\Erequire_test_status::get_desc($item['test_status']);
                $this->cache_set_item_account_nick($item,"test_operator","operator_nick" );
            }

        }
        //dd($ret_info);
        return $this->pageView(__METHOD__, $ret_info);
    }
    /**
     * @author    sam
     * @function  需求页面－测试
     */
    public function requirement_info_test()
    {
        list($start_time,$end_time) = $this->get_in_date_range( 0,0,0,[],3);
        $name = $this->get_in_int_val('name',"-1");
        $priority = $this->get_in_int_val('priority',"-1");
        $significance = $this->get_in_int_val('significance',"-1");
        $status       = $this->get_in_int_val('status',"-1");
        $test_status = $this->get_in_int_val('test_status',"-1");
        $now_status = 4;
        $userid = $this->get_account_id();
     
        if($userid==349){
            $userid=-1;
        }
        $page_info=$this->get_in_page_info();
        $ret_info=$this->t_requirement_info->get_list_test($page_info,$userid,$name,$priority,$significance,$status,$test_status,$now_status,$start_time,$end_time);
        foreach( $ret_info["list"] as $key => &$item ) {
            $ret_info['list'][$key]['num'] = $key + 1;
            \App\Helper\Utils::unixtime2date_for_item($item,"create_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"expect_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"product_submit_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"development_submit_time");
            $item['name_str']        = E\Erequire_class::get_desc($item["name"]);
            $item['priority_str']    = E\Erequire_priority::get_desc($item["priority"]);
            $item['significance_str']= E\Erequire_significance::get_desc($item["significance"]);
            $item['status_str']      = E\Erequire_status::get_desc($item["status"]);
            $this->cache_set_item_account_nick($item,"create_adminid","create_admin_nick" );
            $this->cache_set_item_account_nick($item,"product_operator","product_operator_nick" );
            $this->cache_set_item_account_nick($item,"development_operator","development_operator_nick" );

            $item['flag'] = false;
            if(($item['status'] == 2 && $item['product_status'] === 2)){
                $item['flag'] = true;
            }
            if($item['status'] >= 4){
                $item['operator_status'] = E\Erequire_test_status::get_desc($item['test_status']);
                $this->cache_set_item_account_nick($item,"test_operator","operator_nick" );
            }
        }
        //dd($ret-1
        return $this->pageView(__METHOD__, $ret_info);
    }
    /**
     * @author    sam
     * @function  产品需求删除
     */
    public function requirement_del(){
        $id                       = $this->get_in_int_val('id');
        $data = [
            'status'              => 1,
            'product_status'      => 5, //被删除
            "del_flag"            => 1,
        ];
        $ret = $this->t_requirement_info->field_update_list($id,$data);
        return $this->output_succ();
    }

    /**
     * @author    sam
     * @function  需求信息录入
     */
    public function add_requirement_info(){
        $name            = $this->get_in_int_val('name');
        $priority        = $this->get_in_int_val('priority');
        $significance    = $this->get_in_int_val('significance');
        $expect_time     = strtotime($this->get_in_str_val('expect_time'));
        $statement       = $this->get_in_str_val('statement');
        $content_pic     = $this->get_in_str_val('content_pic');
        $notes           = $this->get_in_str_val('notes');
        $create_adminid  = $this->get_account_id();
        $create_phone    = $this->t_manager_info->get_phone_by_uid($create_adminid);
        $create_time     = time();
        $this->t_requirement_info->row_insert([
            'create_time'              => $create_time,
            'create_adminid'           => $create_adminid,
            'name'                     => $name,
            'priority'                 => $priority ,
            'significance'             => $significance,
            'expect_time'              => $expect_time,
            'statement'                => $statement,
            'content_pic'              => $content_pic,
            'notes'                    => $notes,
            'create_phone'             => $create_phone,
            "status"                   => 2, //提交到达产品
            "product_status"           => 0, //产品未处理
         ]);
        return $this->output_succ();
    }
     /**
     * @author    sam
     * @function  需求信息录入
     */
    public function re_edit_requirement_info(){
        $id              = $this->get_in_int_val('id');
        $name            = $this->get_in_int_val('name');
        $priority        = $this->get_in_int_val('priority');
        $significance    = $this->get_in_int_val('significance');
        $expect_time     = strtotime($this->get_in_str_val('expect_time'));
        $statement       = $this->get_in_str_val('statement');
        $content_pic     = $this->get_in_str_val('content_pic');
        $notes           = $this->get_in_str_val('notes');
        $create_adminid  = $this->get_account_id();
        $create_phone    = $this->t_manager_info->get_phone_by_uid($create_adminid);
        $create_time     = time();
        $data = [
            'create_time'              => $create_time,
            'create_adminid'           => $create_adminid,
            'name'                     => $name,
            'priority'                 => $priority ,
            'significance'             => $significance,
            'expect_time'              => $expect_time,
            'statement'                => $statement,
            'content_pic'              => $content_pic,
            'notes'                    => $notes,
            'create_phone'             => $create_phone,
            "status"                   => 2, //提交到达产品
            "product_status"           => 0, //产品未处理
         ];
        $ret = $this->t_requirement_info->field_update_list($id,$data);
        return $this->output_succ();
    }

    /**
     * @author    sam
     * @function  产品需求处理
     */
    public function product_deal(){
        $id                       = $this->get_in_int_val('id');
        $product_add_time     = time();
        $product_operator         = $this->get_account_id();
        $data = [
            'product_add_time'     => $product_add_time,
            'product_operator'     => $product_operator,
            'product_status'       => 2 //接收处理
        ];
        $ret = $this->t_requirement_info->field_update_list($id,$data);
        return $this->output_succ();
    }
    /**
     * @author    sam
     * @function  产品需求驳回
     */
    public function product_reject(){
        $id                       = $this->get_in_int_val('id');
        $product_reject_time      = time();
        $product_operator         = $this->get_account_id();
        $product_reject           = $this->get_in_str_val('product_reject');
        $data = [
            'product_reject_time' => $product_reject_time,
            'product_reject'      => $product_reject,
            'product_operator'    => $product_operator,
            'product_status'      => 1, //被驳回
        ];
        $ret = $this->t_requirement_info->field_update_list($id,$data);
        return $this->output_succ();
    }
    /**
     * @author    sam
     * @function  产品需求删除
     */
    public function product_delete(){
        $id                       = $this->get_in_int_val('id');
        $product_operator         = $this->get_account_id();
        $data = [
            'product_operator'    => $product_operator,
            'product_status'      => 5, //被删除
            "del_flag"            => 1,
        ];
        $ret = $this->t_requirement_info->field_update_list($id,$data);
        return $this->output_succ();
    }
    /**
     * @author    sam
     * @function  产品需求－处理中
     */
    public function product_do(){
        $id                       = $this->get_in_int_val('id');
        $product_operator         = $this->get_account_id();
        $data = [
            'product_operator'    => $product_operator,
            'product_status'      => 3, //处理中
        ];
        $ret = $this->t_requirement_info->field_update_list($id,$data);
        return $this->output_succ();
    }
    /**
     * @author    sam
     * @function  产品需求－已完成，添加解决方案
     */
    public function product_add(){
        $id                       = $this->get_in_int_val('id');
        $product_operator         = $this->get_account_id();
        $product_solution         = $this->get_in_str_val('product_solution');
        $create_adminid           = $this->get_account_id();
        $product_phone            = $this->t_manager_info->get_phone_by_uid($create_adminid);
        $data = [
            'product_operator'    => $product_operator,
            'product_solution'    => $product_solution,
            'product_phone'       => $product_phone,
            'product_status'      => 4, //已完成
            'product_submit_time' => time(),
            'status'              => 3,
            'development_status'  => 0,//0 未处理
        ];
        $ret = $this->t_requirement_info->field_update_list($id,$data);
        return $this->output_succ();
    }
    /**
     * @author    sam
     * @function  产品需求－已完成，添加解决方案
     */
    public function product_re_edit(){
        $id                       = $this->get_in_int_val('id');
        $product_operator         = $this->get_account_id();
        $product_solution         = $this->get_in_str_val('product_solution');
        $create_adminid           = $this->get_account_id();
        $product_phone            = $this->t_manager_info->get_phone_by_uid($create_adminid);
        $data = [
            'product_operator'    => $product_operator,
            'product_solution'    => $product_solution,
            'product_phone'       => $product_phone,
            'product_status'      => 4, //已完成
            'product_submit_time' => time(),
            'status'              => 3,
            'development_status'  => 0,//0 未处理
        ];
        $ret = $this->t_requirement_info->field_update_list($id,$data);
        return $this->output_succ();
    }


    /**
     * @author    sam
     * @function  研发－需求处理
     */
    public function development_deal(){
        $id                       = $this->get_in_int_val('id');
        $development_add_time     = time();
        $development_operator         = $this->get_account_id();
        $data = [
            'development_add_time' => $development_add_time,
            'development_operator' => $development_operator,
            'development_status'   => 2, //待开发
            'status'               => 3,
            'test_status'          => 0,    
        ];
        $ret = $this->t_requirement_info->field_update_list($id,$data);
        return $this->output_succ();
    }
    /**
     * @author    sam
     * @function  产品需求驳回
     */
    public function development_reject(){
        $id                       = $this->get_in_int_val('id');
        $development_reject_time  = time();
        $development_operator     = $this->get_account_id();
        $development_reject       = $this->get_in_str_val('development_reject');
        $data = [
            'development_reject_time' => $development_reject_time,
            'development_operator'    => $development_operator,
            'development_reject'      => $development_reject,
            'development_status'      => 1, //被驳回
        ];
        $ret = $this->t_requirement_info->field_update_list($id,$data);
        return $this->output_succ();
    }

    /**
     * @author    sam
     * @function  研发－需求处理-开发开始
     */
    public function development_do(){
        $id                       = $this->get_in_int_val('id');
        $development_operator         = $this->get_account_id();
        $data = [
            'development_operator'     => $development_operator,
            'development_status'       => 3 //开发中
        ];
        $ret = $this->t_requirement_info->field_update_list($id,$data);
        return $this->output_succ();
    }
    /**
     * @author    sam
     * @function  研发－需求处理－开发完成
     */
    public function development_finish(){
        $id                       = $this->get_in_int_val('id');
        $development_submit_time  = time();
        $development_operator     = $this->get_account_id();
        $data = [
            'development_submit_time' => $development_submit_time,
            'development_operator'    => $development_operator,
            'development_status'      => 4, //开发完成
            'status'                  => 4, //测试
            'test_status'             => 0, //未处理
        ];
        $ret = $this->t_requirement_info->field_update_list($id,$data);
        return $this->output_succ();
    }
      /**
     * @author    sam
     * @function  测试－需求处理
     */
    public function test_deal(){
        $id                       = $this->get_in_int_val('id');
        $test_add_time     = time();
        $test_operator         = $this->get_account_id();
        $data = [
            'test_add_time' => $test_add_time,
            'test_operator' => $test_operator,
            'test_status'       => 2 //待测试
        ];
        $ret = $this->t_requirement_info->field_update_list($id,$data);
        return $this->output_succ();
    }
    /**
     * @author    sam
     * @function  产品需求驳回
     */
    public function test_reject(){
        $id                       = $this->get_in_int_val('id');
        $test_reject_time  = time();
        $test_operator     = $this->get_account_id();
        $data = [
            'test_reject_time' => $test_reject_time,
            'test_operator'    => $test_operator,
            'test_status'      => 1, //被驳回
        ];
        $ret = $this->t_requirement_info->field_update_list($id,$data);
        return $this->output_succ();
    }

    /**
     * @author    sam
     * @function  测试－需求处理-测试开始
     */
    public function test_do(){
        $id                       = $this->get_in_int_val('id');
        $test_operator            = $this->get_account_id();
        $data = [
            'test_operator'     => $test_operator,
            'test_status'       => 3 //测试中
        ];
        $ret = $this->t_requirement_info->field_update_list($id,$data);
        return $this->output_succ();
    }
    /**
     * @author    sam
     * @function  测试－需求处理－测试完成
     */
    public function test_finish(){
        $id                       = $this->get_in_int_val('id');
        $test_submit_time  = time();
        $test_operator     = $this->get_account_id();
        $data = [
            'test_submit_time' => $test_submit_time,
            'test_operator'    => $test_operator,
            'test_status'      => 4, //测试完成
            'status'           => 5, //开发结束
        ];
        $ret = $this->t_requirement_info->field_update_list($id,$data);
        return $this->output_succ();
    }



}