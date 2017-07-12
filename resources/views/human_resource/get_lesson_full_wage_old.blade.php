@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="input-group">
                    <div id="id_date_range">
                    </div>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">总金额奖:</span>
                    <input value="{{@$all_money}}">
                </div>
            </div>
            <div class="col-xs-12 col-md-2">
                <div class="input-group">
                    <span >常规奖金:</span>
                    <input type="text" id="id_normal_money" />
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
                    <span >排序方式:</span>
                    <select id="id_order_type" >
                        <option value="0">降序</option>
                        <option value="1">升序</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group">
                    <button class="btn btn-primary" id="id_submit">提交</button>
                </div>
            </div>
        </div>
        <hr />
        <div class="body">
            <table class="common-table">
                <thead>
                    <tr>
                        <td >姓名</td>
                        <td >全勤奖</td>
                        <td >操作</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr>
                            <td >{{@$var["nick"]}}</td>
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

