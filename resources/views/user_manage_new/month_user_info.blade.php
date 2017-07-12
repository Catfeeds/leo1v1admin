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
                <tr > <td>属性</td> <td>值</td></tr>
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
    </section>
@endsection

