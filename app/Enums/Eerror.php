<?php
//自动生成枚举类  不要手工修改
//source  file: config_error.php
namespace  App\Enums;

class Eerror extends \App\Core\Enum_base
{
	static public $field_name = "error"  ;
	static public $name = "error"  ;
	 static $desc_map= array(
		0 => "成功",
		7000 => "系统错误",
		7001 => "验证码错误",
		7002 => "用户名密码错误",
		7003 => "没有权限",
		7010 => "没有登录",
		7020 => "用户不操作",
		7030 => "",
		7031 => "",
		7101 => "",
		7201 => "非法参数",
		7202 => "",
		7301 => "",
		7401 => "",
		7402 => "",
	);
	 static $simple_desc_map= array(
		0 => "",
		7000 => "",
		7001 => "",
		7002 => "",
		7003 => "",
		7010 => "",
		7020 => "",
		7030 => "",
		7031 => "",
		7101 => "",
		7201 => "",
		7202 => "",
		7301 => "",
		7401 => "",
		7402 => "",
	);
	 static $s2v_map= array(
		"success" => 0,
		"system_err" => 7000,
		"wrong_verify_code" => 7001,
		"wrong_account_passwd" => 7002,
		"access_denied" => 7003,
		"not_login" => 7010,
		"user_not_exist" => 7020,
		"not_auth" => 7030,
		"not_permit" => 7031,
		"course_not_available" => 7101,
		"illegal_params" => 7201,
		"illegal_grade" => 7202,
		"course_alerted" => 7301,
		"to_late_to_cancel" => 7401,
		"has_a_teacher" => 7402,
	);
	 static $v2s_map= array(
		 0=>  "success",
		 7000=>  "system_err",
		 7001=>  "wrong_verify_code",
		 7002=>  "wrong_account_passwd",
		 7003=>  "access_denied",
		 7010=>  "not_login",
		 7020=>  "user_not_exist",
		 7030=>  "not_auth",
		 7031=>  "not_permit",
		 7101=>  "course_not_available",
		 7201=>  "illegal_params",
		 7202=>  "illegal_grade",
		 7301=>  "course_alerted",
		 7401=>  "to_late_to_cancel",
		 7402=>  "has_a_teacher",
	);

	//成功
	const V_0=0;
	//成功
	const V_SUCCESS=0;
	//系统错误
	const V_7000=7000;
	//系统错误
	const V_SYSTEM_ERR=7000;
	//验证码错误
	const V_7001=7001;
	//验证码错误
	const V_WRONG_VERIFY_CODE=7001;
	//用户名密码错误
	const V_7002=7002;
	//用户名密码错误
	const V_WRONG_ACCOUNT_PASSWD=7002;
	//没有权限
	const V_7003=7003;
	//没有权限
	const V_ACCESS_DENIED=7003;
	//没有登录
	const V_7010=7010;
	//没有登录
	const V_NOT_LOGIN=7010;
	//用户不操作
	const V_7020=7020;
	//用户不操作
	const V_USER_NOT_EXIST=7020;
	//
	const V_7030=7030;
	//
	const V_NOT_AUTH=7030;
	//
	const V_7031=7031;
	//
	const V_NOT_PERMIT=7031;
	//
	const V_7101=7101;
	//
	const V_COURSE_NOT_AVAILABLE=7101;
	//非法参数
	const V_7201=7201;
	//非法参数
	const V_ILLEGAL_PARAMS=7201;
	//
	const V_7202=7202;
	//
	const V_ILLEGAL_GRADE=7202;
	//
	const V_7301=7301;
	//
	const V_COURSE_ALERTED=7301;
	//
	const V_7401=7401;
	//
	const V_TO_LATE_TO_CANCEL=7401;
	//
	const V_7402=7402;
	//
	const V_HAS_A_TEACHER=7402;

	//成功
	const S_0="success";
	//成功
	const S_SUCCESS="success";
	//系统错误
	const S_7000="system_err";
	//系统错误
	const S_SYSTEM_ERR="system_err";
	//验证码错误
	const S_7001="wrong_verify_code";
	//验证码错误
	const S_WRONG_VERIFY_CODE="wrong_verify_code";
	//用户名密码错误
	const S_7002="wrong_account_passwd";
	//用户名密码错误
	const S_WRONG_ACCOUNT_PASSWD="wrong_account_passwd";
	//没有权限
	const S_7003="access_denied";
	//没有权限
	const S_ACCESS_DENIED="access_denied";
	//没有登录
	const S_7010="not_login";
	//没有登录
	const S_NOT_LOGIN="not_login";
	//用户不操作
	const S_7020="user_not_exist";
	//用户不操作
	const S_USER_NOT_EXIST="user_not_exist";
	//
	const S_7030="not_auth";
	//
	const S_NOT_AUTH="not_auth";
	//
	const S_7031="not_permit";
	//
	const S_NOT_PERMIT="not_permit";
	//
	const S_7101="course_not_available";
	//
	const S_COURSE_NOT_AVAILABLE="course_not_available";
	//非法参数
	const S_7201="illegal_params";
	//非法参数
	const S_ILLEGAL_PARAMS="illegal_params";
	//
	const S_7202="illegal_grade";
	//
	const S_ILLEGAL_GRADE="illegal_grade";
	//
	const S_7301="course_alerted";
	//
	const S_COURSE_ALERTED="course_alerted";
	//
	const S_7401="to_late_to_cancel";
	//
	const S_TO_LATE_TO_CANCEL="to_late_to_cancel";
	//
	const S_7402="has_a_teacher";
	//
	const S_HAS_A_TEACHER="has_a_teacher";

	static public function check_success ($val){
		 return $val == 0;
	}
	static public function check_system_err ($val){
		 return $val == 7000;
	}
	static public function check_wrong_verify_code ($val){
		 return $val == 7001;
	}
	static public function check_wrong_account_passwd ($val){
		 return $val == 7002;
	}
	static public function check_access_denied ($val){
		 return $val == 7003;
	}
	static public function check_not_login ($val){
		 return $val == 7010;
	}
	static public function check_user_not_exist ($val){
		 return $val == 7020;
	}
	static public function check_not_auth ($val){
		 return $val == 7030;
	}
	static public function check_not_permit ($val){
		 return $val == 7031;
	}
	static public function check_course_not_available ($val){
		 return $val == 7101;
	}
	static public function check_illegal_params ($val){
		 return $val == 7201;
	}
	static public function check_illegal_grade ($val){
		 return $val == 7202;
	}
	static public function check_course_alerted ($val){
		 return $val == 7301;
	}
	static public function check_to_late_to_cancel ($val){
		 return $val == 7401;
	}
	static public function check_has_a_teacher ($val){
		 return $val == 7402;
	}


};
