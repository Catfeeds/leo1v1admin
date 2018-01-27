
@extends('layouts.app')
@section('content')
<section class='content'>
    <div> <!-- search ... -->
        <div class='row ' >
            
            <div class='col-xs-2 col-md-5'>
                <div id='id_date_range' >
                </div>
            </div>
            
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <bttton id="id_add" class="btn btn-primary">拉取数据</button>
            </div>
        </div>
        </div>
    </div>
    <hr/>
    <table class="common-table">
        <thead>
            <tr>
                <td>id </td>
                <td>申请人</td>
                <td>申请人userid</td>
                <td>申请时间</td>
                <td>数据描述</td>
                <td>数据字段</td>
                <td>申请原因</td>
                <td>需要时间</td>
                <td>负责人</td>
                <td>数据下载地址</td>
                <td>操作  </td>
            </tr>
        </thead>
        <tbody>
            @foreach($info as $item)
                <tr>
                    <td>{{$item['id']}}</td>
                    <td>{{$item['apply_name']}}</td>
                    <td>{{$item['apply_user_id']}}</td>
                    <td>{{$item['apply_time']}}</td>
                    <td>{{$item['data_desc']}}</td>
                    <td>{{$item['data_column']}}</td>
                    <td>{{$item['require_reason']}}</td>
                    <td>{{$item['require_time']}}</td>
                    <td>{{$item['acc']}}</td>
                    @if ($item['data_url'])
                        <td><a target="_blank" href={{$item['data_url']}}>数据下载<a></td>
                    @else
                        <td></td>
                    @endif
                    <td data_id="{{$item['id']}}">
                        <a class="btn  fa fa-cog td-info" title="竖向显示"></a>
                        <a class="btn fa fa-edit opt-edit" title="添加数据下载地址"></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@include('layouts.page')
</section>
@endsection
