<?php
//自动生成枚举类  不要手工修改
//source  file: config_grab_status.php
namespace  App\Enums;

class Egrab_status extends \App\Core\Enum_base
{
	static public $field_name = "grab_status"  ;
	static public $name = "grab_status"  ;
	 static $desc_map= array(
		0 => "未入库",
		1 => "已入库",
		2 => "已排课",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 2,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 2=>  "",
	);

	//未入库
	const V_0=0;
	//已入库
	const V_1=1;
	//已排课
	const V_2=2;

	//未入库
	const S_0="";
	//已入库
	const S_1="";
	//已排课
	const S_2="";



};
