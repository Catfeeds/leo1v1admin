<?php
//自动生成枚举类  不要手工修改
//source  file: config_qingjia_type.php
namespace  App\Enums;

class Eqingjia_type extends \App\Enums\Enum_base
{
	static public $field_name = "qingjia_type"  ;
	static public $name = "请假类型"  ;
	 static $desc_map= array(
		1 => "年假",
		2 => "病假",
		3 => "事假",
	);
	 static $simple_desc_map= array(
		1 => "",
		2 => "",
		3 => "",
	);
	 static $s2v_map= array(
		"year" => 1,
		"sick" => 2,
		"absence" => 3,
	);
	 static $v2s_map= array(
		 1=>  "year",
		 2=>  "sick",
		 3=>  "absence",
	);

	//年假
	const V_1=1;
	//年假
	const V_YEAR=1;
	//病假
	const V_2=2;
	//病假
	const V_SICK=2;
	//事假
	const V_3=3;
	//事假
	const V_ABSENCE=3;

	//年假
	const S_1="year";
	//年假
	const S_YEAR="year";
	//病假
	const S_2="sick";
	//病假
	const S_SICK="sick";
	//事假
	const S_3="absence";
	//事假
	const S_ABSENCE="absence";

	static public function check_year ($val){
		 return $val == 1;
	}
	static public function check_sick ($val){
		 return $val == 2;
	}
	static public function check_absence ($val){
		 return $val == 3;
	}


};
