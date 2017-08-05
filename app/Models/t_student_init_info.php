<?php
namespace App\Models;
use \App\Enums as E;
class t_student_init_info extends \App\Models\Zgen\z_t_student_init_info
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_user_init_info_for_api($userid){
        $sql = $this->gen_sql_new("select i.real_name realname,s.nick stu_nick,i.birth,i.school,i.gender,i.xingetedian,s.reg_time,i.grade,i.phone,i.addr address,s.user_agent,s.lesson_count_left,s.lesson_count_all,s.praise,t.stu_test_paper,t.tea_download_paper_time,i.aihao ,i.yeyuanpai,n.stu_character_info,s.realname s_realname,s.birth s_birth,s.school s_school,s.gender s_gender,s.grade s_grade,s.phone s_phone,s.address s_address ".
                                  " from %s i left join %s s on s.userid = i.userid".
                                  " left join %s t on i.userid = t.userid ".
                                  " left join %s n on i.userid = n.userid".
                                  " where i.userid = %u",
                                  self::DB_TABLE_NAME,
                                  t_student_info::DB_TABLE_NAME,
                                  t_test_lesson_subject::DB_TABLE_NAME,
                                  t_seller_student_new::DB_TABLE_NAME,
                                  $userid
        );
        return $this->main_get_row($sql);
    }

    public function get_subject_info_list($userid){
        $sql=$this->gen_sql_new("select subject_yingyu,subject_yuwen,subject_shuxue,subject_huaxue,subject_wuli,class_top,grade_top,subject_info,order_info,n.user_desc from %s i left join %s n on i.userid =n.userid where i.userid = %u",
                                self::DB_TABLE_NAME,
                                t_seller_student_new::DB_TABLE_NAME,
                                $userid
        );
        return  $this->main_get_row($sql);
    }

    public function get_parent_except_info_list($userid){
        $sql=$this->gen_sql_new("select teacher,teacher_info,test_lesson_info,mail_addr,has_fapiao,except_lesson_count,lesson_plan,parent_other_require,week_lesson_num from %s  where userid = %u",
                                self::DB_TABLE_NAME,
                                $userid
        );
        return  $this->main_get_row($sql);

    }


    public function get_userid_by_parentid($parentid){
        $sql = $this->gen_sql_new(" select userid from %s s where parentid = %d",
                                  self::DB_TABLE_NAME,
                                  $parentid
        );

        return $this->main_get_value($sql);
    }
}
