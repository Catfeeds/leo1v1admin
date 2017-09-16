@extends('layouts.app')
@section('content')
    <section class="content ">
        <div>
            <div class="row ">
                <div class="col-xs-12 col-md-5" data-title="时间段">
                    <div id="id_date_range"> </div>
                </div>
                <div class="col-xs-12 col-md-2">
                    <div class="input-group ">
                        <span >老师:</span>
                        <input type="text" id="id_teacherid" class="opt-change"/>
                    </div>
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
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >开始月</span>
                        <select id="id_not_start" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >结束月</span>
                        <select id="id_not_end" class ="opt-change" ></select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <div >老师总数{{$all_count}}</div>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <div>基础降薪:{{$down_count['base']}}</div>
                        <div>总体降薪:{{$down_count['all']}}</div>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <div >基础升薪:{{$up_count['base']}}</div>
                        <div >总体升薪:{{$up_count['all']}}</div>
                    </div>
                </div>
            </div>
        <hr />
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td>姓名</td>
                    <td>常规课时</td>
                    <td>工资类型</td>
                    <td>等级</td>
                    <td>总工资</td>
                    <td>基础工资</td>
                    <td>课时奖励</td>
                    <td>课程收入</td>

                    <td>模拟工资类型</td>
                    <td>模拟工资等级</td>
                    <td>模拟总工资</td>
                    <td>模拟基础工资</td>
                    <td>模拟课时奖励</td>
                    <td>模拟课程收入</td>

                    <td>老师工资差别</td>
                    <td>课程收入差别</td>
                    <td>老师基础工资差别</td>

                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var['realname']}}</td>
                        <td>{{$var['lesson_count']}}</td>
                        <td>{{$var['now_money_type_str']}}</td>
                        <td>{{$var['level_str']}}</td>
                        <td>{{$var['money']}}</td>
                        <td>{{$var['money_base']}}</td>
                        <td>{{$var['reward']}}</td>
                        <td>{{$var['lesson_price']}}</td>

                        <td>{{$var['teacher_money_type_simulate_str']}}</td>
                        <td>{{$var['level_simulate_str']}}</td>
                        <td>{{$var['money_simulate']}}</td>
                        <td>{{$var['money_simulate_base']}}</td>
                        <td>{{$var['reward_simulate']}}</td>
                        <td>{{$var['lesson_price_simulate']}}</td>

                        <td>{{$var['money_different']}}</td>
                        <td>{{$var['lesson_price_different']}}</td>
                        <td>{{$var['money_base_different']}}</td>
                        <td>
                            <div {!!  \App\Helper\Utils::gen_jquery_data($var)  !!}  >
                                <!-- <a class="opt-set_simulate_info" title="重置等级">设置模拟信息</a> -->
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection

