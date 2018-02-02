@extends('layouts.teacher_header')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <!-- <script type="text/javascript" src="/js/qiniu/ui.js"></script> -->
    <script type="text/javascript" src="/js/qiniu/new_ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/js/jquery.contextify.js"></script>
    <script type="text/javascript" src="/js/area/distpicker.data.js"></script>
	  <script type="text/javascript" src="/js/area/distpicker.js"></script>
	  <script type="text/javascript" src="/js/pdfobject.js"></script>
    <script>
     var cur_dir_id = {{$cur_dir}};
    </script>
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
                    <button class="btn btn-info opt-add-dir">新建文件夹</button>
                </div>
                <div class="col-xs-4 col-md-1">
                    <button class="btn btn-info opt-add-file">上传</button>
                </div>
                <div class="col-xs-4 col-md-1">
                    <button class="btn btn-info opt-del">删除</button>
                </div>
                <div class="col-xs-4 col-md-1">
                    <button class="btn btn-info opt-move">移动</button>
                </div>
                <!-- <div class="col-xs-6 col-md-2">
                     <div class="input-group " >
                     <input type="text" value=""  class="opt-change"  id="id_file_title"  placeholder="输入文件名称搜索"  />
                     </div>
                     </div> -->
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                @foreach ( $crumbs as $v )
                    <a href="/teacher_info/tea_resource?dir_id={{@$v['dir_id']}}">{{@$v['name']}}</a>&nbsp;/&nbsp;
                @endforeach
            </div>
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
                    <td style="width:15%">创建日期</td>
                    <td style="width:10%">文件类型</td>
                    <td style="width:10%">文件大小</td>
                    <td >文件来源</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr class="right-menu" {!!  \App\Helper\Utils::gen_jquery_data($var )  !!} >
                        <td>
                            <input type="checkbox" class="opt-select-item" is_dir="{{@$var['file_id']}}" data-id="{{@$var["tea_res_id"]}}" />
                        </td>
                        <td>
                            @if(@$var['file_id'] == -1)
                                <a href="/teacher_info/tea_resource?dir_id={{@$var['dir_id']}}">{{@$var["file_title"]}}</a>
                            @else
                                {{@$var["file_title"]}}
                            @endif
                        </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>{{@$var["file_type"]}} </td>
                        <td>{{@$var["file_size"]}} </td>
                        <td>
                            @if(@$var['file_id'] == -1)
                                文件夹
                            @elseif(@$var['file_id'] == 0)
                                我的上传
                            @else
                                我的收藏
                            @endif
                        </td>
                        <td><a class="opt-look_new btn color-blue"  title="预览" data-file_id="{{@$var['file_id']}}" data-file_type="{{@$var['file_type']}}">预览</a></td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

    <div class="col-md-12 opt_process"   style="width:600px;position:fixed;right:0;top:200px;border-radius:5px;background:#eee;opacity:0.8;display:none;">
        <div class="hide" id="up_load"> </div>
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
    <div class="col-md-12 look-pdf"   style="width:80%;height:90%;position:fixed;right:10%;top:5%;border-radius:5px;background:#eee;display:none;">
        <div class="look-pdf-son">
        </div>
    </div>

@endsection
