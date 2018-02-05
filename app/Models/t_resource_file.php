<?php
namespace App\Models;
use \App\Enums as E;
class t_resource_file extends \App\Models\Zgen\z_t_resource_file
{
    public function __construct()
    {
        parent::__construct();
    }

    public function update_file_status($resource_id, $val){

        $sql = $this->gen_sql_new("update %s set status=$val where resource_id=$resource_id  and status<2 "
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_update($sql);
    }

    public function get_info_by_file_id($file_id){
        $where_arr = [
            ['file_id=%u', $file_id, -1]
        ];
        $sql = $this->gen_sql_new(
            "select resource_type,subject,grade,tag_one,tag_two,tag_three,tag_four,file_title,file_link,file_size,file_type,file_id"
            ." from %s f"
            ." left join %s r on r.resource_id=f.resource_id"
            ." where %s"
            ,self::DB_TABLE_NAME
            ,t_resource::DB_TABLE_NAME
            ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function add_num($field, $file_id){

        $sql = $this->gen_sql_new("update %s set $field=$field+1 where file_id=$file_id "
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_update($sql);
    }

    public function minus_num($field, $file_id){
        $sql = $this->gen_sql_new("update %s set $field=$field-1 where file_id=$file_id "
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_update($sql);
    }


    public function get_files_by_resource_id($resource_id){
        $where_arr = [
            ['f.resource_id=%u', $resource_id, -1],
            'f.status=0',
            'r.is_del=0',
            'r.resource_type in (1,2,3,5,6,9)',
        ];
        $sql = $this->gen_sql_new("select r.resource_type,f.file_title,f.file_link,f.file_type,f.file_id,f.file_use_type,f.ex_num"
                                  ." from %s f"
                                  ." left join %s r on r.resource_id=f.resource_id"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_resource::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function getResoureList($resource_id_str){
        $where_arr = [
            "rf.status=0",
            'r.is_del=0',
            'ra.is_ban=0',
            "rf.file_use_type=0",//授课课件,
            "sg.del_flag=0"
        ];


        if($resource_id_str){
            $where_arr[] = "rf.resource_id in ($resource_id_str)";
        }


        $sql = $this->gen_sql_new("  select rf.file_title, rf.file_id, rf.file_type, rf.file_link, rf.file_poster, r.tag_three from %s rf "
                                  ." left join %s r on r.resource_id=rf.resource_id"
                                  ." left join %s ra on "
                                  ." ra.resource_type=r.resource_type and ra.subject=r.subject and ra.grade=r.grade and ra.tag_one=r.tag_one and"
                                  ." ra.tag_two=r.tag_two and ra.tag_three=r.tag_three "
                                  ." left join %s sg on sg.id=ra.tag_four"
                                  ." where %s group by rf.file_id"
                                  ,self::DB_TABLE_NAME
                                  ,t_resource::DB_TABLE_NAME
                                  ,t_resource_agree_info::DB_TABLE_NAME
                                  ,t_sub_grade_book_tag::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);

    }


    public function getResoureInfoById($file_id){
        $where_arr = [
            "rf.file_id=$file_id",
            "rf.status=0",
            'r.is_del=0',
            'ra.is_ban=0',
            "rf.file_use_type=0",//授课课件
            "sg.del_flag=0"
        ];

        $sql = $this->gen_sql_new("  select rf.file_title, rf.file_id, rf.file_type, rf.file_link, rf.file_poster, r.tag_three from %s rf "
                                  ." left join %s r on r.resource_id=rf.resource_id"
                                  ." left join %s ra on "
                                  ." ra.resource_type=r.resource_type and ra.subject=r.subject and ra.grade=r.grade and ra.tag_one=r.tag_one and"
                                  ." ra.tag_two=r.tag_two and ra.tag_three=r.tag_three and ra.tag_four=r.tag_four "
                                  ." left join %s sg on sg.id=ra.tag_four"
                                  ." where %s group by file_id"
                                  ,self::DB_TABLE_NAME
                                  ,t_resource::DB_TABLE_NAME
                                  ,t_resource_agree_info::DB_TABLE_NAME
                                  ,t_sub_grade_book_tag::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }





    public function get_max_ex_num($resource_id){
        $where_arr = [
            ['resource_id=%u', $resource_id, -1],
            "status=0",
            "file_use_type=3"
        ];

        $sql = $this->gen_sql_new(" select max(ex_num) from %s  "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_value($sql);
    }

    public function getFileIdByUuid($uuid){
        $sql = $this->gen_sql_new("  select file_id from %s rf"
                                  ." where uuid='$uuid'"
                                  ,self::DB_TABLE_NAME
        );

        return $this->main_get_value($sql);
    }

    public function getResourceFileList(){
        $where_arr = [
            "rf.uuid=''",
            "rf.status=0"
        ];
        $sql = $this->gen_sql_new("  select file_link, file_id, file_title from %s rf"
                                  ." where %s limit 5"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }


    public function getResourceList(){
        $where_arr = [
            "rf.uuid_status=1",
            "rf.status=0",
            "rf.zip_url=''"
        ];

        $sql = $this->gen_sql_new("  select rf.file_id, rf.uuid "
                                  ." from %s rf"
                                  ." where %s limit 5"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function getResourceFileInfoById($resource_id){
        $where_arr = [
            "f.resource_id=$resource_id",
            "f.status=0",
        ];

        $sql = $this->gen_sql_new("  select file_use_type, file_id, file_title, file_type, file_link from %s f "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function getH5PosterInfo(){
        $where_arr = [
            "f.status=0",
            "f.file_use_type=0",
            "f.file_type='pdf'",
            "f.filelinks=''",
            "r.resource_type=3"
        ];

        $sql = $this->gen_sql_new("  select file_id, file_type, file_link from %s f "
                                  ." left join %s r on r.resource_id=f.resource_id"
                                  ." where %s limit 5"
                                  ,self::DB_TABLE_NAME
                                  ,t_resource::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function getList(){
        $sql = $this->gen_sql_new(" select filelinks, file_id from %s rf"
                                  ." where change_status=2"
                                  ,self::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }

    public function get_all_file_title(){
        // where file_id>1107 and file_id < 1120
        $sql = $this->gen_sql_new(" select file_link,file_id from %s "
                                  ,self::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }

    public function get_list_tmp($file_id){
        $where_arr = [
            "file_poster like '%%home%%'",
            ["f.file_id=%d",$file_id,-1]
        ];

        $sql = $this->gen_sql_new("  select file_id, file_poster, filelinks from %s f"
                           ." where %s"
                           ,self::DB_TABLE_NAME
                           ,$where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_resource_list(){
        $sql = $this->gen_sql_new(" select f.file_title,f.file_id,f.file_size,r.create_time from %s f join %s r on f.resource_id = r.resource_id"
                                  ." where r.resource_type=9 and r.create_time > 1517206316 and r.create_time < 1517208049 and r.is_del = 0 by r.resource_id deac,f.file_id desc"
                                  ,self::DB_TABLE_NAME
                                  ,t_resource::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }

    public function batch_del($idstr){
        $sql = $this->gen_sql_new("delete from %s where resource_id in %s",self::DB_TABLE_NAME,$idstr);
        return $this->main_update($sql);
    }

    public function get_teacherinfo($file_id){
      $sql = $this->gen_sql_new("select t.wx_openid,f.file_title, t.nick  "
                              ." from %s f "
                              ." left join %s r on r.resource_id = f.resource_id "
                              ." left join %s m on r.adminid = m.uid"
                              ." left join %s t on t.phone = m.phone"
                              ." where file_id = %s "
                              ,t_resource_file::DB_TABLE_NAME
                              ,t_resource::DB_TABLE_NAME
                              ,t_manager_info::DB_TABLE_NAME
                              ,t_teacher_info::DB_TABLE_NAME
                              ,$file_id
                            );
      return $this->main_get_row($sql);
    }

    public function get_teacherinfo_new($file_id){
      $sql = $this->gen_sql_new("select t.wx_openid,k.file_title, t.nick  "
                              ." from %s f "
                              ." left join %s t on t.teacherid = f.phone"
                              ." left join %s k on k.file_id = f.file_id "
                              ." where file_id = %s "
                              ,t_resource_file_error_info::DB_TABLE_NAME
                              ,t_teacher_info::DB_TABLE_NAME
                              ,t_resource_file::DB_TABLE_NAME
                              ,$file_id
                            );
      return $this->main_get_row($sql);
    }

}
