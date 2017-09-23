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
                            概况
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>现金总收入</td>
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
                                        <td> {{@$ret_info['income_price']}}</td>
                                        <td> {{@$ret_info['income_count']}} </td>
                                        <td> {{@$ret_info['formal_info']}} </td>
                                        <td> {{@$ret_info['formal_num']}} </td>
                                        <td> {{@$ret_info['aver_money']}} </td>
                                        <td> {{@$ret_info['aver_count']}} </td>
                                        <td> {{@$ret_info['seller_kpi']}} </td>
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
                                        <td> {{@$ret_info['']}} </td>
                                        <td> 节点 </td>
                                        <td> 节点 </td>
                                        <td> 节点 </td>
                                        <td> 节点 </td>
                                        <td> 节点 </td>
                                        <td> 节点 </td>
                                        <td> 节点 </td>
                                        <td class="panel-yellow" > 存档 </td>
                                        <td> 节点 </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>


        </div>




    </section>

@endsection
