<?php
namespace App\Models;
class t_origin_key extends \App\Models\Zgen\z_t_origin_key
{
    public function __construct()
    {
        parent::__construct();
    }


    function get_channel_manage( $page_num,$key1,$key2,$key3,$key4,$value,$origin_level=-1,$key0='') {
        $where_arr=[
            ["key0='%s'",$key0,""],
            ["key1='%s'",$key1,""],
            ["key2='%s'",$key2,""],
            ["key3='%s'",$key3,""],
            ["key4='%s'",$key4,""],
            ["value like '%%%s%%' ",$value,""],
            ["origin_level = %u ",$origin_level,-1],
        ];
        $sql=$this->gen_sql_new("select key0,key1, key2, key3, key4, value,origin_level,create_time from %s where  %s order by  create_time desc ",
                            self::DB_TABLE_NAME,
                            $where_arr
        );
        return $this->main_get_list_by_page($sql,$page_num,10);
    }

    function delete_origin_key($value) {
         $sql=$this->gen_sql("delete from %s where value = '%s' ",
                            self::DB_TABLE_NAME,
                            $value
        );

        return $this->main_update($sql);
    }
    function get_origin_key($key4) {
        $sql=$this->gen_sql("select key1, key2, key3, key4 from %s where key4 = '%s' ",
                            self::DB_TABLE_NAME,
                            $key4
        );
        return $this->main_get_list_as_page($sql);
    }

    public function  add_by_admind($key1,$key2,$key3,$key4,$value,$origin_level =1,$create_time=0) {
        if(empty($create_time)){
            $create_time =time();
        }
        $this->row_insert_ignore([
            self::C_key1     => $key1,
            self::C_key2     => $key2,
            self::C_key3     => $key3,
            self::C_key4     => $key4,
            self::C_value    => $value,
            "origin_level"   =>$origin_level,
            "create_time"=>time(NULL)
        ]);

        return $value;
    }

    public function add_origin_key($key1,$key2,$key3,$key4,$value,$origin_level =1,$create_time=0,$key0='') {
        if(empty($create_time)){
            $create_time =time();
        }
        return $this->row_insert([
            self::C_key0     => $key0,
            self::C_key1     => $key1,
            self::C_key2     => $key2,
            self::C_key3     => $key3,
            self::C_key4     => $key4,
            self::C_value       => $value,
            "origin_level"    =>$origin_level,
            "create_time"=>$create_time
        ]);
    }

    public function get_origin_key_value($key1,$key2,$key3,$key4,$key0='') {
        $sql = $this->gen_sql_new(
            " select  value from %s  ".
            "where key1= '%s' and key2= '%s'  and key3= '%s'  and key4= '%s' and key0 = '%s'  ",
            self::DB_TABLE_NAME,
            $key1,
            $key2,
            $key3,
            $key4,
            $key0
        );
        return $this->main_get_value($sql);
    }



    public function edit_origin_key($old_value,$key1,$key2,$key3,$key4,$value,$origin_level = 0,$key0='') {
        $sql = $this->gen_sql("update %s set key0 = '%s',key1 = '%s', key2 = '%s', key3 = '%s', ".
                              "key4 = '%s'  , value='%s',origin_level=%u ".
                              " where value = '%s' ",
                              self::DB_TABLE_NAME,
                              $key0,
                              $key1,
                              $key2,
                              $key3,
                              $key4,
                              $value,
                              $origin_level,
                              $old_value
        );

        return $this->main_update($sql);

    }



    public function edit_origin_level_batch( $key1, $key2, $key3, $key4,$value, $origin_level){
        $where_arr = [
            ["key1='%s'",$key1],
            ["key2='%s'",$key2],
            ["key3='%s'",$key3],
            ["key4='%s'",$key4]
        ];

        $del_index=[];

        if($key1 == ''){
            $del_index[]=0;
        }
        if ($key2=='') {
            $del_index[]=1;
        }
        if($key3 == ''){
            $del_index[]=2;
        }
        if($key4 == ''){
            $del_index[]=3;
        }

        foreach($del_index as $item){
            unset($where_arr[$item]);
        }
        if ($value) {
            $where_arr[]=sprintf( "value like '%s'", $this->ensql($value)) ;
        }

        $sql = $this->gen_sql_new("update %s set origin_level=%d ".
                                  " where %s  ", //and origin_level <> 90
                              self::DB_TABLE_NAME,
                              $origin_level,
                              $where_arr
        );

        return $this->main_update($sql);

    }


