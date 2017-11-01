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
                    <td colspan="22">核心数据</td>
                    <td colspan="13">招师数据</td>
                    <td colspan="3">培训数据</td>
                    <td colspan="16">教务数据</td>
                    <td colspan="21">运营数据</td>                  
                   
                    <td rowspan="2" >操作</td>
                </tr>
                <tr>
                    <td>新老师数(入职)</td>
                    <td>本月上课老师数</td>
                    <td>本月新增上课老师数</td>
                    <td>本月留存上课老师数</td>
                    <td>本月流失上课老师数</td>
                    <td>流失老师数(三个月未上课)</td>
                    <td>在读学生数</td>
                    <td>师生比</td>
                    <td>试听课老师数</td>
                    <td>常规课老师数</td>
                    <td>试听课学生与老师教材匹配度</td>
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
                    <td>培训后转化率提升度</td>
                    <td>月排课数</td>
                    <td> 排课转化率</td>
                   <td> 排课转化率（新签）</td>
                    <td>排课转化率（扩科）</td>
                    <td>排课转化率（换老师）</td>
                    <td> 精排排课数</td>
                    <td> 绿色通道排课数</td>
                    <td>  抢课排课数</td>

                    <td>普通排课数</td>
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


                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td > {{@$var["grade_str"]}} </td>
                        <td > {{@$var["total_num"]}} </td>
                        <td > {{@$var["total_test"]}} </td>
                        <td > {{@$var["total_success"]}} </td>
                        <td > {{@$var["total_order"]}} </td>
                        <td > {{@$var["grade_str"]}} </td>
                        <td > {{@$var["total_num"]}} </td>
                        <td > {{@$var["total_test"]}} </td>
                        <td > {{@$var["total_success"]}} </td>
                        <td > {{@$var["total_order"]}} </td>
                        <td > {{@$var["grade_str"]}} </td>
                        <td > {{@$var["total_num"]}} </td>
                        <td > {{@$var["total_test"]}} </td>
                        <td > {{@$var["total_success"]}} </td>
                        <td > {{@$var["total_order"]}} </td>
                        <td > {{@$var["grade_str"]}} </td>
                        <td > {{@$var["total_num"]}} </td>
                        <td > {{@$var["total_test"]}} </td>
                        <td > {{@$var["total_success"]}} </td>
                        <td > {{@$var["total_order"]}} </td>
                        <td > {{@$var["grade_str"]}} </td>
                        <td > {{@$var["total_num"]}} </td>
                        <td > {{@$var["total_test"]}} </td>
                        <td > {{@$var["total_success"]}} </td>
                        <td > {{@$var["total_order"]}} </td>
                        <td > {{@$var["grade_str"]}} </td>
                        <td > {{@$var["total_num"]}} </td>
                        <td > {{@$var["total_test"]}} </td>
                        <td > {{@$var["total_success"]}} </td>
                        <td > {{@$var["total_order"]}} </td>
                        <td > {{@$var["grade_str"]}} </td>
                        <td > {{@$var["total_num"]}} </td>
                        <td > {{@$var["total_test"]}} </td>
                        <td > {{@$var["total_success"]}} </td>
                        <td > {{@$var["total_order"]}} </td>
                        <td > {{@$var["grade_str"]}} </td>
                        <td > {{@$var["total_num"]}} </td>
                        <td > {{@$var["total_test"]}} </td>
                        <td > {{@$var["total_success"]}} </td>
                        <td > {{@$var["total_order"]}} </td>
                        <td > {{@$var["grade_str"]}} </td>
                        <td > {{@$var["total_num"]}} </td>
                        <td > {{@$var["total_test"]}} </td>
                        <td > {{@$var["total_success"]}} </td>
                        <td > {{@$var["total_order"]}} </td>
                        <td > {{@$var["grade_str"]}} </td>
                        <td > {{@$var["total_num"]}} </td>
                        <td > {{@$var["total_test"]}} </td>
                        <td > {{@$var["total_success"]}} </td>
                        <td > {{@$var["total_order"]}} </td>
                        <td > {{@$var["grade_str"]}} </td>
                        <td > {{@$var["total_num"]}} </td>
                        <td > {{@$var["total_test"]}} </td>
                        <td > {{@$var["total_success"]}} </td>
                        <td > {{@$var["total_order"]}} </td>
                        <td > {{@$var["grade_str"]}} </td>
                        <td > {{@$var["total_num"]}} </td>
                        <td > {{@$var["total_test"]}} </td>
                        <td > {{@$var["total_success"]}} </td>
                        <td > {{@$var["total_order"]}} </td>
                        <td > {{@$var["grade_str"]}} </td>
                        <td > {{@$var["total_num"]}} </td>
                        <td > {{@$var["total_test"]}} </td>
                        <td > {{@$var["total_success"]}} </td>
                        <td > {{@$var["total_order"]}} </td>
                        <td > {{@$var["grade_str"]}} </td>

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
        <table class="common-table"> 
            <thead>
                <tr>
                    <td >ID</td>
                    <td >姓名</td>
                    <td >年级</td>
                    <td >科目</td>
                    <td >试听需求</td>
                    <td >教材版本</td>
                    <td >地区</td>
                    <td >试听是否有效</td>
                    <td >订单是否有效</td>
                    <td >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td > {{$var["userid"]}} </td>
                        <td > {{$var["nick"]}} </td>
                        <td > {{$var["grade_str"]}} </td>
                        <td > {{$var["subject_str"]}} </td>
                        <td > {{$var["stu_request_test_lesson_demand"]}} </td>
                        <td > {{$var["textbook"]}} </td>
                        <td > {{$var["phone_location"]}} </td>
                        <td > {{$var["lesson_user_online_status_str"]}} </td>
                        <td > {{$var["status_str"]}} </td>
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
