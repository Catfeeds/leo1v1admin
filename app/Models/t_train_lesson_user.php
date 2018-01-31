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

    /**
     * 获取培训未通过的老师名单
     */
    public function get_not_through_user($start_time,$end_time,$has_openid=-1,$type=-1){
        $where_arr = [
            ["add_time>%u",$start_time,0],
            ["add_time<%u",$end_time,0],
            "t.train_through_new_time=0",
            "t.trial_lecture_is_pass=1",
            "t.is_test_user=0"
        ];
        if($has_openid==0){
            $where_arr[]="wx_openid=''";
        }elseif($has_openid>0){
            $where_arr[]="wx_openid!=''";
        }

        $sql = $this->gen_sql_new("select t.teacherid,t.nick,t.train_through_new_time,t.phone,t.wx_openid,"
                                  ." max(tl.score) as score,t.create_time"
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

    public function get_is_through_user($start_time,$end_time,$has_openid=-1,$subject,$grade,$is_pass){
        $where_arr = [
            ["add_time>%u",$start_time,0],
            ["add_time<%u",$end_time,0],
            "t.train_through_new=0",
            //"t.trial_lecture_is_pass=1",
            "t.is_test_user=0"
        ];
        if($has_openid==0){
            $where_arr[]="wx_openid=''";
        }elseif($has_openid>0){
            $where_arr[]="wx_openid!=''";
        }
        if ($is_pass != -1) {
            $where_arr[]="t.trial_lecture_is_pass=".$is_pass;
        }
        if ($subject != -1) {
            $where_arr[]="t.subject=".$subject;
        }
        if ($grade != -1) {
            $where_arr[]="t.grade=".$grade;
        }

        $sql = $this->gen_sql_new("select t.teacherid,t.nick,t.train_through_new_time,t.phone,t.wx_openid,max(tl.score) as score,t.create_time"
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

    public function get_max_lesson_time($userid){
        $sql = $this->gen_sql_new("select max(l.lesson_start) from %s ta "
                                  ." left join %s l on (ta.lessonid = l.lessonid and l.train_type=1 and l.lesson_status>0)"
                                  ." where ta.userid = %u",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME,
                                  $userid
        );
        return $this->main_get_value($sql);
    }

    public function get_userid_list_new($lessonid){
        $sql = $this->gen_sql_new("select userid from %s where lessonid = %u",self::DB_TABLE_NAME,$lessonid);
        return $this->main_get_list($sql);
    }

    public function get_count($start_time, $end_time) {
        $where_arr = [
            ["tl.add_time>%u",$start_time,0],
            ["tl.add_time<%u",$end_time,0],
            "tl.score>90",
            "tl.train_type=2"
        ];
        $sql = $this->gen_sql_new("select count(*) from %s tl left join %s l "
                                  ." ON(tl.lessonid = l.lessonid) where %s group by l.subject",
                                 self::DB_TABLE_NAME,
                                 t_lesson_info::DB_TABLE_NAME,
                                 $where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_all_info_no_train_type(){
        $sql = $this->gen_sql_new("select ta.*,l.train_type lesson_train_type,l.lesson_del_flag"
                                  ." from %s ta left join %s l on ta.lessonid = l.lessonid"
                                  ." where l.train_type>0 and ta.train_type=0",
                                  self::DB_TABLE_NAME,
                                  t_lesson_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }


}