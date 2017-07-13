<?php
//自动生成枚举类  不要手工修改
//source  file: config_employee_level.php
namespace  App\Enums;

class Eemployee_level extends \App\Core\Enum_base
{
	static public $field_name = "employee_level"  ;
	static public $name = "employee_level"  ;
	 static $desc_map= array(
		0 => "未设置",
		1 => "员工",
		2 => "实习生",
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

	//未设置
	const V_0=0;
	//员工
	const V_1=1;
	//实习生
	const V_2=2;

	//未设置
	const S_0="";
	//员工
	const S_1="";
	//实习生
	const S_2="";



};
