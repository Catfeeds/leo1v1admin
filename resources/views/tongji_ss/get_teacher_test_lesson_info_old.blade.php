@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <link rel='stylesheet' href='/css/fullcalendar.css' />
    <script src='/js/moment.js'></script>
    <script src='/js/fullcalendar.js'></script>
    <script src='/js/lang-all.js'></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time.js"></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time_new.js"></script>

    <section class="content ">
        <div>
            <div class="row ">
                <div class="col-xs-12 col-md-4"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >老师 </span>
                        <input type="text" value=""  class="opt-change"  id="id_teacherid"  placeholder="" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" style="display:none">
                    <div class="input-group ">
                        <span >工资分类</span>
                        <select id="id_teacher_money_type" class ="opt-change" ></select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >教师分类</span>
                        <select id="id_identity" class ="opt-change" ></select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >科目</span>
                        <select id="id_subject" class ="opt-change" ></select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >新教师筛选</span>
                        <select id="id_is_new_teacher" class ="opt-change" >
                            <option value="1"> 全部老师</option>
                            <option value="2"> 最新入职老师</option>
                            <option value="3"> 最近一周入职老师</option>
                            <option value="4"> 最近两周入职老师</option>
                            <option value="5"> 最近30天入职老师</option>
                        </select>
                    </div>
                </div>

                
               
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    {!!\App\Helper\Utils::th_order_gen([
                        ["老师","nick" ],
                        ["工资分类","teacher_money_type_str" ],
                        ["等级","level_str" ],
                        ["学校","school" ],
                        ["入职时间","create_time_str" ],
                        ["入职时长(天)","work_day" ],
                        ["当前常规课学生数","regular_stu_num" ],
                        ["今后三周试听课数","test_lesson_num" ],
                        ["本周剩余试听课数","test_lesson_num_week" ],
                        ["试听课","all_lesson" ],
                        ["签约数","have_order" ],
                        ["签约率","order_per" ],
                       ])  !!}

                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["teacher_money_type_str"]}} </td>
                        <td>{{@$var["level_str"]}} </td>
                        <td>{{@$var["school"]}} </td>
                        <td> {{@$var["create_time_str"]}}</td>
                        <td>{{@$var["work_day"]}} </td>
                        <td>{{@$var["regular_stu_num"]}} </td>
                        <td>{{@$var["test_lesson_num"]}} </td>
                        <td>{{@$var["test_lesson_num_week"]}} </td>
                        <td> {{@$var["all_lesson"]}}</td>
                        <td> {{@$var["have_order"]}}</td>
                        <td>
                            @if (isset($var["order_per"]))
                                {{@$var["order_per"]}}%   
                            @endif
                        </td>
                       
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
           
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection


