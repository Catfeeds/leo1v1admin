<?php
namespace App\OrderPrice\Activity;
use \App\Enums as E;
class activity_config_base extends  activity_base {
    public static $order_activity_type = 0;

    public $open_flag=true;
    // 活动开始时间, 结束时间
    public $date_range=[];

    public  $period_flag_list =[ ];//分期,true, false

    //是否可不使用
    public $can_disable_flag =true;

    //是否可不使用
    public $is_need_share_wechat = 0;

    public  $check_grade_list=[] ; //适配年级 [ 101,102,103,104,105,106, . ]

    static public  $max_count_activity_type_list=[]; // 总配额 组合
    public  $max_count=0;//
    public  $max_change_value=0;// 最大修改 累计值

    public  $contract_type_list=[] ; //常规,续费
    // 课次数  区间
    public $lesson_times_range=[];
    public  $user_join_time_range=[];//用户加入时间区间

    public  $last_test_lesson_range=[];//检查最近一次试听课时间,在什么时间之前


    public  $lesson_times_off_perent_list=[
    ]; //按课次数打折

    public  $grade_off_perent_list=[
    ]; //按年级打折

    /*
      E\Eperiod_flag::V_PERIOD => [
      10 => 78;
      ]
      E\Eperiod_flag::V_NOT_PERIOD => [
      10 => 72;
      ]
    */

    public  $lesson_times_present_lesson_count =[]; //按课次数送课

    public  $price_off_money_list=[]; //按金额 立减



    public function __construct( $args ) {
        parent::__construct($args);

    }

    protected function do_exec (&$out_args ,&$can_period_flag,   &$price,  &$present_lesson_count,  &$desc_list )
    {
        if (!$this->open_flag ) {
            return false;
        }

        //手动开启检查
        if ($this->can_disable_flag ) {
            if ( in_array( static::$order_activity_type ,$this->args["disable_activity_list"] ) ) {

                $desc_list[]=static::gen_activity_item(2,  "手动不开启" , $price,  $present_lesson_count, $can_period_flag );
                return false;
            }
        }


        //时间检查
        if (count($this->date_range)==2 ) {
            if (!$this->check_now( $this->date_range[0], $this->date_range[1] )) {
                return false;
            }
        }

        //年级检查
        if (count($this->check_grade_list ) ) {
            if (! in_array( $this->grade , $this->check_grade_list) ) {
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
            if  (!( $lesson_times>= $this->lesson_times_range[0]
                    && $lesson_times<= $this->lesson_times_range[1] ) ) {
                $desc_list[]=static::gen_activity_item(0,  "购买课时{$lesson_times}, 课时数不匹配  " , $price,  $present_lesson_count, $can_period_flag );
                return false ;
            }
        }

        $activity_desc='';
        //用户加入时间检查
        if (count($this->user_join_time_range )==2 ) {
            $user_add_time= $this->task->t_seller_student_new->get_add_time($this->userid);
            $user_add_time_str=date("Y-m-d",$user_add_time );
            if  ( !($user_add_time >= strtotime( $this->user_join_time_range [0])
                    && $user_add_time <= (strtotime( $this->user_join_time_range[1]) +86400 ))) {
                $desc_list[]=static::gen_activity_item(0,  "用户加入时间[$user_add_time_str]不匹配" , $price,  $present_lesson_count, $can_period_flag );
                return false ;
            }else{
                $activity_desc.=" 加入时间:$user_add_time_str, ";
            }
        }


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
                list( $check_ok_flag,$now_all_change_value, $activity_desc_cur_count )= $this->check_max_change_value($this->max_change_value, $present_lesson_count_1);
                if ( $check_ok_flag ) {
                    $present_lesson_count += $present_lesson_count_1 *3;
                    $off_money=  $present_lesson_count_1 * $this->grade_price *0.6;
                    $desc_list[] = static::gen_activity_item(1, "  $activity_desc 购满 $find_free_lesson_level 次课 送 $present_lesson_count_1 次课  $activity_desc_cur_count "   , $price,  $present_lesson_count, $can_period_flag, $present_lesson_count_1 , $off_money );
                    return true;
                }else{
                    $desc_list[]=static::gen_activity_item(0, " $activity_desc   购满 $find_free_lesson_level 次课 送 $present_lesson_count_1 次课  $activity_desc_cur_count 配额不足  ", $price,  $present_lesson_count,$can_period_flag );
                }
            }else{
                $desc_list[]=static::gen_activity_item(0, " $activity_desc {$this->lesson_times}次课 未匹配", $price,  $present_lesson_count,$can_period_flag );
                return false;
            }
        }

