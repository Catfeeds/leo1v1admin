<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use \App\Enums as E;
use App\Helper\Utils;

class seller_student2 extends Controller
{
    use CacheNick;
    use TeaPower;

    public function show_order_activity_info() {
        $open_flag   = $this->get_in_int_val('id_open_flag',-1);
        $can_disable_flag   = $this->get_in_int_val('id_can_disable_flag',-1);
        $order_activity_discount_type   = $this->get_in_int_val('id_discount_type',-1);
        $need_spec_require_flag   = $this->get_in_int_val('id_spec_need_flg',-1);

        $where_arr = [
            ["open_flag=%d" , $open_flag,-1 ],
            ["can_disable_flag=%d",$can_disable_flag,-1 ],
            ["order_activity_discount_type=%d",$order_activity_discount_type,-1 ],
            ["need_spec_require_flag=%d",$need_spec_require_flag,-1 ],
        ];

        $page_num        = $this->get_in_page_num();

        $ret_list = $this->t_order_activity_config->get_list($where_arr,$page_num);

        $gradeArr = E\Egrade_only::$desc_map;
        if($ret_list['list']){
            foreach( $ret_list['list'] as &$item){
                $item = $this->return_item($item);
            }
        }
        return $this->pageView(__METHOD__,$ret_list,
                               [
                                   "_publish_version"      => "201711251256",
                                   "gradeArr" => $gradeArr,
                               ]
        );
    }

    public function add_order_activity(){
        $title = $this->get_in_str_val('title');
        $id = $this->get_in_int_val('id');
        if(empty($id)){
            $id = date('Ymd',strtotime('now')).rand(1,99);
        }else{
            $item = $this->t_order_activity_config->get_by_id($id);
            if($item){
                $result['status'] = 500;
                $result['msg'] = "活动id:".$id."已经存在，请换个id输入！";
                return $this->output_succ($result);
            }
        }

        $period_flag_list = $this->get_in_str_val('period_flag_list','0,1');
        $contract_type_list = $this->get_in_str_val('contract_type_list','0,3');
        $grade_list = $this->get_in_str_val('grade_list',0);
        $max_count = $this->get_in_int_val('max_count',20);

        $can_disable_flag = $this->get_in_int_val('can_disable_flag',1);
        $open_flag = $this->get_in_int_val('open_flag',2);
        $need_spec_require_flag = $this->get_in_int_val('need_spec_require_flag',0);
        $order_activity_discount_type = $this->get_in_int_val('order_activity_discount_type',1);
        $power_value =  $this->get_in_int_val('power_value',50);


        $ret = $this->t_order_activity_config->row_insert([
            "id"   => $id,
            "title"   => $title,
            "period_flag_list"   => $period_flag_list,
            "contract_type_list"   => $contract_type_list,
            "grade_list"   => $grade_list,
            "can_disable_flag"   => $can_disable_flag,
            "open_flag"   => $open_flag,
            "need_spec_require_flag"=>$need_spec_require_flag,
            "order_activity_discount_type"   => $order_activity_discount_type,
            "power_value" => $power_value
        ]);
        if($ret){
            $result['status'] = 200;
            $result['msg'] = "插入成功";
            return $this->output_succ($result);
        }else{
            $result['status'] = 500;
            $result['msg_get_queue($key, $perms)'] = "插入失败！";
            return $this->output_succ($result);

        }
    }

    public function dele_order_activity(){
        $id = $this->get_in_int_val('id');
        $this->t_order_activity_config->del_by_id($id);
        return $this->output_succ();
    }

