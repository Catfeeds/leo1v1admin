<?php
namespace App\Models\Zgen;
class z_t_order_lesson_info extends \App\Models\NewModel   
{
    const DB_TABLE_NAME="db_weiyi.t_order_lesson_info";



	public function __construct()
	{
		parent::__construct();
        $this->field_id1_name="orderid";
        $this->field_id2_name="lessonid";
        $this->field_table_name="db_weiyi.t_order_lesson_info";
	}

    public function field_get_value_2(  $orderid, $lessonid,$field_name ) {
        return parent::field_get_value_2(  $orderid, $lessonid,$field_name ) ;
    }

    public function field_get_list_2( $orderid,  $lessonid,  $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list_2'), func_get_args()); 
    }

    public function field_update_list_2( $orderid, $lessonid,  $set_field_arr ) {
        return parent::field_update_list_2( $orderid, $lessonid,  $set_field_arr );
    }
    public function row_delete_2(  $orderid ,$lessonid ) {
        return parent::row_delete_2( $orderid ,$lessonid );
    }


}
/*
  
 */
