@extends('layouts.app')
@section('content')

    <section class="content ">
        <div class="row">
            <div class="col-xs-6 col-md-3">
                <div id="id_date_range">
                </div>
            </div>

        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>老师姓名</td>
                    <td>全转兼前等级 </td>
                    <td>全转兼后等级 </td>
                    <td>全转兼前工资等级 </td>
                    <td>全转兼后工资等级 </td>
                    <td>申请人</td>
                    <td>申请时间</td>
                    <td>申请原因</td>
                    <td>审核状态</td>
                    <td>操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $info as $var )
                    <tr>
                        <td>{{$var["nick"]}}</td>
                        <td>{{$var['level_before_str']}}</td>
                        <td>{{$var['level_after_str']}}</td>
                        <td>{{$var['teacher_money_type_before_str']}}</td>
                        <td>{{$var['teacher_money_type_after_str']}}</td>
                        <td>{{$var['require_adminid']}}</td>
                        <td>{{$var['require_time']}}</td>
                        <td>{{$var['require_reason']}}</td>
                        <td>{{$var['accept_status']}}</td>
                        <td data_id="{{$var['id']}}" data_teacherid="{{$var['teacherid']}}">
                            <a class="fa fa-edit opt-accept"  title="审核"> </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
