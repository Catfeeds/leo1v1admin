@extends('layouts.app')
@section('content')
    <script>
     var book = {{@$book}};
     var resource_type = {{@$resource_type}};
    </script>

    <section class="content">
        <div class="row">
            
            <div class="col-xs-2 col-md-2">
                <div class="input-group">
                    <span class="input-group-addon">科目</span>
                    <select class="opt-change form-control" id="id_subject">
                    </select>
                </div>
            </div>      

            <div class="col-xs-2 col-md-2">
                <div class="input-group">
                    <span class="input-group-addon">年级</span>
                    <select class="opt-change form-control" id="id_grade">
                    </select>
                </div>
            </div>

            <div class="col-xs-2 col-md-2">
                <div class="input-group">
                    <span class="input-group-addon">教材</span>
                    <select class="opt-change form-control" id="id_book">
                    </select>
                </div>
            </div>

            <div class="col-xs-2 col-md-2">
                <div class="input-group">
                    <span class="input-group-addon">资源类型</span>
                    <select class="opt-change form-control" id="id_resource_type">
                    </select>
                </div>
            </div>

   
            <div class="col-xs-2 col-md-2 @if($resource_type != 1) hide @endif">
                <div class="input-group">
                    <span class="input-group-addon">春暑秋寒</span>
                    <select class="opt-change form-control" id="id_season_id">
                    </select>
                </div>
            </div>
            
            <!-- <div class="col-xs-1 col-md-1">
                 <div class="input-group">
                 <button id="search" type="button" class="btn btn-primary">搜索</button>
                 </div>
                 </div>
            -->
            <div class="col-xs-4 col-md-4">
                <div class="input-group">
                    <button id="tag_add" type="button" style="margin-right:10px" class="btn btn-info">添加</button>        
                    <button  id="batach_add" type="button" style="margin-right:10px" class="btn btn-info">批量添加</button>
                    <button  id="batach_dele" type="button" class="btn btn-danger">批量删除</button>

                </div>

            </div>

        </div>

        <hr/>

        <table   class="common-table"   >
            <thead>
                <tr>                    
                    <td ></td>
                    <td >ID</td>
                    <td >科目</td>
                    <td >年级</td>
                    <td >教材</td>
                    <td >资源类型</td>
                    @if($resource_type == 1)
                        <td >春暑秋寒</td>
                    @endif
                    <td >学科化标签</td>
                    <td >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                    <tr>
                        <td ><input type="checkbox" class="id_str"></td>
                        <td >{{$var["id"]}}</td>
                        <td >{{$var["subject_str"]}}</td>
                        <td >{{$var["grade_str"]}}</td>
                        <td >{{$var["book_str"]}}</td>
                        <td >{{$var["resource_str"]}}</td>
                        @if($resource_type == 1)
                            <td >{{$var["season_str"]}}</td>
                        @endif
                        <td >{{$var["tag"]}}</td>
                        <td >
                            <div 
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa-edit opt-set" title="编辑"> </a>
                                <a class="fa fa-times opt-del" title="删除"> </a>
                                <a class="fa fa-times fa-long-arrow-up" title="上移排序"> </a>
                                <a class="fa fa-times fa-long-arrow-down" title="下移排序"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")

    </section>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>

@endsection

