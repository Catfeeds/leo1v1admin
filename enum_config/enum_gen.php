#!/usr/bin/php
<?php

function gen_js_enum( $className,$conf_data ) {
    $check_flag_str="";
    $const_str="";
    $const_S_str="";
    $map_desc_str="\tdesc_map : {\n";
    $simple_desc_map_str="\tsimple_desc_map: {\n";
    $s2v_map_str="\ts2v_map:{\n";
    $v2s_map_str="\tv2s_map:{\n";
    $name=@$conf_data["name"];
    unset($conf_data["name"]);

    foreach ($conf_data as $item){
        $v=$item[0];
        $str=trim($item[1]);
        if ($str==""){
            $str=$v ;
        }

        $upper_str=strtoupper($str);
        $desc=$item[2];

        $simple_desc= "";
        if (count($item ) >3 ){
            $simple_desc= $item[3];
        }


        $const_str.="\t\t//$desc\n\t\tV_$upper_str:$v,\n";
        $const_S_str.="\t\t//$desc\n\t\tS_$upper_str:\"$str\",\n";
        $map_desc_str.= "\t\t'$v' : \"$desc\",\n";
        $simple_desc_map_str .= "\t\t'$v' : \"$simple_desc\",\n";
        $s2v_map_str .= "\t\t\"$str\" : $v,\n";
        $v2s_map_str .= "\t\t '$v':  \"$str\",\n";
    }

    $gen_date=date('Y-m-d H:i:s');
    $map_desc_str= substr($map_desc_str,0,-2). "\n\t},";
    $simple_desc_map_str= substr($simple_desc_map_str,0,-2)."\n\t},";
    $s2v_map_str=substr($s2v_map_str,0,-2)."\n\t},";
    $v2s_map_str=substr($v2s_map_str,0,-2)."\n\t},";

    $const_S_str=substr( $const_S_str,0,-2)."\n";
    $data= "
$className : {
$map_desc_str
$simple_desc_map_str
$s2v_map_str
$v2s_map_str

$const_str
$const_S_str

},
";
    return $data;
}

function gen_php_enum( $field_name,$opt_file ,$conf_data ) {

    $className="E".$field_name;
    $check_flag_str="";
    $const_str="";
    $const_S_str="";
    $map_desc_str="\t static \$desc_map= array(\n";
    $simple_desc_map_str="\t static \$simple_desc_map= array(\n";
    $s2v_map_str="\t static \$s2v_map= array(\n";
    $v2s_map_str="\t static \$v2s_map= array(\n";
    $name=@$conf_data["name"];
    unset($conf_data["name"]);
    if(!$name)  {
        $name=  $field_name;
    }

    foreach ($conf_data as $item){
        $v=$item[0];
        $str=trim($item[1]);
        if ( $str &&  !preg_match("/[a-zA-Z][0_9a-zA-Z_]*/" , $str ) ) {
            echo " FIND $str error ";
            exit;
        }


        $upper_str=strtoupper($str);
        $desc=$item[2];

        $simple_desc= "";
        if (count($item ) >3 ){
            $simple_desc= $item[3];
        }


        $const_str.="\t//$desc\n\tconst V_$v=$v;\n";
        $const_S_str.="\t//$desc\n\tconst S_$v=\"$str\";\n";


        if ($upper_str){
            $const_str.="\t//$desc\n\tconst V_$upper_str=$v;\n";
            $const_S_str.="\t//$desc\n\tconst S_$upper_str=\"$str\";\n";
        }



        if ( strlen($str)>0){
            $check_flag_str.="\tstatic public function check_$str (\$val){
\t\t return \$val == $v;
\t}\n";
        }

        $map_desc_str.= "\t\t$v => \"$desc\",\n";
        $simple_desc_map_str .= "\t\t$v => \"$simple_desc\",\n";
        $s2v_map_str .= "\t\t\"$str\" => $v,\n";
        $v2s_map_str .= "\t\t $v=>  \"$str\",\n";
    }

    $gen_date=date('Y-m-d H:i:s');
    $map_desc_str.="\t);";
    $simple_desc_map_str.="\t);";
    $s2v_map_str.="\t);";
    $v2s_map_str.="\t);";

    $data= "<?php
//自动生成枚举类  不要手工修改
//source  file: $opt_file
namespace  App\Enums;

class $className extends \App\Core\Enum_base
{
\tstatic public \$field_name = \"$field_name\"  ;
\tstatic public \$name = \"$name\"  ;
$map_desc_str
$simple_desc_map_str
$s2v_map_str
$v2s_map_str

$const_str
$const_S_str
$check_flag_str

};
";
    $out_file_name= "../app/Enums/$className.php";
    $old_data=file_get_contents($out_file_name);
    if ($old_data != $data) {
        file_put_contents($out_file_name, $data  );
        echo "update ....\n";
    }

}
//gen_php_enum


$js_data= "var g_enum_map={\n";
$dir=dir( __DIR__ );
while (($file = $dir->read()) !== false)
{

    if(preg_match("/^config_(.*)\.php/i" ,  $file,$matches)) {
        $field_name = $matches[1];
        echo "deal $field_name \n ";
        $conf_data  = include($file);
        gen_php_enum($field_name, $file,$conf_data );
        $js_data.=gen_js_enum( $field_name, $conf_data  );
    }

}
$js_data.= "\n};";

file_put_contents( "./enum_map.js", $js_data );
