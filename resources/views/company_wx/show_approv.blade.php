
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
                <bttton id="id_pull_data" class="btn btn-primary">拉取数据</button>
            </div>
        </div>
        </div>
    </div>
    <hr/>
    <table class="common-table">
        <thead>
            <tr>
                <td>id </td>
                <td>审批名 </td>
                <td>申请人名</td>
                <td>开始时间</td>
                <td>结束时间</td>
                <td>申请原因</td>
                <td>申请人部门</td>
                <td>审批人</td>
                <td>抄送人</td>
                <td>审核状态</td>
                <td>申请时间</td>
                <td>操作  </td>
            </tr>
        </thead>
        <tbody>
            @foreach($info as $item)
            <tr>
                <td>{{$item['id']}}</td>
                <td>{{$item['spname']}}</td>
                <td>{{$item['apply_name']}}</td>
                <td>{{$item['start_time_str']}}</td>
                <td>{{$item['end_time_str']}}</td>
                <td>{{$item['reason']}}</td>
                <td>{{$item['apply_org']}}</td>
                <td>{{$item['approval_name']}}</td>
                <td>{{$item['notify_name']}}</td>
                <td>{{$item['sp_status_str']}}</td>
                <td>{{$item['apply_time_str']}}</td>
                <td data_id="{{$item['id']}}">
                    <a class="btn  fa fa-cog td-info" title="竖向显示"></a>
                    @if ($item['flag'])
                        <a class="btn fa fa-edit opt-edit" title="更新修改"></a>
                    @endif
                    <a class="btn fa opt-detail" title="详细信息">详情</a>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
@include('layouts.page')
</section>
@endsection
