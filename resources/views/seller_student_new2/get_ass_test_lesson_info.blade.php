@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <style>
     .input-group{
         width:100%;
     }
     .input-group-w145{
         width:145px !important;
     }
    </style>
    <section class="content ">
        <div>
            <div class="row " >
               
            </div>
        </div>
        <hr/>
        <div class="body">
            <table class="common-table ">
                <thead>
                    <tr>
                        <td >学生姓名 </td>
                        <td >年级 </td>
                        <td >科目 </td>
                        <td >教材版本 </td>
                        <td >类型 </td>
                        <td >老师 </td>
                        <td >试听时间 </td>
                        <td >课时确认 </td>
                        <td >结果设置</td>
                       
                        <td >操作</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr>
                           
                            <td >
                               {{$var["nick"]}}
                            </td>
                            <td >
                                {{$var["grade_str"]}} 
                            </td>
                            <td >
                                {{$var["subject_str"]}} 
                            </td>
                            <td>{{$var["editionid_str"]}}</td>
                            <td>{{$var["ass_test_lesson_type_str"]}}</td>
                            <td>{{$var["realname"]}}</td>
                            <td>{{$var["lesson_start_str"]}}</td>
                            <td>{!!$var["success_flag_str"]!!}</td>
                            <td></td>

                           
                            <td>
                                <div class="opt-div"
                                     {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >
                                   
                                    <a title="确认课时" class="btn fa fa-gavel opt-confirm show_flag" ></a>
                                    <a title="设置成功" class="fa fa-heart opt-set-success show_flag"> </a>
                                    <a title="设置失败" class="fa fa-heart-o opt-set-fail show_flag"></a>
                                   

                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include("layouts.page")
        </div>
    </section>
@endsection
