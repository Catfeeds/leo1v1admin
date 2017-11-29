@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/js/jquery.contextify.js"></script>
    <section class="content ">

        <div>
            <!-- <div class="row  row-query-list" >
                 <div class="col-xs-12 col-md-5"  data-title="时间段">
                 <div  id="id_date_range" >
                 </div>
                 </div>

                 <div class="col-xs-6 col-md-2">
                 <div class="input-group " >
                 <span >xx</span>
                 <input type="text" value=""  class="opt-change"  id="id_"  placeholder=""  />
                 </div>
                 </div>
                 </div> -->
            <div class="row">
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">使用角色</span>
                        <select class="form-control opt-change" id="id_user_type"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">资源类型</span>
                        <select class="form-control opt-change" id="id_resource_type"> </select>
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">科目</span>
                        <select class="form-control opt-change" id="id_subject"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">年级</span>
                        <select class="form-control opt-change" id="id_grade"> </select>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-xs-2 col-md-1 ">
                    <button class="btn btn-warning opt-add">上传</button>
                </div>
                <div class="col-xs-2 col-md-1 ">
                    <button class="btn btn-warning opt-down">下载</button>
                </div>
                <div class="col-xs-2 col-md-1 ">
                    <button class="btn btn-warning opt-del">删除</button>
                </div>
                <div class="col-xs-2 col-md-1 ">
                    <button class="btn btn-warning opt-move">移动</button>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <input class="opt-change form-control" id="id_file_title" placeholder="输入文件名称搜索" />
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <table     class="common-table" id="menu_mark">
            <thead>
                <tr>
                    <td style="width:10px">
                        <a href="javascript:;" id="id_select_all" title="全选">全</a>
                        <a href="javascript:;" id="id_select_other" title="反选">反</a>
                    </td>
                    <td style="width:20%">文件名</td>
                    <td style="width:15%">修改日期</td>
                    <td style="width:10%">操作人</td>
                    <td style="width:10%">文件类型</td>
                    <td style="width:10%">文件大小</td>
                    <td style="width:10%">下载次数</td>
                    <td style="width:10%">纠错次数</td>
                    <td style="width:10%">是否使用</td>
                    <!-- <td> 操作  </td> -->
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr class="right-menu" {!!  \App\Helper\Utils::gen_jquery_data($var )  !!} >
                        <td>
                            <input type="checkbox" class="opt-select-item" data-id="{{$var["resource_id"]}}"/>
                        </td>
                        <td>{{@$var["file_title"]}} </td>
                        <td>{{@$var["update_time"]}} </td>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["file_type"]}} </td>
                        <td>{{@$var["file_size"]}}M </td>
                        <td>{{@$var["down_num"]}} </td>
                        <td>{{@$var["error_num"]}} </td>
                        <td>{{@$var["is_use_str"]}} </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

    <div class="col-md-12 opt_process"   style="width:600px;position:fixed;right:0;top:200px;border:1px solid red;background:#ccc;display:none;">
        <table class="table table-striped table-hover text-left" >
            <thead>
                <tr>
                    <th class="col-md-4">文件名</th>
                    <th class="col-md-2">文件大小</th>
                    <th class="col-md-6">上传进度</th>
                </tr>
            </thead>
            <tbody id="fsUploadProgress">
            </tbody>
        </table>
    </div>

@endsection