        //按金额 立减
        if (count($this->price_off_money_list)>0 ) {

            $tmp_present_lesson_count=0 ;
            list($find_money_level , $off_money )=static::get_value_from_config_ex(
                $this->price_off_money_list,  $price , [0,0] );
            if ( $off_money) {
                $price-=$off_money;
                $desc_list[] = static::gen_activity_item(1, " $activity_desc 购满 $find_money_level 元 立减 $off_money 元 "   , $price,  $present_lesson_count, $can_period_flag, $off_money, $off_money ,$off_money );
                return true;
            }else{
                $desc_list[]=static::gen_activity_item(0, " $activity_desc 购买 $price 未匹配", $price,  $present_lesson_count,$can_period_flag );
                return false;
            }
        }

        //按课次数打折
        if ( isset ($this->lesson_times_off_perent_list[$can_period_flag ]) ) {
            list($find_lesson_times_level , $off_percent )=static::get_value_from_config_ex(
                $this->lesson_times_off_perent_list[$can_period_flag ],  $lesson_times , [0,100] );
            if ( $off_percent) {
                $tmp_price=  intval($price* $off_percent /100) ;
                $diff_money= $price- $tmp_price;
                $price= $tmp_price;
                $desc_list[] = static::gen_activity_item(1, " $activity_desc 购满 $find_lesson_times_level 次课 打 $off_percent 折   "   , $price,  $present_lesson_count, $can_period_flag , $diff_money,$diff_money );
                return true;
            }else{
                $desc_list[]=static::gen_activity_item(0, " $activity_desc {$this->lesson_times}次课 未匹配", $price,  $present_lesson_count,$can_period_flag );
                return false;
            }

        }
        //按年级打折
        if ( isset ($this->grade_off_perent_list[$can_period_flag ]) ) {
            $off_percent=  @$this->grade_off_perent_list[$can_period_flag] [$this->grade];
            if ($off_percent) {
                $grade_str= E\Egrade::get_desc($this->grade);
                $price=  intval($price* $off_percent /100) ;
                $diff_money= $price- $tmp_price;
                $price= $tmp_price;
                $desc_list[] = static::gen_activity_item(1, " $activity_desc  ,年级 $grade_str 打 $off_percent 折   "   , $price,  $present_lesson_count, $can_period_flag, $diff_money );
                return true;
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
            $tmp_str_arr[]= E\Eperiod_flag::get_desc($val). "适用" ;
        }
        $arr[]=["分期适用",  join(",",$tmp_str_arr )  ];



        //新签 续费检查
        $tmp_str_arr=[];
        foreach ( $this->contract_type_list as $val ) {
            $tmp_str_arr[]=  E\Econtract_type::get_desc($val)."可用";
        }
        $arr[]=["新签 续费 适用",  join(",",$tmp_str_arr )  ];

        if ( count( $this->check_grade_list ) >0  ) {
            //年级
            $tmp_str_arr=[];
            foreach ( $this->check_grade_list   as $val ) {
                $tmp_str_arr[]=  E\Egrade::get_desc($val);
            }

            $arr[]=["年级适用 ",  join(",",$tmp_str_arr )  ];
        }


        //用户加入时间
        if (count ($this->user_join_time_range ) ==2 ) {
            $arr[]=["用户加入时间区间", $this->user_join_time_range [0]. " 到 " . $this->user_join_time_range[1]  ];
        }

        if (count ($this->lesson_times_range ) ==2 ) {
            $arr[]=["适用的购买课次数区间", $this->lesson_times_range [0]. " 到 " . $this->lesson_times_range[1]  ];
        }

        if (count($this->last_test_lesson_range)==2) {
            $arr[]=["最后一次试听时间区间", $this->last_test_lesson_range [0]. " 到 " . $this->last_test_lesson_range[1]  ];
        }

        if (count(static::$max_count_activity_type_list)>0 ) {
            //年级
            $tmp_str_arr=[];
            foreach (static::$max_count_activity_type_list   as $val ) {
                $tmp_str_arr[]=  $val.":".  E\Eorder_activity_type::get_desc($val);
            }

            $arr[]=["打包活动 总配额 ",  join("<br/>",$tmp_str_arr )  ];

        }

        if ($this->max_count){
            list( $count_check_ok_flag,$now_count, $activity_desc_cur_count)= static::check_use_count($this->max_count );
            $arr[]=["合同最大个数",  $activity_desc_cur_count  ];
        }
        if ($this->max_change_value){
            list( $count_check_ok_flag,$now_count, $activity_desc_cur_count)= static::check_max_change_value($this->max_change_value,0);
            $arr[]=["优惠份额最大个数",  $activity_desc_cur_count  ];
        }


        $str="";
        if (count($this->lesson_times_present_lesson_count)>0 ) {
            foreach ( $this->lesson_times_present_lesson_count as  $key => $val) {
                $str.="购满 $key 次课 送 $val 次课 <br/> ";
            }
            $arr[]=["--", ""];
            $arr[]=["优惠", $str  ];

        }else if ( count($this->price_off_money_list )>0 ) {
            foreach ( $this->price_off_money_list as  $key => $val) {
                $str.="购满 $key 元 立减  $val  元  <br/> ";
            }
            $arr[]=["--", ""];
            $arr[]=["优惠", $str  ];
        }else if ( count($this->lesson_times_off_perent_list)>0 ) {

            $arr[]=["--", ""];
            if  (isset( $this->lesson_times_off_perent_list[ E\Eperiod_flag::V_0  ]) ) {
                $str="";
                foreach ( $this->lesson_times_off_perent_list[ E\Eperiod_flag::V_0  ] as  $key => $val) {
                    $str.="购满 $key 次课 打 $val 折  <br/> ";
                }
                $arr[]=["全款优惠", $str  ];
            }

            if  (isset( $this->lesson_times_off_perent_list[ E\Eperiod_flag::V_1  ]) ) {
                $str="";
                foreach ( $this->lesson_times_off_perent_list[ E\Eperiod_flag::V_1  ] as  $key => $val) {
                    $str.="购满 $key 次课  打 $val 折 <br/> ";
                }
                $arr[]=["分期", $str  ];
            }

        }else if ( count($this->grade_off_perent_list)>0 ) {
            $arr[]=["--", ""];
            if  (isset( $this->grade_off_perent_list[ E\Eperiod_flag::V_0  ]) ) {
                $str="";
                foreach ( $this->grade_off_perent_list[ E\Eperiod_flag::V_0  ] as  $key => $val) {
                    $grade_str= E\Egrade::get_desc($key);
                    $str.=" $grade_str  打 $val 折  <br/> ";
                }
                $arr[]=["全款优惠", $str  ];
            }

            if  (isset( $this->grade_off_perent_list[ E\Eperiod_flag::V_1  ]) ) {
                $str="";
                foreach ( $this->grade_off_perent_list[ E\Eperiod_flag::V_1  ] as  $key => $val) {
                    $grade_str= E\Egrade::get_desc($key);
                    $str.=" $grade_str  打 $val 折  <br/> ";
                }
                $arr[]=["分期优惠", $str  ];
            }

        }

        return $arr;
    }


}