<?php
//自动生成枚举类  不要手工修改
//source  file: config_question_check_flag.php
namespace  App\Enums;

class Equestion_check_flag extends \App\Core\Enum_base
{
	static public $field_name = "question_check_flag"  ;
	static public $name = "question_check_flag"  ;
	 static $desc_map= array(
		0 => "无",
		1 => "提交审核",
		2 => "一审通过",
		3 => "一审不通过 扣10%",
		4 => "一审不通过 扣50%",
		5 => "一审不通过 扣100%",
		6 => "一审不通过 不入库",
		12 => "再审通过",
		13 => "再审不通过 扣10%",
		14 => "再审不通过 扣50%",
		15 => "再审不通过 扣100%",
		16 => "再审不通过 不入库",
	);
	 static $simple_desc_map= array(
		0 => "无",
		1 => "提交",
		2 => "",
		3 => "",
		4 => "",
		5 => "",
		6 => "",
		12 => "",
		13 => "",
		14 => "",
		15 => "",
		16 => "",
	);
	 static $s2v_map= array(
		"nopost" => 0,
		"post" => 1,
		"pass" => 2,
		"nopass_10" => 3,
		"nopass_50" => 4,
		"nopass_100" => 5,
		"nopass_del" => 6,
		"pass2" => 12,
		"nopass2_10" => 13,
		"nopass2_50" => 14,
		"nopass2_100" => 15,
		"nopass2_del" => 16,
	);
	 static $v2s_map= array(
		 0=>  "nopost",
		 1=>  "post",
		 2=>  "pass",
		 3=>  "nopass_10",
		 4=>  "nopass_50",
		 5=>  "nopass_100",
		 6=>  "nopass_del",
		 12=>  "pass2",
		 13=>  "nopass2_10",
		 14=>  "nopass2_50",
		 15=>  "nopass2_100",
		 16=>  "nopass2_del",
	);

	//无
	const V_0=0;
	//无
	const V_NOPOST=0;
	//提交审核
	const V_1=1;
	//提交审核
	const V_POST=1;
	//一审通过
	const V_2=2;
	//一审通过
	const V_PASS=2;
	//一审不通过 扣10%
	const V_3=3;
	//一审不通过 扣10%
	const V_NOPASS_10=3;
	//一审不通过 扣50%
	const V_4=4;
	//一审不通过 扣50%
	const V_NOPASS_50=4;
	//一审不通过 扣100%
	const V_5=5;
	//一审不通过 扣100%
	const V_NOPASS_100=5;
	//一审不通过 不入库
	const V_6=6;
	//一审不通过 不入库
	const V_NOPASS_DEL=6;
	//再审通过
	const V_12=12;
	//再审通过
	const V_PASS2=12;
	//再审不通过 扣10%
	const V_13=13;
	//再审不通过 扣10%
	const V_NOPASS2_10=13;
	//再审不通过 扣50%
	const V_14=14;
	//再审不通过 扣50%
	const V_NOPASS2_50=14;
	//再审不通过 扣100%
	const V_15=15;
	//再审不通过 扣100%
	const V_NOPASS2_100=15;
	//再审不通过 不入库
	const V_16=16;
	//再审不通过 不入库
	const V_NOPASS2_DEL=16;

	//无
	const S_0="nopost";
	//无
	const S_NOPOST="nopost";
	//提交审核
	const S_1="post";
	//提交审核
	const S_POST="post";
	//一审通过
	const S_2="pass";
	//一审通过
	const S_PASS="pass";
	//一审不通过 扣10%
	const S_3="nopass_10";
	//一审不通过 扣10%
	const S_NOPASS_10="nopass_10";
	//一审不通过 扣50%
	const S_4="nopass_50";
	//一审不通过 扣50%
	const S_NOPASS_50="nopass_50";
	//一审不通过 扣100%
	const S_5="nopass_100";
	//一审不通过 扣100%
	const S_NOPASS_100="nopass_100";
	//一审不通过 不入库
	const S_6="nopass_del";
	//一审不通过 不入库
	const S_NOPASS_DEL="nopass_del";
	//再审通过
	const S_12="pass2";
	//再审通过
	const S_PASS2="pass2";
	//再审不通过 扣10%
	const S_13="nopass2_10";
	//再审不通过 扣10%
	const S_NOPASS2_10="nopass2_10";
	//再审不通过 扣50%
	const S_14="nopass2_50";
	//再审不通过 扣50%
	const S_NOPASS2_50="nopass2_50";
	//再审不通过 扣100%
	const S_15="nopass2_100";
	//再审不通过 扣100%
	const S_NOPASS2_100="nopass2_100";
	//再审不通过 不入库
	const S_16="nopass2_del";
	//再审不通过 不入库
	const S_NOPASS2_DEL="nopass2_del";

	static public function check_nopost ($val){
		 return $val == 0;
	}
	static public function check_post ($val){
		 return $val == 1;
	}
	static public function check_pass ($val){
		 return $val == 2;
	}
	static public function check_nopass_10 ($val){
		 return $val == 3;
	}
	static public function check_nopass_50 ($val){
		 return $val == 4;
	}
	static public function check_nopass_100 ($val){
		 return $val == 5;
	}
	static public function check_nopass_del ($val){
		 return $val == 6;
	}
	static public function check_pass2 ($val){
		 return $val == 12;
	}
	static public function check_nopass2_10 ($val){
		 return $val == 13;
	}
	static public function check_nopass2_50 ($val){
		 return $val == 14;
	}
	static public function check_nopass2_100 ($val){
		 return $val == 15;
	}
	static public function check_nopass2_del ($val){
		 return $val == 16;
	}


};
