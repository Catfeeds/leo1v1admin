<?php
namespace App\OrderPrice\Activity;
use \App\Enums as E;
class activity_config_new extends  activity_new_base {


    public $open_flag=0 ;
    // 活动开始时间, 结束时间
    public $date_range=[];

    public  $period_flag_list =[ ];//分期,true, false

    //是否可不使用
    public $can_disable_flag =true;

    //是否需要分享微信
    public $is_need_share_wechat = 0;

    public  $check_grade_list=[] ; //适配年级 [ 101,102,103,104,105,106, . ]

    public  $max_count_activity_type_list=[]; // 总配额 组合
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


    public  $lesson_times_present_lesson_count =[]; //按课次数送课

    public  $price_off_money_list=[]; //按金额 立减
    public  $lesson_times_off_money_list=[]; //按课次 立减



    public function __construct(  $activity_config ,  $args ) {
        //\App\Helper\Utils::logger("activity_config:".json_encode($activity_config));
        parent::__construct($args);
        if ($activity_config ) {
            $this->init_activity_config($activity_config);
        }
    }

    public function init_activity_config( $item ) {

        $date_range_start = !empty($item['date_range_start']) ? date('Y-m-d H:i:s',$item['date_range_start']) : null;
        $date_range_end = !empty($item['date_range_end']) ? date('Y-m-d H:i:s',$item['date_range_end']) : null;
        $user_join_time_start = !empty($item['user_join_time_start']) ? date('Y-m-d H:i:s',$item['user_join_time_start']) : null;
        $user_join_time_end = !empty($item['user_join_time_end']) ? date('Y-m-d H:i:s',$item['user_join_time_end']) : null;
        $last_test_lesson_start = !empty($item['last_test_lesson_start']) ?  date('Y-m-d H:i:s',$item['last_test_lesson_start']) : null;
        $last_test_lesson_end = !empty($item['last_test_lesson_end']) ?  date('Y-m-d H:i:s',$item['last_test_lesson_end']) : null;
        $this->order_activity_type = $item['id'];
        $this->date_range = [];
        $this->user_join_time_range = [];
        $this->last_test_lesson_range = [];
        $this->lesson_times_range = [];
        $this->title = $item["title"] ;


        if( $date_range_start && $date_range_end){
            $this->date_range = [$date_range_start,$date_range_end];
        }
        if( $user_join_time_start && $user_join_time_end){
            $this->user_join_time_range = [$user_join_time_start,$user_join_time_end];
        }
        if( $last_test_lesson_start && $last_test_lesson_end){
            $this->last_test_lesson_range = [$last_test_lesson_start,$last_test_lesson_end];
        }
        if( $item['lesson_times_min'] && $item['lesson_times_max'] ){
            $this->lesson_times_range = [$item['lesson_times_min'],$item['lesson_times_max']];
        }
        if( !empty($item['grade_list']) ){
            $this->check_grade_list = explode(",",$item['grade_list']);
        }
        if( !empty($item['max_count_activity_type_list']) ){
            $this->max_count_activity_type_list = explode(",",$item['max_count_activity_type_list']);
        }
        if( isset($item['contract_type_list']) ){
            $this->contract_type_list = explode(",",$item['contract_type_list']);
        }

        if( isset($item['period_flag_list'])){
            $this->period_flag_list = explode(",",$item['period_flag_list']);
        }

        $this->open_flag = $item['open_flag'];

        $this->can_disable_flag = $item['can_disable_flag'] == 1 ? true : false;

        $this->max_count = $item['max_count'];
        $this->max_change_value = $item['max_change_value'];
        $this->need_spec_require_flag = $item['need_spec_require_flag'];
        $this->is_need_share_wechat = $item['is_need_share_wechat'];
        $discount_json = json_decode($item['discount_json'],true);

        switch($item['order_activity_discount_type']){
        case 1:
            //按课次数打折
            $this->lesson_times_off_perent_list = $discount_json;
            break;
        case 2:
            //按年级打折
            $this->grade_off_perent_list = $discount_json;
            break;
        case 3:
            //按课次数送课
            $this->lesson_times_present_lesson_count = $discount_json;
            break;
        case 4:
            //按金额 立减
            $this->price_off_money_list = $discount_json;
            break;
        case E\Eorder_activity_discount_type::V_5:
            //按金额 立减
            $this->lesson_times_off_money_list
                = $discount_json;
            break;

        }

    }

