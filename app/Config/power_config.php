<?php
namespace App\Config;
class power_config {
	static  public  function get_default_config()  {
		return [
			'/authority/manager_list' 	=> [
				'field_name'	=>'gen_assistant_account',
				'desc'	=>'生成助教账号',
				'default_value'	=>'1',
			],
 			'/authority/manager_list' 	=> [
				'field_name'	=>'opt_login_other',
				'desc'	=>'登录其他用户',
				'default_value'	=>'1',
			],
 			'/test/tongji_seller_test_lesson_order_info_zs' 	=> [
				'field_name'	=>'gen_account',
				'desc'	=>'科目输入框',
				'default_value'	=>'1',
			],
 			'/test/tongji_seller_test_lesson_order_info_zs' 	=> [
				'field_name'	=>'login_other',
				'desc'	=>'年级输入框',
				'default_value'	=>'1',
			],
 		];
 	}
 }
 