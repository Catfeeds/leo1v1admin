
@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/js/all.js"></script>
<section class='content'>
    <div> <!-- search ... -->
        <div class='row ' >
            
            <div class='col-xs-2 col-md-5'>
                <div id='id_date_range' >
                </div>
            </div>

            @if ($flag)
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <bttton id="id_add" class="btn btn-primary">拉取数据</button>
            </div>
        </div>
        @endif
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
                <td>数据页面地址</td>
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
                    @if ($item['page_url'])
                        <td><a class="page_url" target="_blank" href="{{$item['page_url']}}">数据页面</a></td>
                    @else
                        <td><a class="page_url"></a> </td>
                    @endif
                    @if ($item['data_url'])
                        <td><a target="_blank" href={{$item['data_url']}} class="download">数据下载<a></td>
                    @else
                        <td></td>
                    @endif
                    <td data_id="{{$item['id']}}">
                        <a class="btn  fa fa-cog td-info" title="竖向显示"></a>
                        @if($flag)
                            <a class="btn opt-add-page" title="添加页面地址">添加页面</a>
                            <a class="btn fa fa-edit opt-edit" title="添加数据下载地址"></a>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @include('layouts.page')
</section>
<div class="dlg_add_pic_info" style="display:none">
    <table class="table table-bordered table-striped">
	      <tbody>
            <tr>
            <td style="text-align:right; width:150px;">上传下载文件</td>
			      <td>
                <div id="id_container_add">
                    <input id="id_upload_add" value="上传下载文件" class="btn btn-primary add_pic_img" style="margin-bottom:5px;" type="button"/>
                </div>
                <div class="add_header_img"></div>
                <div class="data_url"></div>
            </td>
		        </tr>

        </tbody>
    </table>
</div>
@endsection
