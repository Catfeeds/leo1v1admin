#!/usr/bin/php
<?php
chdir( dirname( $argv[0] ) );

function exec_cmd($cmd){
  $fp = popen("$cmd", "r");
  $ret="";
  while(!feof($fp)) {
    $ret.=fread($fp, 1024);
  }
  fclose($fp);
  return $ret;
}
$cmd="find ./src/views -name \"*.ts\"  ! -path \"./src/views/page.d.ts*\" -a  ! -path \"./src/views/layout/*\" | sed -e 's/............//' -e 's/...$//'";
$ret=exec_cmd ($cmd );
$files=preg_split("/\n/",  $ret );
$route_str="";
foreach ($files as $file ){
  if ($file){
    $route_str.= "  { path: '/$file', component: _import_ts('$file')  },\n";
  }
}
$tmp_data=file_get_contents("./src/router/index.js.tmp");
$ret_str=str_replace("__ROUTE_LIST__",  $route_str , $tmp_data );


$obj_file="./src/router/index.js";
$old_data=@file_get_contents($obj_file);
if ($old_data == $ret_str  ) {
  echo "没有更新\n";
}else{
  file_put_contents($obj_file, $ret_str);
  echo "更新完毕\n";
}
