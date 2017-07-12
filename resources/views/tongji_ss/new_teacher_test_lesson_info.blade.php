@extends('layouts.app')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

               
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">有无试听课</span>
                        <select class="opt-change form-control" id="id_have_test_lesson_flag" >
                            <option value="-1">全部</option>
                            <option value="0">无</option>
                            <option value="1">有</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">科目</span>
                        <select class="opt-change form-control" id="id_subject" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">年级段</span>
                        <select class="opt-change form-control" id="id_grade_part_ex" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">是否培训通过</span>
                        <select class="opt-change form-control" id="id_train_through_new" >
                            <option value="-1">全部</option>
                            <option value="0">否</option>
                            <option value="1">是</option>
                        </select>
                    </div>
                </div>

               
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >新老师使用率</span>
                        <input value="{{@$have_per}}" type="text" readOnly="true" id="have_per">
                    </div>
                </div>
               
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >新老师人数占比</span>
                        <input value="{{@$tea_per}}" type="text" readOnly="true" id="tea_per">
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >新老师试听占比</span>
                        <input value="{{@$lesson_per}}" type="text" readOnly="true" id="lesson_per">
                    </div>
                </div>
                <div class="col-xs-6 col-md-4">
                    <button class="btn" id="id_have_lesson" data-value="{{$have_lesson_count}}" >{{$have_lesson_count}}</button>
                    <button class="btn" id="id_no_lesson" data-value="{{$no_lesson_count}}" >{{$no_lesson_count}}</button> 
                    <button class="btn" id="id_all_tea" data-value="{{$all_tea}}" >{{$all_tea}}</button> 
                    <button class="btn" id="id_all_lesson" data-value="{{$all_lesson}}" >{{$all_lesson}}</button> 
                    <button class="btn" id="id_train_through_count" data-value="{{$train_through_count}}" >{{$train_through_count}}</button> 
                    <button class="btn" id="id_all_interview_count" data-value="{{$all_interview_count}}" >{{$all_interview_count}}</button> 
                </div>
                

            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>老师</td>
                    <td>入职时长</td>
                    <td>第一次试听课时间</td>
                    <td>第一次试听课减去面试时间</td>
                    <td>科目</td>
                    <td>年级段</td>
                    <td>手机</td>
                    <td>试听成功数</td>
                    <td>签单数 </td>
                    <td>签单率</td>                   
                    <td> 操作</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>{{@$arr["realname"]}} </td>
                    <td>{{@$arr["work_day"]}} </td>
                    <td> {{@$arr["first_time_str"]}} </td>
                    <td>
                        @if(@$arr["range_time"]>0)
                            {{@$arr["range_time"]}}天
                        @endif
                    </td>
                    <td>{{@$arr["subject_str"]}} </td>
                    <td>{{@$arr["grade_part_ex_str"]}} </td>
                    <td>{{@$arr["phone"]}} </td>
                    <td>{{@$arr["all_lesson"]}} </td>
                    <td>{{@$arr["order_num"]}} </td>
                    <td>{{@$arr["per"]}}% </td>
                    <td>
                    </td>
                </tr>

                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["work_day"]}} </td>
                        <td> {{@$var["first_time_str"]}} </td>
                        <td>
                            @if(@$var["range_time"]>0)
                                {{@$var["range_time"]}}天
                            @endif
                        </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["grade_part_ex_str"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["all_lesson"]}} </td>
                        <td>{{@$var["order_num"]}} </td>
                        <td>{{@$var["per"]}}% </td>
                        <td>
                            <div
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

