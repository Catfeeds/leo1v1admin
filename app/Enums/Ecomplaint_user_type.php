<?php
//自动生成枚举类  不要手工修改
//source  file: config_complaint_user_type.php
namespace  App\Enums;

class Ecomplaint_user_type extends \App\Core\Enum_base
{
	static public $field_name = "complaint_user_type"  ;
	static public $name = "complaint_user_type"  ;
	 static $desc_map= array(
		0 => "全部",
		1 => "家长",
		2 => "老师",
		3 => "QC",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		3 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 2,
		"" => 3,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 2=>  "",
		 3=>  "",
	);

	//全部
	const V_0=0;
	//家长
	const V_1=1;
	//老师
	const V_2=2;
	//QC
	const V_3=3;

	//全部
	const S_0="";
	//家长
	const S_1="";
	//老师
	const S_2="";
	//QC
	const S_3="";



};