    public function get_order_activity(){
        $id = $this->get_in_int_val('id');
        $item = $this->t_order_activity_config->get_by_id($id);
        $activity_type_list = []; //已选组合列表
        $discount_list = [];           //优惠信息
        if($item){
            $item['period_flag_list_str'] = '';
            if($item['period_flag_list']){
                $periodArr = explode(",",$item['period_flag_list']);
                foreach($periodArr as $pe){
                    $item['period_flag_list_str'] .= E\Eperiod_flag::get_desc($pe).',';
                }
                $item['period_flag_list_str'] = substr($item['period_flag_list_str'],0,-1);
            }else{
                $item['period_flag_list_str'] = E\Eperiod_flag::get_desc($item['period_flag_list']);
            }

            $item['contract_type_list_str'] = '';
            if($item['contract_type_list']){
                $conArr = explode(",",$item['contract_type_list']);
                foreach($conArr as $con){
                    $item['contract_type_list_str'] .= E\Econtract_type::get_desc($con).',';
                }
                $item['contract_type_list_str'] = substr($item['contract_type_list_str'],0,-1);
            }else{
                $item['contract_type_list_str'] = E\Econtract_type::get_desc($item['contract_type_list']);
            }
            $item['is_need_share_wechat_str']   = E\Eboolean::get_desc($item['is_need_share_wechat']);
            $item['need_spec_require_flag_str']   = E\Eboolean::get_desc($item['need_spec_require_flag']);
            $item['can_disable_flag_str']   = E\Ecan_disable_flag::get_desc($item['can_disable_flag']);
            $item['open_flag_str']   = E\Eopen_flag::get_desc($item['open_flag']);
            $item['order_activity_discount_type_str']   = E\Eorder_activity_discount_type::get_desc($item['order_activity_discount_type']);
            if($item['grade_list']){
                if( strpos($item['grade_list'], ",")){
                    $gradeArr = explode(",",$item['grade_list']);
                    $item['grade_list_str'] = '';
                    foreach( $gradeArr as $grade){
                        $item['grade_list_str'] .= E\Egrade_only::get_desc($grade).',';
                    }
                    $item['grade_list_str'] = substr($item['grade_list_str'],0,-1);

                }else{
                    $item['grade_list_str'] = E\Egrade_only::get_desc($item['grade_list']);
                }
            }else{
                $item['grade_list_str'] = '';
            }
            //时间
            // \App\Helper\Utils::unixtime2date_for_item($item,"date_range_start","","Y-m-d");
            // \App\Helper\Utils::unixtime2date_for_item($item,"date_range_end","","Y-m-d");
            // \App\Helper\Utils::unixtime2date_for_item($item,"user_join_time_start","","Y-m-d");
            // \App\Helper\Utils::unixtime2date_for_item($item,"user_join_time_end","","Y-m-d");
            // \App\Helper\Utils::unixtime2date_for_item($item,"last_test_lesson_start","","Y-m-d");
            // \App\Helper\Utils::unixtime2date_for_item($item,"last_test_lesson_end","","Y-m-d");
            // \App\Helper\Utils::unixtime2date_for_item($item,"success_test_lesson_start","","Y-m-d");
            // \App\Helper\Utils::unixtime2date_for_item($item,"success_test_lesson_end","","Y-m-d");

            $item["date_range_start"] = !empty($item["date_range_start"]) ? date('Y-m-d',$item["date_range_start"]) : '';
            $item["date_range_end"] = !empty($item["date_range_end"]) ? date('Y-m-d',$item["date_range_end"]) : '';
            $item["user_join_time_start"] = !empty($item["user_join_time_start"]) ? date('Y-m-d',$item["user_join_time_start"]) : '';
            $item["user_join_time_end"] = !empty($item["user_join_time_end"]) ? date('Y-m-d',$item["user_join_time_end"]) : '';
            $item["last_test_lesson_start"] = !empty($item["last_test_lesson_start"]) ? date('Y-m-d',$item["last_test_lesson_start"]) : '';
            $item["last_test_lesson_end"] = !empty($item["last_test_lesson_end"]) ? date('Y-m-d',$item["last_test_lesson_end"]) : '';
            $item["success_test_lesson_start"] = !empty($item["success_test_lesson_start"]) ? date('Y-m-d',$item["success_test_lesson_start"]) : '';
            $item["success_test_lesson_end"] = !empty($item["success_test_lesson_end"]) ? date('Y-m-d',$item["success_test_lesson_end"]) : '';

            //寻找配额组合
            $activity_type_list = $this->t_order_activity_config->get_activity_exits_list($item['max_count_activity_type_list']);


            //优惠列表展示
            $discount_list = $this->discount_list($item['order_activity_discount_type'],$item['discount_json']);

        }
        $gradeArr = E\Egrade_only::$desc_map;
        return $this->pageView(__METHOD__,null,
                               [
                                   "_publish_version"      => "201712281757",
                                   "ret_info" => $item,
                                   "gradeArr" => $gradeArr,
                                   "discount_list"=>$discount_list,
                                   'activity_type_list' => $activity_type_list,
                               ]
        );

    }

