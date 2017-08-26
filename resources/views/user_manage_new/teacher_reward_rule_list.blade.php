@extends('layouts.app')
@section('content')
    <section class="content">
        <div>
            <div class="row">
                <div class="col-xs-6 col-md-3">
                    <div class="input-group " >
                        <span >奖励类型</span>
                        <select class="opt-change"  id="id_reward_count_type"   > </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >规则类型</span>
                        <select class="opt-change"  id="id_rule_type"   > </select>
                    </div>
                </div>
                <div class="col-xs-12 col-md-2">
                    <div class="input-group ">
                        <button class="btn btn-primary" id="id_add_reward_type">添加规则</button>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td>奖励规则</td>
                    <td>规则类型</td>
                    <td>累积数量</td>
                    <td>奖励金额</td>
                    <td> 操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["reward_count_type_str"]}}</td>
                        <td>{{$var["rule_type_str"]}}</td>
                        <td>{{$var["num"]}}</td>
                        <td>{{$var["money"]}}</td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                @if($var['reward_count_type']==1 && $var['rule_type']==6)
                                    <a class="opt-update">编辑</a>
                                    <a class="opt-del">删除</a>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
