@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="row">
            <div class="col-xs-12 col-md-4">
                <div class="input-group ">
                    <span class="input-group-addon">课时区间:</span>
                    <input type="text" class=" form-control opt-change " id="id_lesson_count_start" />
                    <span class="input-group-addon">-</span>
                    <input type="text" class=" form-control opt-change "  id="id_lesson_count_end" />
                </div>
            </div>
            <div class="col-xs-12 col-md-4">
                <div class="input-group ">
                    <span class="input-group-addon">时间:</span>
                    <input type="text" class=" form-control " id="id_start_time" />
                    <span class="input-group-addon">-</span>
                    <input type="text" class=" form-control "  id="id_end_time" />
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">年级</span>
                    <select class="stu_sel form-control " id="id_grade" >
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">结课状态</span>
                    <select class="stu_sel form-control" id="id_type" >
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2" >
                <div class="input-group ">
                    <span >助教</span>
                    <input id="id_assistantid"  /> 
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table">
            <thead>
                <tr>
                    <td >uid </td>
                    <td >昵称</td>
                    <td >年级</td>
                    <td >助教</td>
                    <td >总课时数</td> 
                    <td >剩余课时数</td> 
                    <td >最后一次上课时间</td> 
                    <td >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
				    <tr>
                        <td >{{$var["userid"]}}</td>
                        <td >{{$var["nick"]}}</td>
                        <td >{{$var["grade"]}}</td>
                        <td >{{$var["assistant_nick"]}}</td>
                        <td >{{$var["lesson_count_all"]/100}}</td>
                        <td >{{$var["lesson_count_left"]/100}}</td>
                        <td >{{$var["last_lesson_time"]}}</td>
                        <td >
                            <div class="btn-group"
                                 data-userid="{{$var["userid"]}}" ;
                            >
                                <a class="fa-user opt-user " title="个人信息" ></a>
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

