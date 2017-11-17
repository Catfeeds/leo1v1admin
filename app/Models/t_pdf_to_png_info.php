<?php
namespace App\Models;
use \App\Enums as E;
class t_pdf_to_png_info extends \App\Models\Zgen\z_t_pdf_to_png_info
{
    public function __construct()
    {
        parent::__construct();
    }


    public function get_pdf_list_for_doing (){
        $sql = $this->gen_sql_new(" select id, pdf_url, lessonid from %s tp where id_do_flag=0 order by create_time asc limit 10"
                                  ,self::DB_TABLE_NAME
        );

        return $this->main_get_list($sql);
    }

    public function get_untreated_pdf($num){

        if($num>0){
            $limit_num = "limit $num";
        }else{
            $limit_num = '';
        }

        $sql = "select * from t_pdf_to_png_info where create_time>1510761600 and id_do_flag=2 and lessonid not in (select lessonid from t_pdf_to_png_info where create_time>1510761600 and id_do_flag=1 ) ".$limit_num;
        return $this->main_get_list($sql);
    }

}
