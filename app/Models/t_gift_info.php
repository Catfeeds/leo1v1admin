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
            ."gift_desc from %s where del_flag = 0",
            self::DB_TABLE_NAME
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }
}
