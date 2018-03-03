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
        @if(@$all_normal_count>0)
            <div class="lesson_count">
                <div>签约常规课时:{{ $all_normal_count}}</div>
                <div>已排常规课时:{{ $lesson_normal_count}}</div>
                <div>未排常规课时:{{ $all_normal_count-$lesson_normal_count}}</div>
                <div>已消常规耗课时:{{ $lesson_normal_cost}}</div>
                <div>剩余常规课时:{{ $all_normal_count-$lesson_normal_cost}}</div>
            </div>
        @endif
        @if(@$all_competition_count>0)
            <div class="lesson_count">
                <div>签约竞赛课时:{{ $all_competition_count}}</div>
                <div>已排竞赛课时:{{ $lesson_competition_count}}</div>
                <div>未排竞赛课时:{{ $all_competition_count-$lesson_competition_count}}</div>
                <div>已消竞赛耗课时:{{ $lesson_competition_cost}}</div>
                <div>剩余竞赛课时:{{ $all_competition_count-$lesson_competition_cost}}</div>
            </div>
        @endif
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



