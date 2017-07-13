<?php
//自动生成枚举类  不要手工修改
//source  file: config_subject.php
namespace  App\Enums;

class Esubject extends \App\Core\Enum_base
{
	static public $field_name = "subject"  ;
	static public $name = "科目"  ;
	 static $desc_map= array(
		0 => "未设置",
		1 => "语文",
		2 => "数学",
		3 => "英语",
		4 => "化学",
		5 => "物理",
		6 => "生物",
		7 => "政治",
		8 => "历史",
		9 => "地理",
		10 => "科学",
		11 => "教育学",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		3 => "",
		4 => "",
		5 => "",
		6 => "",
		7 => "",
		8 => "",
		9 => "",
		10 => "",
		11 => "",
	);
	 static $s2v_map= array(
		"未设置" => 0,
		"语文" => 1,
		"数学" => 2,
		"英语" => 3,
		"化学" => 4,
		"物理" => 5,
		"生物" => 6,
		"政治" => 7,
		"历史" => 8,
		"地理" => 9,
		"科学" => 10,
		"教育学" => 11,
	);
	 static $v2s_map= array(
		 0=>  "未设置",
		 1=>  "语文",
		 2=>  "数学",
		 3=>  "英语",
		 4=>  "化学",
		 5=>  "物理",
		 6=>  "生物",
		 7=>  "政治",
		 8=>  "历史",
		 9=>  "地理",
		 10=>  "科学",
		 11=>  "教育学",
	);

	//未设置
	const V_0=0;
	//未设置
	const V_未设置=0;
	//语文
	const V_1=1;
	//语文
	const V_语文=1;
	//数学
	const V_2=2;
	//数学
	const V_数学=2;
	//英语
	const V_3=3;
	//英语
	const V_英语=3;
	//化学
	const V_4=4;
	//化学
	const V_化学=4;
	//物理
	const V_5=5;
	//物理
	const V_物理=5;
	//生物
	const V_6=6;
	//生物
	const V_生物=6;
	//政治
	const V_7=7;
	//政治
	const V_政治=7;
	//历史
	const V_8=8;
	//历史
	const V_历史=8;
	//地理
	const V_9=9;
	//地理
	const V_地理=9;
	//科学
	const V_10=10;
	//科学
	const V_科学=10;
	//教育学
	const V_11=11;
	//教育学
	const V_教育学=11;

	//未设置
	const S_0="未设置";
	//未设置
	const S_未设置="未设置";
	//语文
	const S_1="语文";
	//语文
	const S_语文="语文";
	//数学
	const S_2="数学";
	//数学
	const S_数学="数学";
	//英语
	const S_3="英语";
	//英语
	const S_英语="英语";
	//化学
	const S_4="化学";
	//化学
	const S_化学="化学";
	//物理
	const S_5="物理";
	//物理
	const S_物理="物理";
	//生物
	const S_6="生物";
	//生物
	const S_生物="生物";
	//政治
	const S_7="政治";
	//政治
	const S_政治="政治";
	//历史
	const S_8="历史";
	//历史
	const S_历史="历史";
	//地理
	const S_9="地理";
	//地理
	const S_地理="地理";
	//科学
	const S_10="科学";
	//科学
	const S_科学="科学";
	//教育学
	const S_11="教育学";
	//教育学
	const S_教育学="教育学";

	static public function check_未设置 ($val){
		 return $val == 0;
	}
	static public function check_语文 ($val){
		 return $val == 1;
	}
	static public function check_数学 ($val){
		 return $val == 2;
	}
	static public function check_英语 ($val){
		 return $val == 3;
	}
	static public function check_化学 ($val){
		 return $val == 4;
	}
	static public function check_物理 ($val){
		 return $val == 5;
	}
	static public function check_生物 ($val){
		 return $val == 6;
	}
	static public function check_政治 ($val){
		 return $val == 7;
	}
	static public function check_历史 ($val){
		 return $val == 8;
	}
	static public function check_地理 ($val){
		 return $val == 9;
	}
	static public function check_科学 ($val){
		 return $val == 10;
	}
	static public function check_教育学 ($val){
		 return $val == 11;
	}


};
