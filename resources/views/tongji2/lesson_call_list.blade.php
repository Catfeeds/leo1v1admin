@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="//g.alicdn.com/sj/aliphone-sdk/aliphone.min.js" charset="utf-8"></script>

    <script type="text/javascript" src="/page_js/seller_student/common.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js?v={{@$_publish_version}}"></script>
    <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.min.js"></script>

    <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.categories.js"></script>
    <script language="javascript" type="text/javascript" src="/page_js/lib/select_date_range.js"></script>
    <script type="text/javascript" >

     var g_data_ex_list= <?php  echo json_encode($table_data_list); ?> ;
    </script>
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" >
    var g_adminid_right= <?php  echo json_encode($adminid_right); ?> ;
        var g_adminid= "{{$adminid}}" ;
    </script>
    <style>
     .input-group{
         width:100%;
     }
     .input-group-w145{
         width:145px !important;
     }
    </style>

    <section class="content">
        <div class="book_filter">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div id="id_date_range"> </div>
                </div>
                <div  class="col-xs-6 col-md-4">
                    <div class="input-group ">
                        <span class="input-group-addon">申请人选择</span>
                        <input class="opt-change form-control" id="id_seller_groupid_ex" />
                    </div>
                </div>
            </div>
        </div>
        <hr />
        <div class="body">
            <table class="common-table  ">
                <thead>
                    <tr>
                        <td>lessonid </td>
                        <td>学生 </td>
                        <td>老师 </td>
                        <td>销售 </td>
                        <td>上课时间 </td>
                        <td>课前回访 </td>
                        <td>课后回访 </td>
                        <td> 操作  </td> </tr>

                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)

                        <tr class="">
                            <td>
                                {{@$var["lessonid"]}}
                            </td>
                            <td>
                                {{@$var["stu_nick"]}}
                            </td>
                            <td>
                                {{@$var["tea_nick"]}}
                            </td>
                            <td>
                                {{@$var["account"]}}
                            </td>
                            <td>
                                {{@$var["lesson_start"]}}
                            </td>
                            <td>
                                {{@$var["call_before_time"]}}
                            </td>
                            <td>
                                {{@$var["call_end_time"]}}
                            </td>
                            <td>
                                <div class=" row-data"
                                     {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >
                                    <a class="fa-phone opt-call-phone " title="电话记录" ></a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include("layouts.page")
        </div>

        <script src="/js/qiniu/plupload/plupload.full.min.js"></script>

@endsection
