<?php
//自动生成枚举类  不要手工修改
//source  file: config_revisit_type.php
namespace  App\Enums;

class Erevisit_type extends \App\Enums\Enum_base
{
	static public $field_name = "revisit_type"  ;
	static public $name = "revisit_type"  ;
	 static $desc_map= array(
		0 => "学情回访",
		1 => "首次回访",
		2 => "月度回访",
		3 => "其他回访",
		10 => "系统",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		3 => "",
		10 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 10,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 10=>  "",
	);

	//学情回访
	const V_0=0;
	//首次回访
	const V_1=1;
	//月度回访
	const V_2=2;
	//其他回访
	const V_3=3;
	//系统
	const V_10=10;

	//学情回访
	const S_0="";
	//首次回访
	const S_1="";
	//月度回访
	const S_2="";
	//其他回访
	const S_3="";
	//系统
	const S_10="";



};
