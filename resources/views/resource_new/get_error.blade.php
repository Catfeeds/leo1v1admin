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
     var identity = "{{@$identity}}";
    </script>
    <style>
     .hide{ display:none }
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
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">二级错误</span>
                        <select class="form-control opt-change" id="id_sub_error_type">
                            @foreach($sub_error_arr as $k => $v)
                                <option value="{{$k}}">{{$v}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-xs-1 col-md-1">
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
                    <th style="max-width:200px">文件名</th>
                    <th>报错详情</th>
                    <th>错误类型</th>
                    <th style="max-width:200px">报错内容</th>
                    <th>讲义详情</th>

                    <th>上传详情</th>
                    <th>状态</th>
                    
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr class="right-menu" {!!  \App\Helper\Utils::gen_jquery_data($var )  !!} >
                        <td style="max-width:200px">{{@$var["file_title"]}} </td>
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

                            <td style="max-width:200px">
                                {{@$var['detail_error']}}<br/>
                                @if(@$var['picture_one'] != '')
                                    <a href="{{@$var['picture_one']}}" target="_blank">图片1</a>
                                @endif
                                @if(@$var['picture_two'] != '')
                                    <a href="{{@$var['picture_two']}}" target="_blank">图片2</a>
                                @endif
                                @if(@$var['picture_three'] != '')
                                    <a href="{{@$var['picture_three']}}" target="_blank">图片3</a>
                                @endif
                                @if(@$var['picture_four'] != '')
                                    <a href="{{@$var['picture_five']}}" target="_blank">图片4</a>
                                @endif
                                @if(@$var['picture_five'] != '')
                                    <a href="{{@$var['picture_five']}}" target="_blank">图片5</a>
                                @endif
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

                        <td class="file_status">
                            @if(@$var['estatus'] == 0)
                                <span style="color:#e81616">未处理</span>
                            @elseif(@$var['estatus'] == 1)
                                <span style="color:#e81616">待修改</span>
                            @elseif(@$var['estatus'] == 2)
                                <span style="color:#2c8404">已修改</span>
                            @elseif(@$var['estatus'] == 3)
                                <span style="color:#e81616">初审驳回</span>
                            @elseif(@$var['estatus'] == 4)
                                <span style="color:#e81616">复审驳回</span>
                            @endif
                        </td>
                        <td style="max-width:150px">
                            <a class="opt-look btn color-blue" data-file_id="{{$var["file_id"]}}"  title="预览">预览</a>
                            @if(@$var['estatus'] == 0)
                                <a class="opt-agree btn color-blue" title="预览">同意修改</a>
                            @else
                                <span style="color:#2d2828">已同意</span>
                            @endif

                            @if(@$var['estatus'] == 0)
                                <a class="opt-upload btn color-blue hide" title="上传">上传</a>
                            @elseif(@$var['estatus'] == 1)
                                <a class="opt-upload btn color-blue" title="上传">上传</a>
                            @else
                                <a class="opt-upload btn color-blue" title="重传">重传</a>
                            @endif

                            @if($var['estatus'] == 3 && $var['estatus'] != 4)
                                <span style="color:#e81616">初审已驳回</span>
                            @endif

                            @if($var['estatus'] != 3 && $var['estatus'] != 4)
                                <a class="opt-first-look btn color-blue" title="初审驳回">初审驳回</a>
                            @endif

                            @if(@$var['estatus'] == 4)
                                <span style="color:#e81616">初审已驳回</span>
                                <span style="color:#e81616">复审已驳回</span>
                            @else
                                <a class="opt-sec-look btn color-blue"  title="复审驳回">复审驳回</a>
                            @endif

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
