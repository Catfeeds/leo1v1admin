@extends('layouts.teacher_header')
@section('content')
    <script type="text/javascript" src="/js/area/distpicker.data.js"></script>
	  <script type="text/javascript" src="/js/area/distpicker.js"></script>
	  <script type="text/javascript" src="/js/pdfobject.js"></script>
    <script>
     var tag_one = '{{$tag_info['tag_one']['menu']}}';
     var tag_two = '{{$tag_info['tag_two']['menu']}}';
     var tag_three = '{{$tag_info['tag_three']['menu']}}';
     var tag_four = '{{$tag_info['tag_four']['menu']}}';
     var tag_five = '{{$tag_info['tag_five']['menu']}}';
     var tea_sub = {!! @$tea_sub!!} ;
     var tea_gra = {!! @$tea_gra !!};
     var type_list = {!! @$type_list !!};
     var book = {{@$book}};
    </script>
    <style>
     .fl{ float:left }
     .fr{ float:right }
     .clall{ clear:both}
     .comment{ width:800px}
     .comment .comment_title{ width:100px;font-size:14px;font-weight:bold}
     .comment .comment_star{ width:160px}
     .comment .comment_info{ width:380px;font-size:13px;color:#948f8f;padding-top: 3px;}
     
    </style>
    <section class="content li-section">
        <div>
            <!-- <div class="row  row-query-list" >
                 <div class="col-xs-12 col-md-5"  data-title="时间段">
                 <div  id="id_date_range" >
                 </div>
                 </div>
                 </div> -->
            <div class="row">
                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3">
                    <div class="input-group ">
                        <span class="input-group-addon">资源类型</span>
                        <select class="form-control opt-change" id="id_resource_type"> </select>
                    </div>
                </div>


                <div class="col-xs-6 col-sm-3 col-md-2 col-lg-2">
                    <div class="input-group ">
                        <span class="input-group-addon">科目</span>
                        <select class="form-control opt-change" id="id_subject"> </select>
                    </div>
                </div>

                <div class="col-xs-6 col-sm-3 col-md-2 col-lg-2">
                    <div class="input-group ">
                        <span class="input-group-addon">年级</span>
                        <select class="form-control opt-change" id="id_grade"> </select>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-3 col-lg-2 {{$tag_info['tag_one']['hide']}}">
                    <div class="input-group ">
                        <span class="input-group-addon">{{$tag_info['tag_one']['name']}}</span>
                        <select class="form-control opt-change" id="id_tag_one"> </select>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2 {{$tag_info['tag_two']['hide']}} ">
                    <div class="input-group ">
                        <span class="input-group-addon">{{$tag_info['tag_two']['name']}}</span>
                        <select class="form-control opt-change" id="id_tag_two"> </select>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-2 {{$tag_info['tag_three']['hide']}} ">
                    <div class="input-group ">
                        <span class="input-group-addon">{{$tag_info['tag_three']['name']}}</span>
                        <select class="form-control opt-change" id="id_tag_three"> </select>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 {{$tag_info['tag_four']['hide']}} ">
                    <div class="input-group ">
                        <span class="input-group-addon">{{$tag_info['tag_four']['name']}}</span>
                        <select class="form-control opt-change" id="id_tag_four"> </select>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 col-md-4 col-lg-3 {{$tag_info['tag_five']['hide']}} ">
                    <div class="input-group ">
                        <span class="input-group-addon">{{$tag_info['tag_five']['name']}}</span>
                        <select class="form-control opt-change" id="id_tag_five"> </select>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table" id="menu_mark">
            <thead>
                <tr>
                    <td>文件名</td>
                    <td>科目</td>
                    <td>年级</td>
                    @if(in_array($resource_type,[1,3,5,6]))
                        <td>教材</td>
                    @endif
                    @if(in_array($resource_type,[1,2]))
                        <td>春暑秋寒</td>
                    @endif
                    <td>文件标题</td>
                    <td>修改日期</td>
                    <td>文件格式</td>
                    <td>文件信息</td>
                    <td>文件大小</td>
                    <td>使用次数</td>
                    <td>收藏状态</td>
                    <td> 操作 </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr data-resource_id="{{@$var['resource_id']}}">
                        <td>{{@$var["file_title"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        @if(in_array($resource_type,[1,3,5,6]))
                            <td>{{@$var["tag_one_str"]}}</td>
                        @endif
                        @if(in_array($resource_type,[1,2]))
                            <td>{{@$var["tag_two_str"]}}</td>
                        @endif

                        <td>{{@$var["file_title"]}} </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>{{@$var["file_type"]}} </td>
                        <td>{{@$var["file_use_type_str"]}} </td>
                        <td>{{@$var["file_size"]}}</td>
                        <td>{{@$var["use_num"]}} </td>
                        <td>
                            @if(@$var['tea_res_id'] == 0)
                                <a class="collect opt-get btn color-red" data-file_id="{{@$var['file_id']}}">未收藏</a>
                            @else
                                <a class="collect btn color-blue" data-id="{{@$var['tea_res_id']}}" data-file_id="{{@$var['file_id']}}">已收藏</a>
                            @endif
                        </td>
                        <td>
                            <a class="opt-look btn color-blue"  title="预览" data-file_id="{{@$var['file_id']}}" data-file_type="{{@$var['file_type']}}">预览</a>

                            <a class="opt-error btn color-blue"  title="报错" data-file_id="{{@$var['file_id']}}">报错</a>

                            <a class="opt-comment btn color-blue"  title="评价" data-file_id="{{@$var['file_id']}}">评价</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    <div class="col-md-12 look-pdf"   style="width:80%;height:95%;position:fixed;right:10%;top:2.5%;border-radius:5px;background:#eee;display:none;z-index:8888;overflow:hidden;">
        <div class="look-pdf-son">
        </div>
    </div>

    
    <div class="comment">
        <div class="comment_item">
            <span class="comment_title fl">质量总评：</span>
            <div class="comment_star comment_quality fl" onmouseover="rate(this,event)">
                <img src="/img/x1.png" title="1分" />
                <img src="/img/x1.png" title="2分" />
                <img src="/img/x1.png" title="3分" />
                <img src="/img/x1.png" title="4分" />
                <img src="/img/x1.png" title="5分" />
            </div>
            <div class="comment_info fl">
                <span>质量很差/</span>
                <span>质量较差/</span>
                <span>质量一般/</span>
                <span>质量较高/</span>
                <span>质量很高</span>
            </div>
            <div class="clall"></div>
        </div>
        <div class="comment_item">
            <span class="comment_title fl">帮助指数：</span>
            <div class="comment_star comment_help fl"></div>
            <div class="comment_info fl">
                <span>毫无帮助/</span>
                <span>帮助较小/</span>
                <span>帮助一般/</span>
                <span>帮助较高/</span>
                <span>帮助极大</span>
            </div>
            <div class="clall"></div>
        </div>
        <div class="comment_item">
            <span class="comment_title fl">全面指数：</span>
            <div class="comment_star comment_whole fl"></div>
            <div class="comment_info fl">
                <span>很不全面/</span>
                <span>不够全面/</span>
                <span>一般全面/</span>
                <span>内容较全/</span>
                <span>内容很全</span>
            </div>
            <div class="clall"></div>
        </div>
        <div class="comment_item">
            <span class="comment_title fl">详细指数：</span>
            <div class="comment_star comment_detail fl"></div>
            <div class="comment_info fl">
                <span>很不详细/</span>
                <span>不够详细/</span>
                <span>一般详细/</span>
                <span>比较详细/</span>
                <span>非常详细</span>
            </div>
            <div class="clall"></div>
        </div>

    </div>

@endsection
