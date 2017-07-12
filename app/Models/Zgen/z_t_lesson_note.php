<?php
namespace App\Models\Zgen;
class z_t_lesson_note  extends  \App\Models\NewModel
{
    const DB_TABLE_NAME="db_question.t_lesson_note";



  public function __construct()
  {
    parent::__construct();
        $this->field_id1_name="note_id";
        $this->field_table_name="db_question.t_lesson_note";
  }
    public function field_get_list( $note_id, $__field_name_args__ ) {
        return call_user_func_array(array('parent', 'field_get_list'), func_get_args());
    }

    public function field_update_list ( $note_id, $set_field_arr) {
        return parent::field_update_list( $note_id, $set_field_arr);
    }


    public function field_get_value(  $note_id, $field_name ) {
        return parent::field_get_value( $note_id, $field_name);
    }

    public function row_delete(  $note_id) {
        return parent::row_delete( $note_id);
    }

}

/*
  
 */
