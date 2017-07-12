@extends('layouts.stu_header')
@section('content')
    <section class="content ">
        <div class="row">
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">课堂类型</span>
                    <select id="id_competition_flag" class="opt-change">
                        <option value="0">常规1对1</option>
                        <option value="1">竞赛1对1</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">合同剩余课时</span>
                    <input value="{{$order_left}}"/>
                </div>
            </div>
            <div class="col-xs-6 col-md-2"> <div class="input-group ">
                    <span class="input-group-addon">课程总消耗</span>
                    <input value="{{$lesson_sum}}"/>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td>orderid</td>
                    <td>合同类型</td>
                    <td>lessonid</td>
                    <td>上课时段</td>
                    <td>老师信息</td>
                    <td>年级</td>
                    <td>科目</td>
                    <td>课堂金额</td>
                    <td>课时数</td>
                    <td>课时确认情况</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td> {{$var["orderid"]}} </td>
                        <td> {{$var["contract_type_str"]}} </td>
                        <td> {{$var["lessonid"]}} </td>
                        <td> {{$var["lesson_time"]}} </td>
                        <td class="tea_nick" data-teacherid="{{$var["teacherid"]}}">
                            <a href="/human_resource/index?teacherid={{$var["teacherid"]}}" target="_blank">{{$var["tea_nick"]}}</a>
                        </td>
                        <td> {{$var["grade_str"]}} </td>
                        <td> {{$var["subject_str"]}} </td>
                        <td> {{$var["price"]}} </td>
                        <td> {{$var["lesson_count"]}} </td>
                        <td> {{$var["confirm_flag_str"]}} </td>
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

