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
                    <td colspan="18" align="center">教务数据</td>
                    <td colspan="21" align="center">运营数据</td>                  
                   
                    <td  >操作</td>
                </tr>
                <tr>
                    <td class="custom" data-title="本月入职的老师">新老师数(入职)</td>
                    <td class="custom" data-title="本月上过课的老师">本月上课老师数</td>
                    <td class="custom" data-title="本月入职且在本月上过课的老师">本月新增上课老师数</td>
                    <td class="custom" data-title="本月之前入职且在本月上过课的老师">本月留存上课老师数</td>
                    <td class="custom" data-title="本月之前入职且未在本月上过课的老师">本月流失上课老师数</td>
                    <td class="custom" data-title="本月之前入职且未在本月前三个月(包含本月)上过课的老师">流失老师数(三个月未上课)</td>
                    <td class="custom" data-title="本月有试听课但无常规课老师数">试听(无常规)老师数</td>

                    <td class="custom" data-title="本月有过常规课的学生">在读学生数</td>
                    <td class="custom" data-title="本月有过常规课的老师/学生">师生比</td>
                    <td class="custom" data-title="本月上过试听课的老师">试听课老师数</td>
                    <td class="custom" data-title="本月上过常规课的老师">常规课老师数</td>
                    <td class="custom" data-title="试听课学生与老师教材匹配度">试听课学生与老师教材匹配度</td>
                    <td class="custom" data-title="本月面试通过的老师入职的人数/本月面试通过的老师数">新老师入职通过率</td>
                    <td class="custom" data-title="本月入职老师入职时间减去其报名时间">新老师入职时长</td>
                    <td class="custom" data-title="本月入职老师入职30天内上过课的老师数/本月入职老师">新老师30天留存率</td>
                    <td class="custom" data-title="本月入职老师入职60天内上过课的老师数/本月入职老师">新老师60天留存率</td>
                    <td class="custom" data-title="本月入职老师入职90天内上过课的老师数/本月入职老师">新老师90天留存率</td>
                    <td class="custom" data-title="本月入职老师入职30天内上过的试听课转化的单子/本月入职老师30天内上过的试听课">新老师30天转化率</td>
                    <td class="custom" data-title="本月入职老师入职60天内上过的试听课转化的单子/本月入职老师30天内上过的试听课">新老师60天转化率</td>
                    <td class="custom" data-title="本月入职老师入职90天内上过的试听课转化的单子/本月入职老师30天内上过的试听课">新老师90天转化率</td>
                    <td class="custom" data-title="本月入职老师入职30天内上过的课的平均课时数">新老师30天平均课耗数</td>
                    <td class="custom" data-title="本月入职老师入职60天内上过的课的平均课时数">新老师60天平均课耗数</td>
                    <td class="custom" data-title="本月入职老师入职90天内上过的课的平均课时数">新老师90天平均课耗数</td>
                    <td class="custom" data-title="本月入职的公校老师">新老师公校老师数</td>
                    <td class="custom" data-title="本月入职的高校生老师">  新老师在校学生数</td>
                    <td class="custom" data-title="本月入职的机构老师">   新老师机构老师数</td>

                    <td class="custom" data-title="本月报名的老师数">  面试邀约数</td>
                    <td class="custom" data-title="本月报名的老师中面试通过的人数">    面试通过数</td>
                    <td class="custom" data-title="本月报名的老师中参加新师培训的人数">   新师培训数</td>
                    <td class="custom" data-title="本月报名的老师中参加模拟试听的人数">  模拟试听数</td>
                    <td class="custom" data-title="本月报名的老师中入职的的人数">  新老师入职数</td>

                    <td class="custom" data-title="本月报名的老师的第一次面试时间减去报名时间">  面试邀约时长</td>
                    <td class="custom" data-title="本月报名的老师第一次面试通过的老师的通过时间减去第一次面试时间">  面试通过时长</td>
                    <td class="custom" data-title="本月报名的老师中面试通过的老师的第一次新师培训参加时间减去第一次面试通过时间">  新师培训时长</td>
                    <td class="custom" data-title="本月报名的老师中参加模拟试听的老师的第一次有效模拟试听课时间减去第一次新师培训时间"> 模拟试听时长</td>
                    <td class="custom" data-title="本月报名的老师中入职的老师的入职时间减去第一次有效模拟试听课时间"> 新老师入职时长</td>
                    <td class="custom" data-title="本月的新师培训课数量">培训次数</td>
                    <td class="custom" data-title="本月的新师培训课中老师的参与比例">培训参与率</td>
                    <td class="custom" data-title="本月的新师培训课中老师的通过比例">培训通过率</td>
                    <td class="custom" data-title="教务在本月排的试听课的数量">月排课数</td>
                    <td class="custom" data-title="试听时间在本月的试听课的整体转化率"> 排课转化率</td>
                    <td class="custom" data-title="CC提交的试听时间在本月的试听课的转化率"> 排课转化率（新签）</td>
                    <td class="custom" data-title="CR提交的试听时间在本月的扩课试听课的转化率">排课转化率（扩科）</td>
                    <td class="custom" data-title="CR提交的试听时间在本月的换老师试听课的转化率">排课转化率（换老师）</td>
                    <td class="custom" data-title="教务在本月排的试听课的数量(高意向且销售top25,非绿色通道)"> 精排排课数</td>
                    <td class="custom" data-title="教务在本月排的试听课的数量(绿色通道)"> 绿色通道排课数</td>
                    <td class="custom" data-title="教务在本月排的试听课的数量(普通申请,抛链接抢课拍的课)">  抢课排课数</td>

                    <td class="custom" data-title="教务在本月排的试听课的数量(普通申请,非抛链接抢课拍的课)">普通排课数</td>
                    <td class="custom" data-title="类型同时是精排试听和绿色通道的试听课的转化率"> 精排排课转化率(绿色通道)</td>
                    <td class="custom" data-title="类型只是精排试听的试听课的转化率"> 精排排课转化率</td>
                    <td  class="custom" data-title="类型只是绿色通道的试听课的转化率"> 绿色通道转化率</td>
                    <td  class="custom" data-title="类型是普通申请抛链接抢课的试听课的转化率"> 抢课排课转化率</td>
                    <td class="custom" data-title="类型是普通申请非抛链接抢课的试听课的转化率">  普通排课转化率</td>
                    <td class="custom" data-title="本月每个教务平均排试听课量"> 月平均排课量</td>

                    <td class="custom" data-title="排课时间减去申请时间"> 月平均排课时长</td>
                    <td class="custom" data-title="老师抢课链接抢课成功数/老师抢课链接抢课总数"> 抢课成功率</td>
                    <td class="custom" data-title="本月老师上课迟到总数"> 老师迟到次数</td>
                    <td class="custom" data-title="本月常规课老师调课总数">老师调课次数</td>
                    <td class="custom" data-title="本月常规课老师请假总数"> 老师请假次数</td>
                    <td class="custom" data-title="本月换老师试听申请成功数(试听时间在本月)"> 老师更换次数</td>
                    <td class="custom" data-title="退费申请责任鉴定为老师管理或教学部的,对应的老师数"> 老师退费人数</td>
                    <td class="custom" data-title="本月老师上课迟到总数/所有课数量"> 老师迟到率</td>
                    <td class="custom" data-title="本月常规课老师调课总数/所有常规课数量"> 老师调课率</td>
                    <td class="custom" data-title="本月常规课老师请假总数/所有常规课数量"> 老师请假率</td>
                    <td class="custom" data-title="本月换老师试听申请成功数(试听时间在本月)/所有上常规课老师数"> 老师更换率</td>

                    <td class="custom" data-title="本月常规课数大于30人的老师数"> 常规课数大于30人数</td>

                    <td class="custom" data-title="本月常规课数大于60人的老师数">常规课数大于60人数</td>
                    <td class="custom" data-title="本月常规课数大于90人的老师数">  常规课数大于90人数</td>
                    <td class="custom" data-title="本月常规课数大于120人的老师数"> 常规课数大于120人数</td>

                    <td class="custom" data-title="本月之前入职且未在本月前三个月(包含本月)上过课的第一科目是语文的老师">  语文流失老师数</td>
                    <td class="custom" data-title="本月之前入职且未在本月前三个月(包含本月)上过课的第一科目是数学的老师">  数学流失老师数</td>
                    <td class="custom" data-title="本月之前入职且未在本月前三个月(包含本月)上过课的第一科目是英语的老师"> 英语流失老师数</td>
                    <td class="custom" data-title="本月之前入职且未在本月前三个月(包含本月)上过课的第一科目是化学的老师"> 化学流失老师数</td>
                    <td class="custom" data-title="本月之前入职且未在本月前三个月(包含本月)上过课的第一科目是物理的老师"> 物理流失老师数</td>
                    <td class="custom" data-title="本月之前入职且未在本月前三个月(包含本月)上过课的第一科目是小科目的老师"> 综合学科流失老师数</td>

                    <td class="custom" data-title="本月发生的投诉人身份是老师的投诉次数">  老师投诉次数</td>
                    <td class="custom" data-title="本月发生的且处理完的投诉人身份是老师的投诉处理时间减去其投诉时间"> 投诉处理时长</td>
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
                        <td > {{@$var["test_no_reg_num"]}} </td>
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
