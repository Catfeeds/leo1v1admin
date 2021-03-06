<?php
namespace App\Models;
use \App\Enums as E;
class t_resource extends \App\Models\Zgen\z_t_resource
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_all(
        $use_type, $resource_type, $subject, $grade, $tag_one, $tag_two, $tag_three, $tag_four,$tag_five, $file_title, $page_info,
        $is_del = 0,$status = 0,$has_comment = -1,$has_error = -1,$id_order = 1

    ){
        $where_arr = [
            ['r.use_type=%u', $use_type, -1],
            ['r.resource_type=%u', $resource_type, -1],
            ['r.subject=%u', $subject, -1],
            ['r.grade=%u', $grade, -1],
            ['r.tag_one=%u', $tag_one, -1],
            ['r.tag_two=%u', $tag_two, -1],
            ['r.tag_three=%u', $tag_three, -1],
            ['r.tag_four=%u', $tag_four, -1],
            ['r.tag_five=%u', $tag_five, -1],
            ['r.is_del=%u', $is_del, -1],
            ['f.status=%u', $status, -1],
        ];

        if( $has_comment == 1 ){
            $where_arr[] = ['com.id > %u',0];
        }

        if( $has_error == 1 ){
            $where_arr[] = ['error.id > %u',0];
        }

        $not = '';

        if( $has_comment == 0 ){
            $not .= " and not exists ( select com.id from db_weiyi.t_resource_file_evalutation com where com.file_id=f.file_id) ";
        }

        if( $has_error == 0 ){
            $not .= " and not exists ( select error.id from db_weiyi.t_resource_file_error_info error where error.file_id=f.file_id) ";
        }

        $order = "";
        if($id_order == 1){
            $order = " order by r.resource_id desc,v.file_id desc";
        }else if($id_order == 2){
            if($has_comment != 1){
                $where_arr[] = ['com.id > %u',0];
            }
            $order = " order by com.id desc,r.resource_id desc,v.file_id desc";
        }else if($id_order == 3){
            if($has_error != 1){
                $where_arr[] = ['error.id > %u',0];
            }
            $order = " order by error.id desc,r.resource_id desc,v.file_id desc";
        }

        if( in_array($resource_type, [1,2,3,4,5,9]) ){
            //添加通用版50000
            if($tag_one != -1){
                $where_arr[] = " r.tag_one in ($tag_one, 50000) ";
            }
        } else {
            $where_arr[] = ['r.tag_one=%u', $tag_one, -1];
        }

        if($file_title != ''){
            $where_arr[] = ["f.file_title like '%%%s%%'", $this->ensql( $file_title), ""];
        }

        $sql = $this->gen_sql_new(
            "select f.file_title,f.file_size,f.file_type,f.ex_num,f.file_hash,f.file_link,f.file_id,f.file_use_type,"
            ." r.use_type,r.resource_id,r.resource_type,r.subject,r.grade,r.tag_one,r.tag_two,r.tag_three,r.tag_four,r.tag_five,"
            ." t.tag as tag_four_str,v.create_time,v.visitor_id,com.id as comment_id,error.id as error_id,error.status as error_status"
            ." from %s r"
            ." left join %s f on f.resource_id=r.resource_id"
            ." left join %s v on v.file_id=f.file_id and v.visitor_type=0 "
            ." left join %s t on t.id=r.tag_four"
            ." left join %s com on com.file_id=f.file_id"
            ." left join %s error on error.file_id=f.file_id"
            ." where %s"
            ." and not exists ( select 1 from %s where file_id=v.file_id and v.create_time<create_time and visitor_type=0) "
            .$not
            ." group by v.file_id"
            ." order by r.resource_id desc,v.file_id desc"
            ,self::DB_TABLE_NAME
            ,t_resource_file::DB_TABLE_NAME
            ,t_resource_file_visit_info::DB_TABLE_NAME
            ,t_sub_grade_book_tag::DB_TABLE_NAME
            ,t_resource_file_evalutation::DB_TABLE_NAME
            ,t_resource_file_error_info::DB_TABLE_NAME
            ,$where_arr
            ,t_resource_file_visit_info::DB_TABLE_NAME
        );
        //dd($sql);
        return $this->main_get_list_by_page($sql,$page_info,10,true);
    }

    public function get_all_error($start_time,$end_time,$error_type,$sub_error_type,$file_id,
                                $use_type, $resource_type, $subject, $grade, $tag_one, 
                                $tag_two, $tag_three, $tag_four,$tag_five, $page_info, 
                                $is_del = 0,$status = 0
    ){
        $where_arr = [
            ['r.use_type=%u', $use_type, -1],
            ['r.resource_type=%u', $resource_type, -1],
            ['r.subject=%u', $subject, -1],
            ['r.grade=%u', $grade, -1],
            ['r.tag_one=%u', $tag_one, -1],
            ['r.tag_two=%u', $tag_two, -1],
            ['r.tag_three=%u', $tag_three, -1],
            ['r.tag_four=%u', $tag_four, -1],
            ['r.tag_five=%u', $tag_five, -1],
            ['is_del=%u', $is_del, -1],
            ['f.status=%u', $status, -1],
            'e.id>0',
            ['e.add_time>=%u',$start_time,-1],
            ['e.add_time<=%u',$end_time,-1],
            ['e.error_type=%u',$error_type,-1],
            ['e.sub_error_type=%u',$sub_error_type,-1],
            ['e.file_id=%u',$file_id,-1],
        ];
        if( in_array($resource_type, [1,2,3,4,5,9]) ){
            //添加通用版50000
            if($tag_one != -1){
                $where_arr[] = " r.tag_one in ($tag_one, 50000) ";
            }
        } else {
            $where_arr[] = ['r.tag_one=%u', $tag_one, -1];
        }

        $sql = $this->gen_sql_new(
            "select f.file_title,f.file_size,f.file_type,f.ex_num,f.file_hash,f.file_link,f.file_id,f.file_use_type,"
            ." r.use_type,r.resource_id,r.resource_type,r.subject,r.grade,r.tag_one,r.tag_two,r.tag_three,r.tag_four,r.tag_five,"
            ." t.tag as tag_four_str,v.create_time,v.visitor_id,r.create_time as c_time, "
            ." e.id as eid,e.teacherid, e.add_time, e.error_type, e.sub_error_type,e.detail_error,e.error_picture, "
            ." e.detail_question, e.train_error_type ,e.phone ,e.nick as error_nick,e.resource_type as etype,e.status as estatus "
            ." from %s r"
            ." left join %s f on f.resource_id=r.resource_id"
            ." left join %s v on v.file_id=f.file_id and v.visitor_type=0 "
            ." left join %s t on t.id=r.tag_four"
            ." left join %s e on v.file_id = e.file_id"
            ." where %s"
            ." and not exists ( select 1 from %s where file_id=v.file_id and v.create_time<create_time and visitor_type=0) "
            //." order by r.resource_id desc,f.file_use_type"
            ." order by e.add_time asc"
            ,self::DB_TABLE_NAME
            ,t_resource_file::DB_TABLE_NAME
            ,t_resource_file_visit_info::DB_TABLE_NAME
            ,t_sub_grade_book_tag::DB_TABLE_NAME
            ,t_resource_file_error_info::DB_TABLE_NAME
            ,$where_arr
            ,t_resource_file_visit_info::DB_TABLE_NAME
        );
        //dd($sql);
        return $this->main_get_list_by_page($sql,$page_info,10,true);
    }

    public function get_all_info(
        $use_type, $resource_type, $subject, $grade, $tag_one, $tag_two, $tag_three, $tag_four,$tag_five, $page_info,
        $adminid,$reload_adminid,$kpi_adminid,$status
    ){
        $where_arr = [
            ['r.use_type=%u', $use_type, -1],
            ['r.resource_type=%u', $resource_type, -1],
            ['r.subject=%u', $subject, -1],
            ['r.grade=%u', $grade, -1],
            ['r.tag_one=%u', $tag_one, -1],
            ['r.tag_two=%u', $tag_two, -1],
            ['r.tag_three=%u', $tag_three, -1],
            ['r.tag_four=%u', $tag_four, -1],
            ['r.tag_five=%u', $tag_five, -1],
            ['r.is_del=%u', 0, -1],
            "f.status = 0",
            ['r.adminid=%u', $adminid, -1],
            ['f.reload_adminid=%u', $reload_adminid, -1],
            ['f.kpi_adminid=%u', $kpi_adminid, -1],
        ];
        if($status != -1){
            $where_arr[] = " ( f.reload_status = $status or f.kpi_status = $status )";
        }

        if( in_array($resource_type, [1,2,3,4,5,9]) ){
            //添加通用版50000
            if($tag_one != -1){
                $where_arr[] = " r.tag_one in ($tag_one, 50000) ";
            }
        } else {
            $where_arr[] = ['r.tag_one=%u', $tag_one, -1];
        }

        $sql = $this->gen_sql_new(
            "select f.file_title,f.file_size,f.file_type,f.ex_num,f.file_hash,f.file_link,f.file_id,f.file_use_type,"
            ." r.use_type,r.resource_id,r.resource_type,r.subject,r.grade,r.tag_one,r.tag_two,r.tag_three,r.tag_four,r.tag_five,"
            ." t.tag as tag_four_str,v.create_time,v.visitor_id, "
            ." r.adminid, r.create_time as c_time, f.reload_adminid,f.kpi_adminid,f.reload_status,f.kpi_status  "
            ." from %s r"
            ." left join %s f on f.resource_id=r.resource_id"
            ." left join %s v on v.file_id=f.file_id and v.visitor_type=0 "
            ." left join %s t on t.id=r.tag_four"
            ." where %s"
            ." and not exists ( select 1 from %s where file_id=v.file_id and v.create_time<create_time and visitor_type=0) "
            ." group by v.file_id"
            ." order by r.resource_id desc,v.file_id desc"
            ,self::DB_TABLE_NAME
            ,t_resource_file::DB_TABLE_NAME
            ,t_resource_file_visit_info::DB_TABLE_NAME
            ,t_sub_grade_book_tag::DB_TABLE_NAME
            ,$where_arr
            ,t_resource_file_visit_info::DB_TABLE_NAME
        );
        //dd($sql);
        return $this->main_get_list_by_page($sql,$page_info,10,true);
    }
    public function get_count($start_time, $end_time, $subject=-1, $grade=-1, $resource_type=-1,$adminid=-1){
        $where_arr = [
            'r.is_del=0',
            'f.status=0',
            ['r.create_time>%u', $start_time, -1],
            ['r.create_time<=%u', $end_time, -1],
            ["subject=%u", $subject, -1],
            ["grade=%u", $grade, -1],
            ["resource_type=%u", $resource_type, -1],
            ["adminid=%u",$adminid,-1],
        ];
        $sql = $this->gen_sql_new("select resource_type,adminid,subject,f.file_id,f.visit_num,f.use_num,f.error_num"
                                  ." from %s f"
                                  ." left join %s r on r.resource_id=f.resource_id"
                                  ." where %s"
                                  ." group by file_id"
                                  ,t_resource_file::DB_TABLE_NAME
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        //dd($sql);
        return $this->main_get_list($sql);
    }

    public function get_count_new($start_time, $end_time, $type){
        $where_arr = [
            'r.is_del=0',
            'f.status=0',
            ['r.create_time>%u', $start_time, -1],
            ['r.create_time<=%u', $end_time, -1],
        ];
        if($type == 2){
            $group = "adminid";
        }else if($type == 3){
            $group = "subject";
        }else if($type == 4){
            $group = "grade";
        }else if($type == 5){
            $group = "resource_type";
        }else if($type == 6){
            $group = "subject, grade";
        }
        $sql = $this->gen_sql_new("select %s,  count(f.file_id) as file_num ,sum(f.visit_num) as visit_num, "
                                ." sum(f.use_num) as use_num ,sum(f.error_num) as error_num, "
                                ." sum(if(f.visit_num>0,1,0)) as visit ,sum(if(f.use_num>0,1,0)) as user, "
                                ." sum(if(f.error_num>0,1,0))  as error "
                                  ." from %s f"
                                  ." left join %s r on r.resource_id=f.resource_id"
                                  ." where %s"
                                  ." group by %s"
                                  ,$group
                                  ,t_resource_file::DB_TABLE_NAME
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
                                  ,$group
        );
        //dd($sql);
        return $this->main_get_list($sql);
    }

    public function is_has_file($resource_type,$subject, $grade, $tag_one, $tag_two, $tag_three, $tag_four){
        $where_arr = [
            ['resource_type=%u', $resource_type, -1],
            ['subject=%u', $subject, -1],
            ['grade=%u', $grade, ''],
            ['tag_one=%u', $tag_one, ''],
            ['tag_two=%u', $tag_two, ''],
            ['tag_three=%u', $tag_three, ''],
            ['tag_four=%u', $tag_four, ''],
            'is_del<3',
        ];
        $sql = $this->gen_sql_new("select count(1) from %s where %s "
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function get_all_for_tea(
        $resource_type, $subject, $grade, $tag_one, $tag_two, $tag_three, $tag_four,$tag_five, $page_info
    ){
        $where_arr = [
            'r.use_type=1',
            ['r.resource_type=%u', $resource_type, -1],
            ['r.subject=%u', $subject, -1],
            ['r.grade=%u', $grade, -1],
            // ['r.tag_one=%u', $tag_one, -1],
            ['r.tag_two=%u', $tag_two, -1],
            ['r.tag_three=%u', $tag_three, -1],
            ['r.tag_four=%u', $tag_four, -1],
            ['r.tag_five=%u', $tag_five, -1],
            'r.is_del=0',
            'f.status=0',
           // 'ra.is_ban!=0',
        ];

        //老师只开放了１－６
        if( $resource_type < 6){
            //添加通用版50000
            if($tag_one != -1){
                $where_arr[] = " r.tag_one in ($tag_one, 50000) ";
            }
        } else {
            $where_arr[] = ['r.tag_one=%u', $tag_one, -1];
        }


        $sql = $this->gen_sql_new(
            "select r.resource_id,r.resource_type,f.file_title,f.file_size,f.file_type, max(v.create_time) create_time,f.file_id,"
            ." v.visitor_id, r.subject,r.grade,r.tag_one,r.tag_two,r.tag_three,r.tag_four,r.tag_five ,f.file_link,f.file_use_type,f.use_num, "
            ." tr.tea_res_id"
            ." from %s r"
            ." left join %s f on f.resource_id=r.resource_id"
            ." left join %s v on v.file_id=f.file_id and v.visitor_type=0 "
            ." left join %s tr on tr.file_id=f.file_id and tr.is_del=0 "
            ." left join %s ra on "
            ." ra.resource_type=r.resource_type and ra.subject=r.subject and ra.grade=r.grade and ra.tag_one=r.tag_one and"
            ." ra.tag_two=r.tag_two and ra.tag_three=r.tag_three and ra.tag_four=r.tag_four "
            ." where %s"
            ." group by f.file_id "
            ." order by r.resource_id desc,f.file_use_type"
            ,self::DB_TABLE_NAME
            ,t_resource_file::DB_TABLE_NAME
            ,t_resource_file_visit_info::DB_TABLE_NAME
            ,t_teacher_resource::DB_TABLE_NAME
            ,t_resource_agree_info::DB_TABLE_NAME
            ,$where_arr
        );
        //dd($sql);
        return $this->main_get_list_by_page($sql,$page_info,10,true);
    }

    public function get_all_for_tea_train(
        $resource_type, $subject, $grade, $tag_one, $tag_two, $tag_three, $tag_four,$tag_five, $page_info
    ){
        $where_arr = [
            'r.use_type=2',
            ['r.resource_type=%u', $resource_type, -1],
            ['r.subject=%u', $subject, -1],
            ['r.grade=%u', $grade, -1],
            // ['r.tag_one=%u', $tag_one, -1],
            ['r.tag_two=%u', $tag_two, -1],
            ['r.tag_three=%u', $tag_three, -1],
            ['r.tag_four=%u', $tag_four, -1],
            ['r.tag_five=%u', $tag_five, -1],
            'r.is_del=0',
            'f.status=0',
           // 'ra.is_ban!=0',
        ];

        //老师只开放了１－６
        if( $resource_type < 6){
            //添加通用版50000
            if($tag_one != -1){
                $where_arr[] = " r.tag_one in ($tag_one, 50000) ";
            }
        } else {
            $where_arr[] = ['r.tag_one=%u', $tag_one, -1];
        }


        $sql = $this->gen_sql_new(
            "select r.resource_id,r.resource_type,f.file_title,f.file_size,f.file_type, max(v.create_time) create_time,f.file_id,"
            ." v.visitor_id, r.subject,r.grade,r.tag_one,r.tag_two,r.tag_three,r.tag_four,r.tag_five ,f.file_link,f.file_use_type,f.use_num, f.visit_num, "
            ." tr.tea_res_id"
            ." from %s r"
            ." left join %s f on f.resource_id=r.resource_id"
            ." left join %s v on v.file_id=f.file_id and v.visitor_type=0 "
            ." left join %s tr on tr.file_id=f.file_id and tr.is_del=0 "
            ." left join %s ra on "
            ." ra.resource_type=r.resource_type and ra.subject=r.subject and ra.grade=r.grade and ra.tag_one=r.tag_one and"
            ." ra.tag_two=r.tag_two and ra.tag_three=r.tag_three and ra.tag_four=r.tag_four "
            ." where %s"
            ." group by f.file_id "
            ." order by r.resource_id desc,f.file_use_type"
            ,self::DB_TABLE_NAME
            ,t_resource_file::DB_TABLE_NAME
            ,t_resource_file_visit_info::DB_TABLE_NAME
            ,t_teacher_resource::DB_TABLE_NAME
            ,t_resource_agree_info::DB_TABLE_NAME
            ,$where_arr
        );
        //dd($sql);
        return $this->main_get_list_by_page($sql,$page_info,10,true);
    }

    public function getResourceId($subject,$grade){
        $where_arr = [
            "r.subject=$subject",
            "r.grade=$grade",
            "r.use_type=1",
            "r.resource_type=3", // 标准试听课
            "r.is_del=0",
            "ra.is_ban=0",
            "f.status=0",
            "sg.del_flag=0"
        ];

        $sql = $this->gen_sql_new("  select r.resource_id from %s r "
                                  ." left join %s f on f.resource_id=r.resource_id"
                                  ." left join %s ra on "
                                  ." ra.resource_type=r.resource_type and ra.subject=r.subject and ra.grade=r.grade and ra.tag_one=r.tag_one and"
                                  ." ra.tag_two=r.tag_two and ra.tag_three=r.tag_three  "
                                  ." left join %s sg on sg.id=ra.tag_four"

                                  ." where %s group by r.resource_id "
                                  ,self::DB_TABLE_NAME
                                  ,t_resource_file::DB_TABLE_NAME
                                  ,t_resource_agree_info::DB_TABLE_NAME
                                  ,t_sub_grade_book_tag::DB_TABLE_NAME
                                  ,$where_arr
        );
        //dd($sql);
        return $this->main_get_list($sql);
    }

    public function get_resource_type_for_tea($subject, $grade){
         $where_arr = [
            'r.use_type=1',
            ['r.subject in (%s)', $subject ,''],
            ['r.grade in (%s)', $grade ,''],
            'r.is_del=0',
            'f.status=0',
            'ra.is_ban=0',
        ];

        $sql = $this->gen_sql_new(
            "select distinct(  r.resource_type) "
            ." from %s r"
            ." left join %s f on f.resource_id=r.resource_id"
            ." left join %s tr on tr.file_id=f.file_id and tr.is_del=0 "
            ." left join %s ra on "
            ." ra.resource_type=r.resource_type and ra.subject=r.subject and ra.grade=r.grade and ra.tag_one=r.tag_one and"
            ." ra.tag_two=r.tag_two and ra.tag_three=r.tag_three  "
            ." left join %s sg on sg.id=ra.tag_four"
            ." where %s"
            ." order by r.resource_type"
            ,self::DB_TABLE_NAME
            ,t_resource_file::DB_TABLE_NAME
            ,t_teacher_resource::DB_TABLE_NAME
            ,t_resource_agree_info::DB_TABLE_NAME
            ,t_sub_grade_book_tag::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_resource_type_all(){

        $sql = $this->gen_sql_new(
            "select * from %s",self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    public function get_next_tag($select, $resource_type,$subject, $grade, $tag_one, $tag_two, $tag_three,$is_end){
        if(!empty($resource_type)){
            $where_arr[] = ['r.resource_type=%u', $resource_type, -1];
        }
        if(!empty($subject)){
            $where_arr[] = ['r.subject=%u', $subject, -1];
        }
        if(!empty($grade)){
            $where_arr[] = ['r.grade=%u', $grade, -1];
        }
        if(!empty($tag_one)){
            $where_arr[] = ['r.tag_one=%u', $tag_one, -1];
        }
        if(!empty($tag_two)){
            $where_arr[] = ['r.tag_two=%u', $tag_two, -1];
        }
        if(!empty($tag_three)){
            $where_arr[] = ['r.tag_three=%u', $tag_three, -1];
        }

        $where_arr[] =  ['is_del=%u', 0];
        $where_arr[] =  ['status=%u', 0];

        //$select = $is_end?$select.',is_ban':$select;


        $sql = $this->gen_sql_new(
            "select r.$select"
            ." from %s r"
            ." left join %s f on f.resource_id=r.resource_id"
            ." left join %s v on v.file_id=f.file_id and v.visitor_type=0 "
            ." where %s  and not exists ( select 1 from %s where file_id=v.file_id and v.create_time<create_time and visitor_type=0) "
            ." group by $select"
            ,self::DB_TABLE_NAME
            ,t_resource_file::DB_TABLE_NAME
            ,t_resource_file_visit_info::DB_TABLE_NAME
            ,$where_arr
            ,t_resource_file_visit_info::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }

    //被彻底删除的文件
    public function get_total_del(
        $use_type, $resource_type, $subject, $grade,$file_title, $page_info, $is_del = -1,$status = 1
    ){
        $where_arr = [
            ['r.use_type=%u', $use_type, -1],
            ['r.resource_type=%u', $resource_type, -1],
            ['r.subject=%u', $subject, -1],
            ['r.grade=%u', $grade, -1],
            ['r.is_del=%u', $is_del, -1],
            ['f.status=%u', $status, -1],
        ];

        if($file_title != ''){
            $where_arr[] = ["f.file_title like '%%%s%%'", $this->ensql( $file_title), ""];
        }

        $sql = $this->gen_sql_new(
            "select f.file_title,f.file_size,f.file_type,f.ex_num,f.file_hash,f.file_link,f.file_id,f.file_use_type,"
            ." r.use_type,r.resource_id,r.resource_type,r.subject,r.grade,r.tag_one,r.tag_two,r.tag_three,r.tag_four,r.tag_five,"
            ." t.tag as tag_four_str,v.create_time,v.visitor_id"
            ." from %s f"
            ." left join %s r on f.resource_id=r.resource_id"
            ." left join %s v on v.file_id=f.file_id "
            ." left join %s t on t.id=r.tag_four"
            ." where %s"
            //." and not exists ( select 1 from %s where file_id=v.file_id and v.create_time<create_time and visitor_type=0) "
            ." order by r.resource_id desc,v.file_id desc"
            ,t_resource_file::DB_TABLE_NAME
            ,self::DB_TABLE_NAME
            ,t_resource_file_visit_info::DB_TABLE_NAME
            ,t_sub_grade_book_tag::DB_TABLE_NAME
            ,$where_arr
            ,t_resource_file_visit_info::DB_TABLE_NAME
        );
        //dd($sql);
        return $this->main_get_list_by_page($sql,$page_info,10,true);
    }

    public function batch_del($idstr){
        $sql = $this->gen_sql_new("delete from %s where resource_id in %s",self::DB_TABLE_NAME,$idstr);
        return $this->main_update($sql);
    }

    public function get_list_for_subject() { //试听课标准化讲义使用次数
        $where_arr = [
            "r.resource_type=3", // 资料类型：标准化试听课
            "r.subject in (1,2,3)", // 科目要求：语文、数学、英语
            "f.file_title!=''"
        ];

        $sql = $this->gen_sql_new("select f.file_title,r.subject,r.grade,r.adminid,sum(f.visit_num) visit_num,sum(f.use_num) use_num from %s r left join %s f on r.resource_id=f.resource_id where %s group by f.file_title order by r.adminid",
                                  self::DB_TABLE_NAME,
                                  t_resource_file::DB_TABLE_NAME,
                                  $where_arr
        );
        
        return $this->main_get_list($sql);
    }

    public function get_latest_id($limit){
        $sql = $this->gen_sql_new("select resource_id from %s order by resource_id desc limit 0,".$limit,self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

    public function get_file_subject($error_id){
        $sql=$this->gen_sql("select r.subject,r.grade from %s r
                             join %s f on r.resource_id = f.resource_id
                             join %s e on e.file_id = f.file_id
                             where e.id=%u",
                            self::DB_TABLE_NAME,
                            t_resource_file::DB_TABLE_NAME,
                            t_resource_file_error_info::DB_TABLE_NAME,
                            $error_id);
        return $this->main_get_row($sql); 
    }

}
