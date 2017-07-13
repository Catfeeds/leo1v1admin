<?php
//自动生成枚举类  不要手工修改
//source  file: config_level.php
namespace  App\Enums;

class Elevel extends \App\Core\Enum_base
{
	static public $field_name = "level"  ;
	static public $name = "level"  ;
	 static $desc_map= array(
		0 => "C",
		1 => "B",
		2 => "A",
		3 => "A+",
		4 => "S",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		3 => "",
		4 => "",
	);
	 static $s2v_map= array(
		"初级" => 0,
		"中级" => 1,
		"高级" => 2,
		"金牌" => 3,
		"" => 4,
	);
	 static $v2s_map= array(
		 0=>  "初级",
		 1=>  "中级",
		 2=>  "高级",
		 3=>  "金牌",
		 4=>  "",
	);

	//C
	const V_0=0;
	//C
	const V_初级=0;
	//B
	const V_1=1;
	//B
	const V_中级=1;
	//A
	const V_2=2;
	//A
	const V_高级=2;
	//A+
	const V_3=3;
	//A+
	const V_金牌=3;
	//S
	const V_4=4;

	//C
	const S_0="初级";
	//C
	const S_初级="初级";
	//B
	const S_1="中级";
	//B
	const S_中级="中级";
	//A
	const S_2="高级";
	//A
	const S_高级="高级";
	//A+
	const S_3="金牌";
	//A+
	const S_金牌="金牌";
	//S
	const S_4="";

	static public function check_初级 ($val){
		 return $val == 0;
	}
	static public function check_中级 ($val){
		 return $val == 1;
	}
	static public function check_高级 ($val){
		 return $val == 2;
	}
	static public function check_金牌 ($val){
		 return $val == 3;
	}


};