    private function return_item($item){
        $item['period_flag_list_str'] = '';
        if($item['period_flag_list']){
            $periodArr = explode(",",$item['period_flag_list']);
            foreach($periodArr as $pe){
                $item['period_flag_list_str'] .= E\Eperiod_flag::get_desc($pe).',';
            }
            $item['period_flag_list_str'] = substr($item['period_flag_list_str'],0,-1);
        }else{
            $item['period_flag_list_str'] = E\Eperiod_flag::get_desc($item['period_flag_list']);
        }

        $item['contract_type_list_str'] = '';
        if($item['contract_type_list']){
            $conArr = explode(",",$item['contract_type_list']);
            foreach($conArr as $con){
                $item['contract_type_list_str'] .= E\Econtract_type::get_desc($con).',';
            }
            $item['contract_type_list_str'] = substr($item['contract_type_list_str'],0,-1);
        }else{
            $item['contract_type_list_str'] = E\Econtract_type::get_desc($item['contract_type_list']);
        }
        $item['is_need_share_wechat_str']   = E\Eboolean::get_desc($item['is_need_share_wechat']);
        $item['need_spec_require_flag_str']   = E\Eboolean::get_desc($item['need_spec_require_flag']);
        $item['can_disable_flag_str']   = E\Ecan_disable_flag::get_desc($item['can_disable_flag']);
        $item['open_flag_str']   = E\Eopen_flag::get_desc($item['open_flag']);
        $item['order_activity_discount_type_str']   = E\Eorder_activity_discount_type::get_desc($item['order_activity_discount_type']);
        if($item['grade_list']){
            if( strpos($item['grade_list'], ",")){
                $gradeArr = explode(",",$item['grade_list']);
                $item['grade_list_str'] = '';
                foreach( $gradeArr as $grade){
                    $item['grade_list_str'] .= E\Egrade_only::get_desc($grade).',';
                }
                $item['grade_list_str'] = substr($item['grade_list_str'],0,-1);

            }else{
                $item['grade_list_str'] = E\Egrade_only::get_desc($item['grade_list']);
            }
        }else{
            $item['grade_list_str'] = '未设置';
        }

        if( $item['date_range_start'] && $item['date_range_end']){
            $item['date_range_time'] = date('Y-m-d',$item["date_range_start"]).' 至 '.date('Y-m-d',$item["date_range_end"]);
        }else{
            $item['date_range_time'] = "未设置";
        }

        if( $item['lesson_times_min'] && $item['lesson_times_max'] ){
            $item['lesson_times_range'] = $item['lesson_times_min']."-".$item['lesson_times_max'];
        }else{
            $item['lesson_times_range'] = "未设置";
        }

        if( $item['user_join_time_start'] && $item['user_join_time_end']){
            $item['user_join_time_range'] = date('Y-m-d',$item["user_join_time_start"]).' 至 '.date('Y-m-d',$item["user_join_time_end"]);
        }else{
            $item['user_join_time_range'] = "未设置";
        }

        if( $item['last_test_lesson_start'] && $item['last_test_lesson_end']){
            $item['last_test_lesson_range'] = date('Y-m-d',$item["last_test_lesson_start"]).' 至 '.date('Y-m-d',$item["last_test_lesson_end"]);
        }else{
            $item['last_test_lesson_range'] = "未设置";
        }

        //优惠列表展示
        $discount_str = '';
        if($item['discount_json']){
            //优惠列表展示
            $discount_list = $this->discount_list($item['order_activity_discount_type'],$item['discount_json']);
            if(!empty($discount_list)){
                foreach( $discount_list as $v){
                    $discount_str .= $v.' ; ';
                }
            }
        }

        $item['discount_list'] = $discount_str;

        //配额组合
        $activity_type_list_str = '';
        if($item['max_count_activity_type_list']){
            $activity_type_list = $this->t_order_activity_config->get_activity_exits_list($item['max_count_activity_type_list']);
            if(!empty($activity_type_list)){
                foreach( $activity_type_list as $v){
                    $activity_type_list_str .= $v['title'].' ; ';
                }
            }
        }
        $item['activity_type_list_str'] = $activity_type_list_str;
        return $item;
    }


