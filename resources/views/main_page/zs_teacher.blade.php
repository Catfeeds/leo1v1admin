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


    <section class="content " id="id_content" style="max-width:1600px;">
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
                            面试科目信息
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>科目</td>
                                        <td>面试数</td>
                                        <td>面试人数</td>
                                        <td>面试通过数</td>
                                        <td>面试通过人数</td>
                                        <td>面试通过率(次数)</td>
                                        <td>面试通过率(人数)</td>
                                        <td>新师培训数</td>
                                        <td>新师培训通过数</td>
                                        <td>新师培训通过率</td>
                                        <td>模拟试听数</td>
                                        <td>模拟试听通过数</td>
                                        <td>模拟试听通过率</td>
                                    </tr>
                                </thead>
                                <tbody >
                                    @foreach ( $res_subject as $key=> $var )
                                        <tr>
                                            <td>{{@$var["subject_str"]}} </td> 
                                            <td>{{@$var["all_num"]}} </td> 
                                            <td>{{@$var["all_count"]}} </td> 
                                            <td>{{@$var["succ_num"]}} </td> 
                                            <td>{{@$var["succ"]}} </td> 
                                            <td>{{@$var["succ_num_per"]}}% </td> 
                                            <td>{{@$var["succ_per"]}}% </td> 
                                            <td>{{@$var["train_num"]}} </td> 
                                            <td>{{@$var["train_succ"]}} </td> 
                                            <td>{{@$var["train_per"]}} %</td>
                                            <td>{{@$var["trial_train_num"]}} </td> 
                                            <td>{{@$var["trial_train_succ"]}} </td> 
                                            <td>{{@$var["trial_train_per"]}} %</td> 
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>总计 </td> 
                                        <td>{{@$data["all_num"]}} </td> 
                                        <td>{{@$data["all_count"]}} </td> 
                                        <td>{{@$data["suc_count"]}} </td> 
                                        <td>{{@$data["succ_num"]}} </td> 
                                        <td>{{@$data["all_pass_per"]}}% </td> 
                                        <td>{{@$data["pass_per"]}}% </td> 
                                        <td>{{@$data["train_all"]}} </td> 
                                        <td>{{@$data["train_succ"]}} </td> 
                                        <td>{{@$data["train_per"]}} </td>
                                        <td>{{@$data["trial_train_all"]}} </td> 
                                        <td>{{@$data["trial_train_succ"]}} </td> 
                                        <td>{{@$data["trial_train_per"]}} </td> 

                                    </tr>

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-12">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            面试年级信息
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>年级</td>
                                        <td>面试数</td>
                                        <td>面试人数</td>
                                        <td>面试通过数</td>
                                        <td>面试通过人数</td>
                                        <td>面试通过率(次数)</td>
                                        <td>面试通过率(人数)</td>
                                        <td>新师培训数</td>
                                        <td>新师培训通过数</td>
                                        <td>新师培训通过率</td>
                                        <td>模拟试听数</td>
                                        <td>模拟试听通过数</td>
                                        <td>模拟试听通过率</td>

                                    </tr>
                                </thead>
                                <tbody >
                                    @foreach ( $res_grade as $key=> $var )
                                        <tr>
                                            <td>{{@$var["grade_ex_str"]}} </td> 
                                            <td>{{@$var["all_num"]}} </td> 
                                            <td>{{@$var["all_count"]}} </td>
                                            <td>{{@$var["succ_num"]}} </td> 
                                            <td>{{@$var["succ"]}} </td> 
                                            <td>{{@$var["succ_num_per"]}}% </td> 
                                            <td>{{@$var["succ_per"]}}% </td> 
                                            <td>{{@$var["train_num"]}} </td> 
                                            <td>{{@$var["train_succ"]}} </td> 
                                            <td>{{@$var["train_per"]}} %</td>
                                            <td>{{@$var["trial_train_num"]}} </td> 
                                            <td>{{@$var["trial_train_succ"]}} </td> 
                                            <td>{{@$var["trial_train_per"]}} %</td> 


                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>总计 </td> 
                                        <td>{{@$data["all_num"]}} </td> 
                                        <td>{{@$data["all_count"]}} </td> 
                                        <td>{{@$data["suc_count"]}} </td> 
                                        <td>{{@$data["succ_num"]}} </td> 
                                        <td>{{@$data["all_pass_per"]}}% </td> 
                                        <td>{{@$data["pass_per"]}}% </td> 
                                        <td>{{@$data["train_all"]}} </td> 
                                        <td>{{@$data["train_succ"]}} </td> 
                                        <td>{{@$data["train_per"]}} </td>
                                        <td>{{@$data["trial_train_all"]}} </td> 
                                        <td>{{@$data["trial_train_succ"]}} </td> 
                                        <td>{{@$data["trial_train_per"]}} </td> 

                                    </tr>

                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-md-12">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            面试老师类型信息
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>老师类型</td>
                                        <td>面试数</td>
                                        <td>面试人数</td>
                                        <td>面试通过数</td>
                                        <td>面试通过人数</td>
                                        <td>面试通过率(次数)</td>
                                        <td>面试通过率(人数)</td>
                                        <td>新师培训数</td>
                                        <td>新师培训通过数</td>
                                        <td>新师培训通过率</td>
                                        <td>模拟试听数</td>
                                        <td>模拟试听通过数</td>
                                        <td>模拟试听通过率</td>

                                    </tr>
                                </thead>
                                <tbody >
                                    @foreach ( $res_identity as $key=> $var )
                                        <tr>
                                            <td>{{@$var["identity_ex_str"]}} </td> 
                                            <td>{{@$var["all_num"]}} </td> 
                                            <td>{{@$var["all_count"]}} </td>
                                            <td>{{@$var["succ_num"]}} </td> 
                                            <td>{{@$var["succ"]}} </td> 
                                            <td>{{@$var["succ_num_per"]}}% </td> 
                                            <td>{{@$var["succ_per"]}}% </td> 
                                            <td>{{@$var["train_num"]}} </td> 
                                            <td>{{@$var["train_succ"]}} </td> 
                                            <td>{{@$var["train_per"]}} %</td>
                                            <td>{{@$var["trial_train_num"]}} </td> 
                                            <td>{{@$var["trial_train_succ"]}} </td> 
                                            <td>{{@$var["trial_train_per"]}} %</td> 


                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td>总计 </td> 
                                        <td>{{@$data["all_num"]}} </td> 
                                        <td>{{@$data["all_count"]}} </td> 
                                        <td>{{@$data["suc_count"]}} </td> 
                                        <td>{{@$data["succ_num"]}} </td> 
                                        <td>{{@$data["all_pass_per"]}}% </td> 
                                        <td>{{@$data["pass_per"]}}% </td> 
                                        <td>{{@$data["train_all"]}} </td> 
                                        <td>{{@$data["train_succ"]}} </td> 
                                        <td>{{@$data["train_per"]}} </td>
                                        <td>{{@$data["trial_train_all"]}} </td> 
                                        <td>{{@$data["trial_train_succ"]}} </td> 
                                        <td>{{@$data["trial_train_per"]}} </td> 

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



