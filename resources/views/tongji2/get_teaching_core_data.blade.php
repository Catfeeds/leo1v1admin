@extends('layouts.app')
@section('content')


    <section class="content ">
        <div class="row">
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td rowspan="2">时间</td>
                    <td colspan="22" align="center">核心数据</td>
                    <td colspan="13" align="center">招师数据</td>
                    <td colspan="3" align="center">培训数据</td>
                    <td colspan="16" align="center">教务数据</td>
                    <td colspan="21" align="center">运营数据</td>                  
                   
                    <td  >操作</td>
                </tr>
                <tr>
                    <td title="本月入职的老师">新老师数(入职)</td>
                    <td title="本月上过课的老师">本月上课老师数</td>
                    <td title="本月入职且在本月上过课的老师">本月新增上课老师数</td>
                    <td title="本月之前入职且在本月上过课的老师">本月留存上课老师数</td>
                    <td title="本月之前入职且未在本月上过课的老师">本月流失上课老师数</td>
                    <td title="本月之前入职且未在本月前三个月(包含本月)上过课的老师">流失老师数(三个月未上课)</td>
                    <td title="本月有过常规课的学生">在读学生数</td>
                    <td title="本月有过常规课的老师/学生">师生比</td>
                    <td title="本月上过试听课的老师">试听课老师数</td>
                    <td title="本月上过常规课的老师">常规课老师数</td>
                    <td title="试听课学生与老师教材匹配度">试听课学生与老师教材匹配度</td>
                    <td>新老师入职通过率</td>
                    <td>新老师入职时长</td>
                    <td>新老师30天留存率</td>
                    <td>新老师60天留存率</td>
                    <td>新老师90天留存率</td>
                    <td>新老师30天转化率</td>
                    <td>新老师60天转化率</td>
                    <td>新老师90天转化率</td>
                    <td>新老师30天平均课耗数</td>
                    <td>新老师60天平均课耗数</td>
                    <td>新老师90天平均课耗数</td>
                    <td>新老师公校老师数</td>
                    <td>  新老师在校学生数</td>
                    <td>   新老师机构老师数</td>

                    <td>  面试邀约数</td>
                    <td>    面试通过数</td>
                    <td>   新师培训数</td>
                    <td>  模拟试听数</td>
                    <td>  新老师入职数</td>

                    <td>  面试邀约时长</td>
                    <td>  面试通过时长</td>
                    <td>  新师培训时长</td>
                    <td> 模拟试听时长</td>
                    <td> 新老师入职时长</td>
                    <td>培训次数</td>
                    <td>培训参与率</td>
                    <td>培训通过率</td>
                    <td>月排课数</td>
                    <td> 排课转化率</td>
                   <td> 排课转化率（新签）</td>
                    <td>排课转化率（扩科）</td>
                    <td>排课转化率（换老师）</td>
                    <td> 精排排课数</td>
                    <td> 绿色通道排课数</td>
                    <td>  抢课排课数</td>

                    <td>普通排课数</td>
                    <td> 精排排课转化率(绿色通道)</td>
                    <td> 精排排课转化率</td>
                    <td> 绿色通道转化率</td>
                    <td> 抢课排课转化率</td>
                    <td>  普通排课转化率</td>
                    <td> 月平均排课量</td>

                    <td> 月平均排课时长</td>
                    <td> 抢课成功率</td>
                    <td> 老师迟到次数</td>
                    <td>老师调课次数</td>
                    <td> 老师请假次数</td>
                    <td> 老师更换次数</td>
                    <td> 老师退费人数</td>
                    <td> 老师迟到率</td>
                    <td> 老师调课率</td>
                    <td> 老师请假率</td>
                    <td> 老师更换率</td>

                    <td> 常规课数大于30人数</td>

                    <td>常规课数大于60人数</td>
                    <td>  常规课数大于90人数</td>
                    <td> 常规课数大于120人数</td>

                    <td>  数学流失老师数</td>
                    <td>  语文流失老师数</td>
                    <td> 英语流失老师数</td>
                    <td> 物理流失老师数</td>
                    <td> 化学流失老师数</td>
                    <td> 综合学科流失老师数</td>

                    <td>  老师投诉次数</td>
                    <td> 投诉处理时长</td>
                    <td></td>


                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td > {{@$var["month"]}} </td>
                        <td > {{@$var["new_train_through_num"]}} </td>
                        <td > {{@$var["lesson_teacher_num"]}} </td>
                        <td > {{@$var["new_lesson_teacher_num"]}} </td>
                        <td > {{@$var["old_lesson_teacher_num"]}} </td>
                        <td > {{@$var["lose_teacher_num"]}} </td>
                        <td > {{@$var["lose_teacher_num_three"]}} </td>
                        <td > {{@$var["read_stu_num"]}} </td>
                        <td > {{@$var["tea_stu_per"]}} </td>
                        <td > {{@$var["test_teacher_num"]}} </td>
                        <td > {{@$var["normal_teacher_num"]}} </td>
                        <td > {{@$var["test_textbook_rate"]}}% </td>
                        <td > {{@$var["new_train_through_per"]}}% </td>
                        <td > {{@$var["new_train_through_time"]}}天 </td>
                        <td > {{@$var["new_tea_thirty_stay_per"]}}% </td>
                        <td > {{@$var["new_tea_sixty_stay_per"]}}% </td>
                        <td > {{@$var["new_tea_ninty_stay_per"]}}% </td>
                        <td > {{@$var["new_tea_thirty_tran_per"]}}% </td>
                        <td > {{@$var["new_tea_sixty_tran_per"]}}% </td>
                        <td > {{@$var["new_tea_ninty_tran_per"]}}% </td>
                        <td > {{@$var["new_tea_thirty_lesson_count"]}} </td>
                        <td > {{@$var["new_tea_sixty_lesson_count"]}} </td>
                        <td > {{@$var["new_tea_ninty_lesson_count"]}} </td>
                        <td > {{@$var["new_teacher_public"]}} </td>
                        <td > {{@$var["new_teacher_college"]}} </td>
                        <td > {{@$var["new_teacher_outfit"]}} </td>
                        <td > {{@$var["appointment_num"]}} </td>
                        <td > {{@$var["interview_pass_num"]}} </td>
                        <td > {{@$var["new_teacher_train_num"]}} </td>
                        <td > {{@$var["simulated_audition_num"]}} </td>
                        <td > {{@$var["new_teacher_train_throuth_num"]}} </td>
                        <td > {{@$var["appointment_time"]}}天 </td>
                        <td > {{@$var["interview_pass_time"]}}天 </td>
                        <td > {{@$var["new_teacher_train_time"]}}天 </td>
                        <td > {{@$var["simulated_audition_time"]}}天 </td>
                        <td > {{@$var["new_teacher_train_throuth_time"]}}天 </td>
                        <td > {{@$var["all_new_train_num"]}} </td>
                        <td > {{@$var["train_part_per"]}}% </td>
                        <td > {{@$var["train_pass_per"]}}% </td>
                        <td > {{@$var["set_count_all"]}} </td>
                        <td > {{@$var["set_count_all_per"]}}% </td>
                        <td > {{@$var["set_count_seller_per"]}}% </td>
                        <td > {{@$var["set_count_expand_per"]}}% </td>
                        <td > {{@$var["set_count_change_per"]}}% </td>
                        <td > {{@$var["set_count_top"]}} </td>
                        <td > {{@$var["set_count_green"]}} </td>
                        <td > {{@$var["set_count_grab"]}} </td>
                        <td > {{@$var["set_count_normal"]}} </td>
                        <td > {{@$var["set_count_green_top_per"]}}% </td>
                        <td > {{@$var["set_count_top_per"]}}% </td>
                        <td > {{@$var["set_count_green_per"]}}% </td>
                        <td > {{@$var["set_count_grab_per"]}}% </td>
                        <td > {{@$var["set_count_normal_per"]}}% </td>
                        <td > {{@$var["set_count_all_avg"]}} </td>
                        <td > {{@$var["set_count_time_avg"]}}天 </td>
                        <td > {{@$var["grab_success_per"]}}% </td>
                        <td > {{@$var["teacher_late_num"]}} </td>
                        <td > {{@$var["teacher_change_num"]}} </td>
                        <td > {{@$var["teacher_leave_num"]}} </td>
                        <td > {{@$var["change_tea_num"]}} </td>
                        <td > {{@$var["teacher_refund_num"]}} </td>
                        <td > {{@$var["teacher_late_per"]}}% </td>
                        <td > {{@$var["teacher_change_per"]}}% </td>
                        <td > {{@$var["teacher_leave_per"]}}% </td>
                        <td > {{@$var["change_tea_per"]}}% </td>
                        <td > {{@$var["thirty_lesson_tea_num"]}} </td>
                        <td > {{@$var["sixty_lesson_tea_num"]}} </td>
                        <td > {{@$var["ninty_lesson_tea_num"]}} </td>
                        <td > {{@$var["hundred_twenty_lesson_tea_num"]}} </td>
                        <td > {{@$var["lose_teacher_num_three_chinese"]}} </td>
                        <td > {{@$var["lose_teacher_num_three_math"]}} </td>
                        <td > {{@$var["lose_teacher_num_three_english"]}} </td>
                        <td > {{@$var["lose_teacher_num_three_chem"]}} </td>
                        <td > {{@$var["lose_teacher_num_three_physics"]}} </td>
                        <td > {{@$var["lose_teacher_num_three_multiple"]}} </td>
                        <td > {{@$var["tea_complaint_num"]}} </td>
                        <td > {{@$var["tea_complaint_deal_time"]}}天 </td>

                        <td>
                            <div class="opt-div" 
                                 {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection
