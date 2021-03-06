@extends('layouts.app')
@section('content')


    <script type="text/javascript" src="/page_js/lib/select_dlg.js?v={{@$_publish_version}}"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>

    <section class="content">
        <div class="row  row-query-list "  >
            <div class="col-md-3 col-xs-0" data-always_show="1">

                <div class="input-group col-sm-12"  >
                    <input  id="id_user_info" type="text" value="" class="form-control opt-change"  placeholder="输入用户名/电话，回车查找" />
                </div>
            </div>

            <div class="col-md-3 col-xs-0">
                <div class="input-group ">
                    <span>包含题库用户</span>
                    <select id="id_has_question_user" class="opt-change">
                        <option value="-1">全部 </option>
                        <option value="0">否 </option>
                        <option value="1">是</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2 col-xs-0">
                <div class="input-group ">
                    <span>角色</span>
                    <select class="opt-change" id="id_account_role">
                    </select>
                </div>
            </div>
            <div class="col-md-2 col-xs-0">
                <div class="input-group ">
                    <span>状态</span>
                    <select id="id_del_flag" class="opt-change" >
                        <option value="-1">全部 </option>
                        <option value="0">在职 </option>
                        <option value="1">离职</option>
                    </select>
                </div>
            </div>
            <div class="col-xs-6 col-md-2" data-always_show="1">
                <div class="input-group ">
                    <span class="input-group-addon">每天新例子</span>
                    <select class="opt-change form-control" id="id_day_new_user_flag" >
                    </select>
                </div>
            </div>



            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">cardid</span>
                    <input class="opt-change form-control" id="id_cardid" />
                </div>
            </div>
            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">tquin</span>
                    <input class="opt-change form-control" id="id_tquin" />
                </div>
            </div>


            <div class="col-md-2 col-xs-6">
                <button class="btn btn-primary fa fa-plus add_player form-control" width="100%">添加用户</button>
            </div>

        </div>

        <hr/>
        <table class="common-table" >

            <thead>
                <tr>
                    <td>id</td>
                    <td>用户名</td>
                    <td>真实姓名</td>
                    <td style="display:none;">电子邮箱</td>
                    <td>手机号</td>
                    <td>角色</td>
                    <td>权限组</td>
                    <td>创建者</td>
                    <td>是否转正</td>
                    <td >考勤卡id</td>
                    <td style="display:none;">微信openid</td>
                    <td >上级</td>
                    <td >微信号/姓名</td>
                    <td > 状态</td>
                    <td >每天新例子 </td>
                    <td > 咨询师等级</td>
                    <td >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                      <tr>
                            <td>{{$var["uid"]}}</td>
                            <td>{{$var["account"]}}</td>
                            <td>{{$var["name"]}}</td>
                            <td >{{$var["email"]}}</td>
                            <td>{{$var["phone"]}}</td>
                            <td>{{$var["account_role_str"]}}</td>
                            <td>{{$var["permission"]}}</td>
                            <td>{{$var["creater_admin_nick"]}}</td>
                            <td>{{$var["become_full_member_flag_str"]}}</td>
                            <td>{{$var["cardid"]}}</td>
                            <td>{{$var["wx_openid"]}}</td>
                            <td>{{$var["up_admin_nick"]}}</td>
                            <td> {{$var["wx_id"]}} /{{$var["nickname"]}}</td>
                            <td>{{$var["del_flag_str"]}}</td>
                            <td>{{$var["day_new_user_flag_str"]}}</td>
                            <td>{{$var["seller_level_str"]}}</td>
                            <td  >
                                <div
                                     {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >
                                    <!-- <a href="javascript:;" title="修改密码" class="fa fa-key opt-set-passwd"></a> -->
                                    <!-- <a href="javascript:;" title="编辑" class="fa fa-edit edit-manage"></a> -->
                                    <!-- <a href="javascript:;" title="设置角色" class="fa fa-venus-double set-account-role"></a> -->
                                    <!-- <a href="javascript:;" title="绑定微信账号" class="fa fa-link opt_set_openid"></a> -->
                                    <a href="javascript:;" title="更改员工状态" class="fa fa-trash-o  opt-del"></a>
                                    <!-- <a href="javascript:;" title="更改权限组" class="fa fa-hand-o-up opt-power"></a> -->
                                    <!-- <a href="javascript:;" title="用此账号登录" class="opt-login">登录</a>
                                         <a href="javascript:;" title="修改账号"
                                         class="fa fa-gears  opt-change-account"> </a>


                                         <a href="javascript:;" title="同步考勤"
                                         class="fa fa-refresh opt-sync-kaoqin  "> </a>
                                       -->


                                </div>
                            </td>
                        </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
        <div class="dlg_add_manager" style="display:none">
            <div class="row">
                <div class="input-group">
                    <span class="input-group-addon">用户名</span>
                    <input class="username" type="text">
                </div>
                <div class="input-group">
                    <span class="input-group-addon">真实姓名</span>
                    <input class="realname" type="text">
                </div>
                <div class="input-group">
                    <span class="input-group-addon">电子邮箱</span>
                    <input class="email" type="text">
                </div>
                <div class="input-group">
                    <span class="input-group-addon">手机号</span>
                    <input class="phone" type="text">
                </div>
                <div class="input-group">
                    <span class="input-group-addon">密码</span>
                    <input class="password" type="text">
                </div>



            </div>
        </div>
        <div class="dlg_edit_manage" style="display:none">
            <table class="table table-bordered table-striped">
              <tbody>
                <tr>
                </tr>
                <tr>
                  <td style="text-align:right; width:30%;">用户名</td>
                  <td><input value="" class="add_schoolid" type="text"/></td>
                </tr>
                <tr>
                  <td style="text-align:right; width:30%;">真实姓名</td>
                  <td><input value="" class="add_school_name" type="text"/></td>
                </tr>
                <tr>
                  <td style="text-align:right; width:30%;">电子邮箱</td>
                  <td><input value="" class="add_school_name_high" type="text"/></td>
                </tr>
                <tr>
                  <td style="text-align:right; width:30%;">手机号</td>
                  <td><input value="" class="add_school_major" type="text"/></td>
                </tr>
              </tbody>
          </table>
        </div>


    </section>
@endsection
