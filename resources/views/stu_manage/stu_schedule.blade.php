@extends('layouts.stu_header')
@section('content')
    <script src='/js/moment.js'></script>
    <link rel='stylesheet' href='/css/fullcalendar.css' />
    <script type="text/javascript" src='/page_js/select_teacher_free_time.js'></script>
    <script type="text/javascript" src='/page_js/set_lesson_time.js'></script>


    <script src='/js/fullcalendar.js'></script>
    <script src='/js/lang-all.js'></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <style>
     .fc-event {
         border-radius:0px;
     }
    </style>
    <section class="content">
        <div>签约课时:{{ $all_count }}</div>
        <div>已排课时:{{ $has_lesson_count }}</div>
        <div>未排课时:{{ $all_count-$has_lesson_count}}</div>
        <div>已消耗课时:{{ $cost_count }}</div>
        <div>剩余课时:{{ $all_count-$cost_count}}</div>

        <div class="row"  >
            <div class="col-xs-6 col-md-2 ">
                <div class="input-group ">
                    <span class="input-group-addon">老师</span>
                    <input class="opt-change form-control" id="id_teacherid" />
                </div>
            </div>
            <div class="col-md-1 col-xs-3">
                <button class="btn btn-primary add_player " >排课</button>
            </div>
            <div class="col-md-2 col-xs-3">
                <button class="btn btn-primary  add_player " >周模板课表</button>
            </div>
            <div class="col-md-2 col-xs-0">
                <div class="input-group ">
                    <span>科目</span>
                    <select id="id_lesson_subject" class="course_select_type">
                        <option value="-1">全部</option>
                        @foreach($stu_subject as $sub_key=>$sub_val)
                            <option value="{{ $sub_key }}">{{ $sub_val }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-2 col-xs-0">
                <div class="input-group ">
                    <span>类型</span>
                    <select id="id_has_question_user" class="course_select_type">
                        <option value="-1">全部</option>
                        <option value="0">常规课</option>
                        <option value="1">奥数课</option>
                    </select>
                </div>
            </div>
        </div>
        <div>
            @foreach($tea_list as $tea_key=>$tea_val)
                <div class="trial_teacher" data-teacherid="{{ $tea_key }}">{{ $tea_val['realname'] }}</div>
            @endforeach
        </div>
        <hr/>
        <div id='id_calendar'></div>
    </section>
@endsection



