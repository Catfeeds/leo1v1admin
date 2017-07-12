<?php
//自动生成枚举类  不要手工修改
//source  file: config_flow_status.php
namespace  App\Enums;

class Eflow_status extends \App\Enums\Enum_base
{
	static public $field_name = "flow_status"  ;
	static public $name = "状态"  ;
	 static $desc_map= array(
		0 => "无",
		1 => "进行中",
		2 => "通过",
		3 => "不通过",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		3 => "",
	);
	 static $s2v_map= array(
		"no_start" => 0,
		"start" => 1,
		"pass" => 2,
		"no_pass" => 3,
	);
	 static $v2s_map= array(
		 0=>  "no_start",
		 1=>  "start",
		 2=>  "pass",
		 3=>  "no_pass",
	);

	//无
	const V_0=0;
	//无
	const V_NO_START=0;
	//进行中
	const V_1=1;
	//进行中
	const V_START=1;
	//通过
	const V_2=2;
	//通过
	const V_PASS=2;
	//不通过
	const V_3=3;
	//不通过
	const V_NO_PASS=3;

	//无
	const S_0="no_start";
	//无
	const S_NO_START="no_start";
	//进行中
	const S_1="start";
	//进行中
	const S_START="start";
	//通过
	const S_2="pass";
	//通过
	const S_PASS="pass";
	//不通过
	const S_3="no_pass";
	//不通过
	const S_NO_PASS="no_pass";

	static public function check_no_start ($val){
		 return $val == 0;
	}
	static public function check_start ($val){
		 return $val == 1;
	}
	static public function check_pass ($val){
		 return $val == 2;
	}
	static public function check_no_pass ($val){
		 return $val == 3;
	}


};
