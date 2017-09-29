@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="/js/wb-reply/audio.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" >
     var g_adminid_right= <?php  echo json_encode($adminid_right); ?> ;
    </script>


    <section class="content ">

        <div>
            <div class="row">
                <div class="col-xs-12 col-md-4" data-title="时间段">
                    <div id="id_date_range"> </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">是否预警</span>
                        <select id="id_is_warning_flag" class="opt-change">
                        </select>
                    </div>
                </div>
                <div  class="col-xs-6 col-md-4">
                    <div class="input-group ">
                        <span class="input-group-addon">申请人选择</span>
                        <input class="opt-change form-control" id="id_seller_groupid_ex" />
                    </div>
                </div>
            </div>
            <div class="row" >
                <div  class="col-xs-6 col-md-4 hide">
                    <input id="id_warning_type" style="display:none;" />
                    <button type="button" class="btn btn-default opt-warning-type" id="warning-one">{{$warning['warning_type_one']}}</button>
                    <button type="button" class="btn btn-default opt-warning-type" id="warning-two">{{$warning['warning_type_two']}}</button>
                    <button type="button" class="btn btn-default opt-warning-type" id="warning-three">{{$warning['warning_type_three']}}</button>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>学生</td>
                    <td>回访时间</td>
                    <td>类型 </td>
                    <td>回访对象 </td>
                    <td>回访记录</td>
                    <td> 回访人 </td>
                    <td> 软件操作是否满意 </td>
                    <td> 软件操作不满意的类型 </td>
                    <td> 软件操作不满意的描述</td>
                    <td> 孩子课堂表现 </td>
                    <td> 孩子课堂表现不好的类型 </td>
                    <td> 孩子课堂表现不好的描述 </td>
                    <td> 学校成绩变化 </td>
                    <td> 学校成绩变化变差的描述 </td>
                    <td>学业是否变化 </td>
                    <td>学业变化的类型 </td>
                    <td>学业变化的描述 </td>
                    <td>对老师or教学是否满意 </td>
                    <td>对老师or教学不满意的类型 </td>
                    <td>对老师or教学不满意的描述 </td>
                    <td>家长意见或建议</td>
                    <td>其他预警问题</td>
                    <td>预警情况</td>
                    <td>预警处理方案</td>
                    <td> 操作 </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["revisit_time_str"]}} </td>
                        <td>{{$var["revisit_type_str"]}} </td>
                        <td>{{@$var["revisit_person"]}} </td>
                        <td>{!! @$var["operator_note"]  !!} </td>

                        <td>{{@$var["sys_operator"]}} </td>
                        <td>{{@$var["operation_satisfy_flag_str"]}} </td>
                        <td>{{@$var["operation_satisfy_type_str"]}} </td>
                        <td>{{@$var["operation_satisfy_info"]}} </td>
                        <td>{{@$var["child_class_performance_flag_str"]}} </td>
                        <td>{{@$var["child_class_performance_type_str"]}} </td>
                        <td>{{@$var["child_class_performance_info"]}} </td>
                        <td>{{@$var["school_score_change_flag_str"]}} </td>
                        <td>{{@$var["school_score_change_info"]}} </td>
                        <td>{{@$var["school_work_change_flag_str"]}} </td>
                        <td>{{@$var["school_work_change_type_str"]}} </td>
                        <td>{{@$var["school_work_change_info"]}} </td>

                        <td>{{@$var["tea_content_satisfy_flag_str"]}} </td>
                        <td>{{@$var["tea_content_satisfy_type_str"]}} </td>
                        <td>{{@$var["tea_content_satisfy_info"]}} </td>
                        <td>{{@$var["other_parent_info"]}} </td>
                        <td>{{@$var["other_warning_info"]}} </td>
                        <td>{{@$var["is_warning_flag_str"]}} </td>
                        <td>
                            {{@$var["warning_deal_info"]}}<br>
                            @if($var["warning_deal_url"])
                                <a class="show_pic" href="javascript:;" data-url="{{@$var["warning_deal_url"]}}" >查看图片</a>
                            @endif

                        </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa opt-warning-record" >预警处置 </a>
                            </div>
                        </td>


                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
