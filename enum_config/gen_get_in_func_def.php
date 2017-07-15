<?php
$file_arr =file( "php://stdin");
$str="";
foreach ($file_arr as $file_name ) {
    preg_match("/config_(.*)\\.php/" , $file_name,$arr);
    $str.="  * @method integer get_in_e_{$arr[1]}( \$def_value=0 , \$filed_name=\"\" );\n"
        ."  * @method integer get_in_el_{$arr[1]}( \$def_value=[-1] , \$filed_name=\"\" );\n";
}
put_control_define($str);


function  put_control_define( $php_define_str ) {
    echo "00000\n";
    $tmp_file_name= './ControllerEnums.temp';
    $file_name= '../app/Http/Controllers/ControllerEnums.php';
    $control_data=file_get_contents($tmp_file_name);

    $control_new_data=preg_replace('/__MODEL_DEFINE__/',$php_define_str,$control_data);

    save_file_check_data($file_name, $control_new_data);


}


function save_file_check_data( $file_name, $data ) {
    if (file_exists($file_name) ) {
        if ( file_get_contents($file_name ) == $data ) {
            #echo " $file_name no need update \n ";
            return;
        }
    }
    echo " $file_name update \n ";
    file_put_contents($file_name, $data);
}
