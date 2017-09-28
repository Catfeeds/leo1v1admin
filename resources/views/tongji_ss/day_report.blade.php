@extends('layouts.app')
@section('content')

    <section class="content ">
      <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.min.js"></script>

      <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.categories.js"></script>
      <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.time.js"></script>
        <script type="text/javascript" >
         var g_data_ex_list= <?php  echo json_encode($g_date_ex_list); ?> ;
        </script>



        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div  class="col-xs-6 col-md-4">
                    <div class="input-group ">
                        <span class="input-group-addon">销售选择</span>
                        <input class="opt-change form-control" id="id_seller_groupid_ex" />
                    </div>
                </div>


            </div>

        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>项目</td>
                    <td>上班人数</td>
                    <td>新签收入</td>
                    <td>新签人数</td>
                    <td>新签单笔</td>
                    <td>平均外呼量</td>
                    <td>平均通话时长</td>
                    <td>排课量</td>
                    <td>试听数</td>
                    <td>试听失败数</td>
                    <td>失败率</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $total_info_list as $key=> $var )
                    <tr>
                        <td>{{@$var["name"]}} </td>
                        @if ($key<3)
                        <td>{{@$var["seller_count"]}} </td>
                        <td>{{@$var["order_money"]}} </td>
                        <td>{{@$var["order_user_count"]}} </td>
                        <td>{{@$var["pre_price"]}} </td>
                        <td>{{@$var["tq_all_count_avg"]}} </td>
                        <td>{{@$var["tq_duration_count_avg_str"]}} </td>
                        <td>{{@$var["set_lesson_count"]}} </td>
                        <td>{{@$var["test_lesson_count"]}} </td>
                        <td>{{@$var["test_lesson_fail_count"]}} </td>
                        <td>{{@$var["test_lesson_fail_percent"]}}% </td>
                        @else
                        <td>{!!  \App\Helper\Utils::get_diff_color_str( @$var["seller_count"],true)!!} </td>
                        <td>{!!  \App\Helper\Utils::get_diff_color_str( @$var["order_money"],true)!!} </td>
                        <td>{!!  \App\Helper\Utils::get_diff_color_str( @$var["order_user_count"],true)!!} </td>
                        <td>{!!  \App\Helper\Utils::get_diff_color_str( @$var["pre_price"],true)!!} </td>
                        <td>{!!  \App\Helper\Utils::get_diff_color_str( @$var["tq_all_count_avg"],true)!!} </td>
                        <td>{!!  \App\Helper\Utils::get_diff_color_str( @$var["tq_duration_count_avg"],true)!!} </td>
                        <td>{!!  \App\Helper\Utils::get_diff_color_str( @$var["set_lesson_count"],true)!!} </td>
                        <td>{!!  \App\Helper\Utils::get_diff_color_str( @$var["test_lesson_count"],true)!!} </td>
                        <td>{!!  \App\Helper\Utils::get_diff_color_str( @$var["test_lesson_fail_count"],false)!!} </td>
                        <td>{!!  \App\Helper\Utils::get_diff_color_str( @$var["test_lesson_fail_percent"],false)!!} </td>
                        @endif
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <hr/>
        <div class="row  " >
            <div class="col-xs-12 col-md-3 "  >
                <div class="input-group">
                    <span style=" background-color: white; font-size: 20px;" >
                        月度新签目标: {{$month_money_info["month_finish_define_money"]}}

                    </span>
                </div>
            </div>
            <div class="col-xs-12 col-md-3  "  >
                <div class="input-group">
                    <span style=" background-color: white; font-size: 20px;" >
                        完成金额:  {{$month_money_info["month_finish_money"]}}
                    </span>
                </div>
            </div>
            <div class="col-xs-12 col-md-3  "  >
                <div class="input-group">
                    <span style=" background-color: white; font-size: 20px;" >
                        完成率: {{$month_money_info["month_finish_persent"]}}%
                    </span>
                </div>
            </div>
            <div class="col-xs-12 col-md-3  "  >
                <div class="input-group">
                    <span style=" background-color: white; font-size: 20px;" >
                        @if  ($month_money_info["month_left_money"] >0 )
                            缺口金额: <span style ="color: red;" > {{$month_money_info["month_left_money"]}}  </span>
                        @else
                            超额: <span style ="color: green;" > {{  -$month_money_info["month_left_money"]}}  </span>
                        @endif

                    </span>
                </div>
            </div>





        </div>


        <div id="id_pic_new_money" > </div>
        <hr/>
        <div id="id_pic_set_lesson" > </div>

        <hr/>
        <div id="id_pic_test_lesson" > </div>


    </section>


@endsection
