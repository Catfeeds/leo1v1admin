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
                <div class="panel panel-danger"  >
                    <div class="panel-heading">
                        <font color="#333" >报名数:</font> 
                        <span style="color:red ; text-decoration: underline; font-size:25px; padding:0px 20px; "  >　{{@$all_total}}　</span> 
                        <font color="#333" >面试试讲:</font>
                        <span style="color:blue ; text-decoration: underline; font-size:25px; padding:0px 20px; " class="video_class" >通过{{@$data1["one_succ"]}}/实到{{@$data1["one_real"]}}/预约{{@$data1["one_count"]}}&nbsp&nbsp{{@$data1["one_per"]}}%　</span> 
                       

                        <font color="#333" >录制试讲:</font> 
                        <span style="color:blue ; text-decoration: underline; font-size:25px; padding:0px 20px; " class="video_class" >通过{{@$data1["video_succ"]}}/实到{{@$data1["video_real"]}}/预约{{@$data1["video_count"]}}&nbsp&nbsp{{@$data1["video_per"]}}%　</span>
                       
                        <font color="#333" >审核通过数:</font> 
                        <span style="color:green ; text-decoration: underline; font-size:25px; padding:0px 20px; " class="suc_class" >　{{@$data["succ_num"]}}　</span> 


                    </div>
                </div>
            </div>


            <div class="row">

                <div class="col-xs-12 col-md-12">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            本月-招师排行榜
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>排名</td>
                                        <td>招师专员</td>
                                        <td>邀约人数</td>
                                        <td>面试预约</td>
                                        <td>视频预约</td>
                                        <td>总体转化率</td>
                                    </tr>
                                </thead>
                                <tbody id="id_per_count_list">
                                    @foreach ( $ret_info as $key=> $var )
                                        <tr>
                                            <td> <span> {{$key+1}} </span> </td>
                                            <td>{{@$var["account"]}} </td> 
                                            <td>{{@$var["all_count"]}} </td> 
                                            <td>{{@$var["one_account_pass"]}}/{{@$var["one_account_real"]}}/{{@$var["one_account"]}}&nbsp&nbsp{{$var["one_per"]}}% </td> 
                                            <td>{{@$var["video_account_pass"]}}/{{@$var["video_account_real"]}}/{{@$var["video_account"]}}&nbsp&nbsp{{$var["video_per"]}}% </td>

                                            <td>{{@$var["all_per"]}}% </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>
                
            </div>

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
                                        <td>培训数</td>
                                        <td>培训通过数</td>
                                        <td>培训通过数</td>
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
                                        <td>培训数</td>
                                        <td>培训通过数</td>
                                        <td>培训通过数</td>

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
                                        <td>培训数</td>
                                        <td>培训通过数</td>
                                        <td>培训通过数</td>

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



