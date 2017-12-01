@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
<script type="text/javascript" src="//g.alicdn.com/sj/aliphone-sdk/aliphone.min.js" charset="utf-8"></script>

    <section class="content">
        <div class="row">

            <div class="col-md-1">
                <button class="btn btn-primary" id="id_add_new">添加</button>
            </div>
            <div class="col-xs-6 col-md-2" >
                <div class="input-group ">
                    <span >key0</span>
                    <select id="id_key0"  >
                        <option value="">全部</option>
                        @foreach ($key0_list as $item )
                            <option value="{{$item["k"]}}">{{$item["k"]}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-xs-6 col-md-2" >
                <div class="input-group ">
                    <span >key1</span>
                    <select id="id_key1"  >
                        <option value="">全部</option>
                        @foreach ($key1_list as $item )
                            <option value="{{$item["k"]}}">{{$item["k"]}}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-xs-6 col-md-2" >
                <div class="input-group ">
                    <span class="input-group-addon">key2</span>
                    <select id="id_key2"  >
                        <option value="">全部</option>
                        @foreach ($key2_list as $item )
                            <option value="{{$item["k"]}}">{{$item["k"]}}</option>
                        @endforeach
                    </select>

                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">key3</span>
                    <select id="id_key3"  >
                        <option value="">全部</option>
                        @foreach ($key3_list as $item )
                            <option value="{{$item["k"]}}">{{$item["k"]}}</option>
                        @endforeach
                    </select>

                </div>

            </div>

            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">key4</span>
                    <select id="id_key4"  >
                        <option value="">全部</option>
                        @foreach ($key4_list as $item )
                            <option value="{{$item["k"]}}">{{$item["k"]}}</option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="col-xs-6 col-md-2" >
                <div class="input-group ">
                    <span >渠道等级</span>
                    <select id="id_origin_level"  >
                    </select>
                </div>
            </div>

            <div class="col-md-3 col-xs-10 ">
                <div class="input-group col-sm-12">
                    <span class="input-group-addon">查询</span>
                    <input type="text" value="" class="for_input form-control opt-change" data-field="ass_name" id="id_value" placeholder="请输入value"  />
                </div>
            </div>


            <div class="col-md-8 col-xs-10 ">
                <button type="button" class="btn btn-info" id="id_edit_all_origin_level">设置当前渠道等级</button>
                <button type="button" class="btn btn-warning" id="id_upload_xlsx">批量上传</button>
                <button type="button" class="btn btn-warning" id="id_download_xlsx">下载value文件</button>
                <button type="button" class="btn btn-info" id="id_example_xlsx">下载 上传的样例文件</button>
            </div>



        </div>
        <hr />
        <div class="body">
            <table class="common-table ">
                <thead>
                    <tr>
                        <td >key0</td>
                        <td >key1</td>
                        <td >key2</td>
                        <td >key3</td>
                        <td >key4</td>
                        <td >value</td>
                        <td >渠道等级</td>
                        <td >生成时间</td>
                        <td >操作</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr>
                            <td >{{@$var["key0"]}}</td>
                            <td >{{$var["key1"]}}</td>
                            <td >{{$var["key2"]}}</td>
                            <td >{{$var["key3"]}}</td>
                            <td >{{$var["key4"]}}</td>
                            <td >{{$var["value"]}}</td>
                            <td >{{$var["origin_level_str"]}}</td>
                            <td >{{$var["create_time_str"]}}</td>
                            <td>
                                <div class="opt-div"
                                     data-value="{{$var["value"]}}"
                                >
                                    <a href="javascript:;" title="编辑信息" class="fa fa-edit done_e"></a>
                                    <a href="javascript:;" title="删除" class="fa fa-trash-o done_t"></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include("layouts.page")
        </div>

    <script src="/js/qiniu/plupload/plupload.full.min.js"></script>

@endsection
