@extends('layouts.app')
@section('content')
    <style>
     .each_knowledge>input{ width:330px;margin:10px 10px 0px 0px}
     .each_knowledge .remove_knowledge{ margin-top:10px }
     .each_knowledge .question_difficult{ margin-top:10px  }
     .question_subject,.question_knowledge_detail{ display:none}
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

            <div class="col-xs-2 col-md-2">
                <div class="input-group">
                    <span class="input-group-addon">是否开启</span>
                    <select class="opt-change form-control" id="id_open_flag">
                    </select>
                </div>
            </div>


            <div class="col-xs-1 col-md-1">
                <div class="input-group">
                    <div class=" input-group-btn ">
                        <button id="id_add_question" type="submit"  class="btn  btn-warning" >
                            <i class="fa fa-plus"></i>添加题目
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-xs-1 col-md-1">
                <div class=" input-group-btn ">
                    <button style="margin-left:10px" id="edit_question_type" type="button" class="btn btn-success">编辑题型</button>
                </div>
            </div>

            <div class="col-xs-1 col-md-1">
                <div class="input-group">
                    <button style="margin-left:10px" id="knowledge_list" type="button" class="btn btn-primary">知识点列表</button>
                </div>
            </div>

        </div>
        <hr/>

        <table class="common-table">
            <thead>
                <tr>
                    <td >题目ID</td>
                    <td >题目标题</td>
                    <td >科目类型</td>
                    <td >题目分值</td>
                    <td style="display:">涉及知识点</td>
                    <td >是否开启</td>
                    <td style="display:none">题目详情</td>
                    <td >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                    <tr>
                        <td >{{$var["question_id"]}}</td>
                        <td >{{$var["title"]}}</td>
                        <td >{{$var["subject_str"]}}</td>
                        <td >{{$var["score"]}}</td>
                        <td >
                            @if(@$var["knowledge_detail"])
                                @foreach (json_decode($var['knowledge_detail'],true) as $item)
                                    <p style="margin-bottom:5px">
                                        {{$item['title']}}
                                        <input type="hidden" value="{{$item['knowledge_id']}}">
                                    </p>
                                @endforeach
                            @endif
                        </td>
                        <td >{{$var["open_str"]}}</td>
                        <td >{{$var["detail"]}}</td>
                        <td >
                            <div 
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa-edit opt-set" title="编辑题目"> </a>
                                <a class="fa-align-justify add_question_know" title="添加题目的知识点"> </a>
                                <a class="fa-tags edit_question_know" title="编辑答案详情"> </a>
                                @if(@$var["open_flag"] == 1)
                                    <a class="fa fa-lock lock_question_know" title="禁用"> </a>
                                @else
                                    <a class="fa fa-unlock unlock_question_know" title="开启"> </a>
                                @endif
                                <a class="fa-times opt-del" title="删除"> </a>
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

    <div class="difficult_box hide">
        <select class="question_difficult"></select>
    </div>
@endsection

