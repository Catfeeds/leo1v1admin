<?php
namespace App\Models;
use \App\Enums as E;

/** 
 
 * @property t_lesson_info  $t_lesson_info
 * @property t_order_info  $t_order_info
* @property t_course_order  $t_course_order
* @property t_seller_student_info $t_seller_student_info 
 */


class t_gift_consign extends \App\Models\Zgen\z_t_gift_consign
{
	public function __construct()
	{
		parent::__construct();
	}

   
    public function get_commodity_consign_list($page_num,$gift_type,$status,$assistantid,$start_time,$end_time )
    {
        $where_arr=[
            ["tgi.gift_type=%u",$gift_type,-1 ],
            ["tgc.status=%u",$status,-1 ],
            ["tgc.exchange_time >%u",$start_time,-1 ],
            ["tgc.exchange_time <=%u",$end_time,-1 ],
            ["tsi.assistantid =%u",$assistantid,-1 ],
        ];

        $sql= $this->gen_sql_new("select tgc.exchangeid as exchangeid,".
                                 " tsi.nick as nick,".
                                 " tsi.phone as phone,".
                                 " tgc.exchange_time as exchange_time,".
                                 " tgi.gift_name as gift_name,".
                                 " tgi.gift_type as gift_type,".
                                 " tgc.account as account,".
                                 " tgc.address as address,".
                                 " tgc.status as status,".
                                 " tgc.consignee,".
                                 " tgc.consignee_phone as consignee_phone, ".
                                 " tgc.express_name as ecg_express_name".
                                 " from %s tgc,%s tsi,%s tgi ".
                                 " where tgc.giftid = tgi.giftid and tsi.userid = tgc.userid and %s ",
                                 self::DB_TABLE_NAME,
                                 t_student_info::DB_TABLE_NAME,
                                 t_gift_info::DB_TABLE_NAME,
                                 $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }

    public function get_consign_status_list($start_time,$end_time,$assistantid )
    {
        $where_arr=[
            ["c.exchange_time >%u",$start_time,-1 ],
            ["c.exchange_time <=%u",$end_time,-1 ],
            ["s.assistantid =%u",$assistantid,-1 ],
        ];
        $sql= $this->gen_sql_new("select sum(c.status=0) send,".
                                 " sum(c.status=1) sent".
                                 " from %s c,%s s ".
                                 " where c.userid=s.userid and %s ",
                                 self::DB_TABLE_NAME,
                                 t_student_info::DB_TABLE_NAME,
                                 $where_arr
        );
        #dd($sql); 
        return $this->main_get_row($sql);

    }

    
}










