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
                                        <td> 节点</td>
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
                                        <td> 节点 </td>
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


                <div class="col-xs-12 col-md-12">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            课时消耗
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>课时系数目标量</td>
                                        <td>在读学生数量</td>
                                        <td>上课学生数量</td>
                                        <td>课时消耗目标数量</td>
                                        <td>课时消耗实际数量</td>

                                        <td>老师请假课时</td>
                                        <td>学生请假课时</td>
                                        <td>其他原因未上课时</td>
                                        <td>课时完成率</td>
                                        <td>学生到课率</td>
                                        <td>课时收入</td>
                                    </tr>
                                </thead>
                                <tbody id="id_lesson_count_list">
                                    <tr>
                                        <td class="panel-yellow" > 存档</td>
                                        <td class="panel-yellow" > 存档  </td>
                                        <td class="panel-yellow" > 存档 </td>
                                        <td> 节点</td>
                                        <td> 节点</td>

                                        <td> 节点</td>
                                        <td> 节点</td>
                                        <td> 节点 </td>
                                        <td > 节点  </td>
                                        <td class="panel-yellow" > 存档  </td>

                                        <td class="panel-yellow" > 存档 </td>

                                    </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-12">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            续费
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>预计结课学生数量</td>
                                        <td>计划内续费学生数量</td>
                                        <td>计划外续费学生数量</td>
                                        <td>实际续费学生数量</td>
                                        <td>续费金额</td>
                                        <td class="panel-red">平均单笔</td>
                                        <td class="panel-red">月续费率</td>
                                        <td class="panel-red">月预警续费率</td>
                                    </tr>
                                </thead>
                                <tbody id="id_assistant_renew_list">
                                    <tr>
                                        <td class="panel-blue">  漏斗-存档</td>
                                        <td class="panel-blue">  漏斗-存档  </td>
                                        <td class="panel-blue">  漏斗-存档  </td>
                                        <td class="panel-blue">  漏斗-存档  </td>
                                        <td>  节点</td>
                                        <td>  节点</td>
                                        <td class="panel-blue">  漏斗-存档 </td>
                                        <td class="panel-blue">  漏斗-存档 </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-12">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            转介绍
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td class="panel-red">转介绍至CC例子量</td>
                                        <td class="panel-red">转介绍至CC例子签单量</td>
                                        <td class="panel-red">转介绍至CC例子签单金额</td>
                                        <td class="panel-red">月转介绍至CC签单率</td>
                                        <td class="panel-red">转介绍成单数量</td>
                                        <td class="panel-red">转介绍总金额</td>
                                        <td>平均单笔</td>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr>
                                        <td class="panel-green">  漏斗-存档-节点  </td>
                                        <td class="panel-green">  漏斗-存档-节点 </td>
                                        <td class="panel-green">  漏斗-存档-节点 </td>
                                        <td class="panel-blue">  漏斗-存档  </td>
                                        <td> 节点</td>
                                        <td> 节点</td>
                                        <td> 节点</td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-6">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            扩科
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>扩课试听数量</td>
                                        <td>扩课成单数量</td>
                                        <td>扩科待跟进数量</td>
                                        <td>扩科未成单数量</td>
                                        <td class="panel-red">月扩课成功率</td>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr>
                                        <td class="panel-green">  漏斗-存档-节点  </td>
                                        <td class="panel-green">  漏斗-存档-节点  </td>
                                        <td class="panel-green">  漏斗-存档-节点  </td>
                                        <td class="panel-green">  漏斗-存档-节点  </td>
                                        <td class="panel-blue">  漏斗-存档 </td>

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-6">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            投诉退费
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>家长投诉数量</td>
                                        <td>非正常退费事件数量</td>
                                        <td>非正常退费金额</td>
                                        <td>不可抗力退费数量</td>
                                        <td>不可抗力退费金额</td>
                                        <td>退费总额</td>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
                                        <td></td>
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
