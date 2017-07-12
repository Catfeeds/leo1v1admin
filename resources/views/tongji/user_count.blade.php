@extends('layouts.app')
@section('content')
	<script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.min.js"></script>

	<script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.categories.js"></script>
	<script language="javascript" type="text/javascript" src="/page_js/lib/select_date_range.js"></script>
    <script type="text/javascript" >
     
     var g_data_ex_list= <?php  echo json_encode($table_data_list); ?> ;
    </script>

    <section class="content ">
        <div class="row">
            <div class="col-xs-12 col-md-5">
                <div id="id_date_range"> </div>
            </div>
            <div class="col-md-2 col-xs-6">
                <div class="input-group">
                    <span class="input-group-addon">负责人</span>
                    <select id="id_admin_revisiterid" class="opt-change  ">
                        <option value="-1">全部</option>
                        <option value="0">未分配</option>

                    </select>
                </div>
            </div>


        </div>
        <div class="row">
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <input type="checkbox" value=""  class="opt-check"  id="id_check_add_time_count"  placeholder="" >新进例子数 </input>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <input type="checkbox" value=""  class="opt-check"   id="id_check_first_revisite_time_count"  placeholder="" >消耗例子数 </input>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <input type="checkbox" value=""   class="opt-check"  id="id_check_call_old_count"  placeholder="" >回访旧例子数 </input>
                </div>
            </div>

            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <input type="checkbox" value="" class="opt-check"    id="id_check_test_lesson_count"  placeholder="" >排课数</input>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <input type="checkbox" value="" class="opt-check"    id="id_check_order_count"  placeholder="" >新签合同数</input>
                </div>
            </div>



        </div>

        <hr/>
        <div id="id_pic_user_count" > </div>

            <hr/>
            <table     class="common-table"  > 
                <thead>
                    <tr>
                        {!!\App\Helper\Utils::th_order_gen([
                            ["日期 ","title" ],
                            ["新进例子数","add_time_count" ],
                            ["消耗例子数","first_revisite_time_count" ],
                            ["例子进入一天内消耗数","after_24_first_revisite_time_count" ],
                            ["回访旧例子数","call_old_count" ],
                            ["申请排课数","require_test_lesson_count" ],
                            ["正式排课数(全部)","test_lesson_count" ],
                            ["试听成功数 (全部)","test_lesson_count_succ" ],
                            ["试听失败-支付老师数(全部)","test_lesson_count_fail_need_money" ],
                            ["正式排课数(销售)","seller_test_lesson_count" ],
                            ["试听成功数(销售)","seller_test_lesson_count_succ" ],
                            ["试听失败-支付老师数(销售)","seller_test_lesson_count_fail_need_money" ],
                            ["试听失败-不支付老师数(销售)","seller_test_lesson_count_fail_not_need_money" ],
                            ["试听失败-换时间(销售)","seller_test_lesson_count_change_time" ],
                            ["合同总数","order_count" ],
                            ["合同数-新签","order_count_new" ],
                            ["合同数-转介绍","order_count_from_stu" ],
                            ["合同数-续费","order_count_next" ],
                           ])!!}
                        <td> 操作  </td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $table_data_list as $var )
                        <tr>
                            <td>{{@$var["title"]}} </td>
                            <td>{{@$var["add_time_count"]*1}} </td>
                            <td>{{@$var["first_revisite_time_count"]*1}} </td>
                            <td>{{@$var["after_24_first_revisite_time_count"]*1}} </td>
                            <td>{{@$var["call_old_count"] }}</td>
                            <td><a  href="javascript:;" class="id_require_test_lesson_each">{{@$var["require_test_lesson_count"]*1}}</a> </td>
                            <td ><a href="javascript:;" class="id_test_lesson_each" >{{@$var["test_lesson_count"]*1}}</a> </td>
                            <td  ><a  href="javascript:;" class="id_test_lesson_count_succ_each">{{@$var["test_lesson_count_succ"]*1}}</a> </td>
                            <td ><a  href="javascript:;" class="id_test_lesson_count_fail_need_money"  >{{@$var["test_lesson_count_fail_need_money"]*1}}</a> </td>
                            <td ><a  href="javascript:;" class="id_seller_test_lesson_count" >{{@$var["seller_test_lesson_count"]*1}}</a> </td>
                            <td ><a  href="javascript:;" class="id_seller_test_lesson_count_succ" >{{@$var["seller_test_lesson_count_succ"]*1}}</a> </td>
                            <td  ><a  href="javascript:;" class="id_seller_test_lesson_count_fail_need_money">{{@$var["seller_test_lesson_count_fail_need_money"]*1}}</a> </td>
                            <td  ><a  href="javascript:;" class=" seller_test_lesson_count_fail_not_need_money ">{{@$var["seller_test_lesson_count_fail_not_need_money"]*1}}</a> </td>
                            <td  ><a  href="javascript:;" class=" seller_test_lesson_count_change_time ">{{@$var["seller_test_lesson_count_change_time"]*1}}</a> </td>


                            <td>{{@$var["order_count"]*1}} </td>
                            <td>{{@$var["order_count_new"]*1}} </td>
                            <td>{{@$var["order_count_from_stu"]*1}} </td>
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

