@extends('layouts.stu_header')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/js/jquery.base64.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/js/svg.js"></script>
    <script type="text/javascript" src="/js/wb-reply/audio.js"></script>
    <script type="text/javascript" src="/page_js/lib/flow.js"></script>
    <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.min.js"></script>
	  <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.categories.js"></script>

    <link href="/css/jquery-ui-1.8.custom.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" >
     var g_subject_list= <?php  echo json_encode(@$subject_list); ?> ;
     var g_grade_list= <?php  echo json_encode(@$grade_list); ?> ;
     var g_data_ex_list= <?php  echo json_encode(@$pic_data); ?> ;
    </script>
    <section class="content ">
        <div >
            <img src="https://ybprodpub.leo1v1.com/a3062c52cafb042250b3dddd2f5317b11516177228556.png"  alt="汇总"  id="id_show_all"  style="float:right;margin-right:-10px" title="汇总"  />
        </div>
        <table border="1" bordercolor="#d5d5d5" cellspacing="0" width="100%" height="30px" style="border-collapse:collapse;margin-top:30px"  class="stu_tab04" >
            <tr align="center">
                <td class="current" width="20%" data-id="1"><a href="javascript:;" style="color:#000" >课前预习</a></td>
                <td width="20%" data-id="2"><a href="javascript:;" style="color:#000" >课堂情况</a></td>
                <td width="20%" data-id="3"><a href="javascript:;" style="color:#000">课程评价</a></td>
                <td width="20%" data-id="4"><a href="javascript:;" style="color:#000">作业情况</a></td>
                <td width="20%" data-id="5"><a href="javascript:;" style="color:#000">平日成绩</a></td>
            </tr>
        </table>

        <div class="row" style="margin-top:10px">
            <div class="col-xs-12 col-md-4">
                <div class="input-group ">
                    <span >时间:</span>
                    <input type="text" id="id_start_date" class="opt-change"/>
                    <span >-</span>
                    <input type="text" id="id_end_date" class="opt-change"/>
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">年级</span>
                    <select id="id_grade" class="opt-change">
                    </select>
                </div>
            </div>
            <div class="col-md-2 col-xs-0">
                <div class="input-group ">
                    <span>学期</span>
                    <select class="opt-change" id="id_semester">
                    </select>
                </div>
            </div>
            <div class="col-md-2 col-xs-0">
                <div class="input-group ">
                    <span>考试类型</span>
                    <select class="opt-change" id="id_stu_score_type">
                    </select>
                </div>
            </div>

            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">科目</span>
                    <select id="id_subject" class="opt-change">
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-1">
                <button class="btn btn-primary" id="id_search" >搜索</button>
            </div>
            <div class="col-xs-6 col-md-2" style="display:none;">
                <button class="btn btn-warning" id="id_add_stu_score" >添加考试成绩</button>
            </div>
            <div class="col-xs-6 col-md-12" >
                <button class="btn " id="id_date_show" ></button>
                <button class="btn " id="id_stu_score_type_show" ></button>
                <button class="btn " id="id_semester_show" ></button>
                <button class="btn " id="id_grade_show" ></button>
                <button class="btn " id="id_subject_show" ></button>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="col-xs-6 col-md-12 ">
                <button class="btn btn-warning btn-flat preview_table_flag" id="id_pre_rate" style="float:right" data-class_id="1">预习率:{{ @$pre_rate }}%</button>
                <button class="btn btn-warning btn-flat lesson_table_flag" id="id_attend_rate" style="float:right" data-class_id="2">正常出勤率:{{ @$attend_rate }}%</button>
                <button class="btn btn-warning btn-flat performance_table_flag" id="id_record_rate" style="float:right" data-class_id="3">反馈率:{{ @$record_rate }}%</button>
                <button class="btn btn-warning btn-flat homework_table_flag" id="id_score_final" style="float:right" data-class_id="4">平均成绩:{{ @$score_final }}</button>
                <button class="btn btn-warning btn-flat homework_table_flag" id="id_complete_rate" style="float:right;margin-right:15px" data-class_id="4">作业完成率:{{ @$complete_rate }}%</button>
                <button class="btn btn-warning btn-flat score_table_flag score_table_flag_show" id="id_score_pic" style="float:right" data-class_id="5" data-table_id="1">图</button>
                <button class="btn btn-warning btn-flat score_table_flag score_table_flag_show current_score" id="id_score_table" style="float:right" data-class_id="5" data-table_id="2">表</button>
            </div>
        </div>
        <table class="common-table preview_table_flag" data-class_id="1">
            <thead>
                <tr >
                    <td >序号</td>
                    <td>时间</td>
                    <td>年级</td>
                    <td>科目</td>
                    <td width="100px">
                        讲义上传
                        <select id="id_cw_status">
                            <option value="-1">全部</option>
                            <option value="1">已上传</option>
                            <option value="0">未上传</option>
                        </select>
                    </td>

                    <td>老师</td>
                    <td width="100px">
                        预习情况
                        <select id="id_preview_status">
                            <option value="-1">全部</option>
                            <option value="1">是</option>
                            <option value="0">否</option>
                        </select>
                    </td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                    <tr>
                        <td class="show_lesson_detail" data-lessonid="{{ @$var["lessonid"] }}"><a href="javascript:;">{{@$var["lesson_num"] }}</a></td>
                        <td>{{@$var["lesson_time"] }}</td>
                        <td>{{@$var["grade_str"] }}</td>
                        <td>{{@$var["subject_str"] }}</td>
                        <td>
                            @if(empty(@$var["cw_status_flag"]))
                                {{@$var["cw_status_str"] }}
                            @else
                                <a class="show_cw_content" href="javascript:;" data-url="{{ $var["cw_url"] }}">
                                    {{@$var["cw_status_str"] }}
                                </a>
                            @endif
                        </td>
                        <td>{{@$var["realname"] }}</td>
                        <td>{{@$var["preview_status_str"] }}</td>
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
        <table class="common-table lesson_table_flag" data-class_id="2">
            <thead>
                <tr >
                    <td >序号</td>
                    <td>时间</td>
                    <td>年级</td>
                    <td>科目</td>
                    <td>学生考勤</td>
                    <td>老师考勤</td>
                    <td>学生登录</td>
                    <td>老师登录</td>
                    <td>家长登录</td>
                    <td>学生画笔</td>
                    <td>老师画笔</td>
                    <td>学生发言</td>
                    <td>老师发言</td>
                    <td>获赞</td>
                    <td>老师</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                    <tr>
                        <td class="show_lesson_detail" data-lessonid="{{ @$var["lessonid"] }}"><a href="javascript:;">{{@$var["lesson_num"] }}</a></td>
                        <td>{{@$var["lesson_time"] }}</td>
                        <td>{{@$var["grade_str"] }}</td>
                        <td>{{@$var["subject_str"] }}</td>
                        <td>{{@$var["stu_attend_str"] }}</td>
                        <td>{{@$var["tea_attend_str"] }}</td>
                        <td>
                            <a class="show_login_info" href="javascript:;" data-lessonid="{{ @$var["lessonid"] }}" data-userid="{{ @$var["userid"] }}" data-role="学生">
                                {{@$var["stu_login_num"] }}
                            </a>
                        </td>
                        <td>
                            <a class="show_login_info" href="javascript:;" data-lessonid="{{ @$var["lessonid"] }}" data-userid="{{ @$var["teacherid"] }}" data-role="老师">
                                {{@$var["tea_login_num"] }}
                            </a>
                        </td>
                        <td>
                            <a class="show_login_info" href="javascript:;" data-lessonid="{{ @$var["lessonid"] }}" data-userid="{{ @$var["parentid"] }}" data-role="家长">
                                {{@$var["parent_login_num"] }}
                            </a>
                        </td>
                        <td>{{@$var["stu_draw"] }}</td>
                        <td>{{@$var["tea_draw"] }}</td>
                        <td>{{@$var["stu_voice"] }}</td>
                        <td>{{@$var["tea_voice"] }}</td>
                        <td>{{@$var["stu_praise"] }}</td>
                        <td>{{@$var["realname"] }}</td>
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

        <table class="common-table performance_table_flag" data-class_id="3">
            <thead>
                <tr >
                    <td >序号</td>
                    <td>时间</td>
                    <td>年级</td>
                    <td>科目</td>
                    <td>学生打分</td>
                    <td>学生评价</td>
                    <td>老师评价</td>
                    <td>老师</td>
                    <td>回放</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                    <tr>
                        <td class="show_lesson_detail" data-lessonid="{{ @$var["lessonid"] }}"><a href="javascript:;">{{@$var["lesson_num"] }}</a></td>
                        <td>{{@$var["lesson_time"] }}</td>
                        <td>{{@$var["grade_str"] }}</td>
                        <td>{{@$var["subject_str"] }}</td>
                        <td ><a class="btn show_stu_score_detail" href="javascript:;" data-lessonid="{{ @$var["lessonid"] }}" data-effect="{{ @$var["teacher_effect"] }}" data-quality="{{ @$var["teacher_quality"] }}" data-interact="{{ @$var["teacher_interact"] }}" data-stability="{{ @$var["stu_stability"] }}">{{@$var["stu_score"]}}</a></td>
                        <td >{{@$var["teacher_comment"]}}</td>
                        <td >{{@$var["stu_point_performance"]}}</td>

                        <td>{{@$var["realname"] }}</td>
                        <td><a class="btn show_lesson_video" href="javascript:;" data-lessonid="{{ @$var["lessonid"] }}">课程回放</a></td>
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

        <table class="common-table homework_table_flag" data-class_id="4">
            <thead>
                <tr >
                    <td >序号</td>
                    <td>时间</td>
                    <td>年级</td>
                    <td>科目</td>
                    <td>布置作业</td>
                    <td>下载作业</td>
                    <td>提交情况</td>
                    <td>是否批改</td>
                    <td>成绩</td>
                    <td>查看批改</td>
                    <td>老师</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                    <tr>
                        <td class="show_lesson_detail" data-lessonid="{{ @$var["lessonid"] }}"><a href="javascript:;">{{@$var["lesson_num"] }}</a></td>
                        <td>{{@$var["lesson_time"] }}</td>
                        <td>{{@$var["grade_str"] }}</td>
                        <td>{{@$var["subject_str"] }}</td>
                        <td>
                            @if(empty(@$var["issue_url"]))
                                {{@$var["issue_flag"] }}
                            @else
                                <a class="show_issue_content" href="javascript:;" data-url="{{ @$var["issue_url_str"] }}">
                                    {{@$var["issue_flag"] }}
                                </a>
                            @endif
                        </td>
                        <td>{{@$var["download_flag"] }}</td>
                        <td>
                            @if(@$var["work_status"]>=2)
                                <a class="show_issue_content" href="javascript:;" data-url="{{ @$var["finish_url_str"] }}">
                                    {{@$var["commit_flag"] }}
                                </a>
                            @else
                                {{@$var["commit_flag"] }}
                            @endif
                        </td>
                        <td>
                            @if(@$var["work_status"]>=3)
                                <a class="show_issue_content" href="javascript:;" data-url="{{ @$var["check_url_str"] }}">
                                    {{@$var["check_flag"] }}
                                </a>
                            @else
                                {{@$var["check_flag"] }}
                            @endif
                        </td>
                        <td>{{@$var["score"] }}</td>
                        <td>{{@$var["stu_check_flag"] }}</td>
                        <td>{{@$var["realname"] }}</td>
                        
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

        <table     class="common-table score_table_flag score_table" data-class_id="5" >
            <thead>
                <tr>
                    <td>年级</td>
                    <td>学期</td>
                    <td>考试类型</td>
                    <td>科目</td>
                    <td>成绩</td>
                    <td>总分</td>
                    <td>班级排名</td>
                    <td>班级人数</td>
                    <td>年级排名</td>
                    <td>年级人数</td>
                    <td>试卷</td>
                    <td>录入者</td>
                    <td>上传时间</td>
                    <td>操作</td>
                    
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["semester_str"]}} </td>
                        <td>{{@$var["stu_score_type_str"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["score"]/10}} </td>
                        <td>{{@$var["total_score"]}} </td>
                        
                        <td>{{@$var["rank"]}} </td>
                        <td>{{@$var["rank_num"]}} </td>
                        <td>{{@$var["grade_rank"]}} </td>
                        <td>{{@$var["grade_rank_num"]}} </td>
                        <td>
                            @if(@$var["file_url"])
                                <a class="show_issue_content" href="javascript:;" data-url="{{ @$var["file_url"] }}">
                                    {{@$var["file_upload_str"] }}
                                </a>
                            @else
                                {{@$var["file_upload_str"] }}
                            @endif
                        </td>
                        <td>{!!@$var["create_admin_nick"]!!} </td>
                        <td>{{@$var["paper_upload_time_str"]}} </td>
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
        <div class="common-table score_table_flag score_pic" data-class_id="5" id="score_pic" ></div>




        @include("layouts.page")
    </section>
@endsection
