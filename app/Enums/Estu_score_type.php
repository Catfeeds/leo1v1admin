<?php
//自动生成枚举类  不要手工修改
//source  file: config_stu_score_type.php
namespace  App\Enums;

class Estu_score_type extends \App\Core\Enum_base
{
	static public $field_name = "stu_score_type"  ;
	static public $name = "测验分类"  ;
	 static $desc_map= array(
		1 => "平时",
		2 => "月考",
		3 => "期中考",
		4 => "期末考",
	);
	 static $simple_desc_map= array(
		1 => "",
		2 => "",
		3 => "",
		4 => "",
	);
	 static $s2v_map= array(
		"common" => 1,
		"" => 2,
		"" => 3,
		"" => 4,
	);
	 static $v2s_map= array(
		 1=>  "common",
		 2=>  "",
		 3=>  "",
		 4=>  "",
	);

	//平时
	const V_1=1;
	//平时
	const V_COMMON=1;
	//月考
	const V_2=2;
	//期中考
	const V_3=3;
	//期末考
	const V_4=4;

	//平时
	const S_1="common";
	//平时
	const S_COMMON="common";
	//月考
	const S_2="";
	//期中考
	const S_3="";
	//期末考
	const S_4="";

	static public function check_common ($val){
		 return $val == 1;
	}


};
