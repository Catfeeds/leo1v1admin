<?php
//自动生成枚举类  不要手工修改
//source  file: config_book_status.php
namespace  App\Enums;

class Ebook_status extends \App\Enums\Enum_base
{
	static public $field_name = "book_status"  ;
	static public $name = "book_status"  ;
	 static $desc_map= array(
		0 => "未回访",
		1 => "无效资源",
		2 => "未接通",
		3 => "有效-意向A档",
		4 => "有效-意向B档",
		5 => "有效-意向C档",
		11 => "试听-时间待定",
		9 => "试听-预约",
		10 => "试听-已排课",
		12 => "试听-时间确定",
		13 => "试听-无法排课",
		14 => "试听-驳回",
		15 => "试听-课程取消",
		6 => "已试听-待跟进",
		7 => "已试听-未签-A档",
		20 => "已试听-未签-B档",
		21 => "已试听-未签-C档",
		8 => "已试听-已签",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		3 => "",
		4 => "",
		5 => "",
		11 => "",
		9 => "",
		10 => "",
		12 => "",
		13 => "",
		14 => "",
		15 => "",
		6 => "",
		7 => "",
		20 => "",
		21 => "",
		8 => "",
	);
	 static $s2v_map= array(
		"" => 0,
		"" => 1,
		"" => 2,
		"" => 3,
		"" => 4,
		"" => 5,
		"" => 11,
		"test_lesson_report" => 9,
		"test_lesson_set_lesson" => 10,
		"" => 12,
		"" => 13,
		"" => 14,
		"" => 15,
		"" => 6,
		"" => 7,
		"" => 20,
		"" => 21,
		"" => 8,
	);
	 static $v2s_map= array(
		 0=>  "",
		 1=>  "",
		 2=>  "",
		 3=>  "",
		 4=>  "",
		 5=>  "",
		 11=>  "",
		 9=>  "test_lesson_report",
		 10=>  "test_lesson_set_lesson",
		 12=>  "",
		 13=>  "",
		 14=>  "",
		 15=>  "",
		 6=>  "",
		 7=>  "",
		 20=>  "",
		 21=>  "",
		 8=>  "",
	);

	//未回访
	const V_0=0;
	//无效资源
	const V_1=1;
	//未接通
	const V_2=2;
	//有效-意向A档
	const V_3=3;
	//有效-意向B档
	const V_4=4;
	//有效-意向C档
	const V_5=5;
	//试听-时间待定
	const V_11=11;
	//试听-预约
	const V_9=9;
	//试听-预约
	const V_TEST_LESSON_REPORT=9;
	//试听-已排课
	const V_10=10;
	//试听-已排课
	const V_TEST_LESSON_SET_LESSON=10;
	//试听-时间确定
	const V_12=12;
	//试听-无法排课
	const V_13=13;
	//试听-驳回
	const V_14=14;
	//试听-课程取消
	const V_15=15;
	//已试听-待跟进
	const V_6=6;
	//已试听-未签-A档
	const V_7=7;
	//已试听-未签-B档
	const V_20=20;
	//已试听-未签-C档
	const V_21=21;
	//已试听-已签
	const V_8=8;

	//未回访
	const S_0="";
	//无效资源
	const S_1="";
	//未接通
	const S_2="";
	//有效-意向A档
	const S_3="";
	//有效-意向B档
	const S_4="";
	//有效-意向C档
	const S_5="";
	//试听-时间待定
	const S_11="";
	//试听-预约
	const S_9="test_lesson_report";
	//试听-预约
	const S_TEST_LESSON_REPORT="test_lesson_report";
	//试听-已排课
	const S_10="test_lesson_set_lesson";
	//试听-已排课
	const S_TEST_LESSON_SET_LESSON="test_lesson_set_lesson";
	//试听-时间确定
	const S_12="";
	//试听-无法排课
	const S_13="";
	//试听-驳回
	const S_14="";
	//试听-课程取消
	const S_15="";
	//已试听-待跟进
	const S_6="";
	//已试听-未签-A档
	const S_7="";
	//已试听-未签-B档
	const S_20="";
	//已试听-未签-C档
	const S_21="";
	//已试听-已签
	const S_8="";

	static public function check_test_lesson_report ($val){
		 return $val == 9;
	}
	static public function check_test_lesson_set_lesson ($val){
		 return $val == 10;
	}


};
