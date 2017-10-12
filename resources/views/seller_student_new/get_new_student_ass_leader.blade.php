@extends('layouts.app')

@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>

    <script type="text/javascript" src="/page_js/seller_student_new/common.js?{{@$_publish_version}}"></script>
    <section class="content ">

        <div>

            <div class="row" >               


                <div class="col-xs-12 col-md-6"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-3 col-md-1" >
                    <button  class="btn btn-info" id="id_add">新增例子</button>
                </div>


            </div>
          
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                  
                    <td>学生</td>
                    <td>手机号</td>
                    <td>资源添加时间</td>
                    <td >地区</td>
                    <td >来源</td>
                    <td>分配助教</td>
                    <td>分配时间</td>
                    <td>分配人</td>
                   

                    <td style="min-width:130px" >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td> <input type="checkbox" class="opt-select-item" data-userid="{{$var["userid"]}}"/>   {{$var["index"]}} </td>
                        <td>{{$var["opt_time"]}} </td>
                        <td>{{$var["phone"]}} </td>
                        <td>
                            {{$var["phone"]}} <br/>
                            {{$var["phone_location"]}} <br/>
                            {{$var["nick"]}} <br/>
                            {{$var["seller_resource_type_str"]}}
                        </td>
                        <td>{{$var["phone_location"]}} </td>

                        <td>
                            @if  ($var["origin_assistantid"]==0)
                                {{$var["origin"]}} ({{$var["origin_level_str"]}})/{{$var["nickname"]}} <br/>
                            @else
                                转介绍: {{$var["origin_assistant_nick"]}} <br/>
                            @endif
                        </td>
                        <td>{{$var["last_call_time_space"]}}天 </td>
                        @if ($show_list_flag==0)
                            <td>
                                {{$var['first_call_time']}}
                            </td>
                            <td>
                                {{$var["seller_student_status_str"]}} <br/>
                            </td>
                            <td>
                                {{$var["seller_student_sub_status_str"]}}
                            </td>

                            <td>
                                {{$var["global_tq_called_flag_str"]}} <br/>
                            </td>

                            <td>
                                {{$var["sys_invaild_flag_str"]}} <br/>
                            </td>


                            <td>
                                {{$var["user_desc"]}} <br/>
                            </td>

                            <td>
                                {{$var["grade_str"]}} <br/>
                            </td>

                            <td>
                                {{$var["subject_str"]}} <br/>
                            </td>

                            <td>
                                {{$var["has_pad_str"]}} <br/>
                            </td>
                            

                            <td>
                                {{$var["sub_assign_admin_2_nick"]}} / {{$var["admin_revisiter_nick"]}}
                                <br/>
                            </td>
                            
                            <td>
                                {{$var["call_count"]}} <br/>
                            </td>

                            <td>
                                {{$var["tmk_admin_nick"]}} <br/>
                                {{$var["tmk_student_status_str"]}} <br/>
                            </td>


                            <td>
                                {{$var["competition_call_admin_nick"]}} /<br/>
                                {{$var["competition_call_time"]}}
                            </td>
                            <td>{{$var["require_admin_nick"]}}</td>
                        @else
                            <td>{{$var["first_tmk_valid_desc"]}}</td>
                            <td>{{$var["first_tmk_set_cc_desc"]}}</td>
                            <td>{{$var["first_set_master_desc"]}}</td>
                            <td>{{$var["first_set_cc_desc"]}}</td>
                            <td>{{$var["first_seller_status_str"]}}</td>


                        @endif

                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}


                            >
                                <a href="javascript:;" title="用户信息" class="fa-user opt-user"></a>
                                <a title="查看回访" class=" show-in-select  fa-comments  opt-return-back-list "></a>

                                <a class="fa fa-times opt-del" title="删除"> </a>
                                <a class="fa fa-phone opt-telphone " title="电话列表"> </a>
                                <a class="fa fa-list   opt-seller-list " title="拨打cc列表"> </a>
                                <a class="fa fa-refresh  opt-reset-sys_invaild_flag" title="刷新无效状态"> </a>
                                <a class="fa fa-flag opt-publish-flag " title="设置是否出现在公海"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
