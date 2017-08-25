@extends('layouts.app')
@section('content')
    <section class="content ">
        <div>
            <div class="row ">
                <div class="col-xs-6 col-md-2">
                    <button class="btn btn-primary" id="id_reset_money_count">重置统计信息</button>
                </div>
            </div>
            <!-- 老师等级 -->
            <div class="col-xs-12 col-md-8">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        老师等级
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered "   >
                            <tr>
                                <td>等级</td>
                                <td>人数</td>
                                <td>比率</td>
                            </tr>
                            @foreach(@$level_list as $l_key=>$l_val)
                                <tr>
                                    <td>{{$l_key}}</td>
                                    <td>{{$l_val['level_num']}}</td>
                                    <td>{{$l_val['level_per']*100}}%</td>
                                </tr>
                            @endforeach
                        </table>
                    </div>
                </div>
            </div>
            <!-- 每月数据 -->
            <div class="col-xs-12 col-md-8">
                <div class="panel panel-warning">
                    <div class="panel-heading">
                        每月统计
                    </div>
                    <div class="panel-body">
                        <table class="table table-bordered ">
                            <tr>
                                <td>月份</td>
                                <td>总课时</td>
                                @if(in_array($account,["adrian","ted"]))
                                    <td>总工资</td>
                                    <td>总收入</td>
                                    <td>模拟工资</td>
                                    <td>模拟收入</td>
                                @endif
                                <td>工资成本</td>
                                <td>模拟工资成本</td>
                            </tr>
                            @if(!empty($money_month))
                            @foreach(@$money_month as $m_key=>$m_val)
                                <tr>
                                    <td>{{$m_key}}</td>
                                    <td>{{$m_val['lesson_total']}}</td>
                                    @if(in_array($account,["adrian","ted"]))
                                        <td>{{$m_val['money']}}</td>
                                        <td>{{$m_val['lesson_price']}}</td>
                                        <td>{{$m_val['money_simulate']}}</td>
                                        <td>{{$m_val['lesson_price_simulate']}}</td>
                                    @endif
                                    <td>{{round($m_val['money']/($m_val['lesson_price']==0?1:$m_val['lesson_price']),4)*100}}%</td>
                                    <td>{{round($m_val['money_simulate']/($m_val['lesson_price_simulate']==0?1:$m_val['lesson_price_simulate']),4)*100}}%</td>
                                </tr>
                            @endforeach
                            @endif
                            @if(isset($all_money) && !empty($all_money))
                            <tr>
                                <td>总计</td>
                                <td>{{$all_money['lesson_total']}}</td>
                                @if(in_array($account,["adrian","ted"]))
                                    <td>{{round($all_money['money'],2)}}</td>
                                    <td>{{round($all_money['lesson_price'],2)}}</td>
                                    <td>{{round($all_money['money_simulate'],2)}}</td>
                                    <td>{{round($all_money['lesson_price_simulate'],2)}}</td>
                                @endif
                                <td>{{round($all_money['money']/($all_money['lesson_price']==0?1:$all_money['lesson_price']),4)*100}}%</td>
                                <td>{{round($all_money['money_simulate']/($all_money['lesson_price_simulate']==0?1:$all_money['lesson_price_simulate']),4)*100}}%</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>
            </div>
            <!-- 老师工资类型及等级分布 -->
            @if(!empty($teacher_money_type_month))
                @foreach(@$teacher_money_type_month as $month_key=>$month_val)
                    <div class="col-xs-12 col-md-8">
                        <div class="panel panel-warning">
                            <div class="panel-heading">
                                {{$month_key}} 老师类型统计
                            </div>
                            <div class="panel-body">
                                <table class="table table-bordered "   >
                                    <tr>
                                        <td>工资类型</td>
                                        <td>等级</td>
                                        <td>总课时</td>
                                        @if(in_array($account,["adrian","ted"]))
                                            <td>总工资</td>
                                            <td>总收入</td>
                                            <td>模拟工资</td>
                                            <td>模拟收入</td>
                                        @endif
                                        <td>工资成本</td>
                                        <td>模拟工资成本</td>
                                    </tr>
                                    @foreach($month_val as $t_key=>$t_val)
                                        @foreach($t_val as $l_key=>$l_val)
                                            <tr>
                                                <td>{{$l_val['teacher_money_type_str']}}</td>
                                                <td>{{$l_val['level_str']}}</td>
                                                <td>{{$l_val['lesson_total']}}</td>
                                                @if(in_array($account,["adrian","ted"]))
                                                    <td>{{$l_val['money']}}</td>
                                                    <td>{{$l_val['lesson_price']}}</td>
                                                    <td>{{$l_val['money_simulate']}}</td>
                                                    <td>{{$l_val['lesson_price_simulate']}}</td>
                                                @endif
                                                <td>{{round($l_val['money']/($l_val['lesson_price']==0?1:$l_val['lesson_price']),4)*100}}%</td>
                                                <td>{{round($l_val['money_simulate']/($l_val['lesson_price_simulate']==0?1:$l_val['lesson_price_simulate']),4)*100}}%</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </table>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        <hr />
        </div>
        <hr/>
    </section>
@endsection
