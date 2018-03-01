
@extends('layouts.app')
@section('content')
    <script>
     window.easemobim = window.easemobim || {};
     easemobim.config = {
         configId: '444c659a-3f92-4719-905e-a2280d9ecd53',
         agentName: 'ricky@leoedu.com'
     };
    </script>
<script src='//kefu.easemob.com/webim/easemob.js'></script>
<section class='content'>
    <div> <!-- search ... -->
    </div>
    <hr/>
    <table class="common-table">
        <thead>
            <tr>
                <th>用户名</th>
                <th>手机号</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach($info as $var)
                <tr>
                    <td class="name">{{$var['name']}}</td>
                    <td class="phone" style="display:none">{{$var['phone']}}</td>
                    <td>{{$var['mobile']}}</td>
                    <td>
                        <a class="opt-edit" title="将企业微信手机号更新至少管理后台">更换后台手机号</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@include('layouts.page')
</section>
@endsection
