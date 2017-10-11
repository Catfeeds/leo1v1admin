<?php
namespace App\Models;
use \App\Enums as E;
class t_yxyx_new_list extends \App\Models\Zgen\z_t_yxyx_new_list
{
	public function __construct()
	{
		parent::__construct();
	}

        //添加一条新闻
    public function add_new($new_title, $new_content, $new_pic, $adminid, $create_time) {
        $res = $this->row_insert([
            "new_title"   => $new_title,
            "new_content" => $new_content,
            "new_pic"     => $new_pic,
            "adminid"     => $adminid,
            "create_time" => $create_time,
        ]);
        return $res;
    }

    //修改新闻
    public function update_new($id, $new_title, $new_content, $new_pic, $adminid, $create_time) {
        $res = $this->field_update_list( ["id" => $id],[
            "new_title"   => $new_title,
            "new_content" => $new_content,
            "new_pic"     => $new_pic,
            "adminid"     => $adminid,
            "create_time" => $create_time,
        ]);
        return $res;
    }

    //获取一条新闻
    public function get_one_new_info($id) {
        $where_arr = [
            'id='.$id,
        ];
        $sql =  $this->gen_sql_new( "select id,new_pic,new_title,new_content,create_time,adminid"
                                    ." from %s"
                                    ." where %s"
                                    ,self::DB_TABLE_NAME
                                    ,$where_arr
        );

        return $this->main_get_row($sql);

    }
        //新闻信息
    public function get_all_list($page_info){
        $sql =  $this->gen_sql_new( "select id,new_pic,new_title,new_content,create_time,adminid"
                                    ." from %s"
                                    ." order by id desc"
                                    ,self::DB_TABLE_NAME
        );
        return $this->main_get_list_by_page($sql,$page_info);

    }

    public function get_all_for_wx(){
        $sql =  $this->gen_sql_new( "select id,new_pic,new_title,new_content"
                                    ." from %s"
                                    ." order by id desc"
                                    ,self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);

    }

    public function get_all_for_wx_new($page_info){
        $sql = $this->gen_sql_new( "select id,new_pic,new_title,new_content"
                                    ." from %s"
                                    ." order by id desc"
                                    ,self::DB_TABLE_NAME
        );
        return $this->main_get_list_by_page($sql,$page_info,5);

    }


    public function get_one_new_for_wx($id) {
        $where_arr = [
            'id='.$id,
        ];
        $sql =  $this->gen_sql_new( "select id,new_title,new_content"
                                    ." from %s"
                                    ." where %s"
                                    ,self::DB_TABLE_NAME
                                    , $where_arr
        );

        return $this->main_get_row($sql);

    }

    public function get_agent_info(){
        $where_arr = [
            'a.create_time >='.strtotime('- 2 day',time(NULL)),
        ];
        $sql =  $this->gen_sql_new( "select a.id,a.phone as new_phone,a.nickname as new_nick,"
                                    ."pa.phone as from_phone,pa.nickname as from_nick"
                                    ." from %s a"
                                    ." left join %s pa on a.parentid = pa.id"
                                    ." where %s"
                                    ." order by a.create_time desc"
                                    ." limit %u"
                                    ,t_agent::DB_TABLE_NAME
                                    ,t_agent::DB_TABLE_NAME
                                    , $where_arr
                                    ,50
        );

        return $this->main_get_list($sql);
    }

}

