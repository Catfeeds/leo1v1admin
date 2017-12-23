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
    <script type="text/javascript" >
     var g_data= <?php  echo json_encode($arr); ?> ;
    </script>



    <section class="content " id="id_content" style="max-width:1200px;">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-12 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">历史记录</span>
                        <select class="opt-change form-control" id="id_is_history_data">
                            <option value="1" >是</option>
                            <option value="2" >否</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-12 col-md-1 col-md-offset-5">
                    <div><a href="javascript:;" id="download_data" class="fa fa-download">导出</a></div>
                </div>

            </div>
            <hr/>

            <div class="row">
                <div class="col-xs-12 col-md-12">
                    <div class="panel panel-warning"  >
                        <!-- 报表表头  begin -->
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
                        <!-- 报表表头  end  -->

                        <!-- 总统计项  begin -->
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>例子数</td>
                                        <td>有效例子</td>
                                        <td>已拨打例子</td>
                                        <td>有效例子数占比</td>
                                        <td>无效资源</td>
                                        <td>无效例子数占比</td>
                                        <td>未接通</td>
                                        <td>未接通例子数占比</td>
                                    </tr>
                                </thead>
                                <tbody id="id_lesson_count_list">
                                    <tr>
                                        <td> {{@$arr['total_income']}}</td> 
                                        <td> {{@$arr['person_num']}}</td> 
                                        <td> {{@$arr['total_price_thirty']}} </td> 
                                        <td> {{@$arr['person_num_thirty']}}</td> 
                                        <td> {{@$arr['person_num_thirty_per']}}</td> 
                                        <td> {{@$arr['average_person_effect']}}</td> 
                                        <td> {{@$arr['contract_per']}} </td> 
                                        <td> {{@$arr['month_kpi_per']}}% </td> 
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <!-- 总统计项  end  -->
                    </div>
                </div>

                <!--年级统计  begin -->
                <div class="col-xs-12 col-md-12">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            年级统计
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>例子数</td>
                                        <td>高中例子</td>
                                        <td>高中例子数占比</td>
                                        <td>初中例子</td>
                                        <td>初中例子数占比</td>
                                        <td>小学例子</td>
                                        <td>小学例子数占比</td>
                                    </tr>
                                </thead>
                                <tbody id="id_lesson_count_list">
                                    <tr>
                                        <td> {{@$arr['total_income']}}</td> 
                                        <td> {{@$arr['person_num']}}</td> 
                                        <td> {{@$arr['total_price_thirty']}} </td> 
                                        <td> {{@$arr['person_num_thirty']}}%</td> 
                                        <td> {{@$arr['person_num_thirty_per']}}</td> 
                                        <td> {{@$arr['average_person_effect']}}</td> 
                                        <td> {{@$arr['contract_per']}} </td> 
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- 年级统计  end -->


                <!-- 微信运营 begin -->
                <div class="col-xs-12 col-md-6">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            微信运营
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>微信运营例子</td>
                                        <td>新签数</td>
                                        <td>新签金额</td>
                                    </tr>
                                </thead>
                                <tbody id="id_lesson_count_list">
                                    <tr>
                                        <td> %{{@$arr['total_income']}}</td> 
                                        <td> {{@$arr['person_num']}}</td> 
                                        <td> {{@$arr['total_price_thirty']}} </td> 
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- 微信运营  end -->

                <!-- 微课统计  begin -->
                <div class="col-xs-12 col-md-6">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            微课统计
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>微课</td>
                                        <td>微课例子</td>
                                        <td>微课签单数</td>
                                        <td>微课签单金额</td>
                                    </tr>
                                </thead>
                                <tbody id="id_lesson_count_list">
                                        <tr>
                                            <td class="panel-yellow" > %{{@$arr['lesson_target']}}</td>
                                            <td class="panel-yellow" > {{@$arr['total_student']}} </td>
                                            <td>{{@$arr['lesson_consume_target']}} </td>
                                            <td> {{@$arr['lesson_consume']}}</td>
                                        </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- 微课统计  end  -->

                <!-- 公众号统计  begin -->
                <div class="col-xs-12 col-md-4">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                           公众号统计 
                        </div>
                        <div class="panel-body">

                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>公众号例子</td>
                                        <td>公众号签单数</td>
                                        <td>公众号签单金额</td>
                                    </tr>
                                </thead>
                                <tbody id="id_lesson_count_list">
                                    <tr>
                                        <td class="panel-yellow" > %{{@$arr['read_num']}}  </td>
                                        <td class="panel-yellow" > {{@$arr['stop_student']}}</td>
                                        <td class="panel-yellow" > {{@$arr['drop_student']}} </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- 公众号统计  end  -->

                <!-- 人工统计  begin -->
                <div class="col-xs-12 col-md-8">
                    <div class="panel panel-warning"  >
                        <div class="panel-heading center-title ">
                            人工统计 
                        </div>
                        <div class="panel-body">
                            <table   class="table table-bordered "   >
                                <thead>
                                    <tr>
                                        <td>平均单线索价格</td>
                                        <td>公开课次数</td>
                                        <td>软文发布篇数</td>
                                        <td>群维护次数</td>
                                        <td>微博微信发布数</td>
                                    </tr>
                                </thead>
                                <tbody id="id_lesson_count_list">
                                    <tr>
                                        <td class="panel-yellow" >投放金额/例子总量(去重)</td>
                                        <td class="panel-yellow" ></td>
                                        <td class="panel-yellow" >人工统计</td>
                                        <td class="panel-yellow" >人工统计</td>
                                        <td>人工统计</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- 人工统计  end  -->
            </div>
        </div>
    </section>
@endsection
