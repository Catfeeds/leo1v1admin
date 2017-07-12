@extends('layouts.app')
@section('content')

    <script src='/js/moment.js'></script>
    <link rel='stylesheet' href='/css/fullcalendar.css' />


    <script src='/js/fullcalendar.js'></script>
    <script src='/js/lang-all.js'></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script src='/page_js/select_teacher_free_time.js'></script>
    <script src='/page_js/set_lesson_time.js'></script>
    <script src='/js/lang-all.js'></script>
    <!-- <script type="text/javascript" src="/page_js/stu_manage/lesson_plan.js"></script> -->
    <script>
     g_arrange_flag = 0;
    </script>

    <style>
     .fc-event {
         border-radius:0px;
     }
    </style>


<section class="content">
    <div  class="div-cur-info div-only-one" >
        <div style="display:none;">
            <a href="javascript:;" class="btn btn-warning"  id="id_arrange_course"  >开始排课 </a>

            <a href="/stu_manage/teaching_plan?sid=[$sid]&nick=[$nick]" class=" btn btn-warning course_plan">课时计划>> </a>
            <a href="javascript:;" id="id_goto_custom_lesson" class="arrange_tess btn btn-warning  ">自定义排课>></a>
        </div>
        <hr />
        <div id='calendar' ></div>
    </div>
    
    <div class="class_ing  div-only-one" style="display:none;">
        <span style="font-size:25px" >可排课程 --
            <a class="btn btn-warning" href="javascript:;" id="id_return_cur_info"><li class="fa fa-reply"></li>返回课程表</a>
        </span>
        <div class="cont">
            <table   class="table table-bordered table-striped can_plan "   >
                <thead>
                    <tr>
                        <td class="remove-for-not-xs"></td>
                        <td >课程id</td>
                        <td >老师</td>
                        <td >开始</td>
                        <td >结束</td>
                        <td >课次</td>
                        <td class="remove-for-xs" >操作</td>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
    </div>

    <!--老师空闲时间-->
    <div class=" class_end div-only-one "  style="display:none" >
        <span style="font-size:25px">
            老师可排课时间
            <a href="#" class="btn btn-warning"  id="id_return_lesson_list" > <li class="fa fa-reply"></li>返回</a>
        </span> 
        <div id='calendar02' ></div>
    </div>
    

    <div id="id_dlg_cancel_reason" style="display:none">
        <td>取消原因：</td>
        <td><input type="text" class="cancel_cause" id="id_cancel_reason"/></td>
    </div>

    <div id="id_dlg_change_time" style="display:none;" >
        <div class="row">

            <div class="col-xs-0 col-md-3">
            </div>
            <div class="col-xs-12 col-md-6">
                <div class="input-group ">
                    <span class="input-group-addon"> 课程:</span>
                    <span class="form-control" id="id_courseid"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-0 col-md-3">
            </div>

            <div class="col-xs-12 col-md-6">
                <div class="input-group ">
                    <span class="input-group-addon"> 课次:</span>
                    <span class="form-control" id="id_lesson_num"></span>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-0 col-md-3">
            </div>

            <div class="col-xs-12 col-md-6">
                <div class="input-group ">
                    <span class="input-group-addon">日期:</span>
                    <span class="form-control"  id="id_date"></span>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-0 col-md-3">
            </div>

            <div class="col-xs-12 col-md-6">
                <div class="input-group ">
                    <span class="input-group-addon">时间:</span>
                    <input type="text" id="id_time_start" class="form-control"/>
                    <span class="input-group-addon">-</span>
                    <input type="text" id="id_time_end" class="form-control"/>
                </div>
            </div>
        </div>

    </div>

  </section>
@endsection

