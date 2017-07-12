<?php
namespace App\Models;
use App\Models\Zgen as Z;
class t_small_class_user extends \App\Models\Zgen\z_t_small_class_user
{
    function get_small_class_list_by_userid ($userid) {
        $sql=$this->gen_sql("select from_unixtime(t1.join_time) as join_time, t1.courseid,t2.course_name,t2.teacherid ,t2.assistantid from  %s  t1, %s t2 ".
                            " where t1.courseid=t2.courseid  and t1.userid=%u  and t1.del_flag=0 ",
                            self::DB_TABLE_NAME,t_course_order::DB_TABLE_NAME,
                            $userid);
        return $this->main_get_list($sql);
    }


	public function __construct()
	{
		parent::__construct();
	}

    public function get_small_class_user_list( $courseid,$user_name='',$page_id=0 )
    {
        $where_str = "true ";
        if ($user_name!='') {
            $where_str = sprintf("(t1.userid like '%%%s%%'  or nick like '%%%s%%')  ",
                                 $this->ensql($user_name),
                                 $this->ensql($user_name)
            );
        }
        $sql = sprintf("select t1.userid ,from_unixtime(join_time) as join_time ,nick as student_nick, user_agent from %s t1 , %s t2 where  t1.userid=t2.userid and  courseid=%u and del_flag=0  and %s ",
                       self::DB_TABLE_NAME,
                       Z\z_t_student_info::DB_TABLE_NAME,
                       $courseid,
                       $where_str
        );

        if($page_id==0){
            return $this->main_get_list($sql);
        }else{
            return $this->main_get_list_by_page($sql,$page_id,10);
        }
    }

    public function check_user($courseid,$userid){
        $where_arr=[
            ["courseid=%u",$courseid,0],
            ["userid=%u",$userid,0],
        ];
        $sql=$this->gen_sql_new("select 1 from %s "
                                ." where %s"
                                ,self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_small_class_user_login_time($lessonid, $userid)
    {
        $sql =sprintf("select count(*) from %s where lessonid= %u and userid = %u and server_type=2 and opt_type=1",
                      Z\z_t_lesson_opt_log::DB_TABLE_NAME,
                      //$this->t_lesson_opt_log,
                      $lessonid,
                      $userid
        );
   
        return $this->main_get_value($sql);
    }

    public function get_small_class_user_count( $courseid )
    {
        $sql=sprintf("select count(*) from %s where courseid=%u and del_flag=0",
                     self::DB_TABLE_NAME,
                     $courseid
        );
        return $this->main_get_value($sql);
        
    }
    //================================================================================================ 
    public function add_pic_info($opt_type,$id,$name,$type,$time_type,$url){
        if($opt_type == 'add'){
            $this->row_insert([
                self::C_name      => $name,
                self::C_type      => $type,
                self::C_time_type => $time_type,
                self::C_url       => $url,
            ]);
        }else{
            $set_field_arr=array(
                self::C_name      => $name,
                self::C_type      => $type,
                self::C_time_type => $time_type,
                self::C_url       => $url,
            );
            $this->field_update_list($id,$set_field_arr);
        }
    }
 
}











