<?php
namespace App\Models;
/**
 * @property t_test_lesson_assign_teacher  $t_test_lesson_assign_teacher
 *
 */
class t_teacher_closest extends \App\Models\Zgen\z_t_teacher_closest
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_teacher_closest( $grade,$subject,$teacherid,$page_num)
    {
        $where_arr=[
            ["grade=%u", $grade,-1],
            ["subject=%u", $subject,-1],
            ["teacherid=%u", $teacherid,-1],
        ];
        $sql=$this->gen_sql("select * from %s where %s ",
                            self::DB_TABLE_NAME,
                            [$this->where_str_gen($where_arr)]
        );
        return $this->main_get_list_by_page($sql,$page_num,10);
    }

    public function add_teacher_info($grade,$subject,$degree,$teacher,$introduction)
    {
        return $this->row_insert([
            "grade"        => $grade,
            "subject"      => $subject,
            "degree"       => $degree,
            "teacherid"    => $teacher,
            "introduction" => $introduction,
        ]);
 
    }

    public function update_teacher_list($teacherid,$grade,$subject,$degree,$introduction)
    {
        /*
        $set_field_arr=array(
            self::C_grade        => $grade,
            self::C_subject      => $subject,
            self::C_degree       => $degree,
            self::C_introduction => $introduction,
        );
        $this->field_update_list($teacherid,$set_field_arr);
        */   
         $sql = $this->gen_sql("update %s set degree = %u, introduction='%s' where teacherid = %u and  grade = %u and subject = %u ",
                               self::DB_TABLE_NAME,
                               $degree,
                               $introduction,
                               $teacherid,
                               $grade, $subject);
        return $this->main_update($sql);

    }

    public function delete_tea_info($teacherid,$subject,$grade)
    {
        $sql = $this->gen_sql("delete from %s where teacherid = %u and  subject=%u and grade=%u ",
                              self::DB_TABLE_NAME,
                              $teacherid,
                              $subject,$grade
        );
        return $this->main_update($sql);
    }

    public function get_list_by_grade_subject($grade,$subject){
        $sql=$this->gen_sql("select  teacherid,degree  from  %s  where ".
                            " grade=%u and subject=%u order by degree desc limit 100 ",
                            self::DB_TABLE_NAME,
                            $grade,$subject
        );
        return $this->main_get_list($sql);
    }

    public function gen_top_to_test_lesson($grade,$subject, $seller_student_id ){
        $list=$this->get_list_by_grade_subject($grade,$subject);
        $find_count=0;
        foreach ($list as $item) {
            $check_ret=$this->t_test_lesson_assign_teacher->check_existed( $seller_student_id, $item["teacherid"] );
            if (!$check_ret) {
                $this->t_test_lesson_assign_teacher->row_insert([
                    "seller_student_id" => $seller_student_id,
                    "teacherid"         => $item["teacherid"],
                    "degree"            => $item["degree"],
                ]);
            }
            if ($find_count>=10){
                return ;
            }
            $find_count++;
        }
    }

    public function get_test_lesson_teacher_list($subject,$grade){
        $where_arr=[
            ["tc.subject=%u",$subject,0],
            ["tc.grade=%u",$grade,0],
        ];
        $sql=$this->gen_sql_new("select t.teacherid,realname,t.phone,need_test_lesson_flag,free_time_new"
                                ." from %s t"
                                ." left join %s tc on t.teacherid=tc.teacherid"
                                ." left join %s tf on t.teacherid=tf.teacherid"
                                ." where %s"
                                ." and need_test_lesson_flag=1"
                                ,t_teacher_info::DB_TABLE_NAME
                                ,self::DB_TABLE_NAME
                                ,t_teacher_freetime_for_week::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list($sql);
    }

}