    protected function do_exec (&$out_args ,&$can_period_flag,   &$price,  &$present_lesson_count,  &$desc_list )
    {

        \App\Helper\Utils::logger("查看当前adminid: ".session("acc"));

        if ( in_array( session("acc"), ["jim","吴昊",  "boby","顾培根","林文彬"]) ) {
            if ( $this->open_flag ==0 ) { // 1,2
                return false;
            }
        }else{
            if ($this->open_flag !=1 ) { //1
                return false;
            }
        }

        //手动开启检查
        if ($this->can_disable_flag ) {
            if ( in_array( $this->order_activity_type ,$this->args["disable_activity_list"] ) ) {
                $desc_list[]=$this->gen_activity_item(2,  "手动不开启" , $price,  $present_lesson_count, $can_period_flag );
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
                $desc_list[]=$this->gen_activity_item(0,  "购买课时{$lesson_times}, 课时数不匹配  " , $price,  $present_lesson_count, $can_period_flag );
                return false;
            }
        }

        $activity_desc='';
        //用户加入时间检查
        if (count($this->user_join_time_range )==2 ) {
            $user_add_time= $this->task->t_seller_student_new->get_add_time($this->userid);
            $user_add_time_str=date("Y-m-d",$user_add_time );
            if  ( !($user_add_time >= strtotime( $this->user_join_time_range [0])
                    && $user_add_time <= (strtotime( $this->user_join_time_range[1]) +86400 ))) {
                $desc_list[]=$this->gen_activity_item(0,  "用户加入时间[$user_add_time_str]不匹配" , $price,  $present_lesson_count, $can_period_flag );
                return false ;
            }else{
                $activity_desc.=" 加入时间:$user_add_time_str, ";
            }
        }

        //用户试听时间检查
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
                    $desc_list[]=$this->gen_activity_item(0,  $activity_desc. "时间不匹配[{$this->last_test_lesson_range[0]}-{$this->last_test_lesson_range[1]}]" , $price,  $present_lesson_count, $can_period_flag );
                    return false;
                }

            }else{
                $desc_list[]=$this->gen_activity_item(0,  "没有试听课" , $price,  $present_lesson_count, $can_period_flag );
                return false;
            }

        }

        //配额检查
        if ($this->max_count){
            list( $count_check_ok_flag,$now_count, $activity_desc_cur_count)= $this->check_use_count($this->max_count );
            $activity_desc.= $activity_desc_cur_count;
            if (!$count_check_ok_flag) {
                $desc_list[]=$this->gen_activity_item(0,  $activity_desc. ",额度已用完"  , $price,  $present_lesson_count, $can_period_flag );
                return false;
            }
        }

        //查看是否必须分享微信
        if($this->is_need_share_wechat == 1){
            $is_share_result = \App\Helper\Utils::check_is_match($this->get_task_controler()->get_account_id(),$this->order_activity_type);
            \App\Helper\Utils::logger("查看是否必须分享微信: ".json_encode($is_share_result));
            if($is_share_result && @$is_share_result['ret'] == 0){
                $desc_list[]=$this->gen_activity_item(0,  $activity_desc. ",必须分享微信,你还没分享呢！<a target='_blank' href='".$is_share_result['url']."'>分享点击</a>"  , $price,  $present_lesson_count, $can_period_flag );
                return false;
            };
        }

        //\App\Helper\Utils::logger("按课次数送课: ".$this->title.json_encode($this->lesson_times_present_lesson_count));

        //按课次数送课
        if (count($this->lesson_times_present_lesson_count)>0 ) {
            list($find_free_lesson_level , $present_lesson_count_1 )=$this->get_value_from_config_ex(
                $this->lesson_times_present_lesson_count ,  $this->lesson_times , [0,0] );
            if ( $present_lesson_count_1) {

                list( $check_ok_flag,$now_all_change_value, $activity_desc_cur_count )= $this->check_max_change_value($this->max_change_value, $present_lesson_count_1);
                if ( $check_ok_flag ) {
                    $present_lesson_count += $present_lesson_count_1 *3;
                    $off_money=  $present_lesson_count_1 * $this->grade_price *0.6;
                    $desc_list[] = $this->gen_activity_item(1, "  $activity_desc 购满 $find_free_lesson_level 次课 送 $present_lesson_count_1 次课  $activity_desc_cur_count "   , $price,  $present_lesson_count, $can_period_flag, $present_lesson_count_1 , $off_money );
                    return true;
                }else{
                    $desc_list[]=$this->gen_activity_item(0, " $activity_desc   购满 $find_free_lesson_level 次课 送 $present_lesson_count_1 次课  $activity_desc_cur_count 配额不足  ", $price,  $present_lesson_count,$can_period_flag );
                }
            }else{
                $desc_list[]=$this->gen_activity_item(0, " $activity_desc {$this->lesson_times}次课 未匹配", $price,  $present_lesson_count,$can_period_flag );
                return false;
            }
        }

        //按金额 立减
        if (count($this->price_off_money_list)>0 ) {

            list($find_money_level , $off_money )=$this->get_value_from_config_ex(
                $this->price_off_money_list,  $price , [0,0] );
            if ( $off_money) {
                $price-=$off_money;
                $desc_list[] = $this->gen_activity_item(1, " $activity_desc 购满 $find_money_level 元 立减 $off_money 元 "   , $price,  $present_lesson_count, $can_period_flag, $off_money, $off_money ,$off_money );
                return true;
            }else{
                $desc_list[]=$this->gen_activity_item(0, " $activity_desc 购买 $price 未匹配", $price,  $present_lesson_count,$can_period_flag );
                return false;
            }
        }

        //按课次数打折
        if ( count ($this->lesson_times_off_perent_list) > 0 ) {
            list($find_lesson_times_level , $off_percent )=$this->get_value_from_config_ex(
                $this->lesson_times_off_perent_list,  $lesson_times , [0,100] );

            if ( $off_percent &&  $off_percent !=100  ) {
                $tmp_price=  intval($price* $off_percent /100) ;
                $diff_money= $price- $tmp_price;
                $price= $tmp_price;
                $desc_list[] = $this->gen_activity_item(1, " $activity_desc 购满 $find_lesson_times_level 次课 打 $off_percent 折   "   , $price,  $present_lesson_count, $can_period_flag , $diff_money,$diff_money );
                return true;
            }else{
                $desc_list[]=$this->gen_activity_item(0, " $activity_desc {$this->lesson_times}次课 未匹配", $price,  $present_lesson_count,$can_period_flag );
                return false;
            }

        }

        //按年级打折
        if ( count ($this->grade_off_perent_list) > 0 ) {
            $off_percent = @$this->grade_off_perent_list[$this->grade];
            if ($off_percent) {
                $grade_str= E\Egrade::get_desc($this->grade);
                $tmp_price =  intval($price* $off_percent /100) ;
                $diff_money= $price- $tmp_price;
                $price= $tmp_price;
                $desc_list[] = $this->gen_activity_item(1, " $activity_desc  年级 $grade_str 打 $off_percent 折   "   , $price,  $present_lesson_count, $can_period_flag, $diff_money );
                return true;
            }
        }

        //按课次 立减
        if (count($this->lesson_times_off_money_list )>0 ) {
            list($find_money_level , $off_money )=$this->get_value_from_config_ex(
                $this->lesson_times_off_money_list ,  $lesson_times , [0,0] );
            if ( $off_money) {
                $price-=$off_money;
                $desc_list[] = $this->gen_activity_item(1, " $activity_desc 购满 $find_money_level 次课 立减 $off_money 元 "   , $price,  $present_lesson_count, $can_period_flag, $off_money, $off_money ,$off_money );
                return true;
            }else{
                $desc_list[]=$this->gen_activity_item(0, " $activity_desc 购买 $lesson_times 次课 未匹配", $price,  $present_lesson_count,$can_period_flag );
                return false;
            }
        }

        //按课次数打折
        return true;
    }

    public function get_desc() {
        $arr=[];

        $arr[]=["开启:", E\Eopen_flag::get_desc($this->open_flag) ];
        $arr[]=["条件", "--" ];



        if ($this->need_spec_require_flag ) {
            $arr[]=["是否需要特殊申请",  E\Eboolean::get_desc( $this->need_spec_require_flag) ];
        }

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

        if (count($this->max_count_activity_type_list)>0 ) {
            //年级
            $tmp_str_arr=[];
            foreach ($this->max_count_activity_type_list   as $val ) {
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
            if  (isset( $this->lesson_times_off_perent_list) ) {
                $str="";
                foreach ( $this->lesson_times_off_perent_list as $key => $val) {
                    $str.="购满 $key 次课 打 $val 折  <br/> ";
                }
                $arr[]=["优惠", $str  ];
            }


        }else if ( count($this->grade_off_perent_list)>0 ) {
            $arr[]=["--", ""];
            $str="";
            foreach ( $this->grade_off_perent_list  as  $key => $val) {
                $grade_str= E\Egrade::get_desc($key);
                $str.=" $grade_str  打 $val 折  <br/> ";
            }
            $arr[]=["优惠", $str  ];

        }else if ( count($this->lesson_times_off_money_list )>0 ) {
            $arr[]=["--", ""];
            $str="";
            foreach ( $this->lesson_times_off_money_list as  $key => $val) {
                $str.="购满 $key 次课  立减 $val 元  <br/> ";
            }
            $arr[]=["优惠", $str  ];
        }

        return $arr;
    }

}