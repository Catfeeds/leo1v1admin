<?php
namespace App\Config;
class power_config {
	static  public  function get_default_config()  {
		return [
			'/authority/manager_list'	=> [
				'gen_assistant_account','opt_login_other'
			],
 			'/test/tongji_seller_test_lesson_order_info_zs'	=> [
				'gen_account','login_other'
			],
 		];
 	}
 }
 