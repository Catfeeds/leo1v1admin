<?php
namespace App\Models\Zgen;
class z_t_phone_to_user  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_account.t_phone_to_user";


	/*varchar(16) */
	const C_phone='phone';

	/*tinyint(4) */
	const C_role='role';

	/*int(10) unsigned */
	const C_userid='userid';
	function get_role($phone ){
		return $this->field_get_value( $phone , self::C_role );
	}
	function get_userid($phone ){
		return $this->field_get_value( $phone , self::C_userid );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="phone";
        $this->field_table_name="db_account.t_phone_to_user";
  }
    public function field_get_list( $phone, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $phone, $set_field_arr) {
        return parent::field_update_list( $phone, $set_field_arr);
    }


    public function field_get_value(  $phone, $field_name ) {
        return parent::field_get_value( $phone, $field_name);
    }

    public function row_delete(  $phone) {
        return parent::row_delete( $phone);
    }

}

/*
  CREATE TABLE `t_phone_to_user` (
  `phone` varchar(16) NOT NULL COMMENT '手机号',
  `role` tinyint(4) NOT NULL COMMENT '角色, student  1, teacher 2, assistent 3, parent 4,',
  `userid` int(10) unsigned NOT NULL COMMENT '用户id',
  PRIMARY KEY (`phone`,`role`),
  UNIQUE KEY `userid` (`userid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8
 */
