@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-md-4" data-title="时间段">
                <div id="id_date_range"></div>
            </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">年级</span>
                        <select class="opt-change form-control" id="id_grade" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">科目</span>
                        <select class="opt-change form-control" id="id_subject" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">是否通过</span>
                        <select class="opt-change form-control" id="id_is_pass" >
                            <option value="-1">[全部]</option>
                            <option value="0">未通过</option>
                            <option value="1">已通过</option>
                        </select>
                    </div>
                </div>
            <div class="col-xs-3 col-md-2">
                <div class="input-group">
                    <span>微信绑定</span>
                    <select id="id_has_openid" class="opt-change" >
                    </select>
                </div>
            </div>
            <div class="col-xs-3 col-md-1">
                <button class="btn btn-primary" id="id_wx_notice">微信推送</button>
            </div>
        </div>
        <hr />
        <table class="common-table">
            <thead>
                <tr>
                    <td >老师</td>
                    <td >手机号</td>
                    <td >老师创建时间</td>
                    <td >老师通过时间</td>
                    <td >微信绑定</td>
                    <td >最高得分</td>
                    <td >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                    <tr>
                        <td >{{$var['nick']}}</td>
                        <td >{{$var['phone']}}</td>
                        <td >{{$var['create_time_str']}}</td>
                        <td >{{$var['train_through_new_time_str']}}</td>
                        <td >{{$var['has_openid_str']}}</td>
                        <td >{{$var['score']}}</td>
                        <td >
                            <div
                                {!! \App\Helper\Utils::gen_jquery_data($var) !!}
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
