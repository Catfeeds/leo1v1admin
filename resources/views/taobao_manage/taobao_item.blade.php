@extends('layouts.app')
@section('content')
<section class="content">
    <div class="row row-query-list">
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">根分类</span>
                <select class="form-control" id="id_parent_cid" >
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group">
                <span class="input-group-addon">子分类</span>
                <select class="opt-change form-control" id="id_cid" >
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group">
                <span class="input-group-addon">状态</span>
                <select class="opt-change form-control" id="id_status" >
                    <option value="-1">[全部]</option> 
                    <option value="0">已下架</option> 
                    <option value="1">正常</option> 
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group">
                <button class="btn btn-primary" id="id_update_taobao">更新商品</button>
            </div>
        </div>
    </div>
    <hr/>
    <table class="common-table ">
        <thead>
            <tr>
                <td class="remove-for-xs">商品图片</td>
                <td class="remove-for-xs">混淆ID</td>
                <td class="remove-for-xs">商品ID</td>
                <td class="remove-for-xs">商品标题</td>
                <td class="remove-for-xs">商品价格</td>
                <td class="remove-for-xs">最近修改时间</td>
                <td class="remove-for-xs">状态</td>
                <td class="remove-for-xs">排序</td>
                <td style="min-width:200px">操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
                <tr>
                    <td ><img src="{{$var["pict_url"]}}" width="100" height="100"></td>
                    <td >{{$var["open_iid"]}}</td>
                    <td >{{$var["product_str"]}}</td>
                    <td >{{$var["title"]}}</td>
                    <td >{{$var["price"]}}</td>
                    <td >{{$var["last_modified_str"]}}</td>
                    <td >{{$var["status_str"]}}</td>
                    <td >{{$var["sort_order"]}}</td>
                    <td class="remove-for-xs">
                        <div class="opt"
                             {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                        >
                            <a class="btn fa opt-info" title="编辑淘宝额外信息">编辑</a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @include("layouts.page")
</section>
@endsection
