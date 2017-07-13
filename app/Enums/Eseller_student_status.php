<?php
//自动生成枚举类  不要手工修改
//source  file: config_seller_student_status.php
namespace  App\Enums;

class Eseller_student_status extends \App\Core\Enum_base
{
	static public $field_name = "seller_student_status"  ;
	static public $name = "seller_student_status"  ;
	 static $desc_map= array(
		0 => "未回访",
		1 => "无效资源",
		2 => "未接通",
		50 => "无效-公海/试听未签不出现",
		60 => "结课无意向",
		61 => "结课待跟进",
		100 => "有效-意向A档",
		101 => "有效-意向B档",
		102 => "有效-意向C档",
		103 => "有效-上课时间待定",
		110 => "有效-预约驳回",
		120 => "有效-课程取消",
		200 => "预约-未排课",
		210 => "已排课-未通知家长",
		220 => "待开课-已通知家长",
		290 => "已试听-待跟进",
		300 => "已试听-未签-A档",
		301 => "已试听-未签-B档",
		302 => "已试听-未签-C档",
		303 => "未试听-未签-A档",
		304 => "未试听-未签-B档",
		305 => "未试听-未签-C档",
		420 => "签约-完成",
	);
	 static $simple_desc_map= array(
		0 => "",
		1 => "",
		2 => "",
		50 => "",
		60 => "",
		61 => "",
		100 => "",
		101 => "",
		102 => "",
		103 => "",
		110 => "",
		120 => "",
		200 => "",
		210 => "",
		220 => "",
		290 => "",
		300 => "",
		301 => "",
		302 => "",
		303 => "",
		304 => "",
		305 => "",
		420 => "",
	);
	 static $s2v_map= array(
		"NO_CALL" => 0,
		"" => 1,
		"NO_CALL_PASS" => 2,
		"NO_PUBLISH" => 50,
		"" => 60,
		"" => 61,
		"" => 100,
		"" => 101,
		"" => 102,
		"" => 103,
		"" => 110,
		"" => 120,
		"" => 200,
		"" => 210,
		"" => 220,
		"" => 290,
		"" => 300,
		"" => 301,
		"" => 302,
		"" => 303,
		"" => 304,
		"" => 305,
		"" => 420,
	);
	 static $v2s_map= array(
		 0=>  "NO_CALL",
		 1=>  "",
		 2=>  "NO_CALL_PASS",
		 50=>  "NO_PUBLISH",
		 60=>  "",
		 61=>  "",
		 100=>  "",
		 101=>  "",
		 102=>  "",
		 103=>  "",
		 110=>  "",
		 120=>  "",
		 200=>  "",
		 210=>  "",
		 220=>  "",
		 290=>  "",
		 300=>  "",
		 301=>  "",
		 302=>  "",
		 303=>  "",
		 304=>  "",
		 305=>  "",
		 420=>  "",
	);

	//未回访
	const V_0=0;
	//未回访
	const V_NO_CALL=0;
	//无效资源
	const V_1=1;
	//未接通
	const V_2=2;
	//未接通
	const V_NO_CALL_PASS=2;
	//无效-公海/试听未签不出现
	const V_50=50;
	//无效-公海/试听未签不出现
	const V_NO_PUBLISH=50;
	//结课无意向
	const V_60=60;
	//结课待跟进
	const V_61=61;
	//有效-意向A档
	const V_100=100;
	//有效-意向B档
	const V_101=101;
	//有效-意向C档
	const V_102=102;
	//有效-上课时间待定
	const V_103=103;
	//有效-预约驳回
	const V_110=110;
	//有效-课程取消
	const V_120=120;
	//预约-未排课
	const V_200=200;
	//已排课-未通知家长
	const V_210=210;
	//待开课-已通知家长
	const V_220=220;
	//已试听-待跟进
	const V_290=290;
	//已试听-未签-A档
	const V_300=300;
	//已试听-未签-B档
	const V_301=301;
	//已试听-未签-C档
	const V_302=302;
	//未试听-未签-A档
	const V_303=303;
	//未试听-未签-B档
	const V_304=304;
	//未试听-未签-C档
	const V_305=305;
	//签约-完成
	const V_420=420;

	//未回访
	const S_0="NO_CALL";
	//未回访
	const S_NO_CALL="NO_CALL";
	//无效资源
	const S_1="";
	//未接通
	const S_2="NO_CALL_PASS";
	//未接通
	const S_NO_CALL_PASS="NO_CALL_PASS";
	//无效-公海/试听未签不出现
	const S_50="NO_PUBLISH";
	//无效-公海/试听未签不出现
	const S_NO_PUBLISH="NO_PUBLISH";
	//结课无意向
	const S_60="";
	//结课待跟进
	const S_61="";
	//有效-意向A档
	const S_100="";
	//有效-意向B档
	const S_101="";
	//有效-意向C档
	const S_102="";
	//有效-上课时间待定
	const S_103="";
	//有效-预约驳回
	const S_110="";
	//有效-课程取消
	const S_120="";
	//预约-未排课
	const S_200="";
	//已排课-未通知家长
	const S_210="";
	//待开课-已通知家长
	const S_220="";
	//已试听-待跟进
	const S_290="";
	//已试听-未签-A档
	const S_300="";
	//已试听-未签-B档
	const S_301="";
	//已试听-未签-C档
	const S_302="";
	//未试听-未签-A档
	const S_303="";
	//未试听-未签-B档
	const S_304="";
	//未试听-未签-C档
	const S_305="";
	//签约-完成
	const S_420="";

	static public function check_NO_CALL ($val){
		 return $val == 0;
	}
	static public function check_NO_CALL_PASS ($val){
		 return $val == 2;
	}
	static public function check_NO_PUBLISH ($val){
		 return $val == 50;
	}


};
