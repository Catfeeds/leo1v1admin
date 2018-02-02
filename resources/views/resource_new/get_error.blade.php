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
            
            <div class="row">
                <!-- <div class="row row-query-list"> -->
                <div class="col-xs-12 col-md-4" data-title="时间段">
                    <div id="id_date_range"> </div>
                </div>
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
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">一级错误</span>
                        <select class="form-control opt-change" id="id_error_type"> 
                            <option value="-1">未设置</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">二级错误</span>
                        <select class="form-control opt-change" id="id_sub_error_type"> 
                            <option value="-1">未设置</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-2 col-md-2">
                    <div class="input-group ">
                        <input class="opt-change form-control" id="id_file_id" placeholder="" />
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
                    <td>报错详情</td>
                    <td>错误类型</td>
                    <td>报错内容</td>

                    <td>讲义详情</td>

                    <td>上传详情</td>
                    <td>状态</td>
                
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
                        <td>报错人:{{@$var['error_nick']}} <br/>
                            报错时间:{{@$var['add_time']}}
                        </td>
                        @if( @$var['etype'] == 9)

                        @else
                        <td>一级:{{@$var['error_type_str']}}<br/>
                            二级:{{@$var['sub_error_type_str']}}
                        </td>
                        @endif

                        @if( @$var['etype'] == 9)

                        @else
                        <td>
                            {{@$var['detail_error']}}<br/>



                        </td>
                        @endif

                        <td>资源类型:{{@$var['resource_type_str']}}<br/>
                            科目:{{@$var['subject_str']}}<br/>
                            年级:{{@$var['grade_str']}}<br/>
                            @if ($var['tag_one_name'] != '')
                            {{ @$var['tag_one_name']}}:{{@$var['tag_one_str']}}<br/>
                            @endif

                            @if ($var['tag_two_name'] != '')
                            {{@$var['tag_two_name']}}:{{@$var['tag_two_str']}}<br/>
                            @endif
                            @if ($var['tag_three_name'] != '')
                            {{@$var['tag_three_name']}}:{{@$var['tag_three_str']}}<br/>
                            @endif
                            @if ($var['tag_four_name'] != '')
                            {{@$var['tag_four_name']}}:{{@$var['tag_four_str']}}<br/>
                            @endif
                            @if ($var['tag_five_name'] != '')
                            {{@$var['tag_five_name']}}:{{@$var['tag_five_str']}}<br/>
                            @endif
                        </td>

                        <td>上传者:{{@$var['nick']}}<br/>
                            上传时间:{{@$var['c_time']}}<br/>

                        </td>

                        <td></td>
                        <td>
                            <a class="opt-look btn color-blue" data-file_id="{{$var["file_id"]}}"  title="预览">预览</a>

                            <a class="opt-error"></a>
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
