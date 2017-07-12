@extends('layouts.app')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >类型</span>
                        <select id="id_lesson_flag" class="opt-change"   >
                            <option value="-1">全部 </option>
                            <option value="1">正常上课</option>
                            <option value="2">取消-老师无工资 </option>
                            <option value="3">取消-老师有工资 </option>
                        </select>
                    </div>
                </div>

            </div>

        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>上课时间</td>
                    <td>学生</td>
                    <td>老师</td>
                    <td>科目</td>
                    <td>年级</td>
                    <td>申请请人</td>
                    <td>销售提醒:前一天/当天</td>
                    <td>例子状态</td>
                    <td>课堂状态</td>
                    <td>取消原因</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody id="tbody">
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["lesson_time"]}} </td>
                        <td><a href="/user_manage/all_users?userid={{$var["userid"]}}"> {{@$var["student_nick"]}}  </a></td>
                        <td> <a href="/human_resource/index?teacherid={{$var["teacherid"]?$var["teacherid"]:$var["cancel_teacherid"] }}"> {{@$var["teacher_nick"]}} </a> </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["st_application_nick"]}} </td>
                        <td>
                            <font color="{{$var["notify_lesson_day2"]?"green":"red"}}">{{$var["notify_lesson_day2_str"]}} </font>/ 
                            <font color="{{$var["notify_lesson_day1"]?"green":"red"}}">{{$var["notify_lesson_day1_str"]}} </font> 
                        </td>
                        <td>{{@$var["status_str"]}} </td>
                        <td>{{@$var["confirm_flag_str"]}} </td>
                        <td>{{@$var["cancel_reason"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >

                                <a class="opt-st_demand"> 试听需求</a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