    //获取所有活动
    public function get_all_activity(){
        $id = $this->get_in_int_val('id',-1);
        $open_flag = $this->get_in_int_val('open_flag',-1);
        $title = $this->get_in_str_val('title');
        $page_num  = $this->get_in_page_num();
        $ret  = \App\Helper\Utils::list_to_page_info([]);
        $ret = $this->t_order_activity_config->get_all_activity($id,$open_flag,$title,$page_num);

        if($ret){
            foreach($ret['list'] as &$item){
                $item['open_flag_str'] = E\Eopen_flag::get_desc($item['open_flag']);
            }
        }

        return $this->output_ajax_table($ret, [ "lru_list" => [] ]);
    }

    //获取当前时间内所有有效活动
    public function get_current_activity(){

        $open_flag   = $this->get_in_int_val('id_open_flag',-1);
        $page_num        = $this->get_in_page_num();
        $ret = $this->t_order_activity_config->get_current_activity($open_flag,$page_num);

        if($ret['list']){
            foreach($ret['list'] as &$item){
                $item['is_need_share_wechat_str']   = E\Eboolean::get_desc($item['is_need_share_wechat']);
                $item['open_flag_str']   = E\Eopen_flag::get_desc($item['open_flag']);
                if( $item['date_range_start'] && $item['date_range_end']){
                    $item['date_range_time'] = date('Y-m-d',$item["date_range_start"]).' 至 '.date('Y-m-d',$item["date_range_end"]);
                }else{
                    $item['date_range_time'] = "未设置";
                }

            }
        }
        return $this->pageView(__METHOD__,$ret,
                               [
                                   "_publish_version"      => "201711281348",
                                   "ret_info" => $ret,
                               ]
        );

    }

    //拿到所有的配额组合
    public function get_activity_all_list(){
        $id = $this->get_in_int_val('id');
        $activity_type_list_id = $this->get_in_str_val('max_count_activity_type_list');

        $activity_type_list = $this->t_order_activity_config->get_activity_all_list($id);

        $result['status'] = 201;
        $result['data'] = null;
        if(empty($activity_type_list)){
            return $this->output_succ($result);
        }
        $all_activity_type_list = array_column($activity_type_list, 'title', 'id');

        $exits_arr = [];
        $info = [];
        if($activity_type_list_id){
            $exits_arr = explode(',',$activity_type_list_id);
            foreach( $all_activity_type_list as $k => $v){
                $info[$k] = [
                    'title' => $v,
                    'check' => "unchecked"
                ];
                if(in_array($k,$exits_arr)>0){
                    $info[$k]['check'] = "checked";
                }
            }

        }else{
            foreach( $all_activity_type_list as $k => $v){
                $info[$k] = [
                    'title' => $v,
                    'check' => "unchecked"
                ];

            }
        }

        $result['status'] = 200;
        $result['data'] = $info;
        // \App\Helper\Utils::logger("返回结果: ".json_encode($result));

        return $this->output_succ($result);
    }

    private function discount_list($discount_type,$discout_json){
        $dicount_list = array();
        $before = '';
        $middle = '';
        $after = '';
        if(!$discout_json){
            return $dicount_list;
        }

        $discount_type = (int)$discount_type;
        $discout = json_decode($discout_json);
        if(is_array($discout) || is_object($discout)){
            switch($discount_type){
            case 1:
                $before = '满课次数：';
                $middle = ' 打';
                $after = '折';
                foreach( $discout as $var => $val){
                    $dicount_list[] = $before.$var.$middle.$val.$after;
                }
                break;
            case 2:
                $before = '年级：';
                $middle = ' 打';
                $after = '折';
                foreach( $discout as $var => $val){
                    $grade = E\Egrade_only::get_desc($var);
                    $dicount_list[] = $before.$grade.$middle.$val.$after;
                }
                break;
            case 3:
                $before = '满课次数：';
                $middle = ' 送';
                $after = '课';
                foreach( $discout as $var => $val){
                    $dicount_list[] = $before.$var.$middle.$val.$after;
                }
                break;
            case 4:
                $before = '满金额：￥';
                $middle = ' 立减￥';
                $after = '元';
                foreach( $discout as $var => $val){
                    $dicount_list[] = $before.$var.$middle.$val.$after;
                }
                break;
            case 5:
                $before = '满课次数：';
                $middle = ' 立减￥';
                $after = '元';
                foreach( $discout as $var => $val){
                    $dicount_list[] = $before.$var.$middle.$val.$after;
                }
                break;

            default:
                $dicount_list = array();
                break;
            }

        }

        return $dicount_list;
    }

