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
                <div class="col-xs-6 col-md-4">
                    <div class="input-group ">
                        <span class="input-group-addon">申请人选择</span>
                        <input class="opt-change form-control" id="id_seller_groupid_ex" />
                    </div>
                </div>
            </div>
            <hr />
        <div class="body">
            <table class="common-table  ">
                <thead>
                    <tr>
                        <td>类型 </td>
                        <td>主管 </td>
                        <td>小组 </td>
                        <td>负责人 </td>

                        @foreach ( $week_info as $var )
                            <td>{{@$var}} </td>
                        @endforeach

                            <td>周统计 </td>

                            <td> 操作  </td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)

                        <tr class="{{$var["level"]}}">
                            <td style="width:80px;" data-class_name="{{$var["main_type_class"]}}" class="main_type" >{{$var["main_type_str"]}}</td>
                            <td style="width:80px;" data-class_name="{{$var["up_group_name_class"]}}" class=" up_group_name  {{$var["main_type_class"]}}  {{$var["up_group_name_class"]}} " >{{$var["up_group_name"]}}</td>
                            <td style="width:80px;" data-class_name="{{$var["group_name_class"]}}" class="group_name  {{$var["up_group_name_class"]}} {{$var["group_name_class"]}}  "  >{{$var["group_name"]}}</td>
                            <td style="width:80px;" data-class_name="{{$var["account_class"]}}" class="account   {{$var["group_name_class"]}} {{$var["account_class"]}}"  >{{$var["account"]}}</td>
                        @for( $i=0;$i<7;$i++ )
                            <td>
                                <a href="javascript:;" class="td-call_count" value="{{@$week_info[$i]}}">
                                    {{@$var["v_".$i."_lesson_count"]}}/
                                    {{@$var["v_".$i."_lesson_call_before_count"]}}/
                                    {{@$var["v_".$i."_lesson_call_end_count"]}} 
                                </a>
                            </td>
                        @endfor


                        <td>
                            <font color="red" >
                                {{@$var["v_week_lesson_count"] }}/
                                {{@$var["v_week_lesson_call_before_count"]}}/
                                {{@$var["v_week_lesson_call_end_count"]}}
                            </font>
                        </td>

                        <td>
                            <div class=" row-data"
                                     {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >

                                <!-- <a class="fa-comments opt-comments" > </a> -->
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
