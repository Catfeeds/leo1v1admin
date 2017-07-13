#!/usr/bin/php
<?php
$opt_file=$argv[1];
$arr=explode(".", $opt_file, 2 );

$field_name=preg_replace("/^config_/i" , "", $arr[0]);
$className="E".$field_name;
$conf_data=include($opt_file);
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

class $className extends \App\Enums\Enum_base
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
