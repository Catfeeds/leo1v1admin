<?php
//自动生成枚举类  不要手工修改
//source  file: config_complaint_type.php
namespace  App\Enums;

class Ecomplaint_type extends \App\Core\Enum_base
{
	static public $field_name = "complaint_type"  ;
	static public $name = "complaint_type"  ;
	 static $desc_map= array(
		0 => "全部",
		1 => "常规投诉",
		2 => "课程投诉",
		3 => "薪资申诉",
		4 => "QC投诉",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		3 => "",
		4 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 4,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 4=>  "",
	);

	//全部
	const V_0=0;
	//常规投诉
	const V_1=1;
	//课程投诉
	const V_2=2;
	//薪资申诉
	const V_3=3;
	//QC投诉
	const V_4=4;

	//全部
	const S_0="";
	//常规投诉
	const S_1="";
	//课程投诉
	const S_2="";
	//薪资申诉
	const S_3="";
	//QC投诉
	const S_4="";



};
