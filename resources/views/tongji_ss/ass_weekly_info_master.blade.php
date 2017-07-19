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


    </style>


    <section class="content " id="id_content" style="max-width:1200px;">
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
                            课时消耗
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>周课时系数目标量</td>
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
                                        <td>新签学生数</td>
                                        <td>结课学生数</td>
                                    </tr>
                                </thead>
                                <tbody id="id_lesson_count_list">
                                        <tr>
                                            <td> {{@$list["lesson_target"]}} </td> 
                                            <td> {{@$list["read_student"]}} </td> 
                                            <td> {{@$list["lesson_student"]}} </td> 
                                            <td> {{@$list["lesson_count_target"]}} </td> 
                                            <td> {{@$list["lesson_count"]}} </td> 
                                            <td> {{@$list["teacher_leave_count"]}} </td> 
                                            <td> {{@$list["student_leave_count"]}} </td> 
                                            <td> {{@$list["other_count"]}} </td> 
                                            <td> {{@$list["lesson_count_per"]}}% </td> 
                                            <td> {{@$list["stu_lesson_per"]}}% </td> 
                                            <td> {{@$list["lesson_money"]}} </td> 
                                            <td> {{@$list["new_stu_num"]}} </td> 
                                            <td class="end_stu_num" > <a href="javascript:;" > {{@$list["end_stu_num"]}}</a> </td> 

                                        </tr>
                                   
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12 col-md-7">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            续费
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>预计结课学生数量</td>
                                        <td>实际续费学生数量</td>
                                        <td>计划内续费学生数量</td>
                                        <td>计划外续费学生数量</td>
                                        <td>续费率</td>
                                        <td>续费金额</td>
                                        <td>平均单笔</td>
                                    </tr>
                                </thead>
                                <tbody id="id_assistant_renew_list">
                                        <tr>
                                            <td class="warning_student_detail" > <a href="javascript:;" >{{@$list["warning_student"]}}</a> </td> 
                                            <td> {{@$list["renw_num"]}} </td> 
                                            <td> {{@$list["renw_num_plan"]}} </td> 
                                            <td> {{@$list["renw_num_other"]}} </td> 
                                            <td> {{@$list["renw_per"]}}% </td> 
                                            <td> {{@$list["renw_money"]}} </td> 
                                            <td> {{@$list["renw_money_one"]}} </td> 

                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-5">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            转介绍
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>转介绍试听数量</td>
                                        <td>转介绍成单数量</td>
                                        <td>转介绍总金额</td>
                                        <td>平均单笔</td>
                                    </tr>
                                </thead>
                                <tbody >
                                        <tr>
                                            <td> {{@$list["tran_lesson"]}} </td> 
                                            <td> {{@$list["tran_order"]}} </td> 
                                            <td> {{@$list["tran_money"]}} </td> 
                                            <td> {{@$list["tran_money_one"]}} </td> 

                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-6">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            扩课
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>扩课试听数量</td>
                                        <td>扩课成单数量</td>
                                        <td>扩科未成单数量</td>
                                        <td>扩科待跟进数量</td>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr>
                                        <td class="kk_lesson_detail" > <a href="javascript:;" >  {{@$list["kk_lesson"]}}</a> </td> 

                                        <td> {{@$list["kk_succ"]}} </td> 
                                        <td> {{@$list["kk_fail"]}} </td> 
                                        <td> {{@$list["kk_other"]}} </td> 

                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-6">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            投诉与退费
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>家长投诉数量</td>
                                        <td>退费总数量</td>
                                        <td>退费总金额</td>
                                    </tr>
                                </thead>
                                <tbody >
                                    <tr>
                                        <td></td> 
                                        <td> {{@$list["refund_student"]}} </td> 
                                        <td> {{@$list["refund_money"]}} </td> 

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



