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
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">工资类型</span>
                    <select class="opt-change form-control" id="id_teacher_money_type" >
                    </select>
                </div>
            </div>

        </div>




        <hr/>

        <div id="id_pic_user_count" > </div>

            <hr/>
            <table     class="common-table"  >
                <thead>
                    <tr>



                        <td>编号</td>
                        {!!\App\Helper\Utils::th_order_gen([
                            ["老师 ","teacher_nick" ],
                            ["学生数","stu_num" ],
                            ["正常上课","valid_count" ],
                            ["老师迟到","teacher_come_late_count" ],
                            ["迟到比率","lesson_come_late_rate" ],
                            ["老师旷课","teacher_cut_class_count" ],
                            ["旷课比率","lesson_cut_class_rate" ],
                            ["老师调课","teacher_change_lesson" ],
                            ["调课比率","lesson_change_rate" ],
                            ["老师请假","teacher_leave_lesson" ],
                            ["请假比率","lesson_leavel_rate" ],
                            ["老师类型","teacher_money_type_str" ],
                            ["入职天数","work_time" ],
                           ])!!}


                        <td> 操作  </td> </tr>
                </thead>
                <tbody>
                    @foreach ( $table_data_list as $var )
                        <tr>
                            <td>{{@$var["index_num"]}}</td>
                            <td>
                                <a  href="/human_resource/index_ass?teacherid={{@$var["teacherid"]}}" target="_blank">
                                    {{@$var["teacher_nick"]}}
                                </a>
                            </td>
                            <td>{{@$var["stu_num"]}} </td>
                            <td >{{@$var["valid_count"]}} </td>
                            <td class="show_detail" date-teacherid={{@$var['teacherid']}} date-lesson_cancel_reason_type="23">
                                <a>{{@$var["teacher_come_late_count"]}}</a>
                            </td>
                            <td>{{@$var["lesson_come_late_rate"]}}% </td>

                            <td class="show_detail" date-teacherid={{@$var['teacherid']}} date-lesson_cancel_reason_type="21" >
                                <a>{{@$var["teacher_cut_class_count"]}}</a>
                            </td>
                            <td>{{@$var["lesson_cut_class_rate"]}}% </td>

                            <td class="show_detail" date-teacherid={{@$var['teacherid']}} date-lesson_cancel_reason_type="2">
                                <a>{{@$var["teacher_change_lesson"]}}</a>
                            </td>
                            <td>{{@$var["lesson_change_rate"]}}% </td>

                            <td class="show_detail" date-teacherid={{@$var['teacherid']}} date-lesson_cancel_reason_type="12">
                                <a>{{@$var["teacher_leave_lesson"]}}</a>
                            </td>

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


            <div style="display:none;" >
                <div id="id_assign_log">
                    <table   class="table table-bordered "   >
                        <tr>  <th> 老师 <th>类型 <th>上课时段 <th>年级 <th>科目 <th>学生 <th>助教 <th>课时数 <th>课时确认</tr>
                            <tbody class="data-body">
                            </tbody>
                    </table>
                </div>
            </div>


    </section>

@endsection
