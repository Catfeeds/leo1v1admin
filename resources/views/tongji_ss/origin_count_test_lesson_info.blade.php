@extends('layouts.app')
@section('content')

    <section class="content ">
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>id</td>
                    <td>姓名</td>
                    <td>电话</td>
                    <td>省份</td>
                    <td>年级</td>
                    <td>科目</td>
                    <td>渠道</td>
                    <td>状态</td>
                    <td>老师</td>
                    <td>系统判定状态</td>
                    <td>上课时间</td>
                    <td>试听是否成功</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["userid"]}} </td>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["phone_location"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["check_value"]}} </td>
                        <td>{{@$var["seller_student_status_str"]}} </td>
                        <td>{{@$var["tea_nick"]}} </td>
                        <td>{{@$var["lesson_user_online_status_str"]}} </td>
                        <td>{{@$var["lesson_start"]}} </td>
                        <td>{{@$var["success_flag_str"]}} </td>
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

