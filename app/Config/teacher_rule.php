<?php
namespace App\Config;
use Illuminate\Support\Facades\Redis ;
use \App\Enums as E;

class teacher_rule{
    /**
     * 工资体系奖励规则
     * 以下键值为 t_teacher_money_type 表中的 type 字段的值
     * 1     第二版 2016年12月之前老版规则执行,和第一版并行
     * 2,3,5 第一版 2016年12月之前老版规则执行,和第二版并行
     * 4     第三版 2016年12月之后第三版，平台合作执行此规则
     * 6     第四版 2017年9月之后第四版执行此规则
     * 7     武汉全职老师执行此规则
     */
    static public $rule_type = [
        1=>[
            0     => 0,
            1500  => 3,
            4500  => 6,
            7500  => 13,
            13500 => 16,
            18000 => 19
        ],2=>[
            0     => 0,
            15000 => 3,
            22500 => 7
        ],3=>[
            0     => 0,
            15000 => 4,
            22500 => 6
        ],4=>[
            0     => 0,
            1500  => 3,
            4500  => 6,
            10500 => 9,
            16500 => 12,
            22500 => 15,
            28500 => 18,
        ],5=>[
            0     => 0,
            1000  => 5,
            6000  => 10,
            12000 => 20,
        ],6=>[
            0     => 0,
            1000  => 4,
            3000  => 7,
            9000  => 10,
            15000 => 15,
            24000 => 20,
        ],7=>[
            0     => 0,
            1500  => 3,
            4500  => 6,
            7500  => 13,
            13500 => 16,
            18000 => 19,
            24000 => 22,
        ]
    ];

    /**
     * 邀请有奖规则
     * 以下键值分别为老师身份类型和达到人数及其奖励金额
     * 1 高校生推荐类型
     * 2 在职老师推荐类型
     */
    static public $reference_rule = [
        1=>[
            0  => 20,
            10 => 30,
            20 => 50,
            30 => 60,
        ],2=>[
            0  => 40,
            10 => 50,
            20 => 70,
            30 => 80,
        ]
    ];

    /**
     * @param type 老师工资对应的类型
     * @return array
     */
    static public function get_teacher_rule($type="all"){
        $teacher_rule = self::$rule_type;
        if(isset($teacher_rule[$type])){
            return $teacher_rule[$type];
        }else{
            return $teacher_rule;
        }
    }

    /**
     * 获取老师工资类型的累计课时对应范围
     * @param type 老师工资对应的类型
     * @return array
     */
    static public function get_teacher_lesson_count_range($type){
        $rule_type = self::$rule_type;
        $list = array_keys($rule_type[$type]);
        $lesson_count_range = [];

        for($i=0,$last_lesson_count=0;;$i++){
            if(!isset($list[$i])){
                $lesson_count_range[$i]=strval(">=".$last_lesson_count);
                break;
            }elseif($list[$i]!=0){
                $lesson_count_range[$i]=($last_lesson_count)."-".($list[$i]/100-1);
            }
            $last_lesson_count=$list[$i]/100;
        }
        return $lesson_count_range;
    }

    /**
     * 检测老师的推荐类型
     */
    static public function check_reference_type($identity){
        if(in_array($identity,[5,6,7])){
            $reference_type = 2;//公校老师
        }else{
            $reference_type = 1;//大学生
        }
        return $reference_type;
    }

    /**
     * @param identity 老师身份类型
     */
    static public function get_teacher_reference_rule($identity){
        $reference_type = self::check_reference_type($identity);
        $teacher_rule   = self::$reference_rule;

        if(isset($teacher_rule[$reference_type])){
            return $teacher_rule[$reference_type];
        }else{
            return [];
        }
    }


}