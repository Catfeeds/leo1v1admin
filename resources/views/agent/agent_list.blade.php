@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" src="/page_js/seller_student_new/common.js?{{@$_publish_version}}"></script>
    <section class="content ">
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-4" data-title="时间段">
                    <div id="id_date_range"> </div>
                </div>

                <div class="col-xs-6 col-md-2" data-always_show="1">
                    <div class="input-group ">
                        <input type="text" class=" form-control click_on put_name opt-change"  data-field="phone" id="id_phone"  placeholder="手机号/微信昵称 回车查找" />
                    </div>
                </div>

        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon" data-always_show="1" >等级</span>
                <input class="opt-change form-control" id="id_agent_level" />
            </div>
        </div>


        <div class="col-xs-6 col-md-2" data-always_show="1">
            <div class="input-group ">
                <span class="input-group-addon">合同</span>
                <select class="opt-change form-control" id="id_order_flag" >
                </select>
            </div>
        </div>

        <div class="col-xs-6 col-md-3" data-always_show="1">
            <div class="input-group ">
                <span class="input-group-addon">1级个数</span>
                <input class="opt-change form-control" id="id_l1_child_count"  placeholder="[数字-数字]"
                />
            </div>
        </div>

        <div class="col-xs-6 col-md-2" data-always_show="1">
            <div class="input-group ">
                <span class="input-group-addon">是否试听</span>
                <select class="opt-change form-control" id="id_test_lesson_flag" >
                </select>
            </div>
        </div>

                <div class="col-xs-6 col-md-2" data-always_show="1">
                    <div class="input-group ">
                        <span class="input-group-addon">绑定类型</span>
                        <select class="opt-change form-control" id="id_agent_type" >
                        </select>
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" data-always_show="1">
                    <div class="input-group ">
                        <input type="text" class=" form-control click_on put_name opt-change"  data-field="p_phone" id="id_p_phone"  placeholder="上级手机号 回车查找" />
                    </div>
                </div>

                <!-- <div class="col-xs-6 col-md-2" data-always_show="1">
                     <div class="input-group ">
                     <span class="input-group-addon">是否成功试听</span>
                     <select class="opt-change form-control" id="id_success_flag" >
                     </select>
                     </div>
                     </div> -->
                <div class="col-xs-6 col-md-2">
                    <button style="display:none;" id="id_add">添加账号</button>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row have_userid">
            <div class="col-xs-12 col-md-4">
                <div class="input-group ">
                    <span class="input-group-addon">会员总量:{{@$agent_total_num}}</span>
                    <span class="input-group-addon"> <a href="javascript:;" id="id_show_all">全部</a></span>
                </div>
            </div>
        </div>

        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>id </td>
                    <td>会员名</td>
                    <td style="display:none;">个人试听</td>
                    <td style="display:none;">个人签单</td>
                    <td style="display:none;">签单金额</td>
                    <td style="display:none;">签单销售</td>
                    <td style="display:none;">负责助教</td>
                    <td>会员等级</td>
                    <td>绑定类型</td>
                    <td>上级</td>
                    <td>上上级</td>

                    {!!\App\Helper\Utils::th_order_gen([
                        ["总金额" , "all_yxyx_money"],
                        ["当前可提现" , "all_open_cush_money"],
                        ["已提现" , "all_have_cush_money"],
                    ]) !!}
                    <td>邀请学员量</td>
                    <td>邀请会员量</td>
                    <td>邀请学员+会员量</td>

                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["id"]}} </td>
                        <td>{{@$var["nickname"]}}/{{@$var["phone"]}} </td>
                        <td>{{@$var['is_test_lesson_str']}}</td>
                        <td>{{@$var['self_order_count']}}</td>
                        <td>{{@$var['self_order_price']}}</td>
                        <td>{{@$var['sys_operator']}}</td>
                        <td>{{@$var['teach_assistantant']}}</td>
                        <td>{{@$var["agent_level_str"]}} </td>
                        <td>{{@$var["agent_type_str"]}} </td>
                        <td>{{@$var["p_nickname"]}}/{{@$var["p_phone"]}}</td>
                        <td>{{@$var["pp_nickname"]}}/{{@$var["pp_phone"]}}</td>
                        <td>{{@$var["all_yxyx_money"]}} </td>
                        <td>{{@$var["all_open_cush_money"]}} </td>
                        <td>{{@$var["all_have_cush_money"]}} </td>
                        <td>{{@$var["child_student_count"]}} </td>
                        <td>{{@$var["child_member_count"]}} </td>
                        <td>{{@$var["child_student_member_count"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-wechat opt-wechat-desc"  title="微信数据"> </a>
                                <!-- <a class="fa fa-group  opt-user-link"  title="下线"> </a> -->
                                <a class="fa fa-user student_info"  title="学员明细"> </a>
                                <a class="fa fa-user-times member_info"  title="会员明细"> </a>
                                <a class="fa fa-user-plus  member_student_info"  title="会员+学员明细"> </a>
                                <a class="fa fa-refresh opt-reset-info"  title="刷新信息"> </a>
                                <a class="fa fa-times opt-del" title="删除"> </a>
                                <a title="查看回访" class=" show-in-select  fa-comments  opt-return-back-list "></a>
                                <a class="fa fa-phone opt-telphone " title="电话列表"> </a>
                                <a class="fa fa-wechat opt-wechat-new-desc"  title="微信数据新版"> </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
