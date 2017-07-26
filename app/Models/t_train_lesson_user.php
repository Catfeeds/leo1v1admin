<?php
namespace App\Models;
use \App\Enums as E;
class t_train_lesson_user extends \App\Models\Zgen\z_t_train_lesson_user
{
	public function __construct()
	{
		parent::__construct();
	}

    public function check_user_exists($lessonid,$userid){
        $where_arr = [
            ["lessonid=%u",$lessonid,0],
            ["userid=%u",$userid,0],
        ];
        $sql = $this->gen_sql_new("select count(1) from %s where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_train_lesson_user($lessonid,$type){
        $where_arr = [
            ["lessonid=%u",$lessonid,0],
            ["t.train_through_new=%u",$type,0],
        ];
        $sql = $this->gen_sql_new("select tl.userid,tl.score,t.subject,t.realname,t.phone,t.train_through_new"
                                  ." from %s tl"
                                  ." left join %s t on tl.userid=t.teacherid "
                                  ." where %s"
                                  ." order by t.subject asc"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_train_through_teacher_list($train_lessonid,$lessonid){
        $where_arr = [
            ["tl.lessonid=%u",$train_lessonid,0],
        ];
        $sql = $this->gen_sql_new("select t.teacherid"
                                  ." from %s tl"
                                  ." left join %s t on tl.userid=t.teacherid"
                                  ." where %s"
                                  ." and train_through_new=1"
                                  ." and not exists ("
                                  ." select 1 from %s where lessonid=%u and t.teacherid=userid"
                                  ." )"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
                                  ,t_train_lesson_user::DB_TABLE_NAME
                                  ,$lessonid
        );
        return $this->main_get_list($sql,function($item){
            return $item['teacherid'];
        });
    }

    public function get_userid_list($lessonid){
        $where_arr = [
            ["lessonid=%u",$lessonid,0],
            "trial_train_flag=0",
            "train_through_new=1",
            "trial_lecture_is_pass=1",
        ];
        $sql = $this->gen_sql_new("select teacherid,subject,teacher_money_type,level,grade_end,grade_part_ex"
                                  ." from %s tl"
                                  ." left join %s t on tl.userid=t.teacherid"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql,function($item){
            return $item['teacherid'];
        });
    }

    public function get_add_time_by_userid($userid){
        $where_arr = [
            ["userid=%u",$userid,0]
        ];
        $sql = $this->gen_sql_new("select add_time"
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_not_through_user($start_time,$end_time,$has_openid=-1){
        $where_arr = [
            ["add_time>%u",$start_time,0],
            ["add_time<%u",$end_time,0],
            "t.train_through_new=0",
            "t.trial_lecture_is_pass=1",
            "t.is_test_user=0"
        ];
        if($has_openid==0){
            $where_arr[]="wx_openid=''";
        }else{
            $where_arr[]="wx_openid!=''";
        }

        $sql = $this->gen_sql_new("select t.teacherid,t.nick,t.phone,t.wx_openid,max(tl.score) as score,t.create_time"
                                  ." from %s tl"
                                  ." left join %s t on tl.userid=t.teacherid"
                                  ." where %s"
                                  ." group by t.teacherid"
                                  ,self::DB_TABLE_NAME
                                  ,t_teacher_info::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_as_page($sql);
    }

}