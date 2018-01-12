@extends('layouts.app')
@section('content')
    <style>
     .center-title {
         font-size:20px;
         text-align:center;
     }
     .huge {
         font-size: 35px;
     }
     .subjects {
         font-size: 20px;
         text-align:center;
     }
     .plan_font{
         font-size: 18px;
     }
     .panel-red {
         background-color: #d9534f;
     }
     .panel-yellow {
         background-color: #f0ad4f;
     }
     .panel-gray {
         background-color: #ffe;
     }

     .panel-piggy {
         background-color: #eee;
     }
     .panel-white {
         background-color: #fdd;
     }
     .panel-blue {
         background-color: #9ff;
     }

     .panel-green {
         background-color: #090;
     }

     .panel-orange {
         background-color: orange;
     }



     .panel-heading {
         background-color: #f0ad4e;
         border-color: #f0ad4e;
         color: #fff;
     }

     #id_content .panel-heading {
         font-size:20px;
         text-align:center;
     }

    </style>


    <section class="content " id="id_content" style="max-width:1400px;">
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
                            助教组长--KPI
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td class="panel-yellow">组别</td>
                                        <td class="panel-gray">目标回访量</td>
                                        <td class="panel-gray">实际回访量</td>
                                        <td class="panel-gray">完成率</td>
                                        <td class="panel-gray">得分</td>
                                        <td class="panel-piggy">月退费人数</td>
                                        <td class="panel-piggy">月在读人数</td>
                                        <td class="panel-piggy">退费率</td>
                                        <td class="panel-piggy">得分</td>
                                        <td class="panel-white ">成功扩科量</td>
                                        <td class="panel-white ">得分</td>
                                        <td class="panel-blue ">转介绍人数</td>
                                        <td class="panel-blue ">得分</td>
                                        <td class="panel-green ">投诉</td>
                                        <td class="panel-green ">扣分</td>
                                        <td class="panel-orange">24小时内未回访</td>
                                        <td class="panel-orange">扣分</td>
                                        <td class="panel-red">总分</td>
                                    </tr>
                                </thead>
                                <tbody id="id_per_count_list">
                                    @foreach ( $ret_info as $key=> $var )
                                        <tr>
                                            <!-- <td class="panel-yellow">{{@$var["account"]}} </td>
                                                 
                                                 <td class="panel-gray">{{@$var["revisit_num"]}} </td>
                                                 <td class="panel-gray">{{@$var["revisit_do"]}} </td>
                                                 <td class="panel-gray">{{@$var["revisit_per"]}} </td>
                                                 <td class="panel-gray">{{@$var["revisit_score"]}} </td>
                                                 <td class="panel-piggy">{{@$var["refunded_num"]}} </td>
                                                 <td class="panel-piggy">{{@$var["reading_num"]}} </td>
                                                 <td class="panel-piggy">{{@$var["refund_per"]}}% </td>
                                                 <td class="panel-piggy">{{@$var["refund_score"]}} </td>
                                                 <td class="panel-white ">{{@$var["kk_suc_avg"]}} </td>
                                                 <td class="panel-white ">{{@$var["kk_score"]}} </td>
                                                 <td class="panel-blue ">{{@$var["trans_num_avg"]}} </td>
                                                 <td class="panel-blue ">{{@$var["trans_score"]}} </td>
                                                 <td class="panel-green "> </td>
                                                 <td class="panel-green "></td>
                                                 <td class="un_revisit panel-orange" data-adminid="{{$key}}">
                                                 <a href="javascript:;" >{{@$var["un_revisit_num"]}} </a>
                                                 </td>

                                                 <td class="panel-orange ">{{@$var["un_revisit_score"]}} </td>
                                                 <td class="panel-red ">{{@$var["total_score"]}} </td>
                                            -->
                                           

                                            <td class="panel-yellow">{{@$var["account"]}} </td>
                                            <td class="panel-gray">{{@$var["revisit_reword_per"]}} </td>
                                            <td class="panel-gray">{{@$var["seller_week_stu_num"]}} </td>
                                            <td class="panel-gray">{{@$var["first_lesson_stu_list"]}} </td>
                                            <td class="panel-gray">{{@$var["seller_month_lesson_count"]/100}} </td>
                                            <td class="panel-piggy">{{@$var["kpi_lesson_count_finish_per"]}} </td>
                                            <td class="panel-piggy">{{@$var["estimate_month_lesson_count"]}} </td>
                                            <td class="panel-piggy">{{@$var["performance_cc_tran_num"]}} </td>
                                            <td class="panel-piggy">{{@$var["performance_cc_tran_money"]/100}} </td>
                                            <td class="panel-white ">{{@$var["performance_cr_renew_num"]}} </td>
                                            <td class="panel-white ">{{@$var["performance_cr_renew_money"]/100}} </td>
                                            <td class="panel-blue ">{{@$var["stop_student"]}} </td>
                                            <td class="panel-blue ">{{@$var["all_student"]/100}} </td>
                                            <td class="panel-green "> </td>
                                            <td class="panel-green "></td>
                                            <td class="un_revisit panel-orange" data-adminid="{{$key}}">
                                                <a href="javascript:;" >{{@$var["performance_cr_new_num"]}} </a>
                                            </td>

                                            <td class="panel-orange ">{{@$var["performance_cr_new_money"]/100}} </td>
                                            <td class="panel-red ">{{@$var["read_student"]/100}} </td>


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
