@extends('layouts.app')
@section('content')
    <style>
     .table-striped>tbody>tr.level_1{ background-color:#eee }
     .table-striped>tbody>tr.level_2{ background-color:#e6e8df }
     .table-striped>tbody>tr.level_3{ background-color:#d6e0d4 }
     .table-striped>tbody>tr.level_4{ background-color:#e4d1d1 }
    </style>
    <section class="content">
    <div class="row">
    
        <div class="col-xs-2 col-md-2">
            <div class="input-group">
                <span class="input-group-addon">科目类型</span>
                <select class="opt-change form-control" id="id_subject">
                </select>
            </div>
        </div>

        <div class="col-xs-1 col-md-1">
            <div class="input-group">
                <div class=" input-group-btn ">
                    <button id="id_add_knowledge" type="submit"  class="btn  btn-warning" >
                        <i class="fa fa-plus"></i>添加知识点
                    </button>
                </div>
            </div>
        </div>

        <div class="col-xs-1 col-md-1">
            <div class="input-group">
                <button style="margin-left:10px" id="question_list" type="button" class="btn btn-primary">题目列表</button>
            </div>
        </div>

        <div class="col-xs-1 col-md-1">
            <div class="input-group">
                <button style="margin-left:10px" id="text_book_knowledge" type="button" class="btn btn-primary">教材知识点</button>
            </div>
        </div>

    </div>
    <hr/>

    <table class="common-table">
        <thead>
            <tr>
                <td >知识点ID</td>
                <td >知识点标题</td>
                <td >科目类型</td>
                <td style="display:none">知识点详情</td>
                <td >操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
                <tr class="level_{{$var['level']}}">
                    <td >{{$var["knowledge_id"]}}</td>
                    <td >{{$var["title"]}}</td>
                    <td >{{$var["subject_str"]}}</td>
                    <td >{{$var["detail"]}}</td>
                    <td >
                        <div 
                            {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                        >
                            <a class=" fa-edit opt-set" title="编辑知识点"> </a>
                            <a class=" fa-pencil add_son" title="添加子知识点"> </a>
                            <a class="fa fa-times opt-del" title="删除"> </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")

    </section>
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>

@endsection

