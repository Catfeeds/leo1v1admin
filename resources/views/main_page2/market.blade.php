@extends('layouts.app')
@section('content')
    <style>
     .opt_add_order_activity{
         margin-left:5%;
     }
     #tr_template{
         display:none;
     }
     .order_activity_quota{
         float:left;
     }
     .center-title {
         font-size:20px;
         text-align:center;
     }
     .huge {
         font-size: 40px;
     }
     .panel-green {
         border-color: #5cb85c;
     }
     .panel-green .panel-heading {
         background-color: #5cb85c;
         border-color: #5cb85c;
         color: #fff;
     }
     .panel-green a {
         color: #5cb85c;
     }
     .panel-green a:hover {
         color: #3d8b3d;
     }
     .panel-red {
         border-color: #d9534f;
     }
     .panel-red .panel-heading {
         background-color: #d9534f;
         border-color: #d9534f;
         color: #fff;
     }
     .panel-red a {
         color: #d9534f;
     }
     .panel-red a:hover {
         color: #b52b27;
     }
     .panel-yellow {
         border-color: #f0ad4e;
     }
     .panel-yellow .panel-heading {
         background-color: #f0ad4e;
         border-color: #f0ad4e;
         color: #fff;
     }
     .panel-yellow a {
         color: #f0ad4e;
     }
     .panel-yellow a:hover {
         color: #df8a13;
     }
     #order_activity_quota_detail{
         clear:left;
     }

    </style>



    <section class="content " id="id_content">
        <div class="row  row-query-list" >
            <div class="col-xs-12 col-md-5"  data-title="时间段">
                <div  id="id_date_range" >
                </div>
            </div>
        </div>
        <hr/>


        <div>
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title">
                            本月特殊申请配额
                        </div>
                        <div class="panel-body">
                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>角色</td>
                                        <td>配额  </a> </td>
                                        <td> 已用</td>
                                        <td>剩余</td>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr>
                                        <td>销售</td>
                                        <td> <span> {{$role_2_diff_money_def}}</span> <a id="id_edit_seller_diff_money_def" class="fa fa-edit" href="#" ></td>
                                        <td> {{$role_2_diff_money}} </td>
                                        <td> {{$role_2_diff_money_def-$role_2_diff_money}} </td>
                                    </tr>

                                    <tr>
                                        <td>助教</td>
                                         <td> <span> {{$role_1_diff_money_def}}</span> <a id="id_edit_teach_assistant_diff_money_def" class="fa fa-edit" href="#" ></td>
                                        <td> {{$role_1_diff_money}} </td>
                                        <td> {{$role_1_diff_money_def-$role_1_diff_money}} </td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>

                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title">
                            签单例子进入分布
                        </div>
                        <div class="panel-body">
                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>例子时间</td>
                                        <td>历史例子</td>
                                        <td>上月例子</td>
                                        <td>当月例子</td>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr>
                                        <td>合同数量</td>
                                        <td>{{$his_month_cc}}</td>
                                        <td>{{$last_month_cc}}</td>
                                        <td>{{$current_month_cc}}</td>
                                    </tr>
                                    <tr>
                                        <td>合同占比</td>
                                        <td>{{$his_month_rate}}</td>
                                        <td>{{$last_month_rate}}</td>
                                        <td>{{$current_month_rate}}</td>
                                    </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>

                </div>
                
                <div class="order_activity_quota col-md-4">
                    <!-- 合同活动配额  begin -->
                    <div class="col-xs-12 col-md-12">
                        <div class="panel panel-warning"  >
                            <div class="panel-heading center-title">
                                合同相关活动配额汇总
                            </div>
                            <div class="panel-body">
                                <table   class="table table-bordered "   >
                                    <thead>
                                        <tr>
                                            <td>总预算</td>
                                            <td>已投放预算</td>
                                            <td>已使用预算</td>
                                            <td>结余预算</td>
                                        </tr>
                                    </thead>
                                    <tbody >
                                        <tr>
                                            <td><span>{{$sum_order_activity_quota['sum_activity_quota']}}</span><a id="id_edit_order_sum_activity_quota" class="fa fa-edit" href="#" ></td>
                                            <td>{{$sum_order_activity_quota['put_quota']}}  </td>
                                            <td> {{$sum_order_activity_quota['used_quota']}} </td>
                                            <td> {{$sum_order_activity_quota['left_quota']}} </td>
                                        </tr>

                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                    <!-- 合同活动配额  end -->

                    <!-- 合同活动明细配额  begin -->
                    <div class="col-xs-12 col-md-12" id="order_activity_quota_detail">
                        <div class="panel panel-warning"  >
                            <div class="panel-heading center-title">
                                合同相关活动配额明细 <a class="fa fa-plus opt_add_order_activity"></a>
                            </div>
                            <div class="panel-body">
                                <table   class="table table-bordered "   >
                                    <thead>
                                        <tr>
                                            <td>活动名</td>
                                            <td>预算配额</td>
                                            <td>已用额度</td>
                                            <td>剩余额度</td>
                                        </tr>
                                    </thead>
                                    <tbody >
                                        <!-- <tr id ="tr_template">
                                             <td><span id=""> </span> <a id="id_edit_order_activity_desc" class="fa fa-edit" href="#" ></td>
                                             <td> <span> </span> <a id="id_edit_order_activity_quota" class="fa fa-edit" href="#" ></td>
                                             <td> </td>
                                             <td> </td>
                                             </tr>
                                           -->
                                        @foreach($order_activity_detail as $val)
                                        <tr>
                                            <td style="display:none;"> <span>{{$val['id']}}</span> </td>
                                            <td><span>{{$val['order_activity_desc']}}</span> <a  class="fa fa-edit opt-edit_order_activity_detail" href="#" ></td>
                                            <td> <span>{{$val['market_quota']}}</span> </td>
                                                <td> {{$val['used_quota']}}</td>
                                                <td> {{$val['left_quota']}} </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>

                    <!-- 合同活动明细配额  end -->
                </div>


            </div>
        </div>
    </section>

@endsection
