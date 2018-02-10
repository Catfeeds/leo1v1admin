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
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/js/jquery.query.js"></script>
    <script src="/page_js/enum_map.js" type="text/javascript"></script>
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
                <div class="col-xs-6 col-md-2 hide_class">
                    <div class="input-group ">
                        <span >初版讲义上传人</span>
                        <input type="text" value=""  class="opt-change"  id="id_adminid"  placeholder="" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2 hide_class">
                    <div class="input-group ">
                        <span >修改重传负责人</span>
                        <input type="text" value=""  class="opt-change"  id="id_reload_adminid"  placeholder="" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2 hide_class">
                    <div class="input-group ">
                        <span >kpi讲义负责人</span>
                        <input type="text" value=""  class="opt-change"  id="id_kpi_adminid"  placeholder="" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2 hide_class">
                    <div class="input-group ">
                        <span class="input-group-addon">状态</span>
                        <select class="form-control opt-change" id="id_status"> 
                            <option value="-1">全部</option>
                            <option value="1">待审核</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-xs-2">
                    <button id="id_apply_reload" class="btn btn-primary">批量申请——重传负责人</button>
                </div>
                <div class="col-md-2 col-xs-2">
                    <button id="id_apply_kpi" class="btn btn-primary">批量申请——统计负责人</button>
                </div>
                <div class="col-md-2 col-xs-2">
                    <button id="id_affirm_reload" class="btn btn-primary">批量审批——重传负责人</button>
                </div>
                <div class="col-md-2 col-xs-2">
                    <button id="id_affirm_kpi" class="btn btn-primary">批量审批——统计负责人</button>
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
                    <td style="max-width:200px;word-wrap: break-word;">文件名</td>
                    <td>科目</td>
                    <td>年级</td>
                    <td>资源类型</td>

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

                    <td>上传日期</td>
                    <td>初版讲义上传人</td>
                    <td>修改重传负责人</td>
                    <td>kpi讲义负责人</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr class="right-menu" {!!  \App\Helper\Utils::gen_jquery_data($var )  !!} >
                        
                        <td>
                            <input type="checkbox" class="opt-select-item" data-file_id="{{$var["file_id"]}}" data-kpi_status="{{$var["kpi_status"]}}" data-reload_status="{{$var["reload_status"]}}" data-id="{{$var["resource_id"]}}"/>
                        </td>
                        <td style="max-width:200px">{{@$var["file_title"]}} </td>
                        <td>{{@$var['subject_str']}}</td>
                        <td>{{@$var['grade_str']}}</td>
                        <td>{{@$var['resource_type_str']}}</td>
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

                        
                       <td>{{@$var['c_time']}}</td>
                       <td>{{@$var['admin_nick']}}</td>
                       <td>
                       @if(@$var['reload_adminid'] > 0)
                       {{@$var['reload_adminid_str']}}
                       @else
                       {{@$var['admin_nick']}}
                       @endif
                       @if(@$var['reload_status'] == 1)
                       <a class="fa  opt-redo" data-type="1" data-file_id="{{@$var['file_id']}}" data-resource_id="{{@$var['resource_id']}}"title="{{@$var['reload_status_string']}}"><font color="red">{{@$var['reload_status_str']}}</font></a>
                       @else
                       <a class="fa  opt-re-status" data-type="1" data-file_id="{{@$var['file_id']}}" data-resource_id="{{@$var['resource_id']}}"title="{{@$var['reload_status_string']}}">{{@$var['reload_status_str']}}</a>
                       @endif

                       <a class="fa  opt-re-edit"  data-type="1" title="审批" data-file_id="{{@$var['file_id']}}" title="{{@$var['kpi_status_string']}}" data-resource_id="{{@$var['resource_id']}}" data-file_title="{{@$var['file_title']}}" data-subject_str="{{@$var['subject_str']}}" data-grade_str="{{@$var['grade_str']}}" data-reload_status="{{@$var['reload_status']}}">审批</a>
                        </td>
                        <td>
                       @if(@$var['kpi_adminid'] > 0)
                       {{@$var['kpi_adminid_str']}}
                       @else
                       {{@$var['admin_nick']}}
                       @endif

                       @if(@$var['kpi_status'] == 1)
                       <a class="fa  opt-redo" data-type="2" data-file_id="{{@$var['file_id']}}" title="{{@$var['kpi_status_string']}}" data-resource_id="{{@$var['resource_id']}}"><font color="red">{{@$var['kpi_status_str']}}</font></a>
                       @else
                        <a class="fa  opt-re-status" data-type="2" data-file_id="{{@$var['file_id']}}" title="{{@$var['kpi_status_string']}}" data-resource_id="{{@$var['resource_id']}}">{{@$var['kpi_status_str']}}</a>
                       @endif

                       <a class="fa  opt-re-edit" data-type="2" title="审批" data-file_id="{{@$var['file_id']}}" title="{{@$var['kpi_status_string']}}" data-resource_id="{{@$var['resource_id']}}" data-file_title="{{@$var['file_title']}}" data-subject_str="{{@$var['subject_str']}}" data-grade_str="{{@$var['grade_str']}}" data-kpi_status="{{@$var['kpi_status']}}">审批</a>

                        </td>

                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
