<?php
namespace App\Models;
class t_homework_info extends \App\Models\Zgen\z_t_homework_info
{
	public function __construct()
	{
		parent::__construct();
	}

    private function upload_home_quiz($itemid, $type, $urlkey)
    {
        $table = $type == 2?(self::DB_TABLE_NAME):(t_quiz_info::DB_TABLE_NAME);
        $id_type = $type == 2?'lessonid' : 'quizid'; 

        $sql = sprintf("update %s set issue_time = %u, issue_url = '%s', work_status = 1 where $id_type = %u",
                       $table,
                       time(NULL),
                       $urlkey,
                       $itemid
        );
        return $this->main_update($sql);
    }
	public function add($courseid, $lesson_num, $userid, $lessonid, $grade=0, $subject=2,$teacherid=0)
	{
        return $this->row_insert([
            'courseid'   => $courseid,
            'lesson_num' => $lesson_num,
            'userid'     => $userid,
            'lessonid'   => $lessonid,
            'work_name'  => '第'. $lesson_num . "课作业",
            'grade'      => $grade,
            'subject'    => $subject,
            'teacherid'  => $teacherid,
        ]);
	}
    public function add_new($courseid, $lesson_num, $userid, $lessonid, $grade=0, $subject=2,$teacherid= 0,$work_status,$issue_url,$finish_url,$check_url,$tea_research_url,$ass_research_url,$score,$issue_time,$finish_time,$check_time,$tea_research_time,$ass_research_time)
	{
        return $this->row_insert([
            'courseid'   => $courseid,
            'lesson_num' => $lesson_num,
            'userid'     => $userid,
            'lessonid'   => $lessonid,
            'work_name'  => '第'. $lesson_num . "课作业",
            'grade'      => $grade,
            'subject'    => $subject,
            'teacherid'    => $teacherid,
            'work_status'=>$work_status,
            'issue_url'=>$issue_url,
            'finish_url'=>$finish_url,
            'check_url'=>$check_url,
            'ass_research_url'=>$ass_research_url,
            'tea_research_url'=>$tea_research_url,
            'score'=>$score,
            'issue_time'=>$issue_time,
            'finish_time'=>$finish_time,
            'check_time'=>$check_time,
            'ass_research_time'=>$ass_research_time,
            'tea_research_time'=>$tea_research_time
        ]);
	}


    public function get_homework_user_list($lessonid_list,$type){
        $flag_str=" true ";
        if($type==1){
            $flag_str=" contact_student_flag=0 ";
        }elseif($type==2){
            $flag_str=" contact_student_flag=1 ";
        }
        $sql = $this->gen_sql("select h.userid,work_status,quest_bank_quests_work_status,"
                              ." issue_time,lesson_start,lesson_end,l.lesson_type,l.lessonid "
                              ." from %s h "
                              ." left join %s l on l.lessonid=h.lessonid"
                              ." where h.lessonid in (%s) "
                              ." and (work_status=1  or quest_bank_quests_work_status=1)"
                              ." and lesson_del_flag=0 "
                              ." and %s"
                              ,self::DB_TABLE_NAME
                              ,\App\Models\Zgen\z_t_lesson_info::DB_TABLE_NAME
                              ,$lessonid_list
                              ,$flag_str
        );
        return $this->main_get_list($sql);
    }

    public function change_homework_flag($lessonid,$userid,$type){
        $sql=$this->gen_sql("update %s set contact_student_flag=%u where lessonid=%u and userid=%u "
                            ,self::DB_TABLE_NAME
                            ,$type
                            ,$lessonid
                            ,$userid
        );
        return $this->main_update($sql);
    }

    /**
     * 将一节课程的作业信息拷贝至另一节课中
     * 此功能多用于课时确认中的调课选项
     */
    public function copy_lesson_homework_to_new_lesson($lessonid,$copy_lessonid){
        $where_arr = [
            ["h1.lessonid=%u",$lessonid,0],
            ["h2.lessonid=%u",$copy_lessonid,0],
        ];
        $sql = $this->gen_sql_new("update %s h1,%s h2 set "
                                  ." h2.work_status=h1.work_status,h2.score=h1.score,"
                                  ." h2.issue_time=h1.issue_time,h2.issue_url=h1.issue_url,"
                                  ." h2.finish_time=h1.finish_time,h2.finish_url=h1.finish_url,"
                                  ." h2.check_time=h1.check_time,h2.check_url=h1.check_url,"
                                  ." h2.check_time=h1.check_time,h2.check_url=h1.check_url,"
                                  ." h2.tea_research_time=h1.tea_research_time,h2.tea_research_url=h1.tea_research_url,"
                                  ." h2.ass_research_time=h1.ass_research_time,h2.ass_research_url=h1.ass_research_url,"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_update($sql);
    }
}
