@extends('layouts.app')
@section('content')
    <section class="content">
    <div class="row">

        <div class="col-xs-1 col-md-1">
            <div class="input-group">
                <div class=" input-group-btn ">
                    <button id="id_add_answer" type="submit"  class="btn  btn-warning" >
                        <i class="fa fa-plus"></i>添加答案步骤
                    </button>
                </div>
            </div>
        </div>

        <div class="col-xs-1 col-md-1">
            <div class="input-group">
                <button style="margin-left:10px" id="question_list" type="button" class="btn btn-primary">题目列表</button>
            </div>
        </div>

        <div class="col-xs-5 col-md-5">
            <div class="input-group">
                <h4>{{@$question['title']}}</h4>
            </div>
        </div>

    </div>
    <hr/>

    <table class="common-table">
        <thead>
            <tr>
                <td >步骤</td>
                <td >分值</td>
                <td >难易程度</td>
                <td >知识点</td>
                <td >答案详情</td>
                <td >操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
                <tr>
                    <td >{{$var["step_str"]}}</td>
                    <td >{{@$var["score"]}}</td>
                    <td >{{$var["difficult_str"]}}</td>
                    <td >{{$var["title"]}}</td>
                    <td >{{$var["detail"]}}</td>
                    <td >
                        <div 
                            {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                        >
                            <a class="fa-edit opt-set" title="编辑答案"> </a>
                            <a class="fa fa-times opt-del" title="删除"> </a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <input type="hidden" value="{{$next_step}}" id="next_step" />
    @include("layouts.page")
    
    </section>
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>

@endsection

