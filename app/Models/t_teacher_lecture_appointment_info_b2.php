<?php
namespace App\Models;
use \App\Enums as E;
class t_teacher_lecture_appointment_info_b2 extends \App\Models\Zgen\z_t_teacher_lecture_appointment_info
{
    public function __construct()
    {
        parent::__construct();
    }

    // 拉取招师人员名单(根据老师名拉取对应的招师人员)
    public function get_name_for_tea_name($name)
    {
        $where_arr = [
            ["ta.name= '%s' ",$name, ''],
            "ta.subject>0"
        ];
        $sql=$this->gen_sql_new("select ta.accept_adminid,tf.subject,tf.grade "
                                ." from %s ta left join %s tf on ta.phone=tf.phone "
                                ." where %s"
                                ,self::DB_TABLE_NAME
                                ,t_teacher_flow::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_name_data() { // 招师名单
        $sql=$this->gen_sql_new("select uid,name from %s where account_role=8",t_manager_info::DB_TABLE_NAME);
        return $this->main_get_list($sql, function( $item) {
            return $item['uid'];
        });
    }

    //
    public function get_teacher_list(){
        // 拉取所有数据
        $sql = $this->gen_sql_new("select teacherid,realname,user_agent from %s where user_agent!='' ",t_teacher_info::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

    public function get_student_list() {
        $sql = $this->gen_sql_new("select userid,realname,user_agent from %s where user_agent!='' ",t_student_info::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }
}