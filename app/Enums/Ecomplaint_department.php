<?php
//自动生成枚举类  不要手工修改
//source  file: config_complaint_department.php
namespace  App\Enums;

class Ecomplaint_department extends \App\Core\Enum_base
{
	static public $field_name = "complaint_department"  ;
	static public $name = "complaint_department"  ;
	 static $desc_map= array(
		0 => "全部",
		1 => "教务部",
		2 => "咨询部",
		3 => "教学部",
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
	//教务部
	const V_1=1;
	//咨询部
	const V_2=2;
	//教学部
	const V_3=3;

	//全部
	const S_0="";
	//教务部
	const S_1="";
	//咨询部
	const S_2="";
	//教学部
	const S_3="";



};
