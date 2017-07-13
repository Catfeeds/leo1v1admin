<?php
//自动生成枚举类  不要手工修改
//source  file: config_opt_type.php
namespace  App\Enums;

class Eopt_type extends \App\Core\Enum_base
{
	static public $field_name = "opt_type"  ;
	static public $name = "opt_type"  ;
	 static $desc_map= array(
		0 => "get",
		1 => "sel",
		2 => "del",
		3 => "add",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		3 => "",
	);
	 static $s2v_map= array(
		"get" => 0,
		"set" => 1,
		"del" => 2,
		"add" => 3,
	);
	 static $v2s_map= array(
		 0=>  "get",
		 1=>  "set",
		 2=>  "del",
		 3=>  "add",
	);

	//get
	const V_0=0;
	//get
	const V_GET=0;
	//sel
	const V_1=1;
	//sel
	const V_SET=1;
	//del
	const V_2=2;
	//del
	const V_DEL=2;
	//add
	const V_3=3;
	//add
	const V_ADD=3;

	//get
	const S_0="get";
	//get
	const S_GET="get";
	//sel
	const S_1="set";
	//sel
	const S_SET="set";
	//del
	const S_2="del";
	//del
	const S_DEL="del";
	//add
	const S_3="add";
	//add
	const S_ADD="add";

	static public function check_get ($val){
		 return $val == 0;
	}
	static public function check_set ($val){
		 return $val == 1;
	}
	static public function check_del ($val){
		 return $val == 2;
	}
	static public function check_add ($val){
		 return $val == 3;
	}


};
