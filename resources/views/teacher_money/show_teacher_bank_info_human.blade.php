
@extends('layouts.app')
@section('content')
<section class='content'>
    <div> <!-- search ... -->
        <div class="row">
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span>是否有银行卡</span>
                    <select id="id_is_bank" class="opt-change">
                        <option value="-1">全部</option>
                        <option value="1">有银行卡</option>
                        <option value="2">没有银行卡</option>
                    </select>
                </div>
            </div>
        </div>
    </div>
    <hr/>
    <table class="common-table">
        <thead>
            <th>老师id</th>
            <th>老师姓名</th>
            <th>科目</th>
            <th>手机号</th>
            <th>持卡人</th>
            <th>卡号</th>
            <th>银行类型</th>
            <th>开户省</th>
            <th>开户市</th>
            <th>支行</th>
            <th>手机预留号</th>
            <th>身份证</th>
            <td>绑卡时间</td>
        </thead>
        <tbody>
            @foreach($info as $var)
                <tr>
                    <td>{{$var['teacherid']}}</td>
                    <td>{{$var['nick']}}</td>
                    <td>{{$var['subject_str']}}</td>
                    <td>{{$var['phone']}}</td>
                    <td>{{$var['bank_account']}}</td>
                    <td>{{$var['bankcard']}}</td>
                    <td>{{$var['bank_type']}}</td>
                    <td>{{$var['bank_province']}}</td>
                    <td>{{$var['bank_city']}}</td>
                    <td>{{$var['bank_address']}}</td>
                    <td>{{$var['bank_phone']}}</td>
                    <td>{{$var['idcard']}}</td>
                    <td>{{$var['bind_bankcard_time_str']}}</td>
                </tr>
                @endforeach
        </tbody>
    </table>
@include('layouts.page')
</section>
@endsection
