<?php
namespace App\Models;
class t_paper_info extends \App\Models\Zgen\z_t_paper_info
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_list_for_test() {
        $sql=$this->gen_sql( "select * from %s where paper_type=2", self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }

    public function get_paper_list_by_id_str($paperid_str){
        $where_arr = [
            ["paperid in (%s)",$paperid_str,""]
        ];

        $sql = $this->gen_sql_new("select paperid,paper_name,paper_url,paper_down "
                                  ." from %s "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function paper_grow_down($paperid_str){
        $where_arr = [
            ["paperid in (%s)",$paperid_str,""],
        ];
        $sql = $this->gen_sql_new("update %s set paper_down=paper_down+1"
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );
        return $this->main_update($sql);
    }

}
