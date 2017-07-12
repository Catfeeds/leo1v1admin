<?php
namespace App\Models\Zgen;
class z_t_send_wx_template_record_list extends \App\Models\NewModel
{
    const DB_TABLE_NAME="db_weiyi.t_send_wx_template_record_list";


	/*varchar(255) */
	const C_template_id='template_id';

	/*int(11) */
	const C_send_time='send_time';

	/*int(11) */
	const C_template_type='template_type';

	/*varchar(255) */
	const C_title='title';

	/*varchar(255) */
	const C_first_sentence='first_sentence';

	/*varchar(255) */
	const C_end_sentence='end_sentence';

	/*varchar(255) */
	const C_keyword1='keyword1';

	/*varchar(255) */
	const C_keyword2='keyword2';

	/*varchar(255) */
	const C_keyword3='keyword3';

	/*varchar(255) */
	const C_keyword4='keyword4';

	/*varchar(255) */
	const C_url='url';

	/*varchar(255) */
	const C_account='account';
	function get_template_type($template_id, $send_time ){
		return $this->field_get_value_2( $template_id, $send_time  , self::C_template_type  );
	}
	function get_title($template_id, $send_time ){
		return $this->field_get_value_2( $template_id, $send_time  , self::C_title  );
	}
	function get_first_sentence($template_id, $send_time ){
		return $this->field_get_value_2( $template_id, $send_time  , self::C_first_sentence  );
	}
	function get_end_sentence($template_id, $send_time ){
		return $this->field_get_value_2( $template_id, $send_time  , self::C_end_sentence  );
	}
	function get_keyword1($template_id, $send_time ){
		return $this->field_get_value_2( $template_id, $send_time  , self::C_keyword1  );
	}
	function get_keyword2($template_id, $send_time ){
		return $this->field_get_value_2( $template_id, $send_time  , self::C_keyword2  );
	}
	function get_keyword3($template_id, $send_time ){
		return $this->field_get_value_2( $template_id, $send_time  , self::C_keyword3  );
	}
	function get_keyword4($template_id, $send_time ){
		return $this->field_get_value_2( $template_id, $send_time  , self::C_keyword4  );
	}
	function get_url($template_id, $send_time ){
		return $this->field_get_value_2( $template_id, $send_time  , self::C_url  );
	}
	function get_account($template_id, $send_time ){
		return $this->field_get_value_2( $template_id, $send_time  , self::C_account  );
	}


  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="template_id";
        $this->field_id2_name="send_time";
        $this->field_table_name="db_weiyi.t_send_wx_template_record_list";
  }

    public function field_get_value_2(  $template_id, $send_time,$field_name ) {
        return parent::field_get_value_2(  $template_id, $send_time,$field_name ) ;
    }

    public function field_get_list_2( $template_id,  $send_time,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args());
    }

    public function field_update_list_2( $template_id, $send_time,  $set_field_arr ) {
        return parent::field_update_list_2( $template_id, $send_time,  $set_field_arr );
    }
    public function row_delete_2(  $template_id ,$send_time ) {
        return parent::row_delete_2( $template_id ,$send_time );
    }


}
/*
  CREATE TABLE `t_send_wx_template_record_list` (
  `template_id` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '模板id',
  `send_time` int(11) NOT NULL COMMENT '推送时间',
  `template_type` int(11) NOT NULL COMMENT '类型',
  `title` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '标题',
  `first_sentence` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '开头语',
  `end_sentence` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '结束语',
  `keyword1` varchar(255) COLLATE latin1_bin NOT NULL,
  `keyword2` varchar(255) COLLATE latin1_bin NOT NULL,
  `keyword3` varchar(255) COLLATE latin1_bin NOT NULL,
  `keyword4` varchar(255) COLLATE latin1_bin NOT NULL,
  `url` varchar(255) COLLATE latin1_bin NOT NULL,
  `account` varchar(255) COLLATE latin1_bin NOT NULL COMMENT '操作人',
  PRIMARY KEY (`template_id`,`send_time`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_bin
 */
