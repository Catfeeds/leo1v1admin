@extends('layouts.stu_header')
@section('content')

      <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
      <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
      <script type="text/javascript" src="/js/qiniu/ui.js"></script>
      <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
      <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
      <script type="text/javascript" src="/js/jquery.md5.js"></script>

    <section class="content ">
        <div class="row">
           <div class="col-xs-6 col-md-2">
                <button class="btn btn-warning" id="id_add_score_new">增加学生考试成绩</button>
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td>#</td>
                    <td>科目</td>
                    <td>学期</td>
                    <td>类型</td>
                    <td>分数</td>
                    <td>班级排名</td>
                    <td>年级排名</td>
                    <td>试卷</td>
                    <td></td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td> {{$var['num']}}</td>
                        <td> {{$var["subject_str"]}} </td>
                        <td> {{$var["grade_str"]}}{{$var["semester_str"]}} </td>
                        <td> {{$var["stu_score_type_str"]}} </td>
                        <td>
                            @if($var['total_score'] != 0)
                                {{intval(100*$var["score"]/$var['total_score'])}} </td>
                            @else
                                {{$var['score']}}
                            @endif
                        <td> {{$var["rank"]}} </td>
                        <td> {{$var["grade_rank"]}} </td>                       
                        <td> 
                            @if($var['file_url'] === '')
                                 无
                            @else
                                <a href={{$var["file_url"]}} target= _blank>查看</a> 
                            @endif
                        </td>
                        <td>
                            <div class="opt-div" 
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="修改信息"></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

