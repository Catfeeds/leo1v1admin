@extends('layouts.app')
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

    <link href="/css/jquery-ui-1.8.custom.css" rel="stylesheet" type="text/css" />

    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <section class="content ">
         <div>
            <div class="row" >

                <div class="col-xs-12 col-md-4" data-title="时间段">
                    <div id="id_date_range"> </div>
                </div>
            </div>
        </div>
        <hr />
        <table     class="common-table"  > 

            <thead>
                <tr  >
                    <td colspan="3">渠道名称</td>
                    <td colspan="2">面试总数据</td>
                    <td colspan="2">录制试讲</td>
                    <td colspan="2">一对一面试</td>
                    <td colspan="4">入职数</td>
                </tr>
                <tr>
                    <td>主渠道</td>
                    <td>次渠道</td>
                    <td>成员</td>

                    <td>报名数</td>
                    <td>入职数</td>


                    <td>提交数</td>
                    <td>入职数</td>


                    <td>预约数</td>
                    <td>入职数</td>


                    <td>公校教师</td>
                    <td>机构教师</td>
                    <td>在校学生</td>
                    <td>在职人士</td>

                    <td>操作</td>
                </tr>
            </thead>
            <tbody>

                @foreach ( $table_data_list as $var )
                    <tr class="{{$var["level"]}}">
                        <td data-class_name="{{$var["main_type_class"]}}" class="main_type" >
                            {{$var["channel_name"]}}
                        </td>
                        <td  data-class_name="{{$var["up_group_name_class"]}}" class=" up_group_name  {{$var["main_type_class"]}}  {{$var["up_group_name_class"]}} " >
                            {{$var["group_name"]}}
                        </td>
                        <td data-class_name="{{$var["group_name_class"]}}" class="group_name  {{$var["up_group_name_class"]}} {{$var["group_name_class"]}}  "  >
                            {{@$var["admin_name"]}} {{@$var['admin_phone']}}
                        </td>

                        <td>{{@$var['app_num']}}</td>
                        <td>{{@$var['through_all']}}</td>

                        <td>{{@$var['video_add_num']}}</td>
                        <td>{{@$var['through_video']}}</td>

                        <td>{{@$var['lesson_add_num']}}</td>
                        <td>{{@$var['through_lesson']}}</td>

                        <td>{{@$var['through_gx']}}</td>
                        <td>{{@$var['through_jg']}}</td>
                        <td>{{@$var['through_gxs']}}</td>
                        <td>{{@$var['through_zz']}}</td>
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

