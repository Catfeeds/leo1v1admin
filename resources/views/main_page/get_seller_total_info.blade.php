@extends('layouts.app')
@section('content')
    <style>
     #id_content .panel-heading {
         font-size:20px;
         text-align:center;
     }
     .font_thead{
         font-size:17px;
         color:#3c8dbc;
     }
    </style>
    <script type="text/javascript" >
     var g_data= <?php  echo json_encode($ret_info); ?> ;
    </script>

    <section class="content " id="id_content">

        <div  id="id_query_row">
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">历史数据</span>
                        <select class="opt-change form-control" id="id_history_data">
                            <option value="0">是</option>
                            <option value="1">否</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div><a href="javascript:;" id="download_data" class="fa fa-download">导出</a></div>
                </div>

            </div>
        </div>

        <div class="row">
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            {{@$ret_info['data_type']}}
                            <br/>
                            销售额完成情况汇总
                        </div>
                        <div class="panel-body">
                            <table class="table table-bordered ">
                                <thead>
                                    <tr>
                                        <td width="200px" align="center">月度目标收入</td>
                                        <td>{{@$ret_info['seller_target_income']}}</td>
                                        <td width="200px" align="center">完成金额</td>
                                        <td>{{@$ret_info['formal_info']}}</td>
                                        <td width="200px" align="center">完成率</td>
                                        <td >{{@number_format($ret_info['month_finish_persent'],2)}}%</td>
                                        <td width="200px" align="center">缺口金额</td>
                                        <td >{{@number_format($ret_info['month_left_money'],2)}}</td>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>


                <div class="col-xs-12 col-md-12">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            概况
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>收入</td>
                                        <td>下单总人数</td>
                                        <td class="panel-red">入职完整月签单人数</td>
                                        <td class="panel-red">入职完整月人员签单总额</td>
                                        <td class="panel-red">平均人效(入职完整月)</td>
                                        <td class="panel-red">平均人效</td>
                                        <td class="panel-red">平均单笔</td>
                                        <td class="panel-red">月KPI完成率</td>
                                    </tr>
                                </thead>
                                <tbody id="id_lesson_count_list">
                                    <tr>
                                        <td> {{@$ret_info['new_money']}} </td>
                                        <td> {{@$ret_info['order_cc_num']}} </td>
                                        <td> {{@$ret_info['formal_num']}} </td>
                                        <td> {{@$ret_info['all_order_price']}} </td>
                                        <td> {{@number_format($ret_info['aver_money'],2)}} </td>
                                        <td> {{@number_format($ret_info['aver_money_cc'],2)}} </td>
                                        <td> {{@number_format($ret_info['aver_count'],2)}} </td>
                                        <td >{{@number_format($ret_info['month_finish_persent'],2)}}%</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                <div class="col-xs-12 col-md-12">
                    <div class="panel panel-warning"  >
                        <div class="panel-body">
                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td class="panel-red">CC总人数</td>
                                        @if($isTranFlag>0)
                                            @foreach($sellerNumArr as $sellerItem)
                                                <td> {{@$sellerItem['group_name']}}  </td>
                                            @endforeach
                                        @else
                                            <td class="panel-red">咨询一部</td>
                                            <td class="panel-red">咨询二部</td>
                                            <td class="panel-red">咨询三部</td>
                                            <td class="panel-red">咨询五部</td>
                                            <td class="panel-red">新人营</td>
                                        @endif
                                        <td class="panel-red">培训中</td>
                                        <td>转介绍金额占比</td>
                                        <td>高中金额占比</td>
                                        <td>初中金额占比</td>
                                        <td>小学金额占比</td>
                                    </tr>
                                </thead>
                                <tbody id="id_lesson_count_list">
                                    <tr>
                                        <td> {{@$ret_info['seller_num']}} </td>
                                        @if($isTranFlag>0)
                                            @foreach($sellerNumArr as $sellerItem)
                                                <td> {{@$sellerItem['seller_num']}}  </td>
                                            @endforeach
                                        @else
                                            <td> {{@$ret_info['one_department']}}  </td>
                                            <td> {{@$ret_info['two_department']}} </td>
                                            <td> {{@$ret_info['three_department']}} </td>
                                            <td> {{@$ret_info['five_department']}} </td>
                                            <td> {{@$ret_info['new_department']}} </td>
                                        @endif
                                        <td> {{@$ret_info['train_department']}}</td>
                                        <td> {{@number_format($ret_info['referral_money_rate'],2)}}% </td>
                                        <td> {{@number_format($ret_info['high_school_money_rate'],2)}}% </td>
                                        <td> {{@number_format($ret_info['junior_money_rate'],2)}}% </td>
                                        <td> {{@number_format($ret_info['primary_money_rate'],2)}}% </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-12">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            转化率
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>试听邀约数</td>
                                        <td>试听排课数</td>
                                        <td class="panel-red">试听成功数</td>
                                        <td class="panel-red">签单数</td>
                                        <td class="panel-red"><a title="月初至今试听邀约数/已拨通">月邀约率</a></td>
                                        <td class="panel-red"><a title="月初至今试听排课数/试听邀约数">月排课率</a></td>
                                        <td class="panel-red"><a title="月初至今试听成功数/试听排课数">月到课率</a></td>
                                        <td class="panel-red"><a title="月初至今合同人数/试听成功数">月试听转化率</a></td>
                                        <td class="panel-red"><a title="月初至今合同人数/已拨通例子">月签约率</a></td>
                                    </tr>
                                </thead>
                                <tbody id="id_lesson_count_list">
                                    <tr>
                                        <td> {{@$ret_info['seller_invit_num']}}</td>
                                        <td> {{@$ret_info['seller_schedule_num']}} </td>
                                        <td> {{@$ret_info['test_succ_num']}} </td>
                                        <td> {{@$ret_info['new_order_num']}} </td>
                                        <td> {{@number_format($ret_info['invit_month_rate'],2)}}% </td>
                                        <td> {{@number_format($ret_info['test_plan_month_rate'],2)}}% </td>
                                        <td> {{@number_format($ret_info['lesson_succ_month_rate'],2)}}% </td>
                                        <td> {{@number_format($ret_info['trans_month_rate'],2)}}% </td>
                                        <td> {{@number_format($ret_info['sign_month_rate'],2)}}% </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


                <div class="col-xs-12 col-md-12">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            外呼情况
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>呼出量</td>
                                        <td>例子接通率</td>
                                        <td class="panel-red">例子认领率</td>
                                        <td class="panel-red">邀约数</td>
                                        <td class="panel-red">未消耗例子数</td>
                                        <td class="panel-red"><a title="月初至今已拨打例子量/新进例子量">月例子消耗率</a></td>
                                        <td class="panel-red">人均呼出量</td>
                                        <td class="panel-red">人均邀约数</td>
                                        <td class="panel-red">人均通时(分钟)</td>
                                    </tr>
                                </thead>
                                <tbody id="id_lesson_count_list">
                                    <tr>
                                        <td> {{@number_format($ret_info['seller_call_num'])}} </td>
                                        <td> {{@number_format($ret_info['succ_called_rate'],2)}}% </td>
                                        <td> {{@number_format($ret_info['claim_num_rate'],2)}}%</td>
                                        <td> {{@$ret_info['seller_invit_num']}}</td>
                                        <td> {{@$ret_info['un_consumed']}} </td>
                                        <td> {{@number_format($ret_info['stu_consume_rate'],2)}}% </td>
                                        <td> {{@number_format($ret_info['aver_called'],2)}} </td>
                                        <td> {{@number_format($ret_info['invit_rate'],2)}} </td>
                                        <td> {{@number_format($ret_info['called_rate']/60,2)}} </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>


        </div>
        </div>




    </section>

@endsection
