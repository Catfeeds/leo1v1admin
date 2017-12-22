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
            <div class="row  row-query-list" >
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">是否可见</span>
                        <select class="opt-change form-control" id="id_publish_flag" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-12 col-md-6"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <input class="opt-change form-control" id="id_phone_name" placeholder="电话,姓名,回车搜索"/>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">学生</span>
                        <input class="opt-change form-control" id="id_userid" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">年级</span>
                        <select class="opt-change form-control" id="id_grade" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" data-always_show="1"  >
                    <div class="input-group ">
                        <span class="input-group-addon">销售状态</span>
                        <select class="opt-change form-control" id="id_seller_student_status" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">科目</span>
                        <select class="opt-change form-control" id="id_subject" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">渠道</span>
                        <input class="opt-change form-control" id="id_origin" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">Pad</span>
                        <select class="opt-change form-control" id="id_has_pad" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">销售</span>
                        <input class="opt-change form-control" id="id_admin_revisiterid" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2 " data-always_show="1" >
                    <div class="input-group ">
                        <span class="input-group-addon">TMK状态</span>
                        <select class="opt-change form-control" id="id_tmk_student_status" >
                        </select>
                    </div>
                </div>



                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <button class="btn btn-primary" id="id_add">新增例子</button>
                    </div>
                </div>





            </div>

        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td style="width:60px">时间</td>
                    <td >基本信息</td>
                    <td >来源</td>
                    <td >回公海次数</td>
                    <td style="width:70px">TMK状态</td>
                    <td >年级</td>
                    <td >科目</td>
                    <td >是否有pad</td>
                    <td >用户备注</td>
                    <td >下次回访时间</td>
                    <td >销售负责人</td>
                    <td >TMK分配者</td>
                    <td >CC负责人</td>
                    <td >销售状态</td>
                    <td >老师</td>
                    <td >上课时间</td>
                    <td >课程确认</td>
                    <td >失败原因</td>
                    <td style="min-width:130px" >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["opt_time"]}} </td>
                        <td>
                            <a href="javascript:;" class="show_phone" data-phone="{{$var["phone"]}}" >
                                {{@$var["phone_hide"]}}
                            </a>
                            {{$var["phone_location"]}} <br/>
                            {{$var["nick"]}}
                        </td>

                        <td>
                            @if  ($var["origin_assistantid"]==0)
                                {{$var["origin"]}} <br/>
                            @else
                                转介绍: {{@$var["origin_assistant_nick"]}} <br/>
                            @endif
                        </td>
                        <td>
                            {{$var["return_publish_count"]}}  </td>


                        <td>
                            {{$var["tmk_student_status_str"]}} <br/>
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
                            {{$var["tmk_desc"]}} <br/>
                        </td>
                        <td>
                            {{$var["tmk_next_revisit_time"]}}
                        </td>

                        <td>
                            {{$var["sub_assign_admin_2_nick"]}}/
                            {{$var["admin_revisiter_nick"]}}
                        </td>
                        <td >{{$var["tmk_admin_nick"]}}</td>
                        <td >{{$var["tmk_set_seller_adminid_nick"]}}</td>
                        <td >{{$var["seller_student_status_str"]}}</td>

                        <td >{{$var["teacher_nick"]}}</td>
                        <td >{{$var["lesson_start"]}}</td>
                        <td >{{$var["success_flag_str"]}}</td>
                        <td >{{ $var["success_flag"]==2?$var["test_lesson_fail_flag_str"]:""}}</td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >

                                <a title="查看回访" class=" show-in-select  fa-comments  opt-return-back-list "></a>
                                <a title="手机拨打信息 列表 " class=" fa-list  opt-telphone-list   "></a>
                                <a title="手机拨打" class=" fa-phone  opt-telphone "></a>
                                <a class="fa fa-flag opt-publish-flag " title="设置是否出现在公海"> </a>
                                <a class="fa fa-edit opt-edit"  title="编辑"> </a>
                                <a class="fa fa-share opt-jump"  title="分配例子"> </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
