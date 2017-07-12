<?php
namespace App\Models;
class t_mypraise extends \App\Models\Zgen\z_t_mypraise
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_praise_list_by_page($praise_type,$start,$end)
    {
        $where_arr = [
            ["ts > %d ", $start,-1],
            ["ts < %d ", $end,-1],
            ["t.type = %d ", $praise_type,-1],
        ];

        $sql = $this->gen_sql("select t.type,count(t.type) as type_num,sum(praise_num) as praise_num from %s t, %s s where t.userid=s.userid and s.is_test_user=0 and  %s  group by t.type desc   "
                              ,self::DB_TABLE_NAME
                              ,t_student_info::DB_TABLE_NAME
                              ,[$this->where_str_gen($where_arr)]
        );
        return $this->main_get_list($sql);
    }

    public function get_praise_list($type,$page_num)
    {
        $sql = $this->gen_sql("select userid, ts, reason, praise_num, lessonid from %s where type = %u "
                              ,self::DB_TABLE_NAME
                              ,$type
        );

        return $this->main_get_list($sql);
    }

    public function get_all_info($page_num,$start_time,$end_time,$type,$userid,$lessonid)
    {
        $where_arr = [
            ["ts>%u", $start_time,-1 ] ,
            ["ts<%u", $end_time,-1 ] ,
            ["t.type=%u", $type,-1 ] ,
            ["t.userid=%u", $userid,-1 ] ,
            ["t.lessonid=%u", $lessonid,-1 ] ,
        ];

        $sql =$this->gen_sql("select add_userid, t.userid, ts, reason, praise_num, lessonid, t.type   from %s t, %s s where t.userid=s.userid and s.is_test_user=0 and  %s   order by  ts  desc ",
                             self::DB_TABLE_NAME,
                             t_student_info::DB_TABLE_NAME,
                             [$this->where_str_gen($where_arr)]
        );
		return $this->main_get_list_by_page($sql,$page_num,10);
    }

    /**
     * @param userid 用户id
     * @param type 小于2000为加赞 大于2000为减赞
     * @param praise_num 变动赞的数目
     */
    public function add_praise($userid,$type,$praise_num){
        $praise   = $this->t_student_info->get_praise($userid);
        $type_num = substr($type,0,1);
        if($type_num>1){
            if($praise_num>$praise){
                $praise_num = $praise;
                $praise     = 0;
            }else{
                $praise -= $praise_num;
            }
        }else{
            $praise += $praise_num;
        }

        if($praise_num===0){
            return true;
        }

        $this->start_transaction();
        $ret = $this->row_insert([
            "userid"     => $userid,
            "type"       => $type,
            "ts"         => time(),
            "praise_num" => $praise_num
        ]);

        if($ret===false){
            $this->rollback();
            return false;
        }

        $ret_stu  = $this->t_student_info->field_update_list($userid,["praise"=>$praise]);
        if ($ret_stu === false) {
            $this->rollback();
            return false;
        }
        \App\Helper\Utils::logger("add praise,userid:".$userid." type:".$type." praise_num:".$praise_num." time:".time());

        $this->commit();
        return true;
    }

 
    public function add_mypraise($userid,$praise_num,$reason,$add_userid,$type=1099){
        $this->start_transaction();
        $ret_info=$this->row_insert([
            self::C_userid     => $userid,
            self::C_praise_num => $praise_num,
            self::C_reason     => $reason,
            self::C_add_userid => $add_userid,
            self::C_type       => $type,
            self::C_ts         => time(),
        ]);
        if ($ret_info === false) {
            $this->rollback();
            return false;
        }

        $praise=$this->t_student_info->get_praise($userid);
        if($type<2000){
            $praise+=$praise_num;
        }else{
            $praise-=$praise_num;
        }

        $ret_stu=$this->t_student_info->field_update_list($userid,["praise"=>$praise]);
        if ($ret_stu === false) {
            $this->rollback();
            return false;
        }

        $this->commit();
        return true;
    }
}











