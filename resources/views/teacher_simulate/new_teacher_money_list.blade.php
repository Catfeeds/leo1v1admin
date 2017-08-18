@extends('layouts.app')
@section('content')
    <section class="content ">
        <div>
            <div class="row ">
                <div class="col-xs-12 col-md-4" data-title="时间段">
                    <div id="id_date_range"> </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >工资分类</span>
                        <select id="id_teacher_money_type" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >等级分类</span>
                        <select id="id_level" class ="opt-change" ></select>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td>姓名</td>
                    <td>常规课时</td>
                    <td>工资类型</td>
                    <td>等级</td>
                    <td>工资</td>
                    <td>课时奖励</td>
                    <td>课程收入</td>

                    <td>模拟工资类型</td>
                    <td>模拟工资等级</td>
                    <td>模拟工资</td>
                    <td>模拟课时奖励</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var['realname']}}</td>
                        <td>{{$var['lesson_count']}}</td>
                        <td>{{$var['teacher_money_type_str']}}</td>
                        <td>{{$var['level_str']}}</td>
                        <td>{{$var['money']}}</td>
                        <td>{{$var['reward']}}</td>
                        <td>{{$var['lesson_price']}}</td>

                        <td>{{$var['teacher_money_type_simulate_str']}}</td>
                        <td>{{$var['level_simulate_str']}}</td>
                        <td>{{$var['money_simulate']}}</td>
                        <td>{{$var['reward_simulate']}}</td>
                        <td>
                            <div {!!  \App\Helper\Utils::gen_jquery_data($var)  !!}  >
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection

