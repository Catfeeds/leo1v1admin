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

            <div class=" input-group-btn ">
                <button style="margin-left:10px" id="add_textbook" type="button" class="btn btn-success">添加题型</button>
            </div>

        </div>
        <hr/>

        <table class="common-table">
            <thead>
                <tr>
                    <td >ID</td>
                    <td >序号</td>
                    <td >步骤名字</td>
                    <td >科目</td>
                    <td >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($list as $var)
                    <tr>
                        <td >{{$var["id"]}}</td>
                        <td >{{$var["answer_type_no"]}}</td>
                        <td >{{$var["name"]}}</td>
                        <td >{{$var["subject_str"]}}</td>
                        <td >
                            <div 
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa-edit opt-set edit_textbook" title="编辑步骤名称"> </a>
                                @if(@$var["open_flag"] == 1)
                                    <a class="fa fa-lock lock_question_know" title="禁用"> </a>
                                @else
                                    <a class="fa fa-unlock unlock_question_know" title="开启"> </a>
                                @endif
                            </div>
                        </td>
                </tr>
                @endforeach
            </tbody>
        </table>

    </section>

@endsection

