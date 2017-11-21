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
                            教务排课--KPI
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>教务</td>
                                        <td>新老师目标回访量</td>
                                        <td>48小时内已回访量</td>
                                        <td>48小时外已回访量</td>
                                        <td>未回访量</td>
                                        <td>新老师目标排课量</td>
                                        <td>9天内已排量</td>
                                        <td>9天外已排量</td>
                                        <td>未排量</td>
                                        <td>老师旷课</td>
                                    </tr>
                                </thead>
                                <tbody id="id_per_count_list">
                                    @foreach ( $revisit_info as $key=> $var )
                                        <tr>
                                            <td>{{@$var["account"]}} </td> 
                                            <td>{{@$var["revisit_num"]}} </td> 
                                            <td>{{@$var["revisit_time_in"]}} </td> 
                                            <td>{{@$var["revisit_time_out"]}} </td> 
                                            <td>{{@$var["no_revisit"]}} </td> 
                                            <td>{{@$var["lesson_plan_num"]}} </td> 
                                            <td>{{@$var["plan_in"]}} </td> 
                                            <td>{{@$var["plan_out"]}} </td> 
                                            <td>{{@$var["no_plan"]}} </td> 
                                            <td>
                                                <a  href="/seller_student_new2/test_lesson_plan_list?date_type=4&has_1v1_lesson_flag=-1&opt_date_type=0&start_time={{$start}}&end_time={{$end}}&grade=-1&subject=-1&test_lesson_student_status=-1&lessonid=undefined&userid=-1&teacherid=-1&success_flag=-1&require_admin_type=-1&require_adminid=-1&tmk_adminid=-1&is_test_user=0&test_lesson_fail_flag=101&accept_flag=-2&seller_groupid_ex=&seller_require_change_flag=-1&require_assign_flag=-1&jw_test_lesson_status=-1&jw_teacher=-1&ass_test_lesson_type=-1" target="_blank">
                                                    {{@$var["absence_num"]}}
                                                </a>
                                            </td> 
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>

                    <div class="col-xs-12 col-md-12">
                        <div class="panel panel-warning"  >
                            <div class="panel-heading center-title ">
                                教务排课--KPI
                            </div>
                            <div class="panel-body">

                                <table   class="table table-bordered "   >
                                    <thead>
                                        <tr>
                                            <td>教务</td>
                                            <td>回访老师量</td>
                                            <td>回访后一周内排课老师量</td>
                                        </tr>
                                    </thead>
                                    <tbody id="id_per_count_list_lesson">
                                        @foreach ( $revisit_teacher_lesson_info as $key=> $var )
                                            <tr>
                                                <td>{{@$var["acc"]}} </td> 
                                                <td>{{@$var["revisit_num"]}} </td> 
                                                <td>{{@$var["lesson_num"]}} </td>                                                
                                            </tr>
                                        @endforeach
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



