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
                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span >渠道ex</span>
                        <input type="text" value=""   id="id_origin_ex"  placeholder="" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-3">
                    <div class="input-group " >
                        <span >销售分组</span>
                        <select  id="id_groupid" class="opt-change"  >
                                <option value="-1" > 全部 </option>
                            @foreach ( $group_list as $var ) 
                                <option value="{{$var["groupid"]}}"> {{$var["group_name"]}} </option>
                            @endforeach
                        </select>
                    </div>
                </div>

            </div>
            



        </div>
        <hr />
        <div class="body">
            <table class="common-table ">
                <thead>
                    <tr>
                        {!!\App\Helper\Utils::th_order_gen([
                            ["负责人 ","account" ],
                            ["得到资源总数","all_count" ],
                            ["分配用户数","all_count_0" ],
                            ["历史抢单数","all_count_1" ],
                            ["未拨打用户","no_call" ],
                            ["未拨打(新例子)","no_call_0" ],
                            ["未拨打(历史资源)","no_call_1" ],
                            ["已拨打用户","call_count" ],
                            ["无效用户","invalid_count" ],
                            ["未接通用用户","no_connect" ],
                            ["有效用户","valid_count" ],
                            ["申请试听用户","reqiure_test_count" ],
                            ["排课数","test_lesson_count" ],
                            ["签约数","order_count" ],
                           ])!!}

                        <td> 操作  </td> </tr>

                 </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr>
                            <td >{{$var["account"]}}</td>
                            <td >{{@$var["all_count"]}}</td>
                            <td >{{@$var["all_count_0"]}}</td>
                            <td >{{@$var["all_count_1"]}}</td>
                            <td >{{@$var["no_call"]  }}</td>
                            <td >{{@$var["no_call_0"]  }}</td>
                            <td >{{@$var["no_call_1"]  }}</td>
                            <td >{{@$var["call_count"]  }}</td>
                            <td >{{@$var["invalid_count"]}}</td>
                            <td >{{@$var["no_connect"]}}</td>
                            <td >{{@$var["valid_count"]}}</td>
                            <td >{{@$var["reqiure_test_count"]}}</td>
                            <td >{{@$var["test_lesson_count"]}}</td>
                            <td >{{@$var["order_count"]}}</td>
                            <td><div
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

