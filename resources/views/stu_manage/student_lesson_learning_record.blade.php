@extends('layouts.stu_header')
@section('content')
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
                <div  id="id_date_range" >
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">年级</span>
                    <select id="id_grade" class="opt-change">                      
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
                <button class="btn " id="id_grade_show" ></button>
                <button class="btn " id="id_subject_show" ></button>
            </div>
            



        </div>
        <hr/>
        <div class="row">
            <div class="col-xs-6 col-md-12 ">
                <button class="btn btn-warning btn-flat preview_table_flag" id="id_pre_rate" style="float:right" data-class_id="1">预习率:{{ @$pre_rate }}%</button>
                <button class="btn btn-warning btn-flat lesson_table_flag" id="id_rate" style="float:right" data-class_id="2">预率:{{ @$pre_rate }}%</button>
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
                        <td class="show_lesson_detail" data-lessonid="{{ $var["lessonid"] }}"><a href="javascript:;">{{@$var["lesson_num"] }}</a></td>
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
                    <td width="100px">
                        讲义上传
                        <select id="id_cw_status">
                            <option value="-1">全部</option>
                            <option value="1">已上传</option>
                            <option value="0">未上传</option>
                        </select>
                    </td>

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
                        <td class="show_lesson_detail" data-lessonid="{{ $var["lessonid"] }}"><a href="javascript:;">{{@$var["lesson_num"] }}</a></td>
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

        @include("layouts.page")
    </section>
@endsection