    public function update_order_activity_01(){
        //返回结果
        $result['status'] = 200;

        $id = $this->get_in_int_val('id');


        $title = $this->get_in_str_val('title','-1');
        $date_range_start = trim($this->get_in_str_val('date_range_start',null));
        $date_range_end = trim($this->get_in_str_val('date_range_end',null));
        $lesson_times_min = $this->get_in_int_val('lesson_times_min',null);
        $lesson_times_max = $this->get_in_int_val('lesson_times_max',null);

        $updateArr = [
            'title' => $title,
            'lesson_times_min' => $lesson_times_min,
            'lesson_times_max' => $lesson_times_max,
        ];

        !empty($date_range_start) ? $updateArr['date_range_start'] = strtotime($date_range_start.' 00:00:00') : $updateArr['date_range_start'] = null;
        !empty($date_range_end) ? $updateArr['date_range_end'] = strtotime($date_range_end.' 23:59:59') : $updateArr['date_range_end'] = null;


        if($this->t_order_activity_config->field_update_list($id,$updateArr)){
            $result['info'] = '更新成功';
            return $this->output_succ($result);
        }else{
            $result['info'] = '更新失败';
            $result['status'] = 500;
            return $this->output_succ($result);

        };

    }

    public function update_order_activity_02(){
        $id = $this->get_in_int_val('id');
        $contract_type_list = $this->get_in_str_val('contract_type_list',null);
        $period_flag_list = $this->get_in_str_val('period_flag_list',null);
        $grade_list = $this->get_in_str_val('grade_list',null);
        $updateArr = [
            'contract_type_list' => $contract_type_list,
            'period_flag_list' => $period_flag_list,
            'grade_list' => $grade_list,
        ];
        if($this->t_order_activity_config->field_update_list($id,$updateArr)){
            return $this->output_succ();
        }else{
            return $this->output_err("更新出错！");
        };

    }

    public function update_order_activity_03(){
        $id = $this->get_in_int_val('id');
        $power_value = $this->get_in_int_val('power_value',null);
        $max_count = $this->get_in_int_val('max_count',null);
        $diff_max_count = $this->get_in_int_val('diff_max_count',100);
        $max_change_value = $this->get_in_int_val('max_change_value',null);
        $updateArr = [
            'power_value' => $power_value,
            'max_count' => $max_count,
            'max_change_value' => $max_change_value,
            'diff_max_count' => $diff_max_count
        ];
        if($this->t_order_activity_config->field_update_list($id,$updateArr)){
            return $this->output_succ();
        }else{
            return $this->output_err("更新出错！");
        };

    }

    public function update_order_activity_04(){
        $id = $this->get_in_int_val('id');
        $max_count_activity_type_list = $this->get_in_str_val('max_count_activity_type_list',null);
        $updateArr = [
            'max_count_activity_type_list' => $max_count_activity_type_list,
        ];
        if($this->t_order_activity_config->field_update_list($id,$updateArr)){
            return $this->output_succ();
        }else{
            return $this->output_err("更新出错！");
        };
    }

    public function update_order_activity_05(){
        $id = $this->get_in_int_val('id');
        $can_disable_flag = $this->get_in_int_val('can_disable_flag',1);
        $open_flag = $this->get_in_int_val('open_flag',2);
        $need_spec_require_flag = $this->get_in_int_val('need_spec_require_flag',0);
        $is_need_share_wechat = $this->get_in_int_val('is_need_share_wechat',0);

        $updateArr = [
            'can_disable_flag' => $can_disable_flag,
            'open_flag' => $open_flag,
            'need_spec_require_flag' => $need_spec_require_flag,
            'is_need_share_wechat'   => $is_need_share_wechat
        ];
        if($this->t_order_activity_config->field_update_list($id,$updateArr)){
            return $this->output_succ();
        }else{
            return $this->output_err("更新出错！");
        };

    }

