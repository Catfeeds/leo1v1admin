<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_money_list extends \App\Models\Zgen\z_t_teacher_money_list
{
	public function __construct()
	{
		parent::__construct();
	}


    public function check_is_exists($money_info,$type=-1){
        $where_arr = [
            ["money_info='%s'",$money_info,""],
            ["type=%u",$type,-1],
        ];
        $sql = $this->gen_sql_new("select 1 from %s where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_teacher_lesson_total_list($time){
        $where_arr=[
            ["add_time>%u",$time,0],
            "type=1"
        ];

        $sql=$this->gen_sql_new("select t.realname,t.nick,add_time,money,money_info as lesson_total"
                                ." from %s l"
                                ." left join %s t on l.teacherid=t.teacherid"
                                ." where %s"
                                ." order by add_time desc,money desc"
                                ,self::DB_TABLE_NAME
                                ,t_teacher_info::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    /**
     * 获取老师的额外奖励薪资总和
     * @param teacherid 老师id
     * @param start     统计奖励的开始时间
     * @param end       统计奖励的开始时间
     * @param type      奖励类型
     * @return int/array
     */
    public function get_teacher_honor_money($teacherid,$start,$end,$type){
        $where_arr = [
            ["add_time>=%u",$start,0],
            ["add_time<%u",$end,0],
            ["teacherid=%u",$teacherid,0],
            ["type=%u",$type,0],
        ];
        $sql=$this->gen_sql_new("select sum(money) as money"
                                ." from %s"
                                ." where %s"
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    //获取额外薪资明细
    public function get_teacher_honor_money_list($teacherid,$start,$end,$type=0){
        $where_arr = [
            ["teacherid=%u",$teacherid,0],
            ["add_time>%u",$start,0],
            ["add_time<%u",$end,0],
            ["type=%u",$type,0],
        ];
        $sql = $this->gen_sql_new("select add_time,money,type,money_info"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    //签单奖
    public function get_teacher_trial_reward_list($start_time,$end_time,$teacherid,$type,$lessonid){
        if($lessonid==-1){
            $where_arr = [
                ["add_time>%u",$start_time,0],
                ["add_time<%u",$end_time,0],
                ["type=%u",$type,-1],
                ["tm.teacherid=%u",$teacherid,-1],
            ];
        }else{
            $where_arr = [
                ["money_info='%s'",$lessonid,0],
            ];
        }

        $sql = $this->gen_sql_new("select id,money,money_info,add_time,type,tm.teacherid,l.userid,tm.acc "
                                  ." from %s tm "
                                  ." left join %s l on tm.money_info=l.lessonid "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function add_reference_reward($reference_info,$teacherid){
        $where_arr = [
            ["reference=%u",$reference_info['phone'],""]
        ];
        $sql = $this->gen_sql_new("select count(1) "
                                  ." from %s "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        $reference_num = $this->main_get_value($sql);
    }

    public function add_teacher_rewrad_money($type,$teacherid,$money,$money_info){
        $ret = $this->t_teacher_money_list->row_insert([
            "teacherid"  => $teacherid,
            "type"       => $type,
            "add_time"   => time(),
            "money"      => $money,
            "money_info" => $money_info,
            "acc"        => $this->get_acount(),
        ]);
        return $ret;
    }

    public function get_teacher_reward_list_for_wages($start_time,$end_time,$teacher_ref_type,$teacher_money_type,$level){
        $where_arr = [
            ["t.teacher_ref_type=%u",$teacher_ref_type,-1],
            ["t.teacher_money_type=%u",$teacher_money_type,-1],
            ["t.level=%u",$level,-1],
        ];
        $add_str = [
            ["add_time>%u",$start_time,0],
            ["add_time<%u",$end_time,0],
        ];
        $lesson_str = [
            ["lesson_start>%u",$start_time,0],
            ["lesson_start<%u",$end_time,0],
            "lesson_type in (0,1,3)",
            "lesson_del_flag=0",
        ];
        $sql = $this->gen_sql_new("select t.teacherid,if(t.realname='',t.nick,t.realname) as tea_nick,t.subject,t.create_time,"
                                  ." t.teacher_money_type,t.level,t.teacher_money_flag,t.teacher_ref_type,t.test_transfor_per,"
                                  ." t.bankcard,t.bank_address,t.bank_account,t.bank_phone,t.bank_type,t.teacher_money_flag,"
                                  ." t.idcard,t.bank_city,t.bank_province,t.phone"
                                  ." from %s t"
                                  ." where %s"
                                  ." and exists (select 1 from %s where t.teacherid=teacherid and %s)"
                                  ." and not exists (select 1 from %s where t.teacherid=teacherid and %s)"
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
                                  ,self::DB_TABLE_NAME
                                  ,$add_str
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,$lesson_str

        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_reward_list($start,$end,$type){
        $where_arr = [
            ["tm.add_time>%u",$start,0],
            ["tm.add_time<%u",$end,0],
            ["tm.type=%u",$type,0],
            "t.is_test_user=0",
            "t2.is_test_user=0"
        ];
        $sql = $this->gen_sql_new("select tm.teacherid,t2.teacherid as o_teacherid,t2.identity"
                                  ." from %s tm "
                                  ." left join %s t on tm.teacherid=t.teacherid "
                                  ." left join %s t2 on tm.money_info=t2.teacherid "
                                  ." where %s "
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    /**
     * 未上过课获得伯乐奖老师列表
     */
    public function get_reward_list($start,$end,$reward_type){
        $where_arr = [
            ["add_time>%u",$start,0],
            ["add_time<%u",$end,0],
            ["type=%u",$reward_type,-1],
        ];
        $lesson_arr = [
            ["lesson_start>%u",$start,0],
            ["lesson_start<%u",$end,0],
            "lesson_type < 1000",
            "lesson_status=2",
            "lesson_del_flag=0",
            "confirm_flag!=2",
            "tm.teacherid=teacherid"
        ];
        \App\Helper\Utils::effective_lesson_sql($lesson_arr);
        $sql = $this->gen_sql_new("select sum(money) as money_total,t.teacherid,t.bankcard,t.bank_account,t.bank_type,t.phone,"
                                  ." t.realname"
                                  ." from %s tm"
                                  ." left join %s t on tm.teacherid=t.teacherid"
                                  ." where %s"
                                  ." and not exists (select 1 from %s where %s)"
                                  ." group by t.teacherid"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,$lesson_arr
        );
        return $this->main_get_list($sql);
    }


}