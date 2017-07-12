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
    <script type="text/javascript" src="//g.alicdn.com/sj/aliphone-sdk/aliphone.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="/js/svg.js"></script>
    <script type="text/javascript" src="/js/wb-reply/audio.js"></script>
    <script type="text/javascript" src="/page_js/lib/flow.js"></script>

    <script type="text/javascript" >
    </script>
    <style>
     .bg_red,.bg_red td{
         background-color:#ff3451 !important;
     }
     .bg_orange,.bg_orange td{
         background-color:#0bceff !important;
     }
     .bg_orange_red,.bg_orange_red td{
         background-color:#F8E81C !important;
     }
    </style>

    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-5"  data-title="时间段" style="display:none">
                    <div  id="id_date_range" >
                    </div>
                </div>               
                <div class="col-xs-6 col-md-2" style="display:none" >
                    <div class="input-group ">
                        <span class="input-group-addon">学科</span>
                        <select class="opt-change form-control" id="id_subject" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" style="display:none">
                    <div class="input-group ">
                        <span class="input-group-addon">年级</span>
                        <select class="opt-change form-control" id="id_grade" >
                        </select>
                    </div>
                </div>

               
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">是否签约</span>
                        <select class="opt-change form-control" id="id_have_order" >
                            <option value="-1">全部</option>
                            <option value="0">未签约</option>
                            <option value="1">已签约</option>
                        </select>
                    </div>
                </div>

               
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td>上课时间</td>
                    <td>老师</td>
                    <td>学生</td>
                    <td>科目</td>
                    <td>是否签约</td> 
                   
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>                
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["lesson_start_str"]}}</td>
                        <td>{{@$var["realname"]}}</td>
                        <td>{{@$var["nick"]}}</td>
                        <td>{{@$var["subject_str"]}}</td>
                        <td>{{@$var["have_order"]}}</td>


                        <td>
                            <div class="data"
                                {!! \App\Helper\Utils::gen_jquery_data($var) !!}
                            >
                               
                                <a href="/tea_manage/lesson_list?lessonid={{@$var["lessonid"]}}" target="_blank" > 课程信息</a>


                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
