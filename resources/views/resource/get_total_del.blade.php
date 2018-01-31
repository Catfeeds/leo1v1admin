@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/area/distpicker.data.js"></script>
	  <script type="text/javascript" src="/js/area/distpicker.js"></script>
    <script type="text/javascript" src="/js/pdfobject.js"></script>

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

                
            </div>
            <div class="row">
                
                <div class="col-xs-2 col-md-1 ">
                    <button class="btn btn-danger opt-forever-del">永久删除</button>
                </div>

                <div class="col-xs-2 col-md-1 ">
                    <button class="btn btn-danger opt-forever-del-file">永久删除文件</button>
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
                    <td>文件信息</td>
                    <td>文件类型</td>
                    <td>文件大小</td>

                    <td>科目</td>
                    <td>年级</td>

                    <td>删除日期</td>
                    <td>操作人</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr class="right-menu" {!!  \App\Helper\Utils::gen_jquery_data($var )  !!} >
                        <td>
                            <input type="checkbox" class="opt-select-item" data-file_id="{{$var["file_id"]}}" data-id="{{$var["resource_id"]}}" data-file_link="{{$var['file_link']}}" />
                        </td>
                        <td>{{@$var["file_title"]}} </td>
                        <td>{{@$var["file_use_type_str"]}} </td>
                        <td>{{@$var["file_type"]}} </td>
                        <td>{{@$var["file_size"]}}M </td>

                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>

                        <td>{{@$var["create_time"]}} </td>
                        <td>{{@$var["nick"]}} </td>
                        <td>
                            <a class="opt-look btn color-blue" data-file_id="{{$var["file_id"]}}" data-file_link="{{$var['file_link']}}"  title="预览">预览</a>
                        </td>

                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
<div class="col-md-12 look-pdf"   style="width:80%;height:95%;position:fixed;right:10%;top:2.5%;border-radius:5px;background:#eee;display:none;z-index:8888;overflow:hidden;">
    <div class="look-pdf-son">
    </div>
</div>
