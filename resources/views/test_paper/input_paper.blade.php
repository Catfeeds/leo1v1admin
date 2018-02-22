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

                <div class="col-xs-6 col-md-2">
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
                        <select class="form-control opt-change" id="id_tag_one"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">教材版本</span>
                        <select class="form-control opt-change" id="id_tag_two"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">录入状态</span>
                        <select class="form-control opt-change" id="id_tag_three"> </select>
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
                                <a class="opt-upload btn color-blue" data-id="{{$var['eid']}}" title="上传">上传</a>
                            @else
                                <a class="opt-upload btn color-blue" data-id="{{$var['eid']}}" title="重传">重传</a>
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
