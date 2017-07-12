<?php
namespace App\Models;
use \App\Enums as E;
class t_test_lesson_assign_teacher extends \App\Models\Zgen\z_t_test_lesson_assign_teacher
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_list_by_seller_student_id($page_num,$seller_student_id) {
       $sql=$this->gen_sql("select id, seller_student_id, teacherid, assign_time,teacher_confirm_flag,teacher_confirm_time, degree, assign_adminid, openid, openid is not null as has_openid from  %s  t  left join  %s w on t.teacherid=w.userid    where  seller_student_id=%u and (w.role= %u  or w.role is null ) ",
                           self::DB_TABLE_NAME,
                           t_wx_openid_bind::DB_TABLE_NAME ,
                           $seller_student_id ,E\Erole::V_TEACHER );
        return $this->main_get_list_by_page($sql,$page_num);

    }
    public function check_existed($seller_student_id,$teacherid) {
        $sql=$this->gen_sql("select  1 from  %s  where   seller_student_id=%u and teacherid=%u ",
                            self::DB_TABLE_NAME, $seller_student_id,$teacherid
        );
        return $this->main_get_value($sql)==1;

    }

    public function get_list_by_teacherid($page_num,$teacherid,$opt_type) {
        $where_arr=[
            ["teacherid=%u" ,$teacherid,-1  ],
        ];
        $start_time=time()-5*3600;
        if ($opt_type==0) {
            $where_arr[]="(assigned_teacherid=0  and  assign_time >$start_time)";
        }else if ($opt_type==1) {
            $where_arr[]=["assigned_teacherid=%u" ,$teacherid];
        }else{
            $where_arr[]=["(assigned_teacherid <>%u and  assign_time <$start_time )" ,$teacherid];
        }
        $sql=$this->gen_sql("select  seller_student_id, grade,subject,st_class_time, assigned_teacherid, teacher_confirm_flag, teacher_confirm_time from  %s tt , %s ts  where  tt.seller_student_id=ts.id and  %s  ",
                            self::DB_TABLE_NAME,
                            t_seller_student_info::DB_TABLE_NAME,
                            [$this->where_str_gen($where_arr)]
        );
        return $this->main_get_list_by_page($sql,$page_num);
    }
}
