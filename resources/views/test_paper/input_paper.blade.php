@extends('layouts.app')
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
    </script>
    <style>
     .hide{ display:none }
     .up_file,.down_file,.dele_file{ padding: 4px;margin-left: 6px;margin-bottom:5px };
    </style>
    <section class="content">

        <div>
            
            <div class="row">
                <!-- <div class="row row-query-list"> -->
                <div class="col-xs-6 col-md-4" data-title="修改时间">
                    <div id="id_date_range"> </div>
                </div>

                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span class="input-group-addon">评测ID</span>
                        <input class="form-control opt-change" id="id_paper"> </select>
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

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">上下册</span>
                        <select class="form-control opt-change" id="id_volume"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">教材版本</span>
                        <select class="form-control opt-change" id="id_book"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">录入状态</span>
                        <select class="form-control opt-change" id="id_input"> </select>
                    </div>
                </div>

                <div class="col-xs-2 col-md-2 ">
                    <button class="btn btn-primary opt-sub-tag">新建评测卷</button>
                </div>

                <div class="col-xs-2 col-md-2 ">
                    <button class="btn btn-primary opt-sub-tag">导入评测卷（excel）</button>
                </div>

            </div>
        </div>
        <hr/>
        <table class="common-table" id="menu_mark">
            <thead>
                <tr>
                    <th>评测ID</th>
                    <th>科目</th>
                    <th>年级</th>
                    <th>上下册</th>
                    <th>教材版本</th>
                    <th>操作人</th>
                    <th>修改时间</th>
                    <th>使用次数</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr class="right-menu" {!!  \App\Helper\Utils::gen_jquery_data($var )  !!} >
                        <td>{{@$var["paper_id"]}} </td>
                        <td>{{@$var["subject"]}} </td>
                        <td>{{@$var["grade"]}} </td>
                        <td>{{@$var["volume"]}} </td>
                        <td>{{@$var["book"]}} </td>
                        <td>{{@$var['operator']}}</td>
                        <td>{{@$var['modify_time']}}</td>
                        <td>{{@$var['use_number']}}</td>
                        <td style="max-width:150px">
                            <a class="opt-edit btn color-blue"  title="编辑">编辑</a>
                            <a class="opt-dele btn color-blue" title="删除">删除</a>          
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

    <div class="col-md-12 opt_process"  style="width:600px;position:fixed;right:0;top:200px;border-radius:5px;background:#eee;opacity:0.8;display:none;">
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

@endsection
