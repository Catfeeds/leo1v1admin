@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_edit.js?v={{@$_publish_version}}"></script>
<section class="content " >
    <div class="row row-query-list">
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">年级</span>
                <select class="opt-change form-control" name="id_grade" id="id_grade" >
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-md-4" data-always_show="1">
            <div class="input-group ">
                <input type="text" class=" form-control click_on put_name opt-change"  data-field="user_name" id="id_user_name"  placeholder="学生/家长姓名, 手机号,userid 回车查找" />
            </div>
        </div>
        <div class="col-xs-6 col-md-2" >
            <div class="input-group ">
                <span >助教</span>
                <input id="id_assistantid"  />
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">测试学员</span>
                <select class="opt-change form-control" id="id_test_user" >
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span class="input-group-addon">学员渠道</span>
                <select class="opt-change form-control" id="id_originid" >
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span >销售</span>
                <input id="id_seller_adminid"  class="opt-change" />
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span >排序</span>
                <select id="id_order_type"  class="opt-change" >
                    <option value="-1">不指定</option>
                    <option value="1">剩余课时</option>
                    <option value="2">赞</option>
                    <option value="3">分配助教时间</option>
                </select>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <span>学生类型</span>
                <select id="id_student_type"  class="opt-change" >
                </select>
            </div>
        </div>
    </div>
    <hr/>
        <table   class=" common-table "    >
            <thead>
                <tr>
                    <td class="remove-for-xs"  >id</td>
                    <td style="width:80px;" >昵称</td>
                    <td style="display:none; width:80px;" >缓冲昵称</td>
                    <td style="display:none;">真正姓名</td>
                    <td style="display:none;" >家长姓名</td>
                    <td style="display:none;" >关系</td>
                    <td class="remove-for-xs" >联系电话</td>
                    <td style="display:none;" >地区</td>
                    <td class="remove-for-xs" >年级</td>
                    <td style="display:none;" >下一年级</td>
                    <td style="width:50px;" >签约课时</td>
                    <td style="width:50px;" >剩余课时</td>
                    <td >赞</td>
                    <td style="display:none;" >是否是测试学员</td>
                    <td style="display:none;" >学员渠道</td>
                    <td style="display:none;" >渠道(2)</td>
                    <td class="remove-for-xs"  style="width:160px"  >版本信息</td>
                    <td style="display:none;"   >版本信息-all</td>
                    <td style="display:none;"   >最近登录IP</td>
                    <td style="display:none;"   >最近上课时间</td>
                    <td >助教</td>
                    <td >转介绍助教</td>
                    <td style="display:none;">分配助教时间</td>
                    <td style="display:none;">销售</td>
                    <td style="display:none;">大礼包</td>
                    <td style="display:none;" >注册时间</td>
                    <td style="width:220px" >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                    <tr>
                        <td >{{$var["userid"]}}</td>
                        <td class="user_nick">
                            {{$var["nick"]}}<br/>
                            实名:{{$var["realname"]}}
                        </td>
                        <td >{{$var["cache_nick"]}}</td>
                        <td >{{$var["realname"]}}</td>
                        <td class="">{{$var["parent_name"]}}</td>
                        <td class="td-parent-type" data-v="{{$var["parent_type"]}}"></td>
                        <td class="user_phone">
                            <a href="javascript:;" class="show_phone" data-phone="{{$var["phone"]}}" >
                                {{@$var["phone_hide"]}}
                            </a>
                            <br>
                            {{$var["phone_location"]}}
                        </td>
                        <td  > {{$var["phone_location"]}}</td>
                        <td class="td-grade" data-v="{{$var["grade"]}}"  ></td>
                        <td class="td-grade-up" data-v="{{$var["grade_up"]}}"  ></td>
                        <td>{{$var["lesson_count_all"]}}</td>
                        <td>{{$var["lesson_count_left"]}}</td>
                        <td >{{$var["praise"]}}</td>
                        <td >{{$var["is_test_user_str"]}}</td>
                        <td >{{$var["originid"]}}</td>
                        <td >{{$var["origin"]}}</td>
                        <td >{{$var["user_agent_simple"]}}</td>
                        <td >{{$var["user_agent"]}}</td>
                        <td >{{$var["last_login_ip"]}}</td>
                        <td >{{$var["last_lesson_time"]}}</td>
                        <td >{{$var["assistant_nick"]}}</td>
                        <td >{{$var["origin_ass_nick"]}}</td>
                        <td >{{$var["ass_assign_time"]}}</td>
                        <td >{{$var["seller_admin_nick"]}}</td>
                        <td >{{$var["spree"]}}</td>
                        <td >{{$var["reg_time"]}}</td>

                        <td  >
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa-user opt-user " title="个人信息" ></a>
                                <a class="fa-comment opt-return-back " title="回访" ></a>
                                <a class="fa-comments opt-return-back-list " title="回访列表" ></a>
                                <a class="fa-calendar opt-lesson " title="排课"></a>
                                <a class="fa-gavel  opt-set-tmp-passwd " title="设置密码"></a>
                                <a class="fa-gratipay opt-test-user" title="设置测试用户"></a>
                                <a class="fa-headphones opt-test-room" title="设置试听"></a>
                                <a class="fa-hand-o-up opt-stu-origin" title="设置学员渠道"></a>
                                <a class="fa-refresh opt-left-time" title="重置课时"></a>
                                <a class="fa-truck opt-set-spree" title="设置大礼包"></a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @include("layouts.page")

    @include("layouts.return_record_add")

    <div class="dlg_set_dynamic_passwd" style="display:none">
        <div class="row ">
            <div class="input-group">
                <label class="stu_nick"> </label>
                <label class="stu_phone"> </label>
            </div>
        </div>
        <div class="row">
            <div class="input-group">
                <span class="input-group-addon">请输入临时密码</span>
                <input type="text" class="dynamic_passwd" />
            </div>
        </div>
    </div>
@endsection
