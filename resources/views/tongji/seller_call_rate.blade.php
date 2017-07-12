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

    <section class="content">
        <div class="book_filter">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div id="id_date_range"> </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">年级</span>
                        <input  placeholder="年级" id="id_grade" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">时段</span>
                        <select class="opt-change form-control" id="id_hour" >
                        </select>
                    </div>
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
                        <td>通话时长</td>
                        <td>接通次数</td>
                        <td>总次数</td>
                        <td>接通率</td>

                        <td>邀约</td>
                        <td>1小时前试听课未接通数</td>
                        <td>合同金额</td>

                        <td> 操作  </td> </tr>

                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)

                        <tr class="{{$var["level"]}}">
                            <td data-class_name="{{$var["main_type_class"]}}" class="main_type" >{{$var["main_type_str"]}}</td>
                            <td  data-class_name="{{$var["up_group_name_class"]}}" class=" up_group_name  {{$var["main_type_class"]}}  {{$var["up_group_name_class"]}} " >{{$var["up_group_name"]}}</td>
                            <td data-class_name="{{$var["group_name_class"]}}" class="group_name  {{$var["up_group_name_class"]}} {{$var["group_name_class"]}}  "  >{{$var["group_name"]}}</td>
                            <td data-class_name="{{$var["account_class"]}}" class="account   {{$var["group_name_class"]}} {{$var["account_class"]}}"  >{{$var["account"]}}</td>

                            <td><a href="javascript:;"  >{{@$var['call_duration_str']}}</a></td>
                            <td><a href="javascript:;"  >{{@$var['called_num']}}</a></td>
                            <td><a href="javascript:;"  >{{@$var['calltotal']}}</a></td>
                            <td><a href="javascript:;"  >{{@$var['called_rate']}}%</a></td>

                            <td> <a href="javascript:;" class="td-require_test_count" >{{@$var["require_test_count"]}}</a></td>
                            <td> {{@$var["lesson_num"]}}</td>
                            <td> {{@$var["order_money"]}}</td>

                            <td><div class=" row-data"
                                     {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >
                                <a class="fa-comments opt-set-vertical" > </a>
                            </div></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include("layouts.page")
        </div>

        <script src="/js/qiniu/plupload/plupload.full.min.js"></script>

@endsection
