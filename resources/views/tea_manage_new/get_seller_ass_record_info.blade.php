@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>

    <section class="content ">
        
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">申请人</span>
                        <input class="opt-change form-control" id="id_require_adminid" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">处理人</span>
                        <input class="opt-change form-control" id="id_accept_adminid" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <button class="btn btn-primary" id="id_add_seller_and_ass_record"> 添加反馈 </button>
                </div>                

            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>编号</td>
                    <td>申请时间</td>
                    <td>申请人</td>
                    <td> 学生 </td>
                    <td> 老师 </td>
                    <td> 科目 </td>
                    <td> 年级 </td>
                    <td> 教材版本 </td>
                    <td> 相关信息 </td>
                    <td> 问题反馈</td>
                    <td> 处理方案</td>
                    <td> 处理人</td>
                    <td> 处理时长</td>
                    <td> 结果</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["id_index"]}}</td>
                        <td>{{@$var["add_time_str"]}} </td>
                        <td>{{@$var["account"]}} </td>
                        
                        @if($adminid ==-1)
                            <td> 
                                <a  href="/stu_manage?sid={{$var["userid"]}}"
                                    target="_blank" title="学生信息">{{@$var["nick"]}} </a>
                            </td >
                            <td>
                                <a  href="/human_resource/index?teacherid={{$var["teacherid"]}}"
                                    target="_blank" title="老师信息">{{@$var["realname"]}} </a>
                            </td>

                        @elseif($adminid >0)
                            <td> {{@$var["nick"]}}</td>
                            <td> {{@$var["realname"]}}</td>
                        @endif
                        
                        
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["textbook"]}} </td>
                        <td>
                            @if($var["stu_score_info"] || $var["stu_request_test_lesson_demand"])
                                @if($var["type"]==1)
                                    学生成绩:&nbsp&nbsp{{@$var["stu_score_info"]}}<br>
                                    学生性格:&nbsp&nbsp{{@$var["stu_character_info"]}}<br>
                                    试听后是否换过老师:&nbsp&nbsp{{@$var["is_change_teacher_str"]}}<br>
                                    老师给学生的上课时长:&nbsp&nbsp{{@$var["tea_time"]}}天<br>
                                @elseif($var["type"]==2)
                                    试听需求:{{@$var["stu_request_test_lesson_demand"]}}
                                @endif
                            @else
                                无
                            @endif

                        </td>
                        <td>
                            {{@$var["record_info"]}}<br>
                            @if($var["record_info_url"])
                                <a class="show_pic" href="javascript:;" data-url="{{@$var["record_info_url"]}}" >查看图片</a>
                            @endif
                        </td>
                        <td>
                            @if($var["accept_time"]>0)
                                {{@$var["record_scheme"]}}<br>
                                操作时间:&nbsp&nbsp{{@$var["accept_time_str"]}}<br>
                                @if($var["record_scheme_url"])
                                    <a class="show_pic" href="javascript:;" data-url="{{@$var["record_scheme_url"]}}" >查看图片</a>
                                @endif
                            @endif
                        </td>
                        <td>{{@$var["accept_account"]}}</td>
                        <td>
                            @if($var["accept_time"]>0)
                                {{@$var["deal_time"]}}小时 
                            @endif
                        </td>

                        <td>
                            @if($var["done_time"]>0)
                                {{@$var["is_done_flag_str"]}}<br>
                                操作时间:&nbsp&nbsp{{@$var["done_time_str"]}}                               
                            @endif
                        </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                               

                                <a class="fa-edit opt-edit"  title="处理方案"> </a>
                                <a class="fa-edit opt-edit-new"  title="修改申请"> </a>
                                <a class="fa-trash-o opt-del"  title="删除申请"> </a>
                                <a class="fa-gavel opt-confirm"  title="确认结果"> </a>
                                @if($acc=="jack")
                                    <a class=" opt-del-new"  title="删除申请">删除 </a>
                                @endif
                                @if($var["add_type"]==1)
                                    <a class="opt-edit-admin" >修改 </a>
                                @endif


                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

