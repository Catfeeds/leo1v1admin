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
                            全职VS全体
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                               
                                <tbody >
                                        <tr>
                                            <td>全职老师总人数</td> 
                                            <td>{{$ret_info["fulltime_teacher_count"]}} </td>
                                            <td>全职老师所带学生总数</td>
                                            <td>{{$ret_info['fulltime_teacher_student']}}</td>
                                        </tr>
                                        <tr>
                                            <td>占平台人数比例</td> 
                                            <td>{{$ret_info['fulltime_teacher_pro']}}%({{$ret_info['fulltime_teacher_count']}}/{{$ret_info['platform_teacher_count']}})</td>
                                            <td>占平台学生总数的比例</td>
                                            <td>{{$ret_info['fulltime_teacher_student_pro']}}%({{$ret_info['fulltime_teacher_student']}}/{{$ret_info['platform_teacher_student']}})</td>
                                        </tr>
                                        <tr>
                                            <td>全职老师完成的课耗总数</td> 
                                            <td>{{$ret_info['fulltime_teacher_lesson_count']}}</td>
                                            <td>全职老师cc转化率</td>
                                            <td>{{$ret_info['fulltime_teacher_cc_per']}}%</td>
                                        </tr>

                                        <tr>
                                            <td>占平台课耗总数的比例</td> 
                                            <td>77</td>
                                            <td>平台整体cc转化率</td>
                                            <td>88</td>
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



