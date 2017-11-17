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
                <div  class="col-xs-6 col-md-4">
                    <div class="input-group ">
                        <span class="input-group-addon">分组选择</span>
                        <input class="opt-change form-control" id="id_seller_groupid_ex" />
                    </div>
                </div>

        </div>

        <hr/>

        <div id="id_pic_user_count" > </div>

            <hr/>
            <table     class="common-table"  >
                <thead>
                    <tr>

                        {!!\App\Helper\Utils::th_order_gen([
                            ["助教 ","ass_nick" ],
                            ["正常上课","valid_count" ],
                            ["课时系数","lesson_rate" ],
                            ["家长调课","family_change_count" ],
                            ["老师调课","teacher_change_count" ],
                            ["设备原因","fix_change_count" ],
                            ["网络原因","internet_change_count" ],
                            ["学生请假","student_leave_count" ],
                            ["老师请假","teacher_leave_count" ],
                            ["课时损失率","lesson_lose_rate" ],
                           ])!!}

                        <td style="display:none">助教id</td>
                        <td> 操作  </td> </tr>
                </thead>
                <tbody>
                    @foreach ( $table_data_list as $var )
                        <tr>
                            <td>{{@$var["ass_nick"]}} </td>
                            <!-- <td>{{@$var["stu_num"]}} </td> -->
                            
                            <td ><a href="javascript:;" class="id_valid_count">{{@$var["valid_count"]/100}}</a> </td>

                            <td>{{@$var["lesson_rate"]}} </td>
                            <td>{{@$var["family_change_count"]/100}} </td>
                            <td>{{@$var["teacher_change_count"]/100}} </td>
                            <td>{{@$var["fix_change_count"]/100 }}</td>
                            <td>{{@$var["internet_change_count"]/100 }}</td>
                            <td>{{@$var["student_leave_count"]/100}}</td>
                            <td>{{@$var["teacher_leave_count"]/100}}</td>
                            <td>{{@$var["lesson_lose_rate"]}}</td>
                            <td>{{@$var["assistantid"]}}</td>

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
