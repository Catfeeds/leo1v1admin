@extends('layouts.app')
@section('content')
       <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <link rel='stylesheet' href='/css/fullcalendar.css' />
    <script src='/js/moment.js'></script>
    <script src='/js/fullcalendar.js'></script>
    <script src='/js/lang-all.js'></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time.js"></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time_new.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/seller_student/common.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_record.js?v={{@$_publish_version}}"></script>
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/teacher_freeze_limit_record.js"></script>
    <script type="text/javascript" src="//g.alicdn.com/sj/aliphone-sdk/aliphone.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="/page_js/lib/flow.js"></script>
    <section class="content ">
        <div>
            <div class="row ">
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师 </span>
                        <input type="text" value=""  class="opt-change"  id="id_teacherid"  placeholder="" />
                    </div>
                </div>

                <a  id="id_add_closest" class="btn btn-warning " > <li  class="fa fa-plus">添加教研老师</li> </a>
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td >ID </td>
                    <td >老师姓名</td>
                    <td>第一科目</td>                  
                    <td>第一科目年级段</td>
                    <td>第二科目</td>                  
                    <td>第二科目年级段</td>
                    <td>课表信息</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["teacherid"]}} </td>                     
                        <td>{{$var["realname"]}} </td>
                        <td>{{$var["subject_str"]}} </td>
                        <td>{{$var["grade_start_str"]}}至{{$var["grade_end_str"]}} </td>
                        <td>{{$var["second_subject_str"]}} </td>
                        <td>{{$var["second_grade_start_str"]}}至{{$var["second_grade_end_str"]}} </td>
                        <td><a href="javascript:;" class="show_lesson_info" data-teacherid="{{$var["teacherid"]}}">查看课表</a></td>

                     
                        <td>
                            <div {!!  \App\Helper\Utils::gen_jquery_data($var)  !!}  >
                                <a class="opt-show-lessons-new"  title="课程列表-new">课程-new</a>                               
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

