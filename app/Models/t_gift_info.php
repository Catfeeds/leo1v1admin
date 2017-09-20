<?php
namespace App\Models;
use \App\Enums as E;
class t_gift_info extends \App\Models\Zgen\z_t_gift_info
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_gift_info($page_num){
        $sql = $this->gen_sql_new(
            "select giftid,gift_type, gift_name, gift_intro, current_praise, gift_pic, "
            ."gift_desc, cost_price, shop_link "
            ."from %s where del_flag = 0",
            self::DB_TABLE_NAME
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_gift_id_praise(){
        $sql = $this->gen_sql_new(
            "select giftid, current_praise "
            ."from %s where del_flag = 0",
            self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }


    public function update_all_price( $giftid, $price ){
        $sql = $this->gen_sql_new(
            "update %s set cost_price='$price' where giftid=$giftid"
            ,self::DB_TABLE_NAME
        );
        return $this->main_get_value($sql);
    }
}
