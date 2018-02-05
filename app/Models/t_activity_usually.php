<?php
namespace App\Models;
use \App\Enums as E;
class t_activity_usually extends \App\Models\Zgen\z_t_activity_usually
{
	public function __construct()
	{
		parent::__construct();
	}

    public function getActivityList($type,$start_time,$end_time,$page_num){
        $where_arr = [
            ["gift_type=%d",$type,-1]
        ];

        $sql = $this->gen_sql_new("  select au.use_flag, au.id, shareImgUrl, coverImgUrl,  activityImgUrl, followImgUrl, gift_type, title, au.id, act_descr, au.url, activity_status, add_time, au.uid, m.account from %s au "
                                  ." left join %s m on m.uid=au.uid"
                                  ." where %s  order by activity_status asc, au.add_time desc "
                                  ,self::DB_TABLE_NAME
                                  ,t_manager_info::DB_TABLE_NAME
                                  ,$where_arr
        );

        return $this->main_get_list_by_page($sql, $page_num);
    }

    public function getMarketExtendInfo($id){
        $sql = $this->gen_sql_new("  select gift_type, title, activity_status, act_descr, shareImgUrl, coverImgUrl, activityImgUrl, followImgUrl "
                                  ." from %s au"
                                  ." where id=$id"
                                  ,self::DB_TABLE_NAME
        );

        return $this->main_get_row($sql);
    }

    public function getImgList($id){
        $sql = $this->gen_sql_new("  select shareImgUrl, coverImgUrl, activityImgUrl, followImgUrl "
                                  ." from %s au"
                                  ." where id=$id"
                                  ,self::DB_TABLE_NAME
        );

        return $this->main_get_row($sql);
    }

    public function getImgUrlInfo($id){
        $sql = $this->gen_sql_new("  select use_flag,  add_num, title, url, act_descr, shareImgUrl, coverImgUrl, activityImgUrl, followImgUrl from %s au"
                                  ." where id=$id"
                                  ,self::DB_TABLE_NAME
        );

        return $this->main_get_row($sql);
    }

    public function updateAddNum($id){
        $sql = $this->gen_sql_new("  update %s set add_num=add_num+1 where id=$id"
                                  ,self::DB_TABLE_NAME
        );
        return $this->main_update($sql);
    }

}











