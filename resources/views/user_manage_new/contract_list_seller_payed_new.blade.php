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


                <div class="col-xs-6 col-md-4" >
                    <div class="input-group ">
                        <span class="input-group-addon">渠道选择</span>
                        <input class="opt-change form-control" id="id_origin_ex" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">年级</span>
                        <input  placeholder="年级" id="id_grade" />
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

                        <td>合同-已付费 </td>
                        <td>课程状态-销售 </td>
                        <td>课程列表-销售 </td>
                        <td> 操作  </td> </tr>
                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)

                        <tr class="{{$var["level"]}}">
                            <td data-class_name="{{$var["main_type_class"]}}" class="main_type" >{{$var["main_type_str"]}}</td>
                            <td  data-class_name="{{$var["up_group_name_class"]}}" class=" up_group_name  {{$var["main_type_class"]}}  {{$var["up_group_name_class"]}} " >{{$var["up_group_name"]}}</td>
                            <td data-class_name="{{$var["group_name_class"]}}" class="group_name  {{$var["up_group_name_class"]}} {{$var["group_name_class"]}}  "  >{{$var["group_name"]}}</td>
                            <td data-class_name="{{$var["account_class"]}}" class="account   {{$var["group_name_class"]}} {{$var["account_class"]}}"  >{{$var["account"]}}</td>



                            <td> <a href="javascript:;" class="td-assign_count" >{{@$var["new_user_count"]*1}}</a></td>
                            <td> <a href="javascript:;" class="td-assign_count" >{{@$var["assigned_count"]*1}}</a></td>
                            <td> <a href="javascript:;" class="td-get_new_count" >{{@$var["get_new_count"]*1}}</a></td>
                            <!-- <td> <a href="javascript:;" class="td-get_histroy_count" >{{@$var["get_histroy_count"]*1}}</a></td>
                               -->
                            <td> <a href="javascript:;" class="td-all_count" >{{@$var["all_count"]}}</a></td>
                            <td> <a href="javascript:;" class="td-all_count_0" >{{@$var["all_count_0"]}}</a></td>
                            <td> <a href="javascript:;" class="td-all_count_1" >{{@$var["all_count_1"]}}</a></td>
                            <td> <a href="javascript:;" class="td-global_tq_no_call " >{{@$var["global_tq_no_call"]}}</a></td>
                            <td> <a href="javascript:;" class="td-no_call" >{{@$var["no_call"]}}</a></td>
                            <td> <a href="javascript:;" class="td-no_call_0" >{{@$var["no_call_0"]}}</a></td>
                            <td> <a href="javascript:;" class="td-no_call_1" >{{@$var["no_call_1"]}}</a></td>
                            <td> {{@$var["invalid_count"]}}</td>
                            <td> {{@$var["no_connect"]}}</td>
                            <td> {{@$var["valid_count"]}}</td>
                            <td> <a href="javascript:;" class="td-require_test_count" >{{@$var["require_test_count"]}}</a></td>
                            <td> <a href="javascript:;" class="td-test_lesson_count" >{{@$var["test_lesson_count"]}}</a></td>

                            <td> <a href="javascript:;" class="td-succ_test_lesson_count" >{{@$var["succ_all_count"]}}</a></td>
                            <td> <a href="javascript:;" class="td-fail_test_lesson_count" >{{@$var["fail_all_count"]}}</a></td>
                            <td> <a href="javascript:;" class="td-fail_need_pay_count" >{{@$var["fail_need_pay_count"]}}</a></td>
                            <td> <a href="javascript:;" class="td-order_count" >{{@$var["order_count"]}}</a></td>
                            <td> {{@$var["order_money"]}}</td>


                            <td><div class=" row-data"
                                     {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >

                                <a class="fa-comments opt-comments" > </a>
                            </div></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include("layouts.page")
        </div>

        <script src="/js/qiniu/plupload/plupload.full.min.js"></script>

@endsection
