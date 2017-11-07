<?php
namespace App\OrderPrice\Activity;
use \App\Enums as E;
class activity_config_base extends  activity_base {
    public static $order_activity_type = 0;

    // 活动开始时间, 结束时间
    public $date_range=[];

    public  $period_flag_list =[ ];//分期,true, false


    public  $max_count=0;//
    public  $contract_type_list=[] ; //常规,续费
    // 课次数  区间
    public $lesson_times_range=[];

    public  $last_test_lesson_range=[];//检查最近一次试听课时间,在什么时间之前

    //public  $lesson_times_off_perent_list=[]; //按课次数打折
    public  $lesson_times_present_lesson_count =[]; //按课次数送课

    public  $price_off_money_list=[]; //按金额 立减



    public function __construct( $args ) {
        parent::__construct($args);

    }

    protected function do_exec (&$out_args ,&$can_period_flag,   &$price,  &$present_lesson_count,  &$desc_list )
    {
        //时间检查
        if (count($this->date_range)==2 ) {
            if (!$this->check_now( $this->date_range[0], $this->date_range[1] )) {
                return false;
            }
        }
        //分期,不分期检查
        if ( !in_array( $can_period_flag, $this->period_flag_list ) ) {
            return false;
        }
        $contract_type=$this->contract_type;

        //常规,续费检查
        if ( !in_array( $contract_type , $this->contract_type_list ) ) {
            return false;
        }

        $lesson_times= $this->lesson_times;
        //课次数检查
        if (count($this->lesson_times_range )==2 ) {
            if  ( $lesson_times>= $this->lesson_times_range[0]
                  && $lesson_times> $this->lesson_times_range[1]  ) {
                $desc_list[]=static::gen_activity_item(0,  "购买课时{$lesson_times}, 课时数不匹配  " , $price,  $present_lesson_count, $can_period_flag );
                return false ;
            }
        }

        $activity_desc='';

        if (count($this->last_test_lesson_range)==2) {
            $from_test_lesson_id=$this->from_test_lesson_id;
            if($from_test_lesson_id ) {
                $lesson_info= $this->task->t_lesson_info_b2->field_get_list(
                    $this->from_test_lesson_id,
                    "userid,grade");
                $userid = $lesson_info["userid"];
                $grade  = $lesson_info["grade"];

                $last_lesson_info=$this->task->t_lesson_info_b3->get_grade_last_test_lesson( $userid, $grade );
                $lesson_start = $last_lesson_info["lesson_start"];

                $activity_desc.=" 试听课时间:".\App\Helper\Utils::unixtime2date($lesson_start ).",";

                if (!( $lesson_start >= strtotime( $this->last_test_lesson_range[0])
                       && $lesson_start < strtotime( $this->last_test_lesson_range[1]))
                ) {
                    $desc_list[]=static::gen_activity_item(0,  $activity_desc. "时间不匹配[{$this->last_test_lesson_range[0]}-{$this->last_test_lesson_range[1]}]" , $price,  $present_lesson_count, $can_period_flag );
                    return false;
                }

            }else{
                $desc_list[]=static::gen_activity_item(0,  "没有试听课" , $price,  $present_lesson_count, $can_period_flag );
                return false;
            }

        }

        //配额检查
        if ($this->max_count){
            list( $count_check_ok_flag,$now_count, $activity_desc_cur_count)= static::check_use_count($this->max_count );
            $activity_desc.= $activity_desc_cur_count;
            if (!$count_check_ok_flag) {
                $desc_list[]=static::gen_activity_item(0,  $activity_desc. ",额度已用完"  , $price,  $present_lesson_count, $can_period_flag );
                return false;
            }
        }

        //按课次数送课
        if (count($this->lesson_times_present_lesson_count)>0 ) {
            $tmp_present_lesson_count=0 ;
            list($find_free_lesson_level , $present_lesson_count_1 )=static::get_value_from_config_ex(
                $this->lesson_times_present_lesson_count ,  $this->lesson_times , [0,0] );
            if ( $present_lesson_count_1) {
                $present_lesson_count += $present_lesson_count_1 *3;
                $desc_list[] = static::gen_activity_item(1, "购满 $find_free_lesson_level 次课 送 $present_lesson_count_1 次课  "   , $price,  $present_lesson_count, $can_period_flag );
                return true;
            }else{
                $desc_list[]=static::gen_activity_item(0, " {$this->lesson_times}次课 未匹配", $price,  $present_lesson_count,$can_period_flag );
                return false;
            }
        }

        //按金额 立减
        if (count($this->price_off_money_list)>0 ) {

            $tmp_present_lesson_count=0 ;
            list($find_money_level , $off_money )=static::get_value_from_config_ex(
                $this->price_off_money_list,  $price , [0,0] );
            if ( $present_lesson_count_1) {
                $price-=$free_money;
                $desc_list[] = static::gen_activity_item(1, "购满 $find_money_level 元 立减 $off_money 元 "   , $price,  $present_lesson_count, $can_period_flag );
                return true;
            }else{
                $desc_list[]=static::gen_activity_item(0, " {$this->lesson_times}次课 未匹配", $price,  $present_lesson_count,$can_period_flag );
                return false;
            }
        }
        return true;

    }
    public function get_desc() {
        $arr=[];
        $arr[]=["条件", "--" ];

        //时间检查
        if (count($this->date_range)==2 ) {
            $arr[]=["活动时间", $this->date_range[0]. " 到 " . $this->date_range[1]  ];
        }

        //分期,不分期检查
        $tmp_str_arr=[];
        foreach ( $this->period_flag_list as $val ) {
            if ($val) {
                $tmp_str_arr[]="分期可用";
            }else{
                $tmp_str_arr[]="不分期可用";
            }
        }

        $arr[]=["分期适用",  join(",",$tmp_str_arr )  ];



        //分期,不分期检查
        $tmp_str_arr=[];
        foreach ( $this->contract_type_list as $val ) {
            $tmp_str_arr[]=  E\Econtract_type::get_desc($val)."可用";
        }
        $arr[]=["新签 续费 适用",  join(",",$tmp_str_arr )  ];

        if (count ($this->lesson_times_range ) ==2 ) {
            $arr[]=["适用的购买课次数区间", $this->lesson_times_range [0]. " 到 " . $this->lesson_times_range[1]  ];
        }

        if (count($this->last_test_lesson_range)==2) {
            $arr[]=["最后一次试听时间区间", $this->last_test_lesson_range [0]. " 到 " . $this->last_test_lesson_range[1]  ];
        }

        if ($this->max_count){
            $arr[]=["合同最大个数",  $this->max_count  ];
        }

        $arr[]=["--", ""];

        $str="";
        if (count($this->lesson_times_present_lesson_count)>0 ) {
            foreach ( $this->lesson_times_present_lesson_count as  $key => $val) {
                $str.="购满 $key 次课 送 $val 次课 <br/> ";
            }

        }else if ( count($this->price_off_money_list )>0 ) {
            foreach ( $this->price_off_money_list as  $key => $val) {
                $str.="购满 $key 次课 送 $val 次课 <br/> ";
            }
        }

        $arr[]=["优惠", $str  ];
        return $arr;
    }


}