@extends('layouts.app')
@section('content')


    <section class="content ">
        <div class="row">
            <div class="col-xs-12 col-md-5">
                <div id="id_date_range" >
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td >年级</td>
                    <td >试听申请数</td>
                    <td >排课数</td>
                    <td >试听有效数</td>
                    <td >签单成功数</td>
                    <td >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $ret as $var )
                    <tr>
                        <td > {{$var["grade_str"]}} </td>
                        <td > {{$var["total_num"]}} </td>
                        <td > {{$var["total_test"]}} </td>
                        <td > {{$var["total_success"]}} </td>
                        <td > {{$var["total_order"]}} </td>
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
