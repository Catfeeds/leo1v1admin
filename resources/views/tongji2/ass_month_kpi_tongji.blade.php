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


     #id_content .panel-body {
         text-align:center;
     }

    </style>

  


    <section class="content " id="id_content" >
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div id="id_date_range" >
                    </div>
                </div>
            </div>
            <hr/>
           

            
            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            各组明细
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>小组</td>
                                        <td>在册学员</td>
                                        <td>结课学生数</td>
                                        <td>结课率</td>
                                        <td>上课学生数</td>
                                        <td>上课率</td>
                                        <td>消耗课时</td>
                                        <td>课时达成率</td>
                                        <td>课时收入</td>
                                        <td>续费目标</td>
                                        <td>实际续费</td>
                                        <td>续费达成率</td>
                                        <td>扩科数量</td>
                                        <td>转介绍人数</td>
                                        <td>转介绍金额</td>
                                        <td>人效</td>
                                    </tr>
                                </thead>
                                <tbody id="id_ass_group">
                                    @foreach ( $ass_group as $key=> $var )
                                        <tr>
                                            <td  > {{@$var["group_name"]}} </td> 
                                            <td>{{@$var["all_student"]}} </td>
                                            <td>{{@$var["read_student_last"]}} </td>
                                            <td>{{@$var["lesson_student"]}} </td>
                                            <td>{{@$var["end_stu_num"]}} </td>
                                            <td>{{@$var["month_stop_student"]}} </td>
                                            <td>{{@$var["refund_student"]}} </td>
                                            <td>{{@$var["warning_student"]}} </td>
                                            <td>{{@$var["lesson_total_old"]}} </td>
                                            <td>{{@$var["lesson_total"]}} </td>
                                            <td>{{@$var["lesson_money"]}} </td>
                                            <td  class="per" data-per="{{@$var["lesson_per"]}}" >
                                                <a href="javascript:;" >{{@$var["lesson_ratio"]}}</a>
                                            </td>
                                            <td  class="per" data-per="{{@$var["lesson_per"]}}" >
                                                <a href="javascript:;" >{{@$var["lesson_per"]}}%</a>
                                            </td>

                                            <td  class="per" data-per="{{@$var["return_stu_per"]}}" >
                                                <a href="javascript:;" >{{@$var["renw_student"]}}</a>
                                            </td>
                                            <td  class="per" data-per="{{@$var["renw_per"]}}" >
                                                <a href="javascript:;" >{{@$var["renw_price"]}}</a>
                                            </td>

                                            <td>{{@$var["tran_price"]}}</td>
                                            
                                            <td>{{@$var["kk_suc"]}}</td>
                                            <td>{{@$var["kk_require"]}}</td>
                                            <td>{{@$var["new_refund_money"]}} </td>
                                            <td>{{@$var["renw_refund_money"]}} </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-12">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            组员明细
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>组别</td>
                                        <td>英文名</td>
                                        <td>助教</td>
                                        <td>回访目标</td>
                                        <td>实际回访量</td>
                                        <td>回访达成率</td>
                                        <td>续费目标</td>
                                        <td>实际续费</td>
                                        <td>续费达成率</td>
                                        <td>新学生数</td>
                                        <td>24小时内首次回访量</td>
                                        <td>未首次回访量</td>
                                        
                                        <td>退费扣分值</td>
                                        <td>月课时收入</td>
                                        <td>月课时</td>
                                        <td>扩课数量</td>
                                        <td>转介绍金额</td>
                                        <td>续费总额</td>
                                        <td>转介绍人数</td>
                                        <td>在册学生数</td>
                                        <td>结课学生数</td>
                                        <td>结课率</td>
                                        <td>上课学生</td>
                                        <td>课时达成率</td>
                                        <td>上课率</td>
                                        <td>人效</td>
                                        <td>KPI</td>
                                    </tr>
                                </thead>
                                <tbody id="id_ass_list">
                                    @foreach ( $ass_list as $key=> $var )
                                        <tr>                                           
                                            <td  > {{@$var["group_name"]}} </td> 
                                            <td  > {{@$var["account"]}} </td> 
                                            <td  > {{@$var["nick"]}} </td> 
                                            <td  > {{@$var["revisit_target"]}} </td> 
                                            <td  > {{@$var["revisit_real"]}} </td> 
                                            <td  > {{@$var["revisit_per"]}}% </td> 
                                            <td  > {{@$var["renw_target"]}} </td> 
                                            <td  > {{@$var["renw_price"]}} </td> 
                                            <td  > {{@$var["renw_per"]}}% </td>

                                            <td  > {{@$var["new_num"]}} </td> 
                                            <td  > {{@$var["first_revisit_num"]}} </td> 
                                            <td  > {{@$var["un_first_revisit_num"]}} </td> 
                                            <td>{{@$var["refund_score"]}}</td>

                                            <td>{{@$var["lesson_money"]}} </td>
                                            <td>{{@$var["lesson_total"]}} </td>
                                            <td>{{@$var["kk_succ"]}} </td>
                                            <td>{{@$var["tran_price"]}} </td>

                                            <td>{{@$var["all_price"]}} </td>
                                            <td>{{@$var["tran_num"]}} </td>
                                            <td>{{@$var["student_all"]}}</td>
                                            <td>{{@$var["student_finish"]}}</td>
                                            <td>{{@$var["student_finish_per"]}}%</td>
                                            <td>{{@$var["student_online"]}}</td>
                                            <td>{{@$var["lesson_do_per"]}}%</td>
                                            <td>{{@$var["student_online_per"]}}%</td>
                                            <td>{{@$var["people_per"]}}</td>
                                            <td>{{@$var["kpi"]}}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
            </div>
             
        </div>
    </section>
    
@endsection



