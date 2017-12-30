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
     var book = {{@$book}};
    </script>
    <section class="content li-section">
        <div>
            <!-- <div class="row  row-query-list" >
                 <div class="col-xs-12 col-md-5"  data-title="时间段">
                 <div  id="id_date_range" >
                 </div>
                 </div>
                 </div> -->
            <div class="row">
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

                <div class="col-xs-6 col-md-2 {{$tag_info['tag_four']['hide']}} ">
                    <div class="input-group ">
                        <span class="input-group-addon">{{$tag_info['tag_four']['name']}}</span>
                        <select class="form-control opt-change" id="id_tag_four"> </select>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table" id="menu_mark">
            <thead>
                <tr>
                    <td style="width:35%">文件名</td>
                    <td style="width:15%">修改日期</td>
                    <td style="width:7%">文件格式</td>
                    <td style="width:17%">文件信息</td>
                    <td style="width:7%">文件大小</td>
                    <td style="width:7%">使用次数</td>
                    <td style="width:7%">收藏状态</td>
                    <td style="width:5%"> 操作 </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr data-resource_id="{{@$var['resource_id']}}">
                        <td>{{@$var["file_title"]}} </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>{{@$var["file_type"]}} </td>
                        <td>{{@$var["file_use_type_str"]}} </td>
                        <td>{{@$var["file_size"]}}</td>
                        <td>{{@$var["use_num"]}} </td>
                        <td>
                            @if($var['tea_res_id'] == 0)
                                <a class="collect opt-get btn color-red" data-file_id="{{@$var['file_id']}}">未收藏</a>
                            @else
                                <a class="collect btn color-blue" data-id="{{@$var['tea_res_id']}}" data-file_id="{{@$var['file_id']}}">已收藏</a>
                            @endif
                        </td>
                        <td>
                            <a class="opt-look btn color-blue"  title="预览" data-file_id="{{@$var['file_id']}}">预览</a>
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


@endsection
