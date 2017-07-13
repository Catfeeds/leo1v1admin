<?php
//自动生成枚举类  不要手工修改
//source  file: config_feedback_type.php
namespace  App\Enums;

class Efeedback_type extends \App\Core\Enum_base
{
	static public $field_name = "feedback_type"  ;
	static public $name = "feedback_type"  ;
	 static $desc_map= array(
		101 => "基础工资",
		102 => "课时奖励工资",
		103 => "全勤奖",
		104 => "荣誉榜排名奖金",
		201 => "上课迟到扣款",
		202 => "规定时间内未评价扣款",
		203 => "课前未传讲义扣款",
		204 => "未提前4小时换课",
		205 => "教学事故",
	);
	 static $simple_desc_map= array(
		101 => "",
		102 => "",
		103 => "",
		104 => "",
		201 => "",
		202 => "",
		203 => "",
		204 => "",
		205 => "",
	);
	 static $s2v_map= array(
		"" => 101,
		"" => 102,
		"" => 103,
		"" => 104,
		"" => 201,
		"" => 202,
		"" => 203,
		"" => 204,
		"" => 205,
	);
	 static $v2s_map= array(
		 101=>  "",
		 102=>  "",
		 103=>  "",
		 104=>  "",
		 201=>  "",
		 202=>  "",
		 203=>  "",
		 204=>  "",
		 205=>  "",
	);

	//基础工资
	const V_101=101;
	//课时奖励工资
	const V_102=102;
	//全勤奖
	const V_103=103;
	//荣誉榜排名奖金
	const V_104=104;
	//上课迟到扣款
	const V_201=201;
	//规定时间内未评价扣款
	const V_202=202;
	//课前未传讲义扣款
	const V_203=203;
	//未提前4小时换课
	const V_204=204;
	//教学事故
	const V_205=205;

	//基础工资
	const S_101="";
	//课时奖励工资
	const S_102="";
	//全勤奖
	const S_103="";
	//荣誉榜排名奖金
	const S_104="";
	//上课迟到扣款
	const S_201="";
	//规定时间内未评价扣款
	const S_202="";
	//课前未传讲义扣款
	const S_203="";
	//未提前4小时换课
	const S_204="";
	//教学事故
	const S_205="";



};
