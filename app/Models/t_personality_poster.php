<?php
namespace App\Models;
use \App\Enums as E;
class t_personality_poster extends \App\Models\Zgen\z_t_personality_poster
{
	public function __construct()
	{
		parent::__construct();
	}

    public function updateClickNum($uid){
        $sql = $this->gen_sql_new(" update %s set clickNum=clickNum+1 where uid=$uid"
                                  ,self::DB_TABLE_NAME
        );
        $this->main_update($sql);
    }

    public function updateStuNum($uid){
        $sql = $this->gen_sql_new(" update %s set stuNum=stuNum+1 where uid=$uid"
                                  ,self::DB_TABLE_NAME
        );
        $this->main_update($sql);
    }


    public function checkHas($RoleId){
        $sql = $this->gen_sql_new("  select 1 from %s "
                                  ." where uid=$RoleId"
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_get_value($sql);
    }

    public function getData($page_num,$uid){
        $where_arr = [
            ["pp.uid=%d",$uid,0]
        ];

        $sql = $this->gen_sql_new("  select pp.uid,clickNum,stuNum,account from %s pp"
                                  ." left join %s m on pp.uid=m.uid "
                                  ." where %s"
                                  ,self::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list_by_page($sql, $page_num, 10);
    }
}











