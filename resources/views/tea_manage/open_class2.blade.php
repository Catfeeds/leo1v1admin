@extends('layouts.app')
@section('content')
<script type="text/javascript">
 g_qiniu_domain = "{{$qiniu_domain}}";
</script>
<script type="text/javascript" src="/page_js/set_lesson_time.js"></script>
<script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
<script type="text/javascript" src="/js/qiniu/ui.js"></script>
<script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
<script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
<script type="text/javascript" src="/js/jquery.md5.js"></script>
<script type="text/javascript" src="/js/svg.js"></script>
<script type="text/javascript" src="/js/wb-reply/audio.js"></script>
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <section class="content">
        <div class="row">

            <div class="col-xs-6 col-md-1">
                <div class="input-group ">
                    <button id="id_query" > 查询 </button>
                </div>
            </div>
            <div class="col-xs-12 col-md-4" data-title="时间段">
                <div id="id_date_range"> </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span >上课状态</span>
                    <select id="id_lesson_status">
                        <option value="-1">全部</option>
                        <option value="2">已上</option>
                        <option value="0">未上</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span >老师</span>
                <input id="id_search_teacher" class="opt-change"/> 
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span>类别</span>
                <select id="id_search_lesson_type">
                    <option value="-1">全部</option>
                    <option value="1001">普通公开课(A)</option>
                    <option value="1002">普通公开课(B)</option>
                    <option value="2001">答疑课程</option>
                    <option value="4001">机器人课程</option>
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-md-4">
            <div class="input-group ">
                <a id="id_add_lesson" class="btn btn-warning"><li class="fa fa-plus">课次</li> </a>
                <a id="id_add_open_course" class="btn btn-warning"><li class="fa fa-plus">课程</li></a>
                <a id="id_add_robot_lesson" class="btn btn-warning"><li class="fa fa-plus">机器人</li></a>
                <a id="id_add_lesson_by_excel" class="btn btn-warning"><li class="fa fa-plus">xls一键添加</li></a>
            </div>
        </div>
    </div>
    <hr/>
    <table class="common-table">
        <thead>
        <tr>
            <td >lessonid</td>
            <td >上课状态</td>
            <td >上课标题</td>
            <td >年级</td>
            <td >课次/总课次</td>
            <td >到课率</td>
            <td >上课时间</td>
            <td >课程类别</td>
            <td >机器课 源课堂ID</td>
            <td >机器课程设置</td>
            <td >上课老师</td>
            <td >课件状态</td>
            <td style="widtd:100px;" class=" remove-for-xs  " >操作</td>
        </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
                <tr>
                    <td>{{$var["lessonid"]}}</td>
                    <td>{{$var["lesson_status"]}}</td>
                    <td>{{$var["course_name"]}}</td>
                    <td>{{$var["grade_str"]}}</td>
                    <td>{{$var["lesson_num"]}}</td>
                    <td>{{$var["stu_join"]}}/{{$var["stu_total"]}}</td>
                    <td>{{$var["lesson_time"]}}</td>
                    <td>{{$var["lesson_type_str"]}}</td>
                    <td>{{$var["from_lessonid"]}}</td>
                    <td>{{$var["can_set"]}}</td>
                    <td>{{$var["nick"]}}</td>
                    <td>{{$var["cw_status"]}}</td>
                    <td class="remove-for-xs">
                        <div class="btn-group"
                             data-courseid="{{$var["courseid"]}}"
                             data-lessonid="{{$var["lessonid"]}}"
                             data-url="{{$var["tea_cw_url"]}}"
                             data-itemid="{{$var["lessonid"]}} "
                             data-lesson_type="{{$var["lesson_type"]}}"
                        >
                            <a href="javascript:;" title="课件上传" class="btn fa fa-upload opt-upload"></a>
                            <a href="javascript:;" title="课件下载" class="btn fa fa-download opt-download"></a>

                            <a href="javascript:;" title="学生列表" class="btn fa fa-group opt-stu-list"></a>
                            <a href="javascript:;" title="设置语源课堂ID" class="btn fa fa-chain opt-from-lessonid"></a>
                            <a href="javascript:;" title="设置机器课程权限" class="btn fa fa-unlock-alt opt-can-set"></a>
                            <a href="javascript:;" title="设置课程名" class="btn fa fa-edit
                                                                             opt-set-course_name"></a>
                            <a href="javascript:;" title="设置上课时间" class="btn fa fa-clock-o opt-set-time"></a>
                            <a href="javascript:;" title="更改老师" class="btn fa fa-edit opt-change-teacher"></a>
                            <a href="javascript:;" title="删除课次" class="btn fa fa-trash-o opt-del"></a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @include("layouts.page")
    </section>
    <div style="display:none;" >
        <select id="id_enter_type">
            <option value="1">逐个加入</option>
            <option value="2">条件判断</option>
        </select>

        <select id="id_can_set">
            <option value="1">不可设置</option>
            <option value="2">可设置</option>
        </select>
        
        <select id="id_lesson_type">
            <option value="1001">可举手</option>
            <option value="1002">不可举手</option>
            <option value="1003">1v1</option>
            <option value="2001">答疑</option>
            <option value="4001">机器课程</option>
        </select>

        <select id="id_subject_list">
        </select>
        
        <select id="id_grade_list">
        </select>
        <input ="id_courseid" />
        <select id="id_contract_name">
            <option value="-1">请选择</option>
            @foreach ($table_data_list as $var)
                <option value="{{$var["courseid"]}}">{{$var["lesson_intro"]}}-{{$var["course_name"]}}</option>
            @endforeach
        </select>
        <div id="id_user_list" >
            <div class="row">
                <div class="col-xs-2 col-md-2">
                    <div class="input-group ">
                        <select id="id_role"  >
                            <option value="1" >学生</option>
                            <option value="2" >家长</option>
                            <option value="4" >老师</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-5 col-md-5">
                    <div class="input-group ">
                        <input type="text" value="" id="id_search_phone"  placeholder="注册账号" />
                        <div class="input-group-btn">
                            <button id="id_query_user" type="submit" class="btn btn-primary"><i class="fa fa-search"></i></button> 
                        </div>
                    </div>
                </div>
                <div class="col-xs-5 col-md-5">
                    <div class="input-group ">
                        <span style=" width:40px">用户:</span>
                        <span id="id_user_name" style="width:60%"></span>
                        <div class=" input-group-btn">
                            <button id="id_add_user" type="submit" class="btn btn-warning"><i class="fa fa-plus">加入</i></button>
                        </div>
                    </div>
                </div>
            </div>
            <hr style="margin-bottom: 7px;margin-top: 7px;"/>
            <table   class="table table-bordered table-striped"   >
                <tr>
                    <td>uid
                    <td>昵称
                    <td>手机号
                    <td>操作
                </tr>
                <tbody  id="id_tbody" >
                </tbody>
            </table>
        </div>
    </div>
@endsection
