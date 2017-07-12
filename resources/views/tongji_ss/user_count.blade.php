@extends('layouts.app')
@section('content')
  <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.min.js"></script>
  <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.categories.js"></script>
  <script language="javascript" type="text/javascript" src="/page_js/lib/select_date_range.js"></script>
    <script type="text/javascript" >
     var g_data_ex_list= <?php  echo json_encode($table_data_list); ?> ;
     var g_adminid_right= <?php  echo json_encode($adminid_right); ?> ;
    </script>
    <section class="content ">
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div id="id_date_range"> </div>
            </div>

            <div  class="col-xs-6 col-md-4">
                <div class="input-group ">
                    <span class="input-group-addon">销售选择</span>
                    <input class="opt-change form-control" id="id_seller_groupid_ex" />
                </div>
            </div>

            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">年级</span>
                    <input class="opt-change form-control" id="id_grade" />
                </div>
            </div>



            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">有试卷</span>
                    <select class="opt-change form-control" id="id_stu_test_paper_flag" >
                    </select>
                </div>
            </div>

        </div>

        <div class="row">
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <input type="checkbox" class="opt-check"  id="id_check_add_time_count"   >新进例子数 </input>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <input type="checkbox" class="opt-check"   id="id_check_first_revisit_time_count">消耗例子数 </input>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <input type="checkbox" class="opt-check"  id="id_check_call_old_count" >回访旧例子数 </input>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <input type="checkbox" class="opt-check" id="id_check_test_lesson_count">排课数</input>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <input type="checkbox" class="opt-check" id="id_check_order_count">新签合同数</input>
                </div>
            </div>
        </div>
        <hr/>
        <div id="id_pic_user_count" > </div>
            <hr/>
            <table class="common-table">
                <thead>
                    <tr>
                        {!!\App\Helper\Utils::th_order_gen([
                            ["日期 ","title" ],
                            ["新进例子数","add_time_count" ],
                            ["消耗例子数","first_revisit_time_count" ],
                            ["例子进入一天内消耗数","after_24_first_revisit_time_count" ],
                            ["例子进入一天内消耗-拨打间隔(分钟)","avg_first_time" ],
                            ["回访旧例子数","call_old_count" ],
                            ["正式试听数(全部)","test_lesson_count" ],
                            ["试听成功数 (全部)","test_lesson_count_succ" ],
                            ["试听失败-支付老师数(全部)","test_lesson_count_fail_need_money" ],
                            ["试听成功数 (第三版老师)","test_lesson_count_succ_new" ],
                            ["试听失败-支付老师数(第三版老师)","test_lesson_count_fail_need_money_new" ],
                            ["申请排课数(销售)","seller_require_test_lesson_count" ],
                            ["正式试听数(销售)","seller_test_lesson_count" ],
                            ["试听成功数(销售)","seller_test_lesson_count_succ" ],
                            ["试听老师学生都进入数(销售)","seller_test_lesson_count_stu_tea_join_count" ],

                            ["试听失败-支付老师数(销售)","seller_test_lesson_count_fail_need_money" ],
                            ["试听失败-不支付老师数(销售)","seller_test_lesson_count_fail_not_need_money" ],
                            ["试听失败-支付老师数(销售第三版)","seller_test_lesson_count_fail_need_money_new" ],
                            ["合同总数","order_count" ],
                            ["合同数-新签","order_count_new" ],
                            ["合同数-续费","order_count_next" ],
                           ])!!}
                        <td> 操作  </td> </tr>
                </thead>
                <tbody>
                    @foreach ( $table_data_list as $var )
                        <tr>
                            <td>{{@$var["title"]}} </td>
                            <td>{{@$var["add_time_count"]*1}} </td>
                            <td>{{@$var["first_revisit_time_count"]*1}} </td>
                            <td>{{@$var["after_24_first_revisit_time_count"]*1}} </td>
                            <td>{{intval(@$var["avg_first_time"]/60)}} </td>
                            <td>{{@$var["call_old_count"] }}</td>
                            <td ><a href="javascript:;" class="id_test_lesson_each" >{{@$var["test_lesson_count"]*1}}</a> </td>
                            <td ><a href="javascript:;" class="id_test_lesson_count_succ_each">{{@$var["test_lesson_count_succ"]*1}}</a> </td>
                            <td ><a href="javascript:;" class="id_test_lesson_count_fail_need_money">{{@$var["test_lesson_count_fail_need_money"]*1}}</a> </td>
                            <td >{{@$var["test_lesson_count_succ_new"]*1}}</a> </td>
                            <td >{{@$var["test_lesson_count_fail_need_money_new"]*1}}</a> </td>
                            <td ><a href="javascript:;" class="id_seller_require_test_lesson_count" >{{@$var["seller_require_test_lesson_count"]*1}}</a> </td>
                            <td ><a href="javascript:;" class="id_seller_test_lesson_count" >{{@$var["seller_test_lesson_count"]*1}}</a> </td>
                            <td ><a href="javascript:;" class="id_seller_test_lesson_count_succ" >{{@$var["seller_test_lesson_count_succ"]*1}}</a> </td>
                            <td ><a href="javascript:;" class="" >{{@$var["seller_test_lesson_count_stu_tea_join_count"]*1}}</a> </td>

                            <td ><a href="javascript:;" class="id_seller_test_lesson_count_fail_need_money">{{@$var["seller_test_lesson_count_fail_need_money"]*1}}</a> </td>
                            <td >{{@$var["seller_test_lesson_count_fail_not_need_money"]*1}} </td>
                            <td>{{@$var["seller_test_lesson_count_fail_need_money_new"]*1}} </td>
                            <td>{{@$var["order_count"]*1}} </td>
                            <td>{{@$var["order_count_new"]*1}} </td>
                            <td>{{@$var["order_count_next"]*1}} </td>
                            <td>
                                <div class="row-data"
                                    {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

    </section>

@endsection
