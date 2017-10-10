@extends('layouts.app')
@section('content')
    <style>
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
     .panel-green {
         background-color: #5cb85c;
     }
     .panel-green a:hover {
         color: #3d8b3d;
     }
     .panel-red {
         background-color: #d9534f;
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
         background-color: #f0ad4f;
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
     .panel-blue {
         background-color: #9ff;
     }


    </style>


    <section class="content " id="id_content" style="max-width:1200px;">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">历史记录</span>
                    <select class="opt-change form-control" id="id_is_history_data">
                        <option value="1" >是</option>
                        <option value="2" >否</option>
                    </select>
                </div>
            </div>
            </div>
            <hr/>      

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
                                        <td>{{@$arr['target']}}</td>
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
                                        <td>现金总收入</td>
                                        <td>下单总人数</td>
                                        <td>入职完整月人员签单额</td>
                                        <td>入职完整月人员人数</td>
                                        <td>平均人效</td>
                                        <td>平均单笔</td>
                                        <td>月KPI完整率</td>
                                        <td>CR总人数</td>
                                        <td>结课学员数</td>
                                        <td>退费总人数</td>
                                    </tr>
                                </thead>
                                <tbody id="id_lesson_count_list">
                                    <tr>
                                        <td> {{@$arr['total_price']}}</td> 
                                        <td> {{@$arr['person_num']}}</td> 
                                        <td> {{@$arr['total_price_thirty']}} </td> 
                                        <td> {{@$arr['person_num_thirty']}}</td> 
                                        <td> {{@$arr['person_num_thirty_per']}}</td> 
                                        <td> {{@$arr['contract_per']}} </td> 
                                        <td> {{@$arr['month_kpi_per']}}% </td> 
                                        <td> {{@$arr['cr_num']}}</td> 
                                        <td class="panel-yellow" > {{@$arr['finish_num']}}</td> 
                                        <td> {{@$arr['refund_num']}}</td> 
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
                                            <td class="panel-yellow" > {{@$arr['lesson_target']}}</td>
                                            <td class="panel-yellow" > {{@$arr['read_num']}}  </td>
                                            <td class="panel-yellow" > {{@$arr['total_student']}} </td>
                                            <td> 节点</td>
                                            <td> {{@$arr['lesson_consume']}}</td>
                                            <td> {{@$arr['teacher_leave']}}</td>
                                            <td> {{@$arr['student_leave']}}</td>
                                            <td> {{@$arr['other_leave']}}</td>
                                            <td > 节点  </td>
                                            <td class="panel-yellow" >{{@$arr['student_arrive_per']}}%({{@$arr['student_arrive']}}/{{@$arr['lesson_plan']}})</td> 

                                            <td class="panel-yellow" > {{@$arr['lesson_income']}} </td> 

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
                                        <td>平均单笔</td>
                                        <td>月续费率</td>
                                        <td>月预警续费率</td>
                                    </tr>
                                </thead>
                                <tbody id="id_assistant_renew_list">
                                        <tr>
                                            <td class="panel-blue">  {{@$arr['expect_finish_num']}}</td> 
                                            <td class="panel-blue">  {{@$arr['plan_renew_num']}}  </td> 
                                            <td class="panel-blue">  {{@$arr['other_renew_num']}} </td> 
                                            <td class="panel-blue">  {{@$arr['real_renew_num']}}  </td> 
                                            <td>{{@$arr['total_renew']}}</td> 
                                            <td>{{@$arr['renew_num_per']}}</td>
                                            <td class="panel-blue">  {{@$arr['renew_per']}}%</td>
                                            <td class="panel-blue">  {{@$arr['finish_renew_per']}}% </td>
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
                                        <td>转介绍至CC例子量</td>
                                        <td>转介绍至CC例子签单量</td>
                                        <td>转介绍至CC例子签单金额</td>
                                        <td>月转介绍至CC签单率</td>
                                        <td>转介绍成单数量</td>
                                        <td>转介绍总金额</td>
                                        <td>平均单笔</td>
                                    </tr>
                                </thead>
                                <tbody >
                                        <tr>
                                            <td class="panel-green"> {{@$arr['tranfer_phone_num']}}  </td> 
                                            <td class="panel-green"> {{@$arr['tranfer_total_num']}} </td> 
                                            <td class="panel-green"> {{@$arr['tranfer_total_price']}}</td>
                                            <td class="panel-blue">  {{@$arr['tranfer_success_per']}}%  </td>
                                            <td>{{@$arr['tranfer_num']}}</td>
                                            <td>{{@$arr['total_tranfer']}}</td>
                                            <td>{{@$arr['tranfer_num_per']}}</td>

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
                                        <td>月扩课成功率</td>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr>
                                        <td class="panel-green">{{@$arr['total_test_lesson_num']}}  </td>
                                        <td class="panel-green">  {{@$arr['success_num']}}</td>
                                        <td class="panel-green"> {{@$arr['wait_num']}}  </td> 
                                        <td class="panel-green"> {{@$arr['fail_num']}}  </td> 
                                        <td class="panel-blue">  {{@$arr['kk_success_per']}}% </td> 

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



