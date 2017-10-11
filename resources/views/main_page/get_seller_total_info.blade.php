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

            </div>
        </div>

        <div class="row">

            <div class="row">

                <div class="col-xs-12 col-md-12">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            @if($arr['type'] == 1)
                                月报
                            @else
                                周报
                            @endif
                            @if($arr['create_time_range'])
                                <br/>统计时段({{@$arr['create_time_range']}})
                            @endif
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td width="200px" align="center">月度目标收入</td>
                                        <td>{{@$ret_info['formal_info']}}</td>
                                        <td width="200px" align="center">完成金额</td>
                                        <td>{{@$arr['total_price']}}</td>
                                        <td width="200px" align="center">完成率</td>
                                        <td >{{@$arr['kpi_per']}}%</td>
                                        <td width="200px" align="center">缺口金额</td>
                                        <td >{{@$arr['gap_money']}}</td>
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
                                        <td>现金总收入(元)</td>
                                        <td>下单总人数</td>
                                        <td class="panel-red">入职完整月人员签单额</td>
                                        <td class="panel-red">入职完整月人员人数</td>
                                        <td class="panel-red">平均人效</td>
                                        <td class="panel-red">平均单笔</td>
                                        <td class="panel-red">月KPI完整率</td>
                                    </tr>
                                </thead>
                                <tbody id="id_lesson_count_list">
                                    <tr>
                                        <td> {{@number_format($ret_info['income_price'],2)}}</td>
                                        <td> {{@$ret_info['income_num']}} </td>
                                        <td> {{@number_format($ret_info['formal_info'],2)}} </td>
                                        <td> {{@$ret_info['formal_num']}} </td>
                                        <td> {{@number_format($ret_info['aver_money'],2)}} </td>
                                        <td> {{@number_format($ret_info['aver_count'],2)}} </td>
                                        <td> {{@number_format($ret_info['seller_kpi'],2)}}% </td>
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
                                        <td class="panel-red">咨询一部</td>
                                        <td class="panel-red">咨询二部</td>
                                        <td class="panel-red">咨询三部</td>
                                        <td class="panel-red">新人营</td>
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
                                        <td> {{@$ret_info['first_num']}}  </td>
                                        <td> {{@$ret_info['second_num']}} </td>
                                        <td> {{@$ret_info['third_num']}} </td>
                                        <td> {{@$ret_info['new_num']}} </td>
                                        <td> {{@$ret_info['traing_num']}}</td>
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
                                        <td class="panel-red">月邀请率</td>
                                        <td class="panel-red">月排课率</td>
                                        <td class="panel-red">月到课率</td>
                                        <td class="panel-red">月试听转化率</td>
                                        <td class="panel-red">月签约率</td>
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
                                        <td>接通率</td>
                                        <td class="panel-red">认领率</td>
                                        <td class="panel-red">邀约数</td>
                                        <td class="panel-red">未消耗例子数</td>
                                        <td class="panel-red">月例子消耗率</td>
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
