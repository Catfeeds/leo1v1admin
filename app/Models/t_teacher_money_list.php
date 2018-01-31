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

    public function check_reference_price($recommended_teacherid){
        $where_arr = [
            "type=6",
            ["recommended_teacherid=%u",$recommended_teacherid,0],
        ];
        $sql = $this->gen_sql_new("select 1"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_teacher_lesson_total_list($time){
        $where_arr = [
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

    public function get_teacher_chunhui_list($time){
        $where_arr = [
            ["add_time>%u",$time,0],
            "type=7"
        ];
        $sql=$this->gen_sql_new("select t.realname,t.nick,add_time,money,money_info,tm.grade"
                                ." from %s tm"
                                ." left join %s t on tm.teacherid=t.teacherid"
                                ." where %s"
                                ." order by add_time desc,tm.grade asc,money desc"
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

    /**
     * 获取所有额外薪资明细
     */
    public function get_teacher_honor_money_list($teacherid,$start,$end,$type=0){
        $where_arr = [
            ["tm.teacherid=%u",$teacherid,0],
            ["tm.add_time>=%u",$start,0],
            ["tm.add_time<%u",$end,0],
            ["tm.type=%u",$type,0],
        ];
        $sql = $this->gen_sql_new("select tm.add_time,tm.money,tm.type,tm.money_info,l.userid,tm.recommended_teacherid"
                                  ." from %s tm"
                                  ." left join %s l on tm.lessonid=l.lessonid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_teacher_trial_reward_list($page_num,$start_time,$end_time,$teacherid,$type,$lessonid,$has_lesson=-1){
		$has_sql = "true";
        if($lessonid==-1){
            $where_arr = [
                ["add_time>=%u",$start_time,0],
                ["add_time<%u",$end_time,0],
                ["type=%u",$type,-1],
                ["tm.teacherid=%u",$teacherid,-1],
            ];

            if($has_lesson==0){
                $has_arr = [
                    ["lesson_start>%u",$start_time,0],
                    ["lesson_start<%u",$end_time,0],
                    "tm.teacherid=teacherid",
                    "lesson_type <1000 ",
                ];
                $has_sql = $this->gen_sql_new("not exists (select 1 from %s where %s)",t_lesson_info::DB_TABLE_NAME,$has_arr);
            }
        }else{
            $where_arr = [
                ["money_info='%s'",$lessonid,0],
            ];
        }

        $sql = $this->gen_sql_new("select tm.id,tm.money,tm.money_info,tm.add_time,tm.type,tm.teacherid,l.userid,tm.acc,"
                                  ." t.bankcard,t.bank_address,t.bank_account,t.bank_phone,t.bank_type,t.bank_province,t.bank_city, "
                                  ." tr.realname,tr.identity"
                                  ." from %s tm "
                                  ." left join %s l on tm.money_info=l.lessonid "
                                  ." left join %s t on tm.teacherid=t.teacherid"
                                  ." left join %s tr on tm.recommended_teacherid=tr.teacherid"
                                  ." where %s "
								  ." and %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_lesson_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
                                  ,$has_sql
        );
        return $this->main_get_list_by_page($sql,$page,10);
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
     * 未上过课获得奖金的老师列表
     */
    public function get_reward_list($start,$end,$reward_type){
        $where_arr = [
            ["add_time>%u",$start,0],
            ["add_time<%u",$end,0],
            ["type=%u",$reward_type,-1],
            "is_test_user = 0"
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

    public function get_order_num_by_time($teacherid,$grade,$start_time,$end_time){
        $where_arr=[
            ["tm.teacherid = %u",$teacherid,-1],
            "tm.type=2",
            ["add_time>%u",$start_time,0],
            ["add_time<%u",$end_time,0],
        ];
        if($grade==100){
            $where_arr[]="l.grade>=100 and l.grade <200";
        }elseif($grade==200){
            $where_arr[]="l.grade>=200 and l.grade <300";
        }elseif($grade==300){
            $where_arr[]="l.grade>=300 and l.grade <400";
        }
        $sql = $this->gen_sql_new("select count(*) num"
                                  ." from %s tm "
                                  ." left join %s l on tm.lessonid = l.lessonid"
                                  ." where %s",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);

    }

    public function get_total_for_teacherid($teacherid, $type=1, $time=0) {
        $where_arr = [
            ['tl.teacherid=%u',$teacherid,-1],
            "tl.recommended_teacherid!=0"
        ];
        if ($type == 1) { // 推荐机构老师总数
            array_push($where_arr, "t.identity in (5,6)");
        } else { // 推荐在校学生总数
            array_push($where_arr, "t.identity not in (5,6)");
        }
        if ($time) {
            array_push($where_arr, ["tl.add_time<=%u", $time,0]);
        }
        $sql = $this->gen_sql_new("select count(*) from %s tl"
                                  ." left join %s t on tl.recommended_teacherid=t.teacherid where %s ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
        //select count(*) from t_teacher_money_list tl left join t_teacher_info t on tl.recommended_teacherid=t.teacherid where tl.teacherid=284393 and tl.recommended_teacherid !=0 and t.identity not in (5,6,7);
    }

    public function get_recommended_for_teacherid($start_time, $teacherid) {
        $where_arr = [
            ["tl.add_time>=%u",$start_time,0],
            ["tl.teacherid=%u",$teacherid,0],
            "tl.recommended_teacherid>0"
        ];

        $sql = $this->gen_sql_new("select tl.id,tl.recommended_teacherid,tl.add_time,tl.money,t.identity "
                                  ."from %s tl left join %s t on tl.recommended_teacherid=t.teacherid where %s ",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql);
        //select tl.recommended_teacherid,tl.add_time,tl.money,t.identity from t_teacher_money_list tl left join t_teacher_info t on tl.recommended_teacherid=t.teacherid  where tl.teacherid = 284393 and tl.recommended_teacherid >0 and tl.add_time > unix_timestamp('2017-11-1')
    }

    public function get_teacher_warn_info($start_time, $end_time) {
        //select teacherid from t_teacher_money_list t left join t_order_info o on t.lessonid=o.from_lessonid where price > 4000000
        $sql = $this->gen_sql_new("select t.teacherid,t.lessonid from %s t "
                                  ."left join %s o on t.lessonid=o.from_test_lesson_id "
                                  ."where o.price > 4000000 ",
                                  self::DB_TABLE_NAME,
                                  t_order_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_money_list($start_time, $end_time, $teacherid) {
        //select recommended_teacherid,t.nick  from t_teacher_money_list l left join t_teacher_info t on t.teacherid=l.recommended_teacherid  where l.teacherid=149697 and l.type=6
        $where_arr = [
            "l.type=6",
            ["t.train_through_new_time>=%u", $start_time, 0],
            ["t.train_through_new_time<%u", $end_time, 0],
            ['l.teacherid=%u', $teacherid, 0]
        ];
        $sql = $this->gen_sql_new("select recommended_teacherid,t.nick from %s l left join %s t on t.teacherid=l.recommended_teacherid where %s",
                                  self::DB_TABLE_NAME,
                                  t_teacher_info::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_list($sql, function($item) {
            return $item['recommended_teacherid'];
        });
    }

    public function get_haruteru_award($start_time, $end_time) {
        $where_arr = [
            ['add_time>=%u', $start_time, 0],
            ['add_time<%u', $end_time, 0],
            "type=7"
        ];
        $sql = $this->gen_sql_new("select id from %s where %s",
                                  self::DB_TABLE_NAME,
                                  $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_reward_total($start,$end,$type,$teacherid,$is_full){
        $where_arr = [
            ["add_time>=%u",$start,0],
            ["add_time<%u",$end,0],
            ["tm.teacherid=%u",$teacherid,0],
            ["type=%u",$type,0],
            "t.is_test_user=0"
        ];
        if($is_full==1){
            $full_sql = "((teacher_type=3 and teacher_money_type=0) or teacher_money_type=7)";
        }elseif($is_full==0){
            $full_sql = "((teacher_type!=3 or teacher_money_type!=0) and teacher_money_type!=7)";
        }else{
            $full_sql = "true";
        }
        $sql = $this->gen_sql_new("select sum(tm.money) as money"
                                  ." from %s tm "
                                  ." left join %s t on tm.teacherid=t.teacherid"
                                  ." where %s"
                                  ." and %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
                                  ,$full_sql
        );
        return $this->main_get_value($sql);
    }

    public function get_reward_info_by_id($id){
        $where_arr = [
            ["id=%u",$id,0]
        ];
        $sql = $this->gen_sql_new("select teacherid,type,money_info,money,add_time,acc"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }

}
