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

        <!-- james-start-->

        <div class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #3c8dbc;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 style="text-align: center;color:white;" class="modal-title">未拨通电话标注</h4>
                    </div>
                    <div class="modal-body" style="text-align:center;">
                        <p>请设置</p>
                        <div class="" id="">
                            <select style="width:35%;" class="invalid_type">
                                <option value="0">请选择状态</option>
                                <option value="1001">无效-空号</option>
                                <option value="1002">无效-停机</option>
                                <option value="1012">无效-屏蔽音</option>
                                <option value="1004">无效-不接电话</option>
                            </select>
                            <p style="color:red;">请至少拨打3次确认状态</p>
                        </div>
                    </div>
                    <div class="modal-footer" style="text-align:center;">
                        <button type="button" class="btn btn-primary submit_tag">提交</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">再想想</button>
                    </div>
                </div>
            </div>
        </div>


        <!-- 此处为模态框-->
        <div class="modal fade confirm-sm" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
            <div class="modal-dialog modal-sm" role="document">
                <div class="modal-content">
                    <div class="modal-header" style="background-color: #3c8dbc;">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <h4 style="text-align: center;color:white;" class="modal-title">未拨通电话标注</h4>
                    </div>
                    <div class="modal-body" style="text-align:center;">
                        <p>是否标注为 <font style="color:red;" class="tip_text">无效-空号？</font></p>
                        <p style="color:red;">提示：如经核验不符，将被罚款！</p>
                    </div>
                    <div class="modal-footer" style="text-align:center;">
                        <button type="button" class="btn btn-primary confirm_tag">确认</button>
                        <button type="button" class="btn btn-default" data-dismiss="modal">再想想</button>
                    </div>
                </div>
            </div>
        </div>





        <div>

            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-6"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">状态</span>
                        <select class="opt-change form-control" id="id_seller_student_status" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span class="input-group-addon">TQ状态</span>
                        <input class="opt-change form-control" id="id_global_tq_called_flag" />
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">年级</span>
                        <input class="opt-change form-control" id="id_grade" />
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">科目</span>
                        <input class="opt-change form-control" id="id_subject" />
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
                    <td style="width:70px">回访状态</td>
                    <td style="width:70px">子状态</td>
                    <td >全局TQ状态</td>
                        {!!\App\Helper\Utils::th_order_gen([
                            ["回公海次数","return_publish_count" ],
                           ])!!}


                    <td >cc备注</td>
                    <td >tmk备注</td>
                    <td >年级</td>
                    <td >科目</td>
                    <td >是否有pad</td>
                    <td >负责人</td>
                    <td style="display:none;">联系负责人</td>
                    <td style="min-width:130px" >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["add_time"]}} </td>
                        <td>
                            <a href="javascript:;" class="show_phone" data-phone="{{$var["phone"]}}" >
                                {{@$var["phone_hide"]}}
                            </a>
                            {{$var["phone_location"]}} <br/>
                            {{$var["nick"]}}
                        </td>

                        <td>
                            @if  ($var["origin_assistantid"]==0)
                                {{$var["origin"]}} ({{$var["origin_level_str"]}}) <br/>
                            @else
                                转介绍: {{$var["origin_assistant_nick"]}} <br/>
                            @endif
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
                            {{$var["return_publish_count"]}} <br/>
                        </td>



                        <td>
                            {{$var["user_desc"]}} <br/>
                        </td>


                        <td>
                            {{@$var["tmk_desc"]}} <br/>
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
                        </td>

                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <!-- <a href="javascript:;" title="用户信息" class="fa-user opt-user"></a> -->
                                <!-- <a class="fa fa-times opt-del" title="删除"> </a> -->
                                <!-- <a class="fa fa-phone opt-telphone " title="电话列表"> </a> -->
                                <a title="手机拨打" class=" fa-phone  opt-telphone   "></a>
                                <a class="fa fa-flag opt-publish-flag " title="设置是否有效"> </a>

                                <a title="查看回访" class=" show-in-select  fa-comments  opt-return-back-list "></a>
                                <a class="fa fa-edit opt-edit"  title="编辑" > </a>
                                <!-- james -->
                                <a class="fa fa-trash-o opt-sign"  title="标注无效资源"></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
