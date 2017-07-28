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
                    <button class="btn btn-primary add_member" id="id_add">添加成员</button>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td class="remove-for-not-xs"></td>
                    <td>id</td>
                    <td>被修改人</td>
                    <td>修改人</td>
                    <td>修改类型</td>
                    <td>原始值</td>
                    <td>修改值</td>
                    <td>修改时间</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
				            <tr>
                        @include('layouts.td_xs_opt')
                        <td class="remove-for-xs">{{$var['id']}}</td>
                        <td class="remove-for-xs">{{$var['adminid']}}</td>
                        <td class="remove-for-xs">{{$var['uid']}}</td>
                        <td class="remove-for-xs">{{$var['type']}}</td>
                        <td class="remove-for-xs">{{$var['old']}}</td>
                        <td class="remove-for-xs">{{$var['new']}}</td>
                        <td class="remove-for-xs">{{$var['create_time']}}</td>
                        <td class="remove-for-xs">
                            <div class="btn-group" >
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
