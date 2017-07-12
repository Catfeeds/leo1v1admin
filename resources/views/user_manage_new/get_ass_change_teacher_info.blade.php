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
                        <span class="input-group-addon">状态</span>
                        <select id="id_accept_flag" class="opt-change">
                            <option value="-1">全部</option>
                            <option value="0">未处理</option>
                            <option value="1">接受</option>
                            <option value="2">驳回</option>
                        </select>
                    </div>
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
                    <td>  当前老师 </td>
                    <td> 科目 </td>
                    <td> 年级 </td>
                    <td> 教材版本 </td>
                    <td> 地区 </td>
                    <td> 学生成绩 </td>
                    <td> 学生性格</td>
                    <td> 换老师类型</td>
                    <td> 申请理由</td>
                    <td> 期望老师</td>
                    <td> 反馈结果</td>
                    <td> 处理人</td>
                    <td> 处理时长</td>
                    <td>助教确认</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["id_index"]}}</td>
                        <td>{{@$var["add_time_str"]}} </td>
                        <td>{{@$var["account"]}} </td>
                        <td>{{@$var["nick"]}} </td>
                        <td>
                            <a  href="/human_resource/index?teacherid={{$var["teacherid"]}}"
                               target="_blank" title="老师信息">{{@$var["realname"]}} </a>
                        </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["textbook"]}} </td>
                        <td>{{@$var["phone_location"]}} </td>
                        <td>{{@$var["stu_score_info"]}} </td>
                        <td>{{@$var["stu_character_info"]}} </td>
                        <td>{{@$var["change_teacher_reason_type_str"]}} </td>
                        <td>{{@$var["change_reason"]}}<br>
                            <a class="show_pic" href="javascript:;" data-url="{{@$var["change_reason_url"]}}" >查看图片</a>
                        </td>
                        <td>{{@$var["except_teacher"]}} </td>
                        <td>
                            @if($var["accept_flag"]==1)
                                状态:接受<br>
                                推荐老师:&nbsp&nbsp{{@$var["commend_realname"]}}<br>
                                备注:&nbsp&nbsp&nbsp&nbsp{{@$var["accept_reason"]}}<br>
                                操作时间:&nbsp&nbsp{{@$var["accept_time_str"]}}
                            @elseif($var["accept_flag"]==2)
                                状态:驳回<br>
                                驳回理由:&nbsp&nbsp{{@$var["accept_reason"]}} <br>
                                操作时间:&nbsp&nbsp{{@$var["accept_time_str"]}}
                            @else
                                状态:未处理
                            @endif
                        </td>
                        <td>{{$var["accept_account"]}}</td>
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
                               

                                <a class="fa-edit opt-edit"  title="推荐老师"> </a>
                                <a class="fa-edit opt-edit-new"  title="修改申请"> </a>
                                <a class="fa-trash-o opt-del"  title="删除申请"> </a>
                                <a class="opt-confirm"  title="确认结果">确认结果 </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

