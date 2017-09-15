@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_edit.js?v={{@$_publish_version}}"></script>
<section class="content " >
    <div class="row ">
        
    </div>
    <hr/>
        <table   class=" common-table "    >
            <thead>
                <tr>
                   
                    <td >助教</td>
                    <td >未试听扩课数</td>
                   
                    <td >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                    <tr>
                        <td  >{{$var["userid"]}} </td>
                        <td class="user_nick">
                            {{$var["nick"]}}<br/>
                            实名:{{$var["realname"]}}
                        </td>
                        <td >{{$var["cache_nick"]}}</td>
                        <td >{{$var["realname"]}}</td>
                        <td class="" >{{$var["parent_name"]}}</td>
                        <td class="td-parent-type" data-v="{{$var["parent_type"]}}"></td>
                        <td class="user_phone" >{{$var["phone"]}} <br/>
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
