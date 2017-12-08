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
                    <td>id</td>
                    <td>名字</td>
                    <td>属性</td>
                    <td>常规课次数</td>
                    <td>老师迟到次数</td>
                    <td>老师有效迟到次数</td>
                    <td>总调课次数</td>
                    <td>老师调课次数</td>
                    <td>学生调课次数</td>
                    <td>总请假次数</td>
                    <td>老师请假次数</td>
                    <td>学生请假次数</td>
                   



                     <td> 操作</td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $list as $k=>$var )
                    <tr>
                        <td>{{@$var["teacherid"]}} </td>
                        <td>{{@$var["realname"]}} </td>
                        <td>{{@$var["identity_str"]}} </td>

                        <td class="reg_num"> {{@$var["reg_num"]}} </td>
                        <td class="late_num"> {{@$var["late_num"]}} </td>
                        <td class="invalid_late_num"> {{@$var["invalid_late_num"]}} </td>
                        <td class="all_change_num"> {{@$var["all_change_num"]}} </td>
                        <td class="change_num"> {{@$var["change_num"]}} </td>
                        <td class="stu_change_num"> {{@$var["stu_change_num"]}} </td>
                        <td class="all_leave_num"> {{@$var["all_leave_num"]}} </td>
                        <td class="leave_num"> {{@$var["leave_num"]}} </td>
                        <td class="stu_leave_num"> {{@$var["stu_leave_num"]}} </td>
                       

                        <td>
                            <div class="row-data" data-teacherid="{{$var["teacherid"]}}" data-subject="{{$var["teacherid"]}}" >
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
