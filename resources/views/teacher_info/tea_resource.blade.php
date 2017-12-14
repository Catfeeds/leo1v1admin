@extends('layouts.teacher_header')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <!-- <script type="text/javascript" src="/js/qiniu/new_ui.js"></script> -->
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/js/jquery.contextify.js"></script>
    <script type="text/javascript" src="/js/area/distpicker.data.js"></script>
	  <script type="text/javascript" src="/js/area/distpicker.js"></script>
    <style>
     td .opt-select-item{
         height:auto;
     }
    </style>

    <section class="content li-section">

        <div>
            <!-- <div class="row  row-query-list" > -->
            <div class="row" >
                <!-- <div class="col-xs-12 col-md-5"  data-title="时间段">
                     <div  id="id_date_range" >
                     </div>
                     </div>

                     <div class="col-xs-6 col-md-2">
                     <div class="input-group " >
                     <span >xx</span>
                     <input type="text" value=""  class="opt-change"  id="id_"  placeholder=""  />
                     </div>
                     </div> -->
                <div class="col-xs-4 col-md-1">
                    <button class="btn btn-info">新建文件夹</button>
                </div>
                <div class="col-xs-4 col-md-1">
                    <button class="btn btn-info">上传</button>
                </div>
                <div class="col-xs-4 col-md-1">
                    <button class="btn btn-info">删除</button>
                </div>
                <div class="col-xs-4 col-md-1">
                    <button class="btn btn-info">移动</button>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <input type="text" value=""  class="opt-change"  id="id_file_title"  placeholder="输入文件名称搜索"  />
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            面包屑
        </div>
        <hr/>
        <table   class="common-table"  >
            <thead>
                <tr>
                    <td style="width:50px">
                        <a href="javascript:;" id="id_select_all" title="全选">全</a>
                        <a href="javascript:;" id="id_select_other" title="反选">反</a>
                    </td>
                    <td style="width:40%">文件名</td>
                    <td style="width:15%">修改日期</td>
                    <td style="width:10%">文件类型</td>
                    <td style="width:10%">文件大小</td>
                    <td >文件来源</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>
                            <input type="checkbox" class="opt-select-item" data-id="{{$var["tea_res_id"]}}" />
                        </td>
                        <td>{{@$var["file_title"]}} </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>{{@$var["file_type"]}} </td>
                        <td>{{@$var["file_size"]}}M </td>
                        <td>
                            @if(@$var['file_id'] > 0)
                                我的收藏
                            @else
                                我的上传
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
