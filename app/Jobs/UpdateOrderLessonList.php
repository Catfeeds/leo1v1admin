<?php
namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateOrderLessonList extends Job implements ShouldQueue
{
    use InteractsWithQueue,SerializesModels;

    var $competition_flag;
    var $start_time;
    var $end_time;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($competition_flag,$start_time=0,$end_time=0)
    {
        $this->competition_flag = $competition_flag;
        $this->start_time       = $start_time;
        $this->end_time         = $end_time;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(){
        $t_order_info        = new \App\Models\t_order_info();
        $t_lesson_info       = new \App\Models\t_lesson_info();
        $t_order_lesson_list = new \App\Models\t_order_lesson_list();


        \App\Helper\Utils::logger("UpdateOrderLessonList start");
        $stu_list = $t_order_info->get_pay_user($this->competition_flag);
        foreach($stu_list as $v){
            $order_list  = $t_order_info->get_user_order_list($v['userid'],$this->competition_flag);
            $lesson_list = $t_lesson_info->get_user_lesson_list(
                $v['userid'],$this->competition_flag,$this->start_time,$this->end_time
            );

            $i                 = 0;
            $left_lesson_count = 0;
            $last_lessonid     = 0;
            $length            = count($lesson_list);
            foreach($order_list as $key => $val){
                if($i>$length-1 && $last_lessonid==0){
                    break;
                }
                $lesson_total     = $val['lesson_total']*$val['default_lesson_count']/100;
                $lesson_left      = $val['lesson_left']/100;
                $price            = $val['price']/100;
                $val['per_price'] = $lesson_total>0?round($price/$lesson_total,2):0;
                //课程跨包消耗的情况
                if($last_lessonid!=0){
                    /**
                     * lesson_count      记录的课堂消耗课时
                     * left_lesson_count 课堂待消耗课时
                     * lesson_left       合同得剩余课时
                     * order_status      合同的状态
                     */
                    if($lesson_left>=$left_lesson_count){
                        $lesson_count      = $left_lesson_count;
                        $lesson_left       = $lesson_left-$left_lesson_count;
                        $contract_status   = $lesson_left==0?2:1;
                        $lessonid          = $last_lessonid;
                        $last_lessonid     = 0;
                        $left_lesson_count = 0;
                    }else{
                        $lesson_count       = $lesson_left;
                        $left_lesson_count -= $lesson_left;
                        $lesson_left        = 0;
                        $contract_status    = 2;
                    }
                    $t_order_lesson_list->row_insert([
                        'orderid'      => $val['orderid'],
                        'lesson_count' => $lesson_count*100,
                        'lessonid'     => $lessonid,
                        'per_price'    => $val['per_price']*100,
                        'price'        => $val['per_price']*$lesson_count*100,
                        'userid'       => $v['userid'],
                    ]);
                    $t_order_info->field_update_list($val['orderid'],[
                        "lesson_left"     => $lesson_left*100,
                        "contract_status" => $contract_status,
                    ]);
                }
                $old_contract_status= $t_order_info->get_contract_status($val['orderid']);

                if($lesson_left>0){
                    for(;$i<=$length-1;$i++){
                        $lessonid      = $lesson_list[$i]['lessonid'];
                        $lesson_count  = $lesson_list[$i]['lesson_count']/100;

                        $check_flag = $t_order_lesson_list->check_lessonid($lessonid);
                        if($check_flag>0){
                            continue;
                        }

                        if($lesson_count<=$lesson_left){
                            $lesson_left     = $lesson_left-$lesson_count;
                            $contract_status = $lesson_left==0?2:1;
                        }elseif($lesson_count>$lesson_left){
                            $last_lessonid     = $lessonid;
                            $left_lesson_count = $lesson_count-$lesson_left;
                            $lesson_count      = $lesson_left;
                            $lesson_left       = 0;
                            $contract_status   = 2;
                        }

                        if($old_contract_status==3){
                            $contract_status=3;
                        }

                        $t_order_info->field_update_list($val['orderid'],[
                            "lesson_left"     => $lesson_left*100,
                            "contract_status" => $contract_status,
                        ]);

                        $t_order_lesson_list->row_insert([
                            'orderid'      => $val['orderid'],
                            'lesson_count' => $lesson_count*100,
                            'lessonid'     => $lessonid,
                            'per_price'    => $val['per_price']*100,
                            'price'        => $val['per_price']*$lesson_count*100,
                            'userid'       => $v['userid'],
                        ]);
                        \App\Helper\Utils::logger("UpdateOrderLessonList has insert lesson".$lessonid);

                        if($contract_status==2){
                            $i++;
                            break;
                        }
                    }
                }
            }
        }
        \App\Helper\Utils::logger("job UpdateOrderLessonList end");
    }

}
