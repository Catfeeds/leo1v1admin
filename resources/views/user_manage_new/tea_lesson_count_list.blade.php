@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="input-group ">
                    <span class="input-group-addon">时间:</span>
                    <input type="text" class=" form-control " id="id_start_time" />
                    <span class="input-group-addon">-</span>
                    <input type="text" class=" form-control "  id="id_end_time" />
                </div>
            </div>
            <div class="col-xs-12 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">老师工资类型:</span>
                    <select type="text" class="form-control" id="id_teacher_money_type">
                    </select>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td >老师id</td>
                    <td >昵称</td>
                    <td >老师工资类型</td>
                    <td >消耗课时数</td> 
                    <td >课次统计</td> 
                    <td >学生数</td> 
                    <td >阶梯1</td> 
                    <td >阶梯2</td> 
                    <td >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
				    <tr>
                        <td >{{$var["teacherid"]}}</td>
                        <td >{{$var["teacher_nick"]}}</td>
                        <td >{{$var["teacher_money_type_str"]}}</td>
                        <td >{{$var["lesson_count"]/100}}</td>
                        <td >{{$var["count"]}}</td>
                        <td >{{$var["stu_num"]}}</td>
                        <td ></td> 
                        <td ></td> 
                        <td >
                            <div class="btn-group"
                                 data-teacherid="{{$var["teacherid"]}}" 
                                 >
                                <a class=" fa-list-alt opt-show-lesson-list" title="显示对应课程列表"></a>
                            </div>
                        </td>
				    </tr>
                @endforeach
            </tbody>
        </table>
            @include("layouts.page")
    </section>
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
@endsection
