
@extends('layouts.app')
@section('content')
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
                        <a class="opt-edit">更换后台手机号</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@include('layouts.page')
</section>
@endsection
