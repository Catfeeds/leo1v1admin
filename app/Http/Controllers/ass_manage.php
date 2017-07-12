<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;


class ass_manage extends Controller
{
    public function add_assistant()
	{
		$ass_nick       = $this->get_in_str_val('ass_nick',"");
		$administrator  = $this->get_in_str_val('administrator',"");
		$gender         = $this->get_in_int_val('gender', -1);
		$birth          = $this->get_in_str_val('birth',"");
		$work_year      = $this->get_in_int_val('work_year', 0);
		$phone          = $this->get_in_str_val('phone',"");
		$email          = $this->get_in_str_val('email',"");
		$assistant_type = $this->get_in_int_val('assistant_type',-1);
		$school         = $this->get_in_str_val('school',"");

        $ret_auth = $this->t_manager_info->check_permission($this->get_account(), ASS_ARCHIVES);
        if(!$ret_auth)
            return outputJson(array('ret' => NOT_AUTH, 'info' => $this->err_string[NOT_AUTH]));

		if($ass_nick == "" || $birth == "" || $phone == "" || $assistant_type == -1 || $gender == -1){
			return outputJson(array('ret' => -1, 'info'  => '参数不完整，请检查后重新确认'));
		} 

        $ret_num = $this->t_phone_to_user->is_phone_valid($phone, 3);
        if($ret_num['num'] > 0)
			return outputJson(array('ret' => -1, 'info'  => '账号已经存在'));
        
		$tmp = explode('-',$birth);
		$birth = "";
		foreach($tmp as $value){
			$birth .= $value;
		}
		srand(microtime(true) * 1000);  
		$passwd = (int)$phone+rand(9999999999,99999999999);
		$passwd = substr("".$passwd, 0, 6);
        $md5_passwd = md5(md5($passwd)."#Aaron");
		$assid = $this->t_user_info->add_account_ass(md5($passwd));
		if($assid === false){
			return outputJson(array('ret' => -1, 'info'  => '插入失败'));
		}

        //加入ejabberd 账号让助教可以进入课堂
        //$this->users->add_ejabberd_account($assid,md5($passwd));
        //加入ejabberd监控账号 以ad开头
        //$this->users->add_ejabberd_account("ad_" . $assid,md5($passwd));

		$ret_db = $this->t_phone_to_user->add_phone_to_ass($assid, $phone);
		if($ret_db === false){
			return outputJson(array('ret' => -1, 'info'  => '插入失败'));
		}


		$ret_db = $this->t_assistant_info->add_new_ass($ass_nick, $gender, $birth, $work_year, $phone, $email,
													   $assistant_type, $assid, $school);
		if(ret_db === false)
			return outputJson(array('ret' => -1, 'info'  => '插入失败'));

        if($this->t_admin_users->is_manage_exist($phone))
            return outputJson(array('ret' => -1, 'info' => '用户已经存在'));

		$message = "您的理优教育账号为:". $phone ."密码为:".$passwd;
		send_message($message, $phone);
        $admin_id = $this->t_admin_users->add_manager($phone, $ass_nick, $email, $phone, $md5_passwd);
        $this->t_adid_to_adminid->add_ad_to_admin($assid, $admin_id);
        return outputJson(array('ret' => 0, 'info'  => '插入成功！'));
	}
   
}