    public function update_order_activity_06(){
        $id = $this->get_in_int_val('id');
        $user_join_time_start = trim($this->get_in_str_val('user_join_time_start',null));
        $user_join_time_end = trim($this->get_in_str_val('user_join_time_end',null));
        $last_test_lesson_start = trim($this->get_in_str_val('last_test_lesson_start',null));
        $last_test_lesson_end = trim($this->get_in_str_val('last_test_lesson_end',null));
        $success_test_lesson_start = trim($this->get_in_str_val('success_test_lesson_start',null));
        $success_test_lesson_end = trim($this->get_in_str_val('success_test_lesson_end',null));

        $updateArr = [];

        !empty($user_join_time_start) ? $updateArr['user_join_time_start'] = strtotime($user_join_time_start.' 00:00:00') : $updateArr['user_join_time_start'] = null;
        !empty($user_join_time_end) ? $updateArr['user_join_time_end'] = strtotime($user_join_time_end.' 23:59:59') : $updateArr['user_join_time_end'] = null;
        !empty($last_test_lesson_start) ? $updateArr['last_test_lesson_start'] = strtotime($last_test_lesson_start.' 00:00:00') : $updateArr['last_test_lesson_start'] = null;
        !empty($last_test_lesson_end) ? $updateArr['last_test_lesson_end'] = strtotime($last_test_lesson_end.' 23:59:59') : $updateArr['last_test_lesson_end'] = null;
        !empty($success_test_lesson_start) ? $updateArr['success_test_lesson_start'] = strtotime($success_test_lesson_start.' 00:00:00') : $updateArr['success_test_lesson_start'] = null;
        !empty($success_test_lesson_end) ? $updateArr['success_test_lesson_end'] = strtotime($success_test_lesson_end.' 23:59:59') : $updateArr['success_test_lesson_end'] = null;


        if($this->t_order_activity_config->field_update_list($id,$updateArr)){
            return $this->output_succ();
        }else{
            return $this->output_err("更新出错！");
        };


    }

    public function update_order_activity_07(){
        $id = $this->get_in_int_val('id');

        $order_activity_discount_type = $this->get_in_int_val('order_activity_discount_type',null);
        $discount_json = $this->get_in_str_val('discount_json',null);

        $updateArr = [
            'order_activity_discount_type' => $order_activity_discount_type,
            'discount_json' => $discount_json,
        ];
        if($this->t_order_activity_config->field_update_list($id,$updateArr)){
            return $this->output_succ();
        }else{
            return $this->output_err("更新出错！");
        };

    }

    public function update_order_activity_08(){
        $id = $this->get_in_int_val('id');
        $discount_json = trim($this->get_in_str_val('discount_json',null));
        $updateArr = [
            'discount_json' => $discount_json,
        ];
        if($this->t_order_activity_config->field_update_list($id,$updateArr)){
            return $this->output_succ();
        }else{
            return $this->output_err("更新出错！");
        };
    }

    public function update_power_value(){
        $id = $this->get_in_int_val('id');
        $power_value = $this->get_in_int_val('power_value',90);
        $open_flag = $this->get_in_int_val('open_flag',2);
        $updateArr = [
            'power_value' => $power_value,
            'open_flag' => $open_flag,
        ];
        if($this->t_order_activity_config->field_update_list($id,$updateArr)){
            return $this->output_succ();
        }else{
            return $this->output_err("更新出错！");
        };

    }

    //给一般人员使用的查看当前有效活动
    public function get_current_commom_activity(){

        $open_flag   = $this->get_in_int_val('id_open_flag',-1);
        $page_num        = $this->get_in_page_num();
        $ret = $this->t_order_activity_config->get_current_activity($open_flag,$page_num);

        if($ret['list']){
            foreach($ret['list'] as &$item){
                $item = $this->return_item($item);
            }
        }
        return $this->pageView(__METHOD__,$ret,
                               [
                                   "_publish_version"      => "201711281348",
                                   "ret_info" => $ret,
                               ]
        );
    }

    //只运行一般人员更新当前活动的最大合同数
    public function update_current_commom_activity(){
        $id = $this->get_in_int_val('id');
        $max_count = $this->get_in_int_val('max_count');
        $updateArr = [
            'max_count' => $max_count,
        ];
        if($this->t_order_activity_config->field_update_list($id,$updateArr)){
            return $this->output_succ();
        }else{
            return $this->output_err("更新出错！");
        };

    }
}
