<?php
namespace  App\Core;

class Enum_base {
    static function get_desc( $val){
        if (isset(static::$desc_map[$val])) {
            return   static::$desc_map[$val];
        }else{
            return $val;
        }
    }
    static function set_item_field_list(&$item, $field_list  ) {

        foreach ($field_list as $key=> $field ) {
            if($key !== '' && $field !== ''){
                if( is_string($key) ){
                    $class_name= "\\App\\Enums\\E".$key;
                }else {
                    $class_name= "\\App\\Enums\\E".$field;
                }
                $class_name::set_item_value_str($item, $field  );
            }
        }
    }

    static function get_color_desc($val) {
        $color= @static::$simple_desc_map[$val];
        $desc=static::get_desc($val);
        return "<font color=\"$color\">$desc</font>";
    }

    static function get_simple_desc( $val){
        $desc="";
        if (isset  (  static::$simple_desc_map[$val]))  {
            $desc=    static::$simple_desc_map[$val];
        }
        if(!$desc){
            return static::get_desc($val);
        }else{
            return $desc;
        };
    }

    static public function set_item_value_color_str(&$item,$key ="" ){
        if(!$key){
            $key=static::$field_name;
        }

        $item[$key."_str"]= static::get_color_desc(@$item[$key]);
    }


    static public function set_item_value_str(&$item,$key ="" ){
        if(!$key){
            $key=static::$field_name;
        }

        $item[$key."_str"]= static::get_desc(@$item[$key]);
    }

    static public function  set_item_value_simple_str(&$item,$key="" ){
        if (!$key){
            $key=static::$field_name;
        }
        $item[$key."_str"]= static::get_simple_desc($item[$key]);
    }

    static public function  s2v($str ){
        if (isset(static::$s2v_map[$str])){
            return static::$s2v_map[$str] ;
        }else{
            return 0;
        }
    }

    static public function namelist2idlist  ($name_list) {
        $id_list=[];
        foreach( explode(",", $name_list )  as $str ) {
            $id_list[] = self::s2v($str);
        }
        return join(",",$id_list);
    }

    static public function idlist2namelist  ($id_list) {
        $name_list=[];
        foreach( explode(",", $id_list )  as $str ) {
            $name_list[] = self::get_desc($str);
        }
        return join(",",$name_list);
    }

    static public function  v2s($v){
        if (isset( static::$v2s_map[$v])){
            return   static::$v2s_map[$v];
        }else{
            return $v;
        }
    }

    static public function get_specify_select($array=[]){
        $map_array = static::$desc_map;
        if(!empty($array)){
            $array     = array_flip($array);
            $map_array = array_intersect_key($map_array,$array);
        }
        return $map_array;
    }
}
