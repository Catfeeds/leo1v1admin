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
     .up_file,.down_file,.dele_file{ padding: 4px;margin-left: 6px;margin-bottom:5px };
     .hide{ display:none}
     .fl{ float:left }
     .fr{ float:right }
     .clear_both{ clear:both }
     .file_tag{ width:800px;height:45px; }
     .tag_item span{ width:130px;text-align:right;font-weight:bold;display:inline-block;padding-right:30px;line-height:45px }
     .tag_con{ width:670px}
     .tag_item{ margin-bottom:15px }
     .tag_item .tag_title{line-height: 30px; }
     .tag_item .upload_file{margin-top:6px}
     .resource_fa span,.resource_son span{ width: 120px;border: 1px solid #777;margin-right: 0px;display: block;float: left;text-align: center;padding-right: 0px;height:50px;cursor:pointer;line-height: 50px;}
     .tag_con span.resource_check{ background: #0088ccb5;color: white;}
     .tag_con label{ margin-right: 30px; }
     .tag_con label input[type='radio']:checked + font { color: red; }
     .tag_con .item_select{ width:230px;height:35px;border:1px solid #ccc;background:white;}
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
                        <span class="input-group-addon">查看权限</span>
                        <select class="form-control opt-change" id="id_use_power"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">资源分类</span>
                        <select class="form-control opt-change" id="id_resource"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">细分类型</span>
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

                <div class="col-xs-6 col-md-2" >
                    <div class="input-group">
                        <span class="input-group-addon">上下册</span>
                        <select class="form-control opt-change" id="id_volume"></select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" >
                    <div class="input-group">
                        <span class="input-group-addon">教材版本</span>
                        <select class="form-control opt-change" id="id_book"></select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" >
                    <div class="input-group">
                        <span class="input-group-addon">春暑秋寒</span>
                        <select class="form-control opt-change" id="id_season"></select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" >
                    <div class="input-group">
                        <span class="input-group-addon">省份</span>
                        <select class="form-control opt-change" id="id_volume"></select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" >
                    <div class="input-group">
                        <span class="input-group-addon">城市</span>
                        <select class="form-control opt-change" id="id_city"></select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" >
                    <div class="input-group">
                        <span class="input-group-addon">年份</span>
                        <select class="form-control opt-change" id="id_year"></select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" >
                    <div class="input-group">
                        <span class="input-group-addon">月份</span>
                        <select class="form-control opt-change" id="id_month"></select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" >
                    <div class="input-group">
                        <span class="input-group-addon">中高考</span>
                        <select class="form-control opt-change" id="id_examination"></select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" >
                    <div class="input-group">
                        <span class="input-group-addon">批次</span>
                        <select class="form-control opt-change" id="id_batch"></select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" >
                    <div class="input-group">
                        <span class="input-group-addon">学校名称</span>
                        <select class="form-control opt-change" id="id_volume"></select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" >
                    <div class="input-group">
                        <span class="input-group-addon">笔记分类</span>
                        <select class="form-control opt-change" id="id_note"></select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" >
                    <div class="input-group">
                        <span class="input-group-addon">专题分类</span>
                        <select class="form-control opt-change" id="id_special"></select>
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
                    <button class="btn btn-primary opt_resource_power">资源分类管理</button>
                </div>

                <div class="col-xs-2 col-md-2 ">
                    <button class="btn btn-primary opt_info_book">教材版本管理</button>
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
                    <td style="max-width:200px;">文件名</td>
                    <td>修改日期</td>
                    <td>操作人</td>
                    <td>文件格式</td>
                    <td>文件信息</td>
                    <td>文件大小</td>
                    <td>科目</td>
               

                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr class="right-menu" {!!  \App\Helper\Utils::gen_jquery_data($var )  !!} >
                        <td>
                            <input type="checkbox" class="opt-select-item" data-file_id="{{$var["file_id"]}}" data-id="{{$var["resource_id"]}}"/>
                        </td>
                        <td style="max-width:200px;word-wrap: break-word;">{{@$var["file_title"]}} </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["file_type"]}} </td>
                        <td>{{@$var["file_use_type_str"]}} </td>
                        <td>{{@$var["file_size_str"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                     
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

    <div class="file_tag">
        <div class="tag_item">
            <span class="tag_title fl">上传文件</span>
            <div class="tag_con fl">
                <button class="btn btn-primary upload_file">选择文件</button>
            </div>
            <div class="clear_both"></div>
        </div>

        <div class="tag_item">
            <span class="tag_title fl">资源分类</span>
            <div class="tag_con fl">
                @if($resource)
                    <div class="resource_fa">
                        @for($i = 1;$i <= $max_length;$i++)
                            <span @if($i == 1)class="resource_check" @endif resource_id="{{$i}}" onclick='choose_type(event)'>
                             {{@$resource[$i]}}
                            </span>
                        @endfor
                        <div class="clear_both"></div>
                    </div>
                    @foreach($resource as $re=>$resource)
                        <div class="resource_son @if($re != 1) hide @endif" resource_id="{{$re}}">
                                    @for($i = 1;$i <= $max_length;$i++)
                                    <span onclick="check_type(event)">{{@$resource_type[$re][$i]}}</span>
                                    @endfor
                                    <div class="clear_both"></div>
                        </div> 
                    @endforeach
                    
                        @else
                    <a href="/info_resource_power/get_resource_power" target="_blank" style="color:red;margin-top: 13px;display: block;">
                        点击请先添加资源分类
                    </a>                   
                        @endif

            </div>
            <div class="clear_both"></div>
        </div>

        <div class="tag_item">
            <span class="tag_title fl">科目</span>
            <div class="tag_con fl">
                @foreach($subject as $sub=>$name)
                    @if($sub>0)
                        <label>
                            <input type="radio" name="subject" value="{{$sub}}">
                            <font>{{$name}}</font>
                        </label>
                    @endif
                @endforeach

            </div>
            <div class="clear_both"></div>
        </div>

        <div class="tag_item">
            <span class="tag_title fl">年级</span>
            <div class="tag_con fl">
                @foreach($grade as $gra=>$name)
                    @if(!in_array($gra,[0,100,200,300]))
                        <label>
                            <input type="radio" name="grade" value="{{$gra}}">
                            <font>{{$name}}</font>
                        </label>
                    @endif
                @endforeach

            </div>
            <div class="clear_both"></div>
        </div>

        <div class="tag_item">
            <span class="tag_title fl">上下册</span>
            <div class="tag_con fl">
                <label>
                    <input type="radio" name="volume" value="0" checked>
                    <font>不限</font>
                </label>

                @foreach($volume as $vo=>$name)
                    @if($vo != 0)
                        <label>
                            <input type="radio" name="volume" value="{{$vo}}">
                            <font>{{$name}}</font>
                        </label>
                    @endif
                @endforeach

            </div>
            <div class="clear_both"></div>
        </div>

        <div class="tag_item">
            <span class="tag_title fl">教材版本</span>
            <div class="tag_con fl">
                <label class="fl">
                    <input type="radio" name="book" value="0" checked>
                    <font>不限</font>
                </label>

                <label class="fl" style="margin-right:10px">
                    <input type="radio" name="book" value="1" />
                    <font>仅限</font>
                </label>

                <div class="fl">
                    <select class="item_select show_book"></select>
                </div>

            </div>
            <div class="clear_both"></div>
        </div>

        <div class="tag_item">
            <span class="tag_title fl">春暑秋寒</span>
            <div class="tag_con fl">
                <label>
                    <input type="radio" name="season" value="0" checked>
                    <font>不限</font>
                </label>

                @foreach($season as $sea=>$name)
                    @if($sea != 0)
                        <label>
                            <input type="radio" name="season" value="{{$sea}}">
                            <font>{{$name}}</font>
                        </label>
                    @endif
                @endforeach

            </div>
            <div class="clear_both"></div>
        </div>

        <div class="tag_item">
            <span class="tag_title fl">省份</span>
            <div class="tag_con fl">
                <label class="fl">
                    <input type="radio" name="province" value="0" checked>
                    <font>不限</font>
                </label>

                <label class="fl" style="margin-right:10px">
                    <input type="radio" name="province" value="1" />
                    <font>仅限</font>
                </label>

                <div class="fl">
                    <select class="item_select province" onchange="get_city(this.options[this.options.selectedIndex].value,event)"></select>
                </div>

            </div>
            <div class="clear_both"></div>
        </div>

        <div class="tag_item">
            <span class="tag_title fl">城市</span>
            <div class="tag_con fl">
                <label class="fl">
                    <input type="radio" name="city" value="0" checked>
                    <font>不限</font>
                </label>

                <label class="fl" style="margin-right:10px">
                    <input type="radio" name="city" value="1" />
                    <font>仅限</font>
                </label>

                <div class="fl">
                    <select class="item_select city"></select>
                </div>

            </div>
            <div class="clear_both"></div>
        </div>

    </div>
@endsection
