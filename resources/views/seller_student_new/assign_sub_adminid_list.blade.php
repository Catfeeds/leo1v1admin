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
                        <span class="input-group-addon">列表模式</span>
                        <select class="opt-change form-control" id="id_show_list_flag" >
                            <option value="0">分配</option>
                            <option value="1">追踪</option>
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon" >   是否可见   </span>
                        <select class="opt-change form-control" id="id_publish_flag" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">系统判定无效</span>
                        <select class="opt-change form-control" id="id_sys_invaild_flag" >
                        </select>
                    </div>
                </div>


                <div class="col-xs-12 col-md-6"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">全局TQ</span>
                        <select class="opt-change form-control" id="id_global_tq_called_flag" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">个人TQ</span>
                        <select class="opt-change form-control" id="id_tq_called_flag" >
                        </select>
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
                        <input class="opt-change form-control" id="id_grade" />
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
                        <span class="input-group-addon">资源</span>
                        <select class="opt-change form-control" id="id_seller_resource_type" >
                        </select>
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon"> 渠道等级</span>
                        <input class="opt-change form-control" id="id_origin_level" />
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
                        <span class="input-group-addon">渠道选择</span>
                        <input class="opt-change form-control" id="id_origin_ex" />
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">手机归属</span>
                        <input class="opt-change form-control" id="id_phone_location" />
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">状态</span>
                        <input class="opt-change form-control" id="id_seller_student_status" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">子状态</span>
                        <select class="opt-change form-control" id="id_seller_student_sub_status" >
                        </select>
                    </div>
                </div>




                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">Pad</span>
                        <select class="opt-change form-control" id="id_has_pad" >
                            <option  value="-2">非无设备</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">主管</span>
                        <input class="opt-change form-control" id="id_sub_assign_adminid_2" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">组员</span>
                        <input class="opt-change form-control" id="id_admin_revisiterid" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">转介绍</span>
                        <input class="opt-change form-control" id="id_origin_assistantid" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">TMK</span>
                        <input class="opt-change form-control" id="id_tmk_adminid" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">TMK状态</span>
                        <select class="opt-change form-control" id="id_tmk_student_status" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">离职</span>
                        <select class="opt-change form-control" id="id_admin_del_flag" >
                        </select>
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">cc角色</span>
                        <input class="opt-change form-control" id="id_account_role" />
                    </div>
                </div>



                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">cc等级</span>
                        <input class="opt-change form-control" id="id_seller_level" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">微信可见</span>

                        <select class="opt-change form-control" id="id_wx_invaild_flag" >
                        </select>


                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">非销售</span>
                        <select class="opt-change form-control" id="id_filter_flag" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">首cc</span>
                        <input class="opt-change form-control" id="id_first_seller_adminid" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">拨打数</span>
                        <input class="opt-change form-control" id="id_call_phone_count" placeholder="数字-数字" />
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">渠道数</span>
                        <input class="opt-change form-control" id="id_origin_count" placeholder="数字-数字" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">试听成功次数</span>
                        <input class="opt-change form-control" id="id_suc_test_count" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">负责人联系次数</span>
                        <input class="opt-change form-control" id="id_call_count" />
                    </div>
                </div>
            </div>

            <div class="row"
            >
                <div class="col-xs-3 col-md-1"
                     @if(in_array($account,['leowang','童宇周','陶建华','王洪艳']))
                     style="display:none;"
                     @endif
                >
                        <button class="btn btn-primary" id="id_set_select_list">批量分配</button>
                </div>

                <div class="col-xs-3 col-md-1"
                     @if(in_array($account,['leowang','童宇周','陶建华','王洪艳']))
                     style="display:none;"
                    @endif

                >
                    <button class="btn btn-primary" id="id_set_origin_list">设置渠道</button>
                </div>
                <div class="col-xs-3 col-md-1"
                     @if(in_array($account,['leowang','童宇周','陶建华','王洪艳']))
                 style="display:none;"
                 @endif

                >
                    <button class="btn btn-info" id="id_set_level_b">设置可抢</button>
                </div>
                <div class="col-xs-3 col-md-1"
                     @if(in_array($account,['leowang','童宇周','陶建华','王洪艳']))
                 style="display:none;"
                 @endif

                >
                    <button class="btn btn-info" id="id_set_history_to_new">公海->新</button>
                </div>

                <div class="col-xs-3 col-md-1"
                     @if(in_array($account,['leowang','童宇周','陶建华','王洪艳']))
                 style="display:none;"
                 @endif

                >
                    <button  class="btn btn-info" id="id_add">新增例子</button>
                </div>

                <div class="col-md-1 remove-for-xs col-xs-6 ""
                     @if(in_array($account,['leowang','童宇周','陶建华','王洪艳']))
                 style="display:none;"
                 @endif

                >
                    <div>
                        <button class="btn btn-primary" id="id_upload_xls"> 上传xls </button>
                    </div>
                </div>
                <div class="col-xs-3 col-md-2"
                     @if(in_array($account,['leowang','童宇周','陶建华','王洪艳']))
                 style="display:none;"
                 @endif

                >
                    <button class="btn btn-primary" id="id_set_select_set_free">批量回流公海</button>
                </div>

                <div class="col-xs-3 col-md-2">
                    <button class="btn btn-primary" id="id_set_select_to_admin_list">分配给组员</button>
                </div>

                <div class="col-xs-3 col-md-2"
                     @if(in_array($account,['leowang','童宇周','陶建华','王洪艳']))
                 style="display:none;"
                 @endif

                >
                    <button class="btn btn-primary" id="id_tmk_set_select_to_cc_list">TMK分配给CC</button>
                </div>

                <div class="col-xs-3 col-md-2"
                     @if(in_array($account,['leowang','童宇周','陶建华','王洪艳']))
                 style="display:none;"
                 @endif

                >
                    <button class="btn btn-primary" id="id_set_select_to_tmk_list">分配给TMK</button>
                </div>

                <div class="col-xs-3 col-md-1"
                     @if(in_array($account,['leowang','童宇周','陶建华','王洪艳']))
                 style="display:none;"
                 @endif

                >
                    <button class="btn btn-primary" id="id_tq_no_call_btn">TQ未回访</button>
                </div>

                <div class="col-xs-6 col-md-8">
                    <button class="btn" id="id_unallot" data-value="{{$unallot_info["zjs_unallot_count"]*1}}" > </button>
                    <button class="btn" id="id_unset_admin_revisiterid" data-value="{{$unallot_info["all_unallot_count"]*1}}" > </button>
                    <button class="btn" id="id_all_unallot_count_hight_school" data-value="{{@$unallot_info["all_unallot_count_hight_school"]*1}}" > </button>
                    <button class="btn" id="id_all_unallot_count_Y" data-value="{{@$unallot_info["all_unallot_count_Y"]*1}}" > </button>
                    <button class="btn" id="id_tmk_unallot" data-value="{{$unallot_info["tmk_unallot_count"]*1}}" > </button>
                    <button class="btn" id="id_all_uncall_count" data-value="{{@$unallot_info["all_uncall_count"]*1}}" > </button>
                    <button class="btn" id="id_by_hand_all_uncall_count" data-value="{{@$unallot_info["by_hand_all_uncall_count"]*1}}" > </button>
                </div>


            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td style="width:10px">
                        <a href="javascript:;" id="id_select_all" title="全选">全</a>
                        <a href="javascript:;" id="id_select_other" title="反选">反</a>
                    </td>
                    <td style="width:60px">时间</td>
                    <td style="display:none;">手机号</td>
                    <td >基本信息</td>
                    <td >地区</td>
                    <td >来源</td>
                    <td >回访间隔</td>
                    @if ($show_list_flag==0)
                        <td style="display:none;">例子第一次拨打时间</td>
                        <td style="width:70px">回访状态</td>
                        <td style="width:70px">子状态</td>
                        <td >全局TQ状态</td>
                        <td >系统判定无效</td>

                        <td >用户备注</td>
                        <td >年级</td>
                        <td >科目</td>
                        <td >是否有pad</td>
                        <td >负责人</td>
                        <td >负责人联系次数</td>
                        <td >TMK负责人</td>
                        <td >抢单人/时间</td>
                        <td style="display:none" >试听申请人</td>
                    @else
                        <td >tmk 有效/时间</td>
                        <td >tmk 分配cc/时间</td>
                        <td >分配主管/时间 </td>
                        <td >分配cc/时间 </td>
                        <td > 状态 </td>
                    @endif
                    <td style="min-width:130px" >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>
                            <input type="checkbox" class="opt-select-item" data-userid="{{$var["userid"]}}"/>   {{$var["index"]}}
                        </td>
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

                        <td

                        >
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                @if(in_array($account,['leowang','龚昊天','潘腾野','孙佳旭','童宇周','孙海俊','陶建华','王洪艳']))
                                style="display:none;"
                                @endif
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
