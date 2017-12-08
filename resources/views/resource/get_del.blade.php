@extends('layouts.app')
@section('content')
    <script>
     var tag_one = '{{$tag_info['tag_one']['menu']}}';
     var tag_two = '{{$tag_info['tag_two']['menu']}}';
     var tag_three = '{{$tag_info['tag_three']['menu']}}';
     var tag_four = '{{$tag_info['tag_four']['menu']}}';
    </script>
    <section class="content">
        <div>
            <!-- <div class="row  row-query-list" >
                 <div class="col-xs-12 col-md-5"  data-title="时间段">
                 <div  id="id_date_range" >
                 </div>
                 </div>
                 </div> -->
            <div class="row row-query-list">
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">使用角色</span>
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

                <div class="col-xs-6 col-md-2 {{$tag_info['tag_four']['hide']}} ">
                    <div class="input-group ">
                        <span class="input-group-addon">{{$tag_info['tag_four']['name']}}</span>
                        <select class="form-control opt-change" id="id_tag_four"> </select>
                    </div>
                </div>


            </div>
            <div class="row">
                <div class="col-xs-2 col-md-1 ">
                    <button class="btn btn-warning opt-restore">还原</button>
                </div>
                <div class="col-xs-2 col-md-1 ">
                    <button class="btn btn-danger opt-forever-del">永久删除</button>
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
                    <td style="width:20%">文件名</td>
                    <td style="width:15%">删除日期</td>
                    <td style="width:10%">操作人</td>
                    <td style="width:8%">文件类型</td>
                    <td style="width:8%">文件大小</td>
                    <td style="width:8%">下载次数</td>
                    <td style="width:8%">纠错次数</td>
                    <td style="width:8%">是否使用</td>
                    <td style="width:8%"> 操作 </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr class="right-menu" {!!  \App\Helper\Utils::gen_jquery_data($var )  !!} >
                        <td>
                            <input type="checkbox" class="opt-select-item" data-file_id="{{$var["file_id"]}}" data-id="{{$var["resource_id"]}}"/>
                        </td>
                        <td>{{@$var["file_title"]}} </td>
                        <td>{{@$var["update_time"]}} </td>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["file_type"]}} </td>
                        <td>{{@$var["file_size"]}}M </td>
                        <td>{{@$var["down_num"]}} </td>
                        <td>{{@$var["error_num"]}} </td>
                        <td>{{@$var["is_use_str"]}} </td>
                        <td>
                            <a class="opt-restore btn"  title="还原" data-resource_id="{{@$var['resource_id']}}">还原</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
