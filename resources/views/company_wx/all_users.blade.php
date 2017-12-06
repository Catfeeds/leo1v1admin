
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
                <td>属标签名</td>
                <td>领导权限</td>
                <td>非领导权限</td>
                <td>操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach($info as $var)
            <tr>
                <td class="id">{{$var['id']}}</td>
                <td>{{$var['name']}}</td>
                <td class="leader_power">{{$var['leader_power']}}</td>
                <td class="no_leader_power">{{$var['no_leader_power']}}</td>
                <td>
                    <a class="fa opt-leader" title="添加领导权限">添加领导权限</a>
                    <a class="fa opt-not-leader" title="添加非领导权限">添加非领导权限</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@include('layouts.page')
</section>
@endsection
