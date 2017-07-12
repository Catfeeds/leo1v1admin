<?php
namespace App\Models;
use \App\Enums as E;
class t_order_refund_confirm_config extends \App\Models\Zgen\z_t_order_refund_confirm_config
{
    public function __construct()
    {
        parent::__construct();
    }

    public function get_key1_list() {
        $where_arr=[
            "key2 = 0",
            "key3 = 0",
            "key4 = 0",
        ];
        $sql= $this->gen_sql_new(
            "select  key1 ,  value "
            . " from %s  where  %s ",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_key2_list($key1) {
        if ($key1== -1) {
            return [];
        }
        $where_arr=[
            ["key1=%u",$key1 ],
            "key2<>0",
            "key3=0",
            "key4=0",
        ];
        $sql= $this->gen_sql_new(
            "select  key2 ,value from %s where %s ",
            self::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_list($sql);
    }


    public function get_key3_list($key1,$key2) {
        if ($key1== -1) {
            return [];
        }
        $where_arr=[
            ["key1=%u",$key1 ],
            "key2=$key2",
            "key3<>0",
            "key4=0",
        ];
        $sql= $this->gen_sql_new(
            "select  key3 ,value from %s where %s ",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }


    public function set_refund_info ($refund_arr) {
        $this->row_insert($refund_arr);
    }

    public function get_next_key_value( $key1, $key2, $key3){
        if ($key1 == -1) {
            $sql= $this->gen_sql_new(
                "select  max(key1) from %s  ",
                self::DB_TABLE_NAME
            );
        } else if ($key2==-1) {

            $where_arr = [
                ["key1=%u",$key1 ],
                "key3=0",
                "key4=0",
            ];
            $sql= $this->gen_sql_new(
                "select  max(key2) from %s where %s ",
                self::DB_TABLE_NAME,
                $where_arr
            );

        } else if ( $key3==-1 ) {
            $where_arr = [
                ["key1=%u",$key1 ],
                ["key2=%u",$key2 ],
                "key4=0",
            ];
            $sql= $this->gen_sql_new(
                "select  max(key3) from %s where %s ",
                self::DB_TABLE_NAME,
                $where_arr
            );
        }else {

            $where_arr = [
                ["key1=%u",$key1 ],
                ["key2=%u",$key2 ],
                ["key3=%u",$key3 ],
            ];
            $sql= $this->gen_sql_new(
                "select  max(key4) from %s where %s ",
                self::DB_TABLE_NAME,
                $where_arr
            );

        }
        return $this->main_get_value($sql) +1;
    }

    public function get_refund_list( $key1,$key2, $key3){
        $where_arr=[];
        $this->where_arr_add_int_field($where_arr,"key1",$key1);
        $this->where_arr_add_int_field($where_arr,"key2",$key2);
        $this->where_arr_add_int_field($where_arr,"key3",$key3);
        $sql = $this->gen_sql_new(
            "select * from %s where %s order by key1, key2, key3, key4",
            self::DB_TABLE_NAME,
            $where_arr
        );
        return $this->main_get_list($sql);
    }

    public function get_refund_list_and_map( $key1,$key2, $key3) {

        $refund_info = $this->t_order_refund_confirm_config->get_refund_list( $key1, $key2, $key3 );

        $map=[];
        foreach ($refund_info as &$item){
            $init_item=["name"=> $item["value"] , "list"=>[]];
            $key1=$item["key1"];
            $key2=$item["key2"];
            $key3=$item["key3"];
            $key4=$item["key4"];
            if ($key2==0) {
                $map[$key1]=$init_item;
            } else if ($key3==0){
                $map[$key1]["list"][$key2]=$init_item;
            } else if ($key4==0){
                $map[$key1]["list"][$key2]["list"][$key3]=$init_item;
            }else {
                $map[$key1]["list"][$key2]["list"][$key3]["list"][$key4]=$init_item;
            }

            //string
            if ($key2==0) {
                $item ["key1_str"]= $map[$key1]["name"]  ;
            } else if ($key3==0){
                $item ["key2_str"]= $map[$key1]["list"][$key2]["name"];
            } else if ($key4==0){
                $item ["key3_str"]= $map[$key1]["list"][$key2]["list"][$key3]["name"];
            }else {
                $item ["key4_str"]= $map[$key1]["list"][$key2]["list"][$key3]["list"][$key4]["name"];
            }
        }

        return array($refund_info, $map);

    }

    public function get_all_config(){
        $configs = [];

        $where_arr_dep = [
            ["key1<>%u", 0 ],
            ["key2=%u" , 0 ],
            ["key3=%u" , 0 ],
            ["key4=%u" , 0 ],
        ];
        $sql_dep = $this->gen_sql_new(
            "select id, value from %s where %s ",
            self::DB_TABLE_NAME,
            $where_arr_dep
        );
        $configs['deparment'] = $this->main_get_list($sql_dep);

        $where_arr_key2 = [
            ["key1<>%u", 0 ],
            ["key2<>%u" , 0 ],
            ["key3=%u" , 0 ],
            ["key4=%u" , 0 ],
        ];
        $sql_key2 = $this->gen_sql_new(
            "select id, value from %s where %s ",
            self::DB_TABLE_NAME,
            $where_arr_key2
        );
        $configs['key2'] = $this->main_get_list($sql_key2);

        $where_arr_key3 = [
            ["key1<>%u", 0 ],
            ["key2<>%u" , 0 ],
            ["key3<>%u" , 0 ],
            ["key4=%u" , 0 ],
        ];
        $sql_key3 = $this->gen_sql_new(
            "select id, value from %s where %s ",
            self::DB_TABLE_NAME,
            $where_arr_key3
        );
        $configs['key3'] = $this->main_get_list($sql_key3);

        $where_arr_key4 = [
            ["key1<>%u" , 0 ],
            ["key2<>%u" , 0 ],
            ["key3<>%u" , 0 ],
            ["key4<>%u" , 0 ],
        ];
        $sql_key4 = $this->gen_sql_new(
            "select id, value from %s where %s ",
            self::DB_TABLE_NAME,
            $where_arr_key4
        );
        $configs['key4'] = $this->main_get_list($sql_key4);

        return $configs;
    }
    public function get_id_by_keys( $key1, $key2, $key3, $key4 ) {
        $where_arr=[
            ["key1=%u",$key1] ,
            ["key2=%u",$key2] ,
            ["key3=%u",$key3] ,
            ["key4=%u",$key4] ,
        ];
        $sql = $this->gen_sql_new("select id from %s where %s", self::DB_TABLE_NAME, $where_arr);
        return  $this->main_get_value($sql);
    }

    public function get_refundid_by_configid($id){
        $where_arr = [
            ["id = %u",$id]
        ];
        $sql = $this->gen_sql_new(
            "select key1, key2, key3, key4 from %s where %s",
            self::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_list($sql);
    }

    public function get_refund_str_by_keys ($keys) {
        foreach ($keys as $item) {
            $key1 = $item['key1'];
            $key2 = $item['key2'];
            $key3 = $item['key3'];
            $key4 = $item['key4'];
        }

        if ($key1 != 0) {
            $where_arr_key1 = [
                ["key1=%u",$key1],
                ["key2=%u",0],
                ["key3=%u",0],
                ["key4=%u",0],
            ];
            $sql_key1 = $this->gen_sql_new(
                "select value from %s where %s",
                self::DB_TABLE_NAME,
                $where_arr_key1
            );
            $key1_str = $this->main_get_value($sql_key1);
        }
        if($key2 != 0){
            // dd($key2);
            $where_arr_key2 = [
                ["key1=%u",$key1],
                ["key2=%u",$key2],
                ["key3=%u",0],
                ["key4=%u",0],
            ];
            $sql_key2 = $this->gen_sql_new(
                "select value from %s where %s",
                self::DB_TABLE_NAME,
                $where_arr_key2
            );
            $key2_str = $this->main_get_value($sql_key2);

        }
        if($key3 != 0){
            $where_arr_key3 = [
                ["key1=%u",$key1],
                ["key2=%u",$key2],
                ["key3=%u",$key3],
                ["key4=%u",0],
            ];
            $sql_key3 = $this->gen_sql_new(
                "select value from %s where %s",
                self::DB_TABLE_NAME,
                $where_arr_key3
            );
            $key3_str = $this->main_get_value($sql_key3);
        }
        if ($key4 != 0) {
            $where_arr_key4 = [
                ["key1=%u",$key1],
                ["key2=%u",$key2],
                ["key3=%u",$key3],
                ["key4=%u",$key4],
            ];
            $sql_key4 = $this->gen_sql_new(
                "select value from %s where %s",
                self::DB_TABLE_NAME,
                $where_arr_key4
            );
            $key4_str = $this->main_get_value($sql_key4);
        }

        return array(
            "key1" => $key1,
            "key1_str" => $key1_str,
            "key2" => $key2,
            "key2_str" => $key2_str,
            "key3" => $key3,
            "key3_str" => $key3_str,
            "key4" => $key4,
            "key4_str" => $key4_str,
        );
    }


    public function get_department_name_by_configid ($configid) {
        $where_arr = [
            ["id = %u", $configid]
        ];

        $sql = $this->gen_sql_new(
            "select key1 from %s where %s",
            self::DB_TABLE_NAME,
            $where_arr
        );

        $key1 = $this->main_get_value($sql);

        $where_arr_name = [
            ["key1 = %u",$key1],
            ["key2 = %u", 0],
            ["key3 = %u", 0],
            ["key4 = %u", 0],
        ];

        $sql_name = $this->gen_sql_new(
            "select value from %s where %s",
            self::DB_TABLE_NAME,
            $where_arr_name
        );

        return $name = $this->main_get_value($sql_name);
    }

    public function get_all_key1_value () {
        $where_arr = [
            ["key1 <> %u" , 0],
            ["key2 = %u" , 0],
            ["key3 = %u" , 0],
            ["key4 = %u" , 0],
        ];

        $sql = $this->gen_sql_new(
            "select value from %s where %s ",
            self::DB_TABLE_NAME,
            $where_arr
        );

        return $this->main_get_list($sql);
    }



}
