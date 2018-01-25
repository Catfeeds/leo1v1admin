<?php
namespace App\Models;
use \App\Enums as E;
class t_sub_grade_book_tag extends \App\Models\Zgen\z_t_sub_grade_book_tag
{
	public function __construct()
	{
		parent::__construct();
	}

    public function get_list($subject,$grade,$bookid,$resource_type,$season_id,$page_num,$page_count){
        $where_arr = [
            ["subject = %u",$subject,-1],
            ["grade = %u",$grade,-1],
            ["bookid = %u",$bookid,-1],
            ["resource_type = %u",$resource_type,-1],
            ["season_id = %u",$season_id,-1],
            ["del_flag = %s",0]
        ];

        $sql = $this->gen_sql_new(" select * from %s  where %s order by subject asc,grade asc,bookid asc,id asc"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num,$page_count);
    }

    public function is_can_add_tag($data){
        $where_arr = [
            ["subject = %u",$data['subject']],
            ["grade = %u",$data['grade']],
            ["bookid = %u",$data['bookid']],
            ["resource_type = %u",$data['resource_type']],
            ["season_id = %u",$data['season_id']],
            ["tag = '%s'",$data['tag']],
            ["del_flag = %s",0]
        ];
        $sql = $this->gen_sql_new(" select * from %s  where %s order by id desc"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);

    }

    public function is_can_edit_tag($id,$data){
        $where_arr = [
            ["id != %u",$id ],
            ["subject = %u",$data['subject']],
            ["grade = %u",$data['grade']],
            ["bookid = %u",$data['bookid']],
            ["resource_type = %u",$data['resource_type']],
            ["season_id = %u",$data['season_id']],
            ["tag = '%s'",$data['tag']],
            ["del_flag = %s",0]
        ];
        $sql = $this->gen_sql_new(" select * from %s  where %s order by id desc"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);

    }

    public function dele_tag($id){
        if($id){
            $sql = $this->gen_sql_new(" delete from %s  where id = %u"
                                      ,self::DB_TABLE_NAME,$id );
            return $this->main_update($sql);

        }else{
            return null;
        }
    }

    public function batch_dele_tag($id_str){
        if($id_str){
            $sql = $this->gen_sql_new(" delete from %s  where id in %s"
                                      ,self::DB_TABLE_NAME,$id_str );
            return $this->main_update($sql);

        }else{
            return null;
        }
    }

    public function get_id($subject,$grade,$tag){
        $where_arr = [
            ["subject = %u",(float)$subject],
            ["grade = %u",(float)$grade],
            ["bookid = %u",50000],
            ["tag = '%s'",$tag],
        ];
        $sql = $this->gen_sql_new(" select id from %s  where %s order by id desc"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);
    }

    public function get_tag_by_sub_grade($subject,$grade,$bookid=50000,$resource_type,$season_id){
        $where_arr = [
            ["subject = %u",(float)$subject],
            ["grade = %u",(float)$grade],
            ["bookid = %u",$bookid],
            ["resource_type = %u",$resource_type],
            ["season_id = %u",$season_id,-1],
        ];
        $sql = $this->gen_sql_new(" select id,tag from %s  where %s order by id desc"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        //dd($sql);
        return $this->main_get_list($sql);

    }

    public function get_biaozun($bookid,$biao){
        if($biao == 1){
            $where_arr = [
                ["bookid = %u",50000 ],
            ];
        }else{
            $where_arr = [
                ["bookid != %u",50000 ],
            ];
        }

        $sql = $this->gen_sql_new(" select * from %s  where %s order by id desc"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_tag_by_id($id){
        $where_arr = [
            ["id = %u",(float)$id],
        ];
        $sql = $this->gen_sql_new(" select id,tag from %s  where %s order by id desc"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_row($sql);

    }

}











