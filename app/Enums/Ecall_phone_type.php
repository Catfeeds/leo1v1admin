<?php
//自动生成枚举类  不要手工修改
//source  file: config_call_phone_type.php
namespace  App\Enums;

class Ecall_phone_type extends \App\Core\Enum_base
{
	static public $field_name = "call_phone_type"  ;
	static public $name = "拨打电话类型"  ;
	 static $desc_map= array(
		0 => "tq",
		1 => "云通讯",
		2 => "天润",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
	);
	 static $s2v_map= array(
		"tq" => 0,
		"ytx" => 1,
		"tl" => 2,
	);
	 static $v2s_map= array(
		 0=>  "tq",
		 1=>  "ytx",
		 2=>  "tl",
	);

	//tq
	const V_0=0;
	//tq
	const V_TQ=0;
	//云通讯
	const V_1=1;
	//云通讯
	const V_YTX=1;
	//天润
	const V_2=2;
	//天润
	const V_TL=2;

	//tq
	const S_0="tq";
	//tq
	const S_TQ="tq";
	//云通讯
	const S_1="ytx";
	//云通讯
	const S_YTX="ytx";
	//天润
	const S_2="tl";
	//天润
	const S_TL="tl";

	static public function check_tq ($val){
		 return $val == 0;
	}
	static public function check_ytx ($val){
		 return $val == 1;
	}
	static public function check_tl ($val){
		 return $val == 2;
	}


};
