@extends('layouts.app')
@section('content')
    <script type="text/javascript" >
     var g_adminid_right= <?php  echo json_encode($adminid_right); ?> ;
    </script>

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
                <div class="col-xs-6 col-md-3" data-always_show="1">
                    <div class="input-group ">
                        <span class="input-group-addon">全职老师分类</span>
                        <select class="opt-change form-control" id="id_fulltime_teacher_type" >
                        </select>
                    </div>
                </div>
                <div  class="col-xs-6 col-md-4">
                    <div class="input-group ">
                        <span class="input-group-addon">全职老师分组</span>
                        <input class="opt-change form-control" id="id_seller_groupid_ex" />
                    </div>
                </div>

            </div>
            <hr/>      

            <div class="row">

                <div class="col-xs-12 col-md-12">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            转化率
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td rowspan="2">老师</td> 
                                        <td rowspan="2">入职天数</td> 
                                        <td colspan="4">试听学生</td>
                                        <td colspan="5">转化学生</td>
                                        <td colspan="4">转化率</td>
                                        <td colspan="6">绩效</td>
                                       
                                    </tr>

                                    <tr>
                                        <td>cc</td>
                                        <td>扩课</td>
                                        <td>换老师</td>
                                        <td>合计</td>
                                        <td>cc</td>
                                        <td>扩课</td>
                                        <td>换老师</td>
                                        <td>合计</td>
                                        <td>合计(非求和)</td>

                                        <td>cc</td>
                                        <td>扩课</td>
                                        <td>换老师</td>
                                        <td>综合</td>

                                        <td>cc</td>
                                        <td>扩课/换老师</td>
                                        <td>综合</td>

                                        <td>合计</td>
                                        <td>绩效奖金</td>
                                        <td>排行奖金</td>
                                    </tr>
                                </thead>
                                <tbody id="id_lesson_count_list">
                                    @foreach($ret_info as $var)
                                        <tr>
                                            <td> {{@$var["realname"]}} </td> 
                                            <td> {{@$var["train_day"]}} </td> 
                                            <td> {{@$var["cc_lesson_num"]}} </td> 
                                            <td> {{@$var["kk_lesson_num"]}} </td> 
                                            <td> {{@$var["hls_lesson_num"]}} </td> 
                                            <td> {{@$var["lesson_all"]}} </td> 
                                            
                                            <td> {{@$var["cc_order_num"]}} </td> 
                                            <td> {{@$var["kk_order_num"]}} </td> 
                                            <td> {{@$var["hls_order_num"]}} </td> 
                                            <td> {{@$var["order_all"]}} </td> 
                                            <td> {{@$var["all_order_num"]}} </td> 

                                            <td> {{@$var["cc_per"]}}% </td> 
                                            <td> {{@$var["kk_per"]}}% </td> 
                                            <td> {{@$var["hls_per"]}}% </td>
                                            <td> {{@$var["all_per"]}}% </td>

                                            <td> {{@$var["cc_score"]}} </td> 
                                            <td> {{@$var["kk_hls_score"]}} </td> 
                                            <td> {{@$var["all_score"]}}</td> 

                                            <td> {{@$var["score"]}} </td> 
                                            <td> {{@$var["reward"]}} </td> 
                                            <td> {{@$var["other_reward"]}} </td> 
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
                            课时
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td >老师</td> 
                                        <td >入职天数</td> 
                                        <td>常规学生数</td>
                                        <td>周常规课时</td>
                                        <td>常规课完成课时</td>

                                        <td>课时消耗完成率</td>
                                        <td>本月剩余课时</td>
                                        <td>已完成常规学生月平均课时</td>
                                        <td>累计课时消耗完成率</td>
                                        <td>绩效奖金</td>
                                        
                                        <td>排行奖金</td>

                                    </tr>
                                </thead>
                                <tbody id="id_assistant_renew_list">
                                    @foreach($list as $var)

                                        <tr>
                                            <td> {{@$var["realname"]}} </td> 
                                            <td> {{@$var["train_day"]}} </td> 
                                            <td> {{@$var["normal_stu"]}} </td> 
                                            <td> {{@$var["week_count"]}} </td> 
                                            <td> {{@$var["lesson_count"]}} </td> 

                                            <td> {{@$var["lesson_per"]}}% </td> 
                                            <td> {{@$var["lesson_count_left"]/100}}</td> 
                                            <td> {{@$var["lesson_count_avg"]}} </td> 
                                            <td> {{@$var["lesson_per_month"]}}% </td>
                                            <td> {{@$var["reward"]}} </td> 
                                            <td> {{@$var["other_reward"]}} </td> 


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



