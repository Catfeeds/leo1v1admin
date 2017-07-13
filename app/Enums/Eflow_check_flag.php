<?php
//自动生成枚举类  不要手工修改
//source  file: config_flow_check_flag.php
namespace  App\Enums;

class Eflow_check_flag extends \App\Core\Enum_base
{
	static public $field_name = "flow_check_flag"  ;
	static public $name = "审核"  ;
	 static $desc_map= array(
		0 => "未审核",
		1 => "通过",
		2 => "不通过",
		3 => "驳回",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		3 => "",
	);
	 static $s2v_map= array(
		"no_check" => 0,
		"pass" => 1,
		"no_pass" => 2,
		"return_back" => 3,
	);
	 static $v2s_map= array(
		 0=>  "no_check",
		 1=>  "pass",
		 2=>  "no_pass",
		 3=>  "return_back",
	);

	//未审核
	const V_0=0;
	//未审核
	const V_NO_CHECK=0;
	//通过
	const V_1=1;
	//通过
	const V_PASS=1;
	//不通过
	const V_2=2;
	//不通过
	const V_NO_PASS=2;
	//驳回
	const V_3=3;
	//驳回
	const V_RETURN_BACK=3;

	//未审核
	const S_0="no_check";
	//未审核
	const S_NO_CHECK="no_check";
	//通过
	const S_1="pass";
	//通过
	const S_PASS="pass";
	//不通过
	const S_2="no_pass";
	//不通过
	const S_NO_PASS="no_pass";
	//驳回
	const S_3="return_back";
	//驳回
	const S_RETURN_BACK="return_back";

	static public function check_no_check ($val){
		 return $val == 0;
	}
	static public function check_pass ($val){
		 return $val == 1;
	}
	static public function check_no_pass ($val){
		 return $val == 2;
	}
	static public function check_return_back ($val){
		 return $val == 3;
	}


};
