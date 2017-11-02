@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="row">

            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">年</span>
                    <select type="text" class="opt-change " id="id_year" >
                        <option value="2014" >2014</option>
                        <option value="2015"  >2015</option>
                        <option value="2016"  >2016</option>
                        <option value="2017"  >2017</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">月</span>
                    <select type="text" class="opt-change " id="id_month" >
                        <option value="1" >1</option>
                        <option  value="2">2</option>
                        <option  value="3">3</option>
                        <option value="4" >4</option>
                        <option value="5" >5</option>
                        <option value="6" >6</option>
                        <option value="7" >7</option>
                        <option value="8" >8</option>
                        <option  value="9">9</option>
                        <option value="10" >10</option>
                        <option value="11" >11</option>
                        <option value="12" >12</option>
                    </select>
                </div>
            </div>
        </div>

        <hr/>
        <table   class="table table-bordered table-striped"   >
            <thead>
                <tr > <td style="width:65%;">属性</td> <td>值</td></tr>
            </thead>

            <tbody>
                <tr > <td> 新增人数</td> <td>{{$info["new_user_count"]}} </td> </tr>
                <tr > <td> 续费人数</td> <td>{{$info["old_pay_user_count"]}} </td> </tr>
                <tr > <td> 新生消耗课次</td> <td>{{$info["new_lesson_count"]}} </td> </tr>
                <tr > <td> 老生消耗课次</td> <td>{{$info["old_lesson_count"]}} </td> </tr>
                <tr > <td> 试听课次</td> <td>{{$info["test_lesson_count"]}} </td> </tr>
                <tr > <td> 在读人数</td> <td>{{$info["lesson_user_count"]}} </td> </tr>
                <tr > <td> 新生在读人数</td> <td>{{$info["new_lesson_user_count"]}} </td> </tr>
                <tr > <td> 老生在读人数</td> <td>{{$info["old_lesson_user_count"]}} </td> </tr>
            </tbody>
        </table>

        <table   class="table table-bordered table-striped"   >
            <thead>
                <tr > <td style="width:65%;">属性</td> <td>值</td></tr>
                <tr > <td style="width:65%;">开发中</td> <td>开发中</td></tr>
            </thead>

            <tbody>
                <tr> <td>存量学生数期初</td> <td>{{@$new["pay_stu_num"]}}</td> </tr>
                <tr> <td>本月新增学员数</td> <td>{{@$new["new_pay_stu_num"]}}</td> </tr>
                <tr> <td>本月正常结课学员数</td> <td>{{@$new["normal_over_num"]}}</td> </tr>
                <tr> <td>本月退费学员数</td> <td>{{@$new["refund_stu_num"]}}</td> </tr>
                <tr> <td>合同累计退费率</td> <td>{{@$new["refund_rate"]}}</td> </tr>
                <tr> <td>本月在读学员期末数</td> <td>{{@$new["study_num"]}}</td> </tr>
                <tr> <td>本月停课学员期末数</td> <td>{{@$new["stop_num"]}}</td> </tr>
                <tr> <td>本月休学学员期末数 </td> <td>{{@$new["drop_out_num"]}}</td> </tr>
                <tr> <td>本月寒暑假停课学员期末数 </td> <td>{{@$new["vacation_num"]}}</td> </tr>
                <tr> <td>新签未排课合同量(已分配助教) </td> <td>{{@$new["has_ass_num"]}}</td> </tr>
                <tr> <td>新签未排课合同量(未分配助教)</td> <td>{{@$new["no_ass_num"]}}</td> </tr>
                <tr> <td>本月预警学员续费数</td> <td>{{@$new["warning_renow_stu_num"]}}</td> </tr>
                <tr> <td>本月非预警学员续费数 </td> <td>{{@$new["no_warning_renow_stu_num"]}}</td> </tr>
                <tr> <td>本月预警续费率 </td> <td>{{@$new["warning_renow_rate"]}}</td> </tr>
                <tr> <td>本月实际续费率 </td> <td>{{@$new["renow_rate"]}}</td> </tr>
            </tbody>
        </table>

    </section>
@endsection

