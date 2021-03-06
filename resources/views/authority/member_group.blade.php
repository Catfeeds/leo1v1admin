@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/js/all.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>

    <section class="content">
        <div class="row">
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span >销售</span>
                    <input class="will_change admin_id" /> 
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">分组</span>
                    <select class="will_change form-control group_id">
                    </select>
                </div>
            </div>
            <div class="col-xs-1">
                <div class="input-group input-group-btn ">
                    <button class="btn btn-primary add_member">添加成员</button>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td class="remove-for-not-xs"></td>
                    <td>序号</td>
                    <td>adminid</td>
                    <td>昵称</td>
                    <td>groupid</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
				    <tr>
                        @include('layouts.td_xs_opt')
                        <td>{{$var["number"]}} </td>
                        <td>{{$var["adminid"]}} </td>
                        <td>{{$var["admin_nick"]}} </td>
                        <td>{{$var["groupid"]}}</td>
                        <td class="remove-for-xs">
                            <div class="btn-group" data-id="{{$var["adminid"]}}">
                                <a href="javascript:;" class="btn fa fa-edit opt-update-member" title="更改"></a>
                                <a href="javascript:;" class="btn fa fa-trash-o opt-del" title="删除"></a>
                            </div>
                        </td>
				    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
@endsection
