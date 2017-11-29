<?php
namespace App\Models;
use \App\Enums as E;
class t_upload_student_info extends \App\Models\Zgen\z_t_upload_student_info
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_list( $postid,$is_new_flag) {
        $where_arr=[];
        $this->where_arr_add_int_field($where_arr,"is_new_flag", $is_new_flag);

        $sql=$this->gen_sql_new(

            "select  u.* , n.add_time as publish_time  "
            . " from %s u "
            . " left join %s n  on n.phone=u.phone "
            . " where postid=%u  and %s" ,
            self::DB_TABLE_NAME,
            t_seller_student_new::DB_TABLE_NAME,
            $postid,
            $where_arr
        );
        $ret_info=$this->main_get_list_as_page($sql);
        $this->reset_phone_location($ret_info);
        return $ret_info;
    }

    public function reset_phone_location($ret_info) {
        foreach  ($ret_info["list"] as &$item) {
            if (!$item["phone_location"] ) {
                //设置到数据库
                $phone= $item["phone"];
                $item["phone_location"] = \App\Helper\Common::get_phone_location($phone);
                if ($item["phone_location"]) {
                    $this->field_update_list_2($item["postid"] ,$item["phone"],[
                     "phone_location" => $item["phone_location"]
                     ]);
                }
            }
        }
        return $ret_info;
    }


}
