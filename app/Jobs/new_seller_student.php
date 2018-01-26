<?php
namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use \App\Enums as E;

class new_seller_student extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    var $userid;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userid  )
    {

        parent::__construct();
        $this->userid=$userid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $n=new \App\Models\t_seller_student_new_b2();
        $need_count= \App\Helper\Config::get_day_system_assign_count();
        if( $n->get_today_can_system_assign_count() < $need_count ) {
            //分配模式 调整
            $n->field_update_list( $this->userid, [
                "seller_student_assign_type"=> E\Eseller_student_assign_type::V_1,
            ]);
        }

    }
}