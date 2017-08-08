@extends('layouts.app')
@section('content')
  <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.min.js"></script>

  <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.categories.js"></script>
  <script language="javascript" type="text/javascript" src="/page_js/lib/select_date_range.js"></script>
    <script type="text/javascript" >

     var g_data_ex_list= <?php  echo json_encode($table_data_list); ?> ;
    </script>

    <section class="content ">
        <div class="row">
            <div class="col-xs-12 col-md-4" data-title="时间段">
                <div id="id_date_range"> </div>
            </div>

        </div>

        <hr/>

        <div id="id_pic_user_count" > </div>

            <hr/>
            <table     class="common-table"  >
                <thead>
                    <tr>

                        {!!\App\Helper\Utils::th_order_gen([
                            ["老师 ","teacher_nick" ],
                            ["学生数","stu_num" ],
                            ["正常上课","valid_count" ],
                            ["老师迟到","teacher_come_late_count" ],
                            ["老师旷课","teacher_cut_class_count" ],
                            ["老师调课","teacher_change_lesson" ],
                            ["老师请假","teacher_leave_lesson" ],
                            ["请假比率","lesson_lost_rate" ],
                            ["老师类型","teacher_money_type_str" ],
                            ["入职天数","work_time" ],
                           ])!!}


                        <td> 操作  </td> </tr>
                </thead>
                <tbody>
                    @foreach ( $table_data_list as $var )
                        <tr>
                            <td>{{@$var["teacher_nick"]}} </td>
                            <td>{{@$var["stu_num"]}} </td>
                            <td ><a href="javascript:;" class="id_valid_count">{{@$var["valid_count"]}}</a> </td>
                            <td>{{@$var["teacher_come_late_count"]}} </td>
                            <td>{{@$var["teacher_cut_class_count"]}} </td>
                            <td>{{@$var["teacher_change_lesson"]}} </td>
                            <td>{{@$var["teacher_leave_lesson"]}}</td>
                            <td>{{@$var["lesson_leavel_rate"]}}%</td>

                            <td>{{@$var["teacher_money_type_str"] }}</td>
                            <td>{{@$var["work_time"]}}天</td>

                            <td>
                                <div class="row-data"
                                    {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

    </section>

@endsection
