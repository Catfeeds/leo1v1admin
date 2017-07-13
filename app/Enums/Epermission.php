<?php
//自动生成枚举类  不要手工修改
//source  file: config_permission.php
namespace  App\Enums;

class Epermission extends \App\Core\Enum_base
{
	static public $field_name = "permission"  ;
	static public $name = "permission"  ;
	 static $desc_map= array(
		0 => "无权限",
		1 => "有权限",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
	);

	//无权限
	const V_0=0;
	//有权限
	const V_1=1;

	//无权限
	const S_0="";
	//有权限
	const S_1="";



};
