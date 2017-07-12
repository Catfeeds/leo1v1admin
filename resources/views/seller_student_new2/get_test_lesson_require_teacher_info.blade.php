@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" >
    </script>
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
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-4"  data-title="时间段">
                    <div id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">老师</span>
                        <input class="opt-change form-control" id="id_teacherid" />
                    </div>
                </div>
                
                <div class="col-xs-6 col-md-2">
                    <button class="btn btn-primary" id="id_update_require_teacher_info">刷新预分配数据</button>
                </div>                

            </div>
        </div>
        <hr/>
        <div class="body">
            <table class="common-table ">
                <thead>
                    <tr>
                        <td >require_id </td>
                        <td >生成时间 </td>
                        <td >试听期待时间</td>
                        <td >预分配老师</td>
                        <td >操作</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr>
                            <td >{{$var["require_id"]}}</td>
                            <td >{{$var["add_time_str"]}}</td>
                            <td >{{$var["stu_request_test_lesson_time"]}}</td>
                            <td >{{$var["realname"]}}</td>
                            <td>
                                <div class="opt-div"
                                     {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >
                                    <a class="fa-trash-o opt-del"  title="删除"> </a>
                                   
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
