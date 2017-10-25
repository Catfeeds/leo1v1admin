<?php

namespace App\Jobs;

use App\Jobs\Job;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class send_wx_to_teacher extends Job implements ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $t_teacher_info = new \App\Models\t_teacher_info;

        $tea_list = $t_teacher_info->get_all_has_wx_tea();
        // $tea_list = [[
        //     'wx_openid' => 'oJ_4fxMltd-j8Pc4-GtJgll0i5SQ',
        //     'grade_start' => 1,
        //     'subject' => 1,
        //     'grade_part_ex' =>0
        // ]];
        /**
         * 模板ID   : rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o
         * 标题课程 : 待办事项提醒
         * {{first.DATA}}
         * 待办主题：{{keyword1.DATA}}
         * 待办内容：{{keyword2.DATA}}
         * 日期：{{keyword3.DATA}}
         * {{remark.DATA}}
         */

        foreach ($tea_list as $item) {
            $html = $this->get_new_qq_group_html($item['grade_start'],$item['grade_part_ex'],$item['subject']);


            $wx_openid = $item['wx_openid'];
            $template_id      = "rSrEhyiqVmc2_NVI8L6fBSHLSCO9CJHly1AU-ZrhK-o";
            $data['first']    = "老师您好，为了给大家提供更优质的服务，现对教研、排课及答疑群按学段和科目进行分类重建，旧群已作废。";
            $data['keyword1'] = "加入相关QQ群";
            $data['keyword2'] = $html;
            $data['keyword3'] = date("Y-m-d H:i",time());
            $data['remark']   = "";
            $url = "";
            \App\Helper\Utils::send_teacher_msg_for_wx($wx_openid,$template_id,$data,$url);
        }

    }


    public function get_new_qq_group_html($grade_start,$grade_part_ex,$subject){
        // 528851744 原答疑1群，人数已满

        if ( $grade_start >= 5 ) {
            $grade = 300;
        } else if ($grade_start >= 3) {
            $grade = 200;
        } else if($grade_start > 0 ) {
            $grade = 100;
        }else if ($grade_part_ex == 1) {
            $grade = 100;
        }else if ($grade_part_ex == 2) {
            $grade = 200;
        }else if ($grade_part_ex == 3) {
            $grade = 300;
        }else{
            $grade = 100;
        }

        $qq_answer = [
            1  => ["答疑-语文","126321887","可咨询软件使用等疑问"],
            2  => ["答疑-数学","29759286","可咨询软件使用等疑问"],
            3  => ["答疑-英语","451786901","可咨询软件使用等疑问"],
            99 => ["答疑-综合学科","513683916","可咨询软件使用等疑问"],
        ];
        $qq_group  = [
            '100' => [
                1=>[
                    ["教研-小学语文","653665526","可获取教研资料"],
                    ["排课-小学语文","387090573","可接试听课"]
                ],2=>[
                    ["教研-小学数学","644724773","可获取教研资料"],
                    ["排课-小学数学","527321518","可接试听课"],
                ],3=>[
                    ["教研-小学英语","653621142","可获取教研资料"],
                    ["排课-小学英语","456074027","可接试听课"],
                ],4=>[
                    ["教研-化学","652504426","可获取教研资料"],
                    ["排课-化学","608323943","可接试听课"],
                ],5=>[
                    ["教研-物理","652500552","可获取教研资料"],
                    ["排课-物理","534509273","可接试听课"],
                ],99=>[
                    ["教研-文理综合","652567225","可获取教研资料"],
                    ["排课-文理综合","598180360","可接试听课"],
                ],
            ],
            '200' => [
                1=>[
                    ["教研-初中语文","623708298","可获取教研资料"],
                    ["排课-初中语文","465023367","可接试听课"]
                ],2=>[
                    ["教研-初中数学","373652928","可获取教研资料"],
                    ["排课-初中数学","665840444","可接试听课"],
                ],3=>[
                    ["教研-初中英语","161287264","可获取教研资料"],
                    ["排课-初中英语","463756557","可接试听课"],
                ],4=>[
                    ["教研-化学","652504426","可获取教研资料"],
                    ["排课-化学","608323943","可接试听课"],
                ],5=>[
                    ["教研-物理","652500552","可获取教研资料"],
                    ["排课-物理","534509273","可接试听课"],
                ],99=>[
                    ["教研-文理综合","652567225","可获取教研资料"],
                    ["排课-文理综合","598180360","可接试听课"],
                ]
            ],
            '300' => [
                1=>[
                    ["教研-高中语文","653689781","可获取教研资料"],
                    ["排课-高中语文","573564364","可接试听课"]
                ],2=>[
                    ["教研-高中数学","644249518","可获取教研资料"],
                    ["排课-高中数学","659192934","可接试听课"],
                ],3=>[
                    ["教研-高中英语","456994484","可获取教研资料"],
                    ["排课-高中英语","280781299","可接试听课"],
                ],4=>[
                    ["教研-化学","652504426","可获取教研资料"],
                    ["排课-化学","608323943","可接试听课"],
                ],5=>[
                    ["教研-物理","652500552","可获取教研资料"],
                    ["排课-物理","534509273","可接试听课"],
                ],99=>[
                    ["教研-文理综合","652567225","可获取教研资料"],
                    ["排课-文理综合","598180360","可接试听课"],
                ]
            ],
        ];

        $html="";
        $list = @$qq_group[ $grade ][ $subject ] ? $qq_group[ $grade ][ $subject ] : $qq_group[ $grade ][99];
        $list[] = @$qq_answer[ $subject ] ? $qq_answer[ $subject ] : $qq_answer[99];
        // dd($list);
        foreach($list as $val){
            $html .= "\n【LEO】".$val[0]."\n群号：".$val[1]."\n群介绍：".$val[2];
        }
        return $html;
    }

}
