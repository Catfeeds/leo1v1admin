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
                        <span class="input-group-addon">培训类型</span>
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
                    @if(in_array($resource_type,[1,2,9]))
                        <td>春暑秋寒</td>
                    @endif
                    <td>修改日期</td>
                    <td>文件格式</td>
                    <td>文件大小</td>
                    <td>查看次数</td>
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
                        @if(in_array($resource_type,[1,2,9]))
                            <td>{{@$var["tag_two_str"]}}</td>
                        @endif
                        <td>{{@$var["create_time"]}} </td>
                        <td>{{@$var["file_type"]}} </td>
                        <td>{{@$var["file_size"]}}</td>
                        <td>{{@$var["visit_num"]}} </td>
                        <td>
                            <a class="opt-look btn color-blue"  title="预览" data-file_id="{{@$var['file_id']}}" data-file_type="{{@$var['file_type']}}">预览</a>

                            <a class="opt-error btn color-blue"  style="display: none" title="提问" data-file_id="{{@$var['file_id']}}">提问</a>

                            <a class="opt-comment btn color-blue"  style="display: none" title="评价" data-file_id="{{@$var['file_id']}}">评价</a>
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
