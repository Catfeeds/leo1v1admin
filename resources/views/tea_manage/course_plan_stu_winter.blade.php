@extends('layouts.app')
@section('content')
    <script src='/page_js/select_teacher_free_time.js?{{@$_publish_version}}'></script>
    <script src='/page_js/set_lesson_time.js?{{@$_publish_version}}'></script>
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/js/svg.js"></script>
    <script type="text/javascript" src="/js/wb-reply/audio.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/page_js/seller_student/common.js?v=121"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" src="/js/svg.js"></script>
    <script type="text/javascript" src="/js/wb-reply/audio.js"></script>
       
    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-3">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <button id="id_plan_regular_course_all" class="btn btn-primary">按常规课表排课</button>
                </div >
                <div class="col-xs-6 col-md-2">
                    <button class="btn" id="id_regular_count_all" data-value="{{$regular_count_all/100}}" >
                        {{$regular_count_all/100}}
                    </button>
                    <button class="btn" id="id_plan_count_all" data-value="{{$plan_count_all/100}}" >
                        {{$plan_count_all/100}}
                    </button>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">学员类型</span>
                        <select class="stu_sel form-control opt-change " id="id_student_type" >
                            
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span >是否完成排课</span>
                        <select class="opt-change form-control " id="id_is_done" >
                            <option value=-1>全部</option>
                            <option value=0>未完成排课</option>
                            <option value=1>已完成排课</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span >助教</span>
                        <input id="id_assistantid"  /> 
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span >学生</span>
                        <input id="id_userid"  /> 
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table class="common-table"> 
            <thead>
                <tr>
                    <td style="width:10px">
                        <a href="javascript:;" id="id_select_all" title="全选">全</a>
                        <a href="javascript:;" id="id_select_other" title="反选">反</a>
                    </td>
                    <td>学生</td>
                    <td>年级</td>
                    <td>常规课表总课时数</td>
                    <td>本周已排课时数</td>                  
                    <td>是否一致</td>                  
                    <td>是否有常规课表外的排课</td>                  
                    <td>是否有与常规课表时间冲突的排课</td>                  
                    <td>状态</td>                  
                    <td>操作 </td>
                </tr>
            </thead>
            <tbody id="id_tbody">
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>
                            <input type="checkbox" class="opt-select-item " />
                        </td>
                        <td>{{@$var["user_nick"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        <td class="regular_total">
                            <a class="regular-info" href="javascript:;" >{{@$var["regular_total"]/100}}</a>
                        </td>
                        <td>
                            <a class="opt-info" href="javascript:;" >{{@$var["lesson_total"]/100}}</a>
                        </td>
                        <td class="is_con">{{@$var["is_con_str"]}}</td>
                        <td class="is_clash_str">{{@$var["is_clash_str"]}}</td>
                        <td class="is_col_str">{{@$var["is_col_str"]}}</td>
                        <td class="status">{{@$var["is_done_str"]}}</td>
                        <td>
                            <div class="row-data"
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-list course_plan" title="按课程包排课"> </a>
                                <a class=" fa-bars btn fa plan_regular_course" title="按常规课程排课"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

