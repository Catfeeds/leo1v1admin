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
     var tag_one = '{{@$tag_info['tag_one']['menu']}}';
     var tag_two = '{{@$tag_info['tag_two']['menu']}}';
     var tag_three = '{{@$tag_info['tag_three']['menu']}}';
     var tag_four = '{{@$tag_info['tag_four']['menu']}}';
     var tag_five = '{{@$tag_info['tag_five']['menu']}}';

     var tag_one_name = '{{@$tag_info['tag_one']['name']}}';
     var tag_two_name = '{{@$tag_info['tag_two']['name']}}';
     var tag_three_name = '{{@$tag_info['tag_three']['name']}}';
     var tag_four_name = '{{@$tag_info['tag_four']['name']}}';
     var tag_five_name = '{{@$tag_info['tag_five']['name']}}';

     var my_subject = {{@$subject}};
     var my_grade = {{@$grade}};
     var book = {{@$book}};
     var is_teacher = {{@$is_teacher}};
    </script>
    <style>
     .up_file,.down_file,.dele_file{ padding: 4px;margin-left: 6px;margin-bottom:5px };
    </style>
    <section class="content">

        <div>
            <!-- <div class="row  row-query-list" >
                 <div class="col-xs-12 col-md-5"  data-title="时间段">
                 <div  id="id_date_range" >
                 </div>
                 </div>
                 </div> -->
            <div class="row">
                <!-- <div class="row row-query-list"> -->
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">分类</span>
                        <select class="form-control opt-change" id="id_use_type"> </select>
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

                <div class="col-xs-6 col-md-2 {{$tag_info['tag_one']['hide']}}">
                    <div class="input-group ">
                        <span class="input-group-addon">{{$tag_info['tag_one']['name']}}</span>
                        <select class="form-control opt-change" id="id_tag_one"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2 {{$tag_info['tag_two']['hide']}} ">
                    <div class="input-group ">
                        <span class="input-group-addon">{{$tag_info['tag_two']['name']}}</span>
                        <select class="form-control opt-change" id="id_tag_two"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2 {{$tag_info['tag_three']['hide']}} ">
                    <div class="input-group ">
                        <span class="input-group-addon">{{$tag_info['tag_three']['name']}}</span>
                        <select class="form-control opt-change" id="id_tag_three"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-3 {{@$tag_info['tag_four']['hide']}} ">
                    <div class="input-group ">
                        <span class="input-group-addon">{{@$tag_info['tag_four']['name']}}</span>
                        <select class="form-control opt-change" id="id_tag_four"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2 {{@$tag_info['tag_five']['hide']}} ">
                    <div class="input-group ">
                        <span class="input-group-addon">{{@$tag_info['tag_five']['name']}}</span>
                        <select class="form-control opt-change" id="id_tag_five"> </select>
                    </div>
                </div>

            </div>
            <div class="row">
                <div class="col-xs-2 col-md-1 ">
                    <button class="btn btn-warning opt-add">上传</button>
                </div>
                <div class="col-xs-2 col-md-1 ">
                    <button class="btn btn-warning opt-del">删除</button>
                </div>
                <div class="col-xs-2 col-md-2 ">
                    <button class="btn btn-primary opt-sub-tag">添加学科化标签</button>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <input class="opt-change form-control" id="id_file_title" placeholder="输入文件名称搜索" />
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <table class="common-table" id="menu_mark">
            <thead>
                <tr>
                    <td style="width:10px">
                        <a href="javascript:;" id="id_select_all" title="全选">全</a>
                        <a href="javascript:;" id="id_select_other" title="反选">反</a>
                    </td>
                    <td>文件名</td>
                    <td>修改日期</td>
                    <td>操作人</td>
                    <td>文件格式</td>
                    <td>文件信息</td>
                    <td>文件大小</td>
                    <td>科目</td>
                    <td>年级</td>
                    @if($resource_type <= 6)
                        <td>教材</td>
                    @endif

                    @if( in_array($resource_type,[1,2,9]))
                        <td>春暑秋寒</td>
                    @endif

                    @if( $resource_type == 1 || $resource_type == 3 )
                        <td>学科化标签</td>
                        <td>难度类型</td>
                    @endif

                    @if($resource_type < 7 && $resource_type > 3)
                        <td>上下册</td>
                    @endif

                    @if($resource_type == 6)
                        <td>年份</td>
                        <td>省份</td>
                        <td>城市</td>
                    @endif

                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr class="right-menu" {!!  \App\Helper\Utils::gen_jquery_data($var )  !!} >
                        <td>
                            <input type="checkbox" class="opt-select-item" data-file_id="{{$var["file_id"]}}" data-id="{{$var["resource_id"]}}"/>
                        </td>
                        <td>{{@$var["file_title"]}} </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["file_type"]}} </td>
                        <td>{{@$var["file_use_type_str"]}} </td>
                        <td>{{@$var["file_size_str"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        @if( $resource_type <= 6)
                            <td>{{@$var["tag_one_str"]}} </td>
                        @endif

                        @if( in_array($resource_type,[1,2,9]))
                            <td>{{@$var["tag_two_str"]}}</td>
                        @endif

                        @if( $resource_type == 1 || $resource_type == 3)
                            <td>{{@$var["tag_four_str"]}} </td>
                            @if( $resource_type == 1)
                                <td>{{@$var["tag_five_str"]}} </td>
                            @else
                                <td>{{@$var["tag_three_str"]}} </td>
                            @endif
                        @endif

                        @if($resource_type < 7 && $resource_type > 3)
                            <td>{{@$var["tag_five_str"]}}</td>
                        @endif

                        @if($resource_type == 6)
                            <td>{{@$var["tag_two"]}}</td>
                            <td class="province">{{@$var["tag_three"]}}</td>
                            <td class="city">{{@$var["tag_four"]}}</td>
                        @endif

                        <td>
                            <a class="opt-look btn color-blue" data-file_id="{{$var["file_id"]}}"  title="预览">预览</a>
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
