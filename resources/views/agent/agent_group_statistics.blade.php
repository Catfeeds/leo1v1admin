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
                <tr>
                    <td>团长信息</td>
                    <td>团队名字</td>
                    <td>成员信息</td>
                    <td>学员量</td>
                    <td>试听量</td>
                    <td>签单量</td>
                    <td>签单金额</td>
                    <td>会员量</td>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="main_type">{{$agent_all_group_result['name']}}</td>
                    <td></td>
                    <td></td>
                    <td>{{$agent_all_group_result['student_count']}}</td>
                    <td>{{$agent_all_group_result['test_lesson_count']}}</td>
                    <td>{{$agent_all_group_result['order_count']}}</td>
                    <td>{{$agent_all_group_result['order_money']}}</td>
                    <td>{{$agent_all_group_result['member_count']}}</td>
                </tr>
                @if($table_data_list)
                    @foreach ( @$table_data_list as $var )
                        <tr class="{{$var["level"]}}">
                            <td data-class_name="{{$var["main_type_class"]}}" class="main_type" >
                                {{$var["colconel_name"]}}
                            </td>
                            <td  data-class_name="{{$var["up_group_name_class"]}}" class=" up_group_name  {{$var["main_type_class"]}}  {{$var["up_group_name_class"]}} " >
                                {{@$var["group_name"]}}
                            </td>
                            <td data-class_name="{{$var["group_name_class"]}}" class="group_name  {{$var["up_group_name_class"]}} {{$var["group_name_class"]}}  "  >
                                {{@$var["phone"]}}/{{@$var['nickname']}}
                            </td>

                            <td>{{@$var['student_count']}}</td>
                            <td>{{@$var['test_lesson_count']}}</td>

                            <td>{{@$var['order_count']}}</td>
                            <td>{{@$var['order_money']}}</td>

                            <td>{{@$var['member_count']}}</td>
                            
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

