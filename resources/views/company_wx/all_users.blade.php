
@extends('layouts.app')
@section('content')
<section class='content'>
    <div> <!-- search ... -->
        <div class='col-xs-12 col-md-5' data-title='时间段'>
        </div>
        <div class='row  row-query-list' >
            <div class='col-xs-12 col-md-5'>
                <div id='id_date_range' >
                </div>
            </div>
        </div>
    </div>
    <hr/>
    <table class="common-table">
        <thead>
            <tr>
                <td>id </td>
                <td>用户名</td>
                <td>所属标签名</td>
                <td>领导权限</td>
                <td>非领导权限</td>
                <td>权限组id</td>
            </tr>
        </thead>
        <tbody>
            @foreach($info as $var)
            <tr>
                <td>{{$var['userid']}}</td>
                <td>{{$var['username']}}</td>
                <td>{{$var['name']}}</td>
                <td>@if($var['isleader'] == 1) 是 @endif</td>
                <td>@if($var['isleader'] == 0) 是 @endif</td>
                <td>{{$var['permission']}}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
@include('layouts.page')
</section>
@endsection
