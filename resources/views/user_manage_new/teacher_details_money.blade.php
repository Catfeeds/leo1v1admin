@extends('layouts.app')
@section('content')
    <section class="content">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div class="col-xs-12 col-md-4" data-title="时间段">
                        <div id="id_date_range"> </div>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td>月份</td>
                    <td>老师类型</td>
                    <td>等级分类</td>
                    <td>总收入</td>
                    <td>总课耗</td>
                    <td>1对1课耗</td>
                    <td>试听课耗</td>
                    <td>总老师数</td>
                    <td>1对1老师</td>
                    <td>试听老师</td>
                    <td>在读学员</td>
                    <td>师生比</td>
                    <td>月平均课耗</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var['date_str']}}</td>
                        <td>{{@$var['teacher_money_type_str']}}</td>
                        <td>{{@$var['level_str']}}</td>
                        <td>{{@$var['lesson_money']}}</td>
                        <td>{{@$var['lesson_total']}}</td>
                        <td>{{@$var['lesson_1v1']}}</td>
                        <td>{{@$var['lesson_trial']}}</td>
                        <td>{{@$var['teacher_num']}}</td>
                        <td>{{@$var['teacher_1v1']}}</td>
                        <td>{{@$var['teacher_trial']}}</td>
                        <td>{{@$var['stu_num']}}</td>
                        <td>{{@$var['tea_stu_ratio']}}</td>
                        <td>{{@$var['per_total']}}</td>
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
    </section>
@endsection
