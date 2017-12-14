@extends('layouts.app')
@section('content')
    <link rel="stylesheet" href="/css/AdminLTE.css">
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
    <div class="row skin-blue">
        <div class="col-xs-2 col-md-2">
            <div id="id_note_list_div">
                <div id="id_sidebar_menu">
                    <section class="sidebar">
                        <ul class="sidebar-menu">
                            <li class="treeview">
                                <a href="#"><i class="fa fa-bar-chart-o"></i><span>  数与式</span><i class="fa fa-angle-left pull-right"></i></a>
                            </li>
                            <li class="treeview">
                                <a href="#"><i class="fa fa-bar-chart-o"></i><span>  方程与不等式</span><i class="fa fa-angle-left pull-right"></i></a>
                            </li>
                            <li class="treeview">
                                <a href="#"><i class="fa fa-bar-chart-o"></i><span>  函数</span><i class="fa fa-angle-left pull-right"></i></a>
                            </li>
                            <li class="treeview">
                                <a href="#"><i class="fa fa-bar-chart-o"></i><span>  图形的变化</span><i class="fa fa-angle-left pull-right"></i></a>
                            </li>

                        </ul>
                    </section>
                </div>
            </div>
        </div>
        <div class="col-xs-10 col-md-10 knowledge_pic">
            
        </div>

    </div>    
    </section>
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/js/d3.v3.min.js"></script>
    <script type="text/javascript" src="/page_ts/question_new/d3_load.ts?v=201712131924"></script>
@endsection

