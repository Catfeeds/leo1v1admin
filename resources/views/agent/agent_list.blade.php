@extends('layouts.app')
@section('content')
    <section class="content ">
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-4" data-title="时间段">
                    <div id="id_date_range"> </div>
                </div>

                <div class="col-xs-6 col-md-2" data-always_show="1">
                    <div class="input-group ">
                        <input type="text" class=" form-control click_on put_name opt-change"  data-field="phone" id="id_phone"  placeholder="手机号 回车查找" />
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

        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>id </td>
                    <td style="display:none;">上级id</td>
                    <td>昵称</td>

                    {!!\App\Helper\Utils::th_order_gen([

                        ["提成金额" , "all_money"],
                        ["1级人数" , "l1_child_count"],
                        ["2级人数" , "l2_child_count"],
                        ["1级试听提成金额" , "l1_agent_status_all_money"],
                        ["1级试听提成金额(可提现)" , "l1_agent_status_all_open_money"],
                       ]) !!}

                    <td style="display:none;" >上级微信昵称</td>
                    <td style="display:none;" >上上级微信昵称</td>
                    <td>会员等级</td>
                    <td style="display:none;" >例子状态/当前cc</td>
                    <td style="display:none;">在读状态</td>
                    <td style="display:none;">是否试听/是否成功</td>

                    <td>试听时间</td>
                    <td>合同金额</td>
                    <td>上级分成</td>
                    <td>上上级分成</td>
                    <td>试听提成状态</td>
                    <td>试听提成上级是否可提现</td>
                    <td>绑定类型</td>
                    <td>渠道</td>
                    <td>创建时间</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["id"]}} </td>
                        <td>{{@$var["parentid"]}} </td>
                        <td>{{@$var["nickname"]}}/{{@$var["phone"]}} </td>
                        <td>{{@$var["all_money"]}} </td>
                        <td>{{@$var["l1_child_count"]}} </td>
                        <td>{{@$var["l2_child_count"]}} </td>
                        <td>{{@$var["l1_agent_status_all_money"]}} </td>
                        <td>{{@$var["l1_agent_status_all_open_money"]}} </td>
                        <td>
                            {{@$var["p_nickname"]}} <br/>
                            {{@$var["p_phone"]}}
                        </td>
                        <td>
                            {{@$var["pp_nickname"]}} <br/>
                            {{@$var["pp_phone"]}}
                        </td>
                        <td>{{@$var["agent_level_str"]}} </td>
                        <td>{{@$var["agent_student_status_str"]}}/{{@$var["cc_nick"]}} </td>
                        <td>{{@$var["student_stu_type_str"]}}</td>
                        <td>{!! @$var["test_lessonid_str"] !!}/{!! @$var["lesson_user_online_status_str"] !!}</td>
                        <td>{{@$var["lesson_start"]}} </td>
                        <td>{{@$var["price"]}} </td>
                        <td>{{@$var["p_off_info"]}} </td>
                        <td>{{@$var["pp_off_info"]}} </td>

                        <td>{{@$var["agent_status_str"]}} </td>
                        <td>{{@$var["agent_status_money_open_flag_str"]}} </td>

                        <td>{{@$var["agent_type_str"]}} </td>
                        <td>{{@$var["origin"]}} </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-wechat opt-wechat-desc"  title="微信数据"> </a>
                                <a class="fa fa-group  opt-user-link"  title="下线"> </a>
                                <a class="fa fa-refresh opt-reset-info"  title="刷新信息"> </a>
                                <a style="display:block;" class="fa fa-times opt-del" title="删除"> </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
