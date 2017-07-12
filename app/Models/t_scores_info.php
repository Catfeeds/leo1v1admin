<?php
namespace App\Models;
class t_scores_info extends \App\Models\Zgen\z_t_scores_info
{
	public function __construct()
	{
		parent::__construct();
	}
    public function min_score($areaid ) {
        
        $where_arr=[
            "school_type=3", 
            "scores_year=2015",  
            ["scores_area=%u" , $areaid, -1 ],
        ];
        $sql=$this->gen_sql("select  scores_school from %s where  %s order by scores_school asc limit 1  ",
                            self::DB_TABLE_NAME,
                            $this->where_str_gen($where_arr)
        );
        return $this->main_get_value($sql);
    }

    public function get_le_1_item($areaid,$score,$count ) {
        $where_arr=[
            "school_type=3", 
            "scores_year=2015",  
            ["scores_area=%u" , $areaid, -1 ],
            ["scores_school<%u" , $score, -1 ],
        ];
        $sql=$this->gen_sql("select school_name,scores_school,scores_area from %s where  %s order by scores_school desc limit $count  ",
                            self::DB_TABLE_NAME,
                            $this->where_str_gen($where_arr)
        );
        return $this->main_get_list($sql);
    }
    public function get_ge_2_item ( $areaid,$score) {
        $where_arr=[
            "school_type=3", 
            "scores_year=2015",  
            ["scores_area=%u" , $areaid, -1 ],
            ["scores_school>=%u" , $score, -1 ],
        ];
        $sql=$this->gen_sql("select school_name ,scores_school ,scores_area  from %s where  %s order by scores_school asc limit 2 ",
                            self::DB_TABLE_NAME,
                            $this->where_str_gen($where_arr)
        );
        return $this->main_get_list($sql);
    }

    public function get_find_school_list($areaid,$score) {
        $list1=$this->get_le_1_item($areaid,$score,3);
        //$list2=$this->get_ge_2_item($areaid,$score);
        do{
            $other_areaid=rand(101,117) ;
        }while( $other_areaid == $areaid );

        $list3=$this->get_le_1_item($other_areaid,$score,1);
        if (count($list3)==0) {
            $list3=$this->get_le_1_item(-1,$score,1);
        }

        $arr=array_merge($list1,$list3);
        if (count($arr)==4){
            $arr[0]["percent"]=20;
            $arr[1]["percent"]=30;
            $arr[2]["percent"]=40;
            $arr[3]["percent"]=10;
        }
        if (count($arr)==3){
            $arr[0]["percent"]=30;
            $arr[1]["percent"]=50;
            $arr[2]["percent"]=20;
        }

        if (count($arr)==2){
            $arr[0]["percent"]=70;
            $arr[1]["percent"]=30;
        }

        if (count($arr)==1){
            $arr[0]["percent"]=100;
        }
        foreach ($arr  as &$item )  {
            unset($item[0]  );
            unset($item[1]  );
            unset($item[2]  );
        }
        return $arr;
    }
}











