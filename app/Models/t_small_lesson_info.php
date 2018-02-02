<?php
namespace App\Models;
class t_small_lesson_info extends \App\Models\Zgen\z_t_small_lesson_info
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_homework_user_list($lessonid_list,$type){
        $flag_str="true";
        if($type==1){
            $flag_str=" contact_student_flag=0 ";
        }elseif($type==2){
            $flag_str=" contact_student_flag=1 ";
        }
        $sql = $this->gen_sql("select h.userid,work_status,quest_bank_quests_work_status,"
                              ." issue_time,lesson_start,lesson_end,l.lesson_type,l.lessonid "
                              ." from %s h "
                              ." left join %s l on l.lessonid=h.lessonid "
                              ." where h.lessonid in (%s) "
                              ." and (work_status=1 or quest_bank_quests_work_status =1)"
                              ." and %s "
                              ,self::DB_TABLE_NAME
                              ,\App\Models\Zgen\z_t_lesson_info::DB_TABLE_NAME
                              ,$lessonid_list
                              ,$flag_str
        );
        return $this->main_get_list($sql);
    }
    public function get_pdf_homework($lessonid)
    {
        $sql = sprintf(" select issue_url, pdf_question_count, work_status from %s where ".
                       " lessonid = %u  limit 1 " ,
                       self::DB_TABLE_NAME, $lessonid);
        return $this->main_get_row($sql);
    }

    public function change_homework_flag($lessonid,$userid,$type){
        $sql=$this->gen_sql("update %s set contact_student_flag =%u where lessonid=%u and userid=%u"
                            ,self::DB_TABLE_NAME
                            ,$type
                            ,$lessonid
                            ,$userid
        );
        return $this->main_update($sql);
    }

    public function small_class_get_lesson_student_list($lessonid,$page_num ){
        $where_arr=[
            ["t1.lessonid=%d",$lessonid, -2 ],
        ];

        $sql=$this->gen_sql_new("select t1.lessonid, t1.userid as studentid, t2.lesson_num,s.nick,"
                                ." from_unixtime( t2.lesson_start) as lesson_start, t1.issue_url,t1.issue_time ,t1.finish_url, "
                                ." t1.finish_time, t1.check_url, t1.check_time, t1.tea_research_url,t1.tea_research_time, "
                                ." t1.ass_research_url, t1.ass_research_time ,t1.work_status "
                                ." from  %s t1 "
                                ." left join %s t2  on t1.lessonid=t2.lessonid"
                                ." left join %s s  on t1.userid=s.userid"
                                ." where %s "
                                ,self::DB_TABLE_NAME
                                ,t_lesson_info::DB_TABLE_NAME
                                ,t_student_info::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num, 10);
    }

    public function check_user($lessonid,$userid){
        $where_arr = [
            ["lessonid=%u",$lessonid,-1],
            ["userid=%u",$userid,-1],
        ];
        $sql = $this->gen_sql_new("select 1 from %s where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_all_user($lessonid){
        $sql=$this->gen_sql_new("select userid from %s where lessonid=%u"
                                ,self::DB_TABLE_NAME
                                ,$lessonid
        );
        return $this->main_get_list($sql);
    }
}