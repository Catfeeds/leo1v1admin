@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>


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
                        <span class="input-group-addon">资源类型</span>
                        <select class="form-control" id="id_resource_type"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">科目</span>
                        <select class="form-control" id="id_subject"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">年级</span>
                        <select class="form-control" id="id_grade"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">文件名搜索</span>
                        <input class="opt-change form-control" id="id_file_title" />
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <button class="btn btn-warning opt-add">上传</button>
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
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
                    <td style="width:10%">使用次数</td>
                    <!-- <td> 操作  </td> -->
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr {!!  \App\Helper\Utils::gen_jquery_data($var )  !!} >
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
                        <td>{{@$var["use_num"]}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
