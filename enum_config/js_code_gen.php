#!/usr/bin/php
<?php
$opt_file=$argv[1];
$arr=explode(".", $opt_file, 2 );
$className=preg_replace("/^config_/i" , "", $arr[0]); 
$conf_data=include($opt_file);
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
echo $data;

#file_put_contents("../$className.class.php", $data  );