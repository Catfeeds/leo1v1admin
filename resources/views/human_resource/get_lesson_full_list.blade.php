@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="input-group ">
                    <div id="id_date_range">
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-md-6">
                <div class="input-group ">
                    <span class="input-group-addon">总金额奖:</span>
                    <input value="{{@$all_money}}">
                    <span class="input-group-addon">试听总和:</span>
                    <input value="{{@$trial_money}}">
                    <span class="input-group-addon">常规总和:</span>
                    <input value="{{@$normal_money}}">
                    <span class="input-group-addon">旧版总和:</span>
                    <input value="{{@$count_money}}">
                </div>
            </div>


            <div class="col-xs-12 col-md-2">
                <div class="input-group ">
                    <span >试听奖金:</span>
                    <input type="text" id="id_trial_money" />
                </div>
            </div>
            <div class="col-xs-12 col-md-2">
                <div class="input-group ">
                    <span >常规奖金:</span>
                    <input type="text" id="id_normal_money" />
                </div>
            </div>
            <div class="col-xs-12 col-md-2">
                <div class="input-group ">
                    <span>全勤类型:</span>
                    <select id="id_full_type" >
                        <option value="0">课次</option>
                        <option value="1">课时</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-md-2">
                <div class="input-group ">
                    <span >奖励课次:</span>
                    <input type="text" id="id_lesson_num" />
                </div>
            </div>
            <div class="col-xs-12 col-md-2">
                <div class="input-group ">
                    <span>排序方式:</span>
                    <select id="id_order_str" >
                        <option value="0">总金额</option>
                        <option value="1">试听</option>
                        <option value="2">常规</option>
                        <option value="3">旧版</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-12 col-md-2">
                <div class="input-group ">
                    <span >排序方式:</span>
                    <select id="id_order_type" >
                        <option value="0">降序</option>
                        <option value="1">升序</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <button class="btn btn-primary" id="id_submit">提交</button>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group">
                    老师总数：{{@$all_teacher}}
                    <br>
                    全勤老师：{{@$count_teacher}}
                    <br>
                    未迟到：{{@$late_teacher}}
                    <br>
                    有全勤且未迟到：{{@$has_full_teacher}}
                </div>
            </div>

        </div>
        <hr />
        <div class="body">
            <table class="common-table ">
                <thead>
                    <tr>
                        <td >姓名</td>
                        <td >试听奖金</td>
                        <td >常规奖金</td>
                        <td >总奖金</td>
                        <td >旧版全勤</td>
                        <td >操作</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr>
                            <td >{{@$var["nick"]}}</td>
                            <td >{{@$var["trial_money"]}}</td>
                            <td >{{@$var["normal_money"]}}</td>
                            <td >{{@$var["all_money"]}}</td>
                            <td >{{@$var["count_money"]}}</td>
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
        </div>
@endsection

