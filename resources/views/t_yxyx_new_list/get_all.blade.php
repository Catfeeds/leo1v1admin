@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/js/all.js"></script>
    <section class="content">
        <div class="row">
            <div class="col-xs-2">
                <div class="input-group input-group-btn ">
                    <button class="btn btn-primary add_new_info">添加新闻</button>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td class="remove-for-not-xs"></td>
                    <td>id</td>
                    <td>新闻标题</td>
                    <td>新闻图片</td>
                    <td>添加时间</td>
                    <td>作者</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
				            <tr>
                        @include('layouts.td_xs_opt')
                        <td>{{$var["id"]}} </td>
                        <td>{{$var["new_title"]}}</td>
                        <td><img src="{{$var["new_pic"]}}" height="100"></td>
                        <td>{{$var["create_time"]}}</td>
                        <td>{{$var["nick"]}}</td>
                        <td class="remove-for-xs">
                            <div class="btn-group" data-id="{{$var["id"]}}">
                                <a href="javascript:;" class="btn fa fa-edit opt-update-new_info" title="更改"></a>
                                <a href="javascript:;" class="btn fa-hand-o-up opt-look" title="查看"></a>
                                <a href="javascript:;" class="btn fa fa-trash-o opt-del" title="删除"></a>
                            </div>
                        </td>
				            </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    <div class="dlg_add_new_info" style="display:none">
        <table class="table table-bordered table-striped">
	          <tbody>
                <tr>
			              <td style="text-align:right; width:30%;">新闻标题</td>
			              <td><input value="" class="add_title" type="text" style="width:90%"/></td>
		            </tr>
                <tr>
			              <td style="text-align:right; width:30%;">新闻内容</td>
			              <td>
                        <textarea class="add_content" cols="5"></textarea>
                    </td>
		            </tr>
                <tr>
			              <td style="text-align:right; width:30%;">新闻图片</td>
			              <td>
                        <div id="id_container_add">
                            <input id="id_upload_add" value="上传图片" class="btn btn-primary add_pic_img" style="margin-bottom:5px;" type="button"/>
                        </div>
                        <div class="add_header_img"></div>
                        <div class="add_pic"></div>
                    </td>
		            </tr>
		        </tbody>
	      </table>
    </div>
@endsection