    public function get_key1_info($page_num){

        $sql= $this->gen_sql("select distinct(key1) from %s",
                             self::DB_TABLE_NAME
        );

        return $this->main_get_list_by_page($sql,$page_num,10);
    }


    public function get_in_str_key_list( $origin_ex,$key_str )  {
        $in_str="in";
        if( $origin_ex && $origin_ex[0] == "!" ) {
            $origin_ex=trim( substr($origin_ex,1)) ;
            $in_str="not in";
        }
        $arr=explode(",",$origin_ex);
        $key0="";
        $key1="";
        $key2="";
        $key3="";
        $key4="";
        if (isset($arr[0])) $key0= $arr[0];
        if (isset($arr[1])) $key1= $arr[1];
        if (isset($arr[2])) $key2= $arr[2];
        if (isset($arr[3])) $key3= $arr[3];
        if (isset($arr[4])) $key4= $arr[4];
        if ($key0=="") {
            return "true";
        }

        $where_arr=[
            ["key0='%s'",$key0,""],
            ["key1='%s'",$key1,""],
            ["key2='%s'",$key2,""],
            ["key3='%s'",$key3,""],
            ["key4='%s'",$key4,""],
        ];

        $sql=$this->gen_sql("select value from %s where %s",
                            self::DB_TABLE_NAME,
                            [$this->where_str_gen($where_arr)]
        );
        $list=$this->main_get_list($sql);
        $ret_arr=[];
        foreach($list as $item ) {
            $ret_arr[]= "'".trim($item["value"])."'";
        }
        if (count( $ret_arr)==0) {
            //有筛选,但没数据

            if ($in_str =="in" ) {
                return "false";
            }else{
                return "true";
            }
        } else  {
            return " $key_str $in_str (". join(",", $ret_arr) . ")" ;
        }
    }

    public function get_key_list( $key1,$key2,$key3, $key_str,$key0='' ){
        $where_arr=[
            ["key0='%s'",$key0,""],
            ["key1='%s'",$key1,""],
            ["key2='%s'",$key2,""],
            ["key3='%s'",$key3,""],
        ];
        $sql=$this->gen_sql("select  distinct $key_str k from %s where %s order by k ",
                            self::DB_TABLE_NAME,
                            [$this->where_str_gen($where_arr)]
        );
        $ret_list= $this->main_get_list($sql);

        return \App\Helper\Common::sort_pinyin($ret_list,"k");
    }
    public function get_last_level( $key1,$key2  ) {
        $sql= $this->gen_sql_new(
            "select  origin_level from %s"
            . " where   key1='%s' and key2='%s' order by create_time desc limit 1  ",
            self::DB_TABLE_NAME,
            $key1,
            $key2);
        return $this->main_get_value($sql);
    }


    public function get_list( ) {
        $sql=$this->gen_sql_new("select * from %s "
                                , self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql,function($item){
            return $item["value"];
        });

    }

    public function get_key1_list_by_origin_level_arr($origin_level_arr){
        $this->where_arr_add_int_or_idlist($where_arr,'origin_level',$origin_level_arr);
        $sql=$this->gen_sql_new("select * from %s "
                                ." where %s "
                                ." group by key1 "
                                , self::DB_TABLE_NAME
                                ,$where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_all_key_list(){
        $sql=$this->gen_sql_new("select * from %s "
                                , self::DB_TABLE_NAME
        );
        return $this->main_get_list($sql);
    }
    //@desn:检索全部的不重复的key1
    public function get_key1_list(){
        $sql = $this->gen_sql_new('select distinct(key1) from %s', self::DB_TABLE_NAME);
        return $this->main_get_list($sql);
    }
    //@desn:修改key1的key0
    //@param:$key1 key1的值
    //@param:$key0 key0的值
    public function update_key0($key1,$key0){
        $sql = sprintf(
            "update %s set key0 = '%s' where key1 = '%s'",
            self::DB_TABLE_NAME,
            $key0,
            $key1
        );
        return $this->main_update($sql);
    }
    //@desn:获取key0为公众号的所有渠道名称
    public function get_all_public_number_origin(){
        $where_arr = [
            "key0 = '公众号'"
        ];
        $sql = $this->gen_sql_new(
            'select value from %s where %s',
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

}
