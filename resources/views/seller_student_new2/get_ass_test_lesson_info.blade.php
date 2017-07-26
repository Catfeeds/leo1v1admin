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
                           
                          
                            <td> 
                                <a  href="/user_manage/ass_archive_ass?order_by_str=ass_assign_time%20desc&grade=-1&student_type=-1&revisit_flag=-1&warning_stu=-1&user_name={{$var["userid"]}}"
                                    target="_blank" title="学生信息">{{@$var["nick"]}} </a>
                            </td >
                           
                            <td >
                                {{$var["grade_str"]}} 
                            </td>
                            <td >
                                {{$var["subject_str"]}} 
                            </td>
                            <td>{{$var["editionid_str"]}}</td>
                            <td>{{$var["ass_test_lesson_type_str"]}}</td>
                            <td>
                                <a  href="/human_resource/index_ass?teacherid={{$var["teacherid"]}}"
                                    target="_blank" title="老师信息">{{@$var["realname"]}} </a>
                            </td>
                            <td>{{$var["lesson_start_str"]}}</td>
                            <td>
                                @if($var["success_flag"]<2)
                                    {!!$var["success_flag_str"]!!}
                                @elseif($var["success_flag"]==2)
                                    {!!$var["success_flag_str"]!!}&nbsp&nbsp&nbsp&nbsp<a class="fa fa-info opt-success-info-list" title="点击查看详情" data-lessonid="{{$var["lessonid"]}}"></a>
                                @endif
                            </td>
                            <td>
                                @if($var["order_confirm_flag"]<2)
                                    {!!$var["order_confirm_flag_str"]!!}
                                @elseif($var["order_confirm_flag"]==2)
                                    {!!$var["order_confirm_flag_str"]!!}&nbsp&nbsp&nbsp&nbsp<a class="fa fa-info opt-order_confirm-info-list" title="点击查看详情"></a>
                                @endif

                            </td>

                           
                            <td>
                                <div class="opt-div"
                                     {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >
                                    @if($var["success_flag"]==0 )
                                        <a title="确认课时" class="btn fa fa-gavel opt-confirm show_flag" ></a>
                                    @endif
                                    @if($var["success_flag"]==1 && $var["order_confirm_flag"]==0)
                                        <a title="设置成功" class="fa fa-heart opt-set-success show_flag"> </a>
                                        <a title="设置失败" class="fa fa-heart-o opt-set-fail show_flag"></a>
                                    @endif
                                   

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
