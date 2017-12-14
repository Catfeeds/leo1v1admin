@extends('layouts.app')
@section('content')

       
   
   

    
    <section class="content ">

        <div>
            <div class="row" >
                <div class="col-xs-6 col-md-2">
                    <button id="id_get_money" class="btn btn-primary">刷新</button>
                </div >
               

            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>老师</td>
                    <td>老师试听课数(总)</td>
                    <td>老师试听课数(有效)</td>
                    <td>老师试听课迟到次数</td>
                    <td>老师试听课旷课次数</td>
                    <td>老师常规课次数(总)</td>
                    <td>老师常规课次数(有效)</td>
                    <td>老师迟到次数</td>
                    <td>老师有效迟到次数</td>
                    <td>总调课次数</td>
                    <td>老师调课次数</td>
                    <td>总请假次数</td>
                    <td>老师请假次数</td>
                    <td>老师旷课次数</td>                                                      

                    <td> 操作</td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $list as $k=>$var )
                    <tr>
                        
                        <td>{{ $var["realname"] }}</td>
                        <td class="all_test_num">  </td>
                        <td class="test_num">  </td>
                        <td class="test_late_num">  </td>
                        <td class="test_kk_num"> </td>
                        <td class="all_reg_num"> </td>
                        <td class="reg_num"> </td>
                        <td class="late_num">  </td>
                        <td class="invalid_late_num">  </td>
                        <td class="all_change_num">  </td>
                        <td class="change_num">  </td>
                        <td class="all_leave_num"> </td>
                        <td class="leave_num"> </td>
                        <td class="kk_num"></td>
                        

                        <td>
                            <div class="row-data" data-teacherid="{{ $var["teacherid"] }}" data-start="{{ $start_time }}" data-end="{{ $end_time }}">
                                <a class="fa fa-list course_plan" title="按课程包排课"> </a>
                            </div>

                        </td>


                    </tr>

                @endforeach



            </tbody>
        </table>


        @include("layouts.page")
    </section>

  


@endsection
