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
                        <div>
                            <font color="#333" >报名数:</font> 
                            <span style="color:red ; text-decoration: underline; font-size:25px; padding:0px 20px; "  >　总-{{@$all_total}},系统-{{@$system_total}},自产-{{@$self_total}}　</span>
                            <font color="#333" >未联系数:</font> 
                            <span style="color:red ; text-decoration: underline; font-size:25px; padding:0px 20px; "  >{{@$no_call_total}}</span>

                        </div>
                        <div>
                            <font color="#333" >面试试讲:</font>
                            <span style="color:blue ; text-decoration: underline; font-size:25px; padding:0px 20px; " class="video_class" >通过{{@$data["one_succ"]}}/实到{{@$data["one_real"]}}/预约{{@$data["one_count"]}}&nbsp&nbsp{{@$data["one_per"]}}%　</span> 
                            

                            <font color="#333" >录制试讲:</font> 
                            <span style="color:blue ; text-decoration: underline; font-size:25px; padding:0px 20px; " class="video_class" >通过{{@$data["video_succ"]}}/审核{{@$data["video_real"]}}/提交{{@$data["video_count"]}}&nbsp&nbsp{{@$data["video_per"]}}%　</span>
                            
                            <font color="#333" >审核通过数:</font> 
                            <span style="color:green ; text-decoration: underline; font-size:25px; padding:0px 20px; " class="suc_class" >　{{@$data["all_succ"]}}　</span>
                        </div>


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
                                        <td>报名人数-系统</td>
                                        <td>报名人数-自产</td>
                                        <td>未联系人数</td>
                                        <td>面试预约</td>
                                        <td>视频预约</td>
                                        <td>总体转化率</td>
                                    </tr>
                                </thead>
                                <tbody id="id_per_count_list">
                                    @foreach ( $ret_info as $key=> $var )
                                        <tr>
                                            <td> <span> {{$key+1}} </span> </td>
                                            <td>{{@$var["name"]}} </td> 
                                            <td>{{@$var["system_count"]}} </td> 
                                            <td>{{@$var["self_count"]}} </td> 
                                            <td>{{@$var["no_call_count"]}} </td> 
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
                            录制试讲通过老师
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>招师专员</td>
                                        <td>小学语文</td>
                                        <td>初中语文</td>
                                        <td>高中语文</td>
                                        <td>小学数学</td>
                                        <td>初中数学</td>
                                        <td>高中数学</td>
                                        <td>小学英语</td>
                                        <td>初中英语</td>
                                        <td>高中英语</td>
                                        <td>初中化学</td>
                                        <td>高中化学</td>
                                        <td>初中物理</td>
                                        <td>高中物理</td>
                                        <td>初中生物</td>
                                        <td>高中生物</td>
                                        <td>科学</td>
                                        <td>其他</td>


                                    </tr>
                                </thead>
                                <tbody id="id_per_count_list">
                                    @foreach ( $zs_video_list as $key=> $var )
                                        <tr>
                                            <td>{{@$var["name"]}} </td> 
                                            <td>{{@$var["xxyw"]}} </td> 
                                            <td>{{@$var["czyw"]}} </td> 
                                            <td>{{@$var["gzyw"]}} </td>
                                            <td>{{@$var["xxsx"]}} </td> 
                                            <td>{{@$var["czsx"]}} </td> 
                                            <td>{{@$var["gzsx"]}} </td> 
                                            <td>{{@$var["xxyy"]}} </td> 
                                            <td>{{@$var["czyy"]}} </td> 
                                            <td>{{@$var["gzyy"]}} </td>
                                            <td>{{@$var["czhx"]}} </td> 
                                            <td>{{@$var["gzhx"]}} </td> 
                                            <td>{{@$var["czwl"]}} </td> 
                                            <td>{{@$var["gzwl"]}} </td> 
                                            <td>{{@$var["czsw"]}} </td> 
                                            <td>{{@$var["gzsw"]}} </td> 
                                            <td>{{@$var["kx"]}} </td> 
                                            <td>{{@$var["other"]}} </td> 


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
                            面试试讲通过老师
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>招师专员</td>
                                        <td>小学语文</td>
                                        <td>初中语文</td>
                                        <td>高中语文</td>
                                        <td>小学数学</td>
                                        <td>初中数学</td>
                                        <td>高中数学</td>
                                        <td>小学英语</td>
                                        <td>初中英语</td>
                                        <td>高中英语</td>
                                        <td>初中化学</td>
                                        <td>高中化学</td>
                                        <td>初中物理</td>
                                        <td>高中物理</td>
                                        <td>初中生物</td>
                                        <td>高中生物</td>
                                        <td>科学</td>
                                        <td>其他</td>


                                    </tr>
                                </thead>
                                <tbody id="id_per_count_list">
                                    @foreach ( $zs_one_list as $key=> $var )
                                        <tr>
                                            <td>{{@$var["name"]}} </td> 
                                            <td>{{@$var["xxyw"]}} </td> 
                                            <td>{{@$var["czyw"]}} </td> 
                                            <td>{{@$var["gzyw"]}} </td>
                                            <td>{{@$var["xxsx"]}} </td> 
                                            <td>{{@$var["czsx"]}} </td> 
                                            <td>{{@$var["gzsx"]}} </td> 
                                            <td>{{@$var["xxyy"]}} </td> 
                                            <td>{{@$var["czyy"]}} </td> 
                                            <td>{{@$var["gzyy"]}} </td>
                                            <td>{{@$var["czhx"]}} </td> 
                                            <td>{{@$var["gzhx"]}} </td> 
                                            <td>{{@$var["czwl"]}} </td> 
                                            <td>{{@$var["gzwl"]}} </td> 
                                            <td>{{@$var["czsw"]}} </td> 
                                            <td>{{@$var["gzsw"]}} </td> 
                                            <td>{{@$var["kx"]}} </td> 
                                            <td>{{@$var["other"]}} </td> 


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



