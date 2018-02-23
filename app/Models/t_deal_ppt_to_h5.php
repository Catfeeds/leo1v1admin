<?php
namespace App\Models;
use \App\Enums as E;
class t_deal_ppt_to_h5 extends \App\Models\Zgen\z_t_deal_ppt_to_h5
{
	public function __construct()
	{
		parent::__construct();
	}

    public function updateStatusByUuid($uuid,$status){
        $sql = $this->gen_sql_new("  update %s set id_deal_falg=$status where uuid='$uuid'"
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_update($sql);
    }

    public function getTeaUploadPPTLink(){
        $sql = $this->gen_sql_new("  select id, lessonid, ppt_url, is_tea, title from %s dp"
                                  ." where id_deal_falg=0 and is_succ=0 and deal_time=0 limit 3"
                                  ,self::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }


    public function getNeedTranLessonUid(){
        $where_arr = [
            "id_deal_falg = 1",
            "is_succ=0"
        ];

        $sql = $this->gen_sql_new("  select id, uuid, lessonid,is_tea from %s dp "
                                  ." where %s limit 3"
                                  ,self::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list($sql);
    }

}











