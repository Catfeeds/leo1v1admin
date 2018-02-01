<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;

use App\Helper\Utils;

/*
3. 修改字段的注释，代码如下：

ALTER TABLE `student` MODIFY COLUMN `id` COMMENT '学号';

查看字段的信息，代码如下：

SHOW FULL COLUMNS  FROM `student`;
*/

class tq extends Controller
{
    use CacheNick;
    public function get_list_by_phone () {
        $phone           = $this->get_in_phone();
        if (!$phone  ) {
            return $this->error_view(["没有电话"]);
        }else{
            return $this->get_list();
        }

    }

    public function index(){

    }

    public function get_list () {
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],1);
        $phone           = $this->get_in_phone();
        $is_called_phone = $this->get_in_int_val("is_called_phone",-1, E\Eboolean::class );
        $uid             = $this->get_in_int_val("uid",-1);
        $user_info         = trim($this->get_in_str_val('user_info',''));
        if($uid>0){
            $uid = $this->t_manager_info->field_get_value($uid,'tquin');
        }
        $page_num        = $this->get_in_page_num();
        $seller_student_status  = $this->get_in_el_seller_student_status();
        $userid=$this->get_in_userid(-1);


        $clink_args="?enterpriseId=3005131&userName=admin&pwd=".md5(md5("leoAa123456" )."seed1")  . "&seed=seed1"  ;

        $ret_info=$this->t_tq_call_info->get_call_phone_list($page_num,$start_time,$end_time,$uid,$is_called_phone,$phone, $seller_student_status,$user_info,$userid );
        $now=time(NULL);
        foreach($ret_info["list"] as &$item) {
            $record_url= $item["record_url"] ;
            if ($now-$item["start_time"] >1*86400 && (preg_match("/saas.yxjcloud.com/", $record_url  )|| preg_match("/121.196.236.95/", $record_url  ) ) ){
                $item["load_wav_self_flag"]=1;
            }else{
                $item["load_wav_self_flag"]=0;
            }
            if (preg_match("/api.clink.cn/", $record_url ) ) {
                $item["record_url"].=$clink_args;
            }

            \App\Helper\Utils::unixtime2date_for_item($item,"start_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"end_time");
            \App\Helper\Utils::unixtime2date_for_item($item,"obj_start_time");
            E\Eboolean::set_item_value_str($item,"is_called_phone");
            E\Eaccount_role::set_item_value_str($item,"admin_role");
            E\Eseller_student_status::set_item_value_str($item);
            $this->cache_set_item_account_nick($item);
            $item['end_reason_str'] = $item['end_reason']>0?($item['end_reason']>1?'客户':'销售'):'';
            $item["duration"]= \App\Helper\Common::get_time_format($item["duration"]);
        }
        return $this->pageView(__METHOD__,$ret_info);
    }

    public function ass_self_tongji_list() {
        $this->set_in_value("callerid", $this->get_account_id() );
        return $this->tongji_list();
    }

    public function ass_tongji_list() {
        $this->set_in_value("account_role", E\Eaccount_role::V_1 );
        return $this->tongji_list();
    }

    public function tongji_list() {
        list($start_time,$end_time)=$this->get_in_date_range(0,0,0,[],1);
        $this->t_tq_call_info->switch_tongji_database();
        $callerid   = $this->get_in_int_val("callerid", -1);

        $account_role=$this->get_in_e_account_role(-1);
        $list=$this->t_tq_call_info->tongi_list($start_time,$end_time, $account_role, $callerid);

        foreach ($list as &$item )  {
            $item["duration_count"]= \App\Helper\Common::get_time_format(@$item["duration_count"]);
        }
        return $this->pageView(__METHOD__, \App\Helper\Utils::list_to_page_info($list) );
    }

}