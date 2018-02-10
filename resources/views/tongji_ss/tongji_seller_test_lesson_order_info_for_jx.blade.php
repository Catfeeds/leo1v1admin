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
                <div  class="col-xs-6 col-md-4">
                    <div class="input-group ">
                        <span class="input-group-addon">申请人选择</span>
                        <input class="opt-change form-control" id="id_seller_groupid_ex" />
                    </div>
                </div>

            </div>
            <hr/>



            <div class="row">
                <div class="col-xs-6 col-md-3">
                    <div align="center"> 年级 </div>
                <div id="id_grade_pic" class="demo-placeholder" style="height:400;"></div>
                    <table   class="table table-bordered table-striped"   >
                        <thead>


                            <!-- <tr>  <td> 年级  </td><td> 试听成功数</td><td> 签单数</td><td> 签单率</td> </tr>
                               -->
                            {!!\App\Helper\Utils::th_order_gen([
                                ["年级","name_grade" ],
                                ["试听成功数","num_grade" ],
                                ["试听转化数","order_grade" ],
                                ["试听转化率","per_grade"],
                               ])  !!}

                        </thead>

                        <tbody>
                            @foreach($grade_arr as $v)
                                <tr>
                                    <td>{{$v["name"]}}</td>
                                    <td>{{$v["num"]}}</td>
                                    <td>{{$v["order"]}}</td>
                                    <td>{{$v["per"]}}%</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="col-xs-6 col-md-3">
                    <div align="center"> 科目</div>
                <div id="id_subject_pic" class="demo-placeholder" style="height:400;"></div>
                    <table   class="table table-bordered table-striped"   >
                        <thead>
                            <!-- <tr>  <td> 科目  </td><td> 试听成功数</td><td> 签单数</td><td> 签单率</td> </tr>
                            -->
                            {!!\App\Helper\Utils::th_order_gen([
                                ["科目","name_subject" ],
                                ["试听成功数","num_subject" ],
                                ["试听转化数","order_subject" ],
                                ["试听转化率","per_subject"],
                               ])  !!}

                        </thead>
                        <tbody>
                            @foreach($subject_arr as $v)
                                <tr>
                                    <td>{{$v["name"]}}</td>
                                    <td>{{$v["num"]}}</td>
                                    <td>{{$v["order"]}}</td>
                                    <td>{{$v["per"]}}%</td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                </div>
                <div class="col-xs-6 col-md-3">
                    <div align="center">试卷</div>
                <div id="id_has_pad_pic" class="demo-placeholder" style="height:400;"></div>
                <table   class="table table-bordered table-striped"   >
                    <thead>
                        <!-- <tr>  <td> 试卷  </td><td> 试听成功数</td><td> 签单数</td><td> 签单率</td> </tr> -->

                        {!!\App\Helper\Utils::th_order_gen([
                            ["试卷","name_paper" ],
                            ["试听成功数","num_paper" ],
                            ["试听转化数","order_paper" ],
                            ["试听转化率","per_paper"],
                           ])  !!}

                    </thead>

                        <tbody>
                            @foreach($paper_arr as $v)
                                <tr>
                                    <td>{{$v["name"]}}</td>
                                    <td>{{$v["num"]}}</td>
                                    <td>{{$v["order"]}}</td>
                                    <td>{{$v["per"]}}%</td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
                <div class="col-xs-6 col-md-3">
                    <div align="center"> 地区</div>
                <div id="id_area_pic" class="demo-placeholder" style="height:400;"></div>
                    <table   class="table table-bordered table-striped"   >
                        <thead>

                            <!-- <tr>  <td> 地区 </td><td> 试听成功数</td><td> 签单数</td><td> 签单率</td> </tr> -->
                            {!!\App\Helper\Utils::th_order_gen([
                                ["地区","name_location" ],
                                ["试听成功数","num_location" ],
                                ["试听转化数","order_location" ],
                                ["试听转化率","per_location"],
                               ])  !!}



                        </thead>
                        <tbody>
                            @foreach($location_arr as $v)
                                <tr>
                                    <td>{{$v["name"]}}</td>
                                    <td>{{$v["num"]}}</td>
                                    <td>{{$v["order"]}}</td>
                                    <td>{{$v["per"]}}%</td>
                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>


            </div>


            <div class="row">
                <div class="col-xs6 col-md-3">

                    <div align="center">渠道转化率</div>
                    <table class="common-table   ">
                        <thead>
                            <tr>
                                <td >key1</td>
                                <td >key2</td>
                                <td >key3</td>
                                <td >渠道</td>
                                <td >试听成功数</td>
                                <td >试听成功数(去重)</td>
                                <td >试听转化数</td>
                                <td >试听转化率</td>
                                <td >签单数</td>
                                <td >签单率</td>

                                <td >操作</td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($origin_info as $var)
                                <tr class="{{$var["level"]}}">
                                    <td data-class_name="{{$var["key1_class"]}}" class="key1" >{{$var["key1"]}}</td>
                                    <td  data-class_name="{{$var["key2_class"]}}" class=" key2  {{$var["key1_class"]}}  {{$var["key2_class"]}} " >{{$var["key2"]}}</td>
                                    <td data-class_name="{{$var["key3_class"]}}" class="key3  {{$var["key2_class"]}} {{$var["key3_class"]}}  "  >{{$var["key3"]}}</td>
                                    <td data-class_name="{{$var["key4_class"]}}" class="key4   {{$var["key3_class"]}} {{$var["key4_class"]}}"  >{{$var["key4"]}}</td>
                                    <td>{{@$var["succ_test_lesson_count"]}}</td>
                                    <td>{{@$var["dic_succ_test_lesson_count"]}}</td>
                                    <td>{{@$var["order_count"]}}</td>

                                    @if(@$var["order_count"]>0)
                                        @if(@$var['succ_test_lesson_count']>0)
                                            <td>{{@number_format($var["order_count"]/@$var["succ_test_lesson_count"],2)*100}}%</td>
                                        @else
                                            <td>0%</td>
                                        @endif
                                    @else
                                        <td>0%</td>
                                    @endif
                                    <td>{{@$var["order_num"]}}</td>

                                    @if(@$var["order_num"]>0)
                                        @if(@$var['dic_succ_test_lesson_count']>0)
                                            <td>{{@number_format($var["order_num"]/@$var["dic_succ_test_lesson_count"],2)*100}}%</td>
                                        @else
                                            <td>0%</td>
                                        @endif
                                    @else
                                        <td>0%</td>
                                    @endif


                                    <td>
                                        <div></div>
                                    </td>

                                </tr>
                            @endforeach

                        </tbody>
                    </table>

                </div>
            </div>

    </section>

@endsection
