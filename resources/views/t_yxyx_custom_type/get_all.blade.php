@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/js/all.js"></script>
    <section class="content">
        <div class="row">
            <div class="col-xs-2">
                <div class="input-group input-group-btn ">
                    <button class="btn btn-primary add_new_type">添加</button>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td class="remove-for-not-xs"></td>
                    <td>id</td>
                    <td>标签名称</td>
                    <td>操作人员</td>
                    <td>时间</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
				            <tr>
                        @include('layouts.td_xs_opt')
                        <td>{{$var["custom_type_id"]}} </td>
                        <td>{{$var["type_name"]}}</td>
                        <td>{{$var["nick"]}}</td>
                        <td>{{$var["create_time"]}}</td>
                        <td class="remove-for-xs">
                            <div class="btn-group" data-id="{{$var["custom_type_id"]}}">
                                <a href="javascript:;" class="btn fa fa-edit opt-update" title="更改"></a>
                                <a href="javascript:;" class="btn fa fa-trash-o opt-del" title="删除"></a>
                            </div>
                        </td>
				            </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    <div class="dlg_add_type" style="display:none">
        <table class="table table-bordered table-striped">
	          <tbody>
                <tr>
			              <td style="text-align:right; width:30%;">标签名称</td>
			              <td>
                        <input value="" class="add_type" type="text" style="width:50%"/>
                    </td>
		            </tr>
		        </tbody>
	      </table>
    </div>
@endsection
