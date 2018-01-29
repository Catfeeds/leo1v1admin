@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg.js?v={{@$_publish_version}}"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_forbid.js?v={{@$_publish_version}}"></script>
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" >
     var g_adminid_right= <?php  echo json_encode($adminid_right); ?> ;
    </script>

    <section class="content">
        <div class="row  row-query-list "  >
            <div class="col-xs-6 col-md-2 " data-always_show="1"  >
                    <div class="input-group ">
                        <span class="input-group-addon">账号</span>
                        <input class="opt-change form-control" id="id_adminid" />
                    </div>
            </div>
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
            @if (  isset($html_power_list["input_account_role"] ) || true  )
            <div class="col-md-2 col-xs-0">
                <div class="input-group ">
                    <span>角色</span>
                    <select class="opt-change" id="id_account_role">
                    </select>
                </div>
            </div>
            @endif

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
            <div class="col-xs-6 col-md-2" style="display:none;">
                <div class="input-group ">
                    <span class="input-group-addon">全职老师分类</span>
                    <select class="opt-change form-control" id="id_fulltime_teacher_type" >
                    </select>
                </div>
            </div>
            <div  class="col-xs-6 col-md-4">
                <div class="input-group ">
                    <span class="input-group-addon">组织架构</span>
                    <input class="opt-change form-control" id="id_seller_groupid_ex" />
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
            <div class="col-xs-6 col-md-2" data-always_show="1">
                <div class="input-group ">
                    <span class="input-group-addon">打电话类型</span>
                    <select class="opt-change form-control" id="id_call_phone_type" >
                    </select>
                </div>
            </div>
            <div class="col-md-2 col-xs-6">
                <button class="btn btn-warning  add_player " >添加</button>
                <button id="id_email_list" class="btn btn-primary " >邮箱</button>
            </div>
            @if($flag)
                <div class="col-md-2 col-xs-6">
                    <button id="id_flush_power" class="btn btn-warning">加载权限</button>
                </div>
            @endif

            @if(in_array($account,["jim","jack","孙瞿"]))
                <div class="col-md-1 remove-for-xs col-xs-6 "  >
                    <div>
                        <button class="btn btn-primary" id="id_upload_xls"> 上传 </button>
                    </div>
                </div>
            @endif
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

                    @if (  isset($html_power_list["account_role"] ) || true )
                        <td>角色</td>
                    @endif
                    <td>权限组</td>
                    <td>创建者</td>
                    <td>是否转正</td>
                    <td >考勤卡id</td>
                    <td style="display:none;">微信openid</td>
                    <td >上级</td>
                    <td >微信号/姓名</td>
                    <td > 状态</td>
                    <td >每天新例子 </td>
                    <td > 咨询师权限等级/不参与升级</td>
                    <td > 入职时间</td>
                    <td > 离职时间</td>
                    <td > 打电话账号</td>
                    <td>操作</td>
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

                            @if (  isset($html_power_list["account_role"] ) || true  )
                                <td>{{$var["account_role_str"]}}</td>
                            @endif

                            <td>{{$var["permission"]}}</td>
                            <td>{{$var["creater_admin_nick"]}}</td>
                            <td>{{$var["become_full_member_flag_str"]}}</td>
                            <td>{{$var["cardid"]}}</td>
                            <td>{{$var["wx_openid"]}}</td>
                            <td>{{$var["up_admin_nick"]}}</td>
                            <td> {{$var["wx_id"]}} /{{$var["nickname"]}}</td>
                            <td>{{$var["del_flag_str"]}}</td>
                            <td>{{$var["day_new_user_flag_str"]}}</td>
                            <td>{{$var["seller_level_str"]}}/{{$var["no_update_seller_level_flag_str"]}}</td>
                            <td>{{$var["become_time"]}}</td>
                            <td>{{$var["leave_time"]}}</td>
                            <td>{{$var["tquin"]}}</td>
                            <td  >
                                <div class="div-row-data"
                                     {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >
                                    <a href="javascript:;" title="修改密码" class="fa fa-key opt-set-passwd"></a>
                                    <a href="javascript:;" title="编辑" class="fa fa-edit edit-manage"></a>
                                    <a href="javascript:;" title="设置角色" class="fa fa-venus-double set-account-role"></a>
                                    <a href="javascript:;" title="绑定微信账号" class="fa fa-link opt_set_openid"></a>
                                    <a href="javascript:;" title="更改员工状态" class="fa fa-trash-o  opt-del"></a>
                                    @if(in_array($account,['孙瞿',"jim","顾培根"]))
                                        <a href="javascript:;" title="超级更改权限组" class="fa fa-hand-o-up opt-power"></a>
                                    @endif
                                    <!-- <a href="javascript:;" title="一般更改权限组" class="fa fa-hand-o-up opt-power2"></a> -->

                                    <a href="javascript:;" title="用此账号登录" class="opt-login">登录</a>
                                    <a href="javascript:;" title="修改账号"
                                       class="fa fa-gears opt-change-account"> </a>
                                    <a href="javascript:;" title="同步考勤" class="fa fa-refresh opt-sync-kaoqin  "> </a>
                                    <a href="javascript:;" title="邮箱配置" class="opt-email"> 邮箱 </a>
                                    <a href="javascript:;" title="设置全职老师类型" class="opt-set-fulltime-teacher-type"> 全</a>
                                    <a href="javascript:;" title="生成学生和家长账号" class="opt-set-user-phone">生成账号</a>
                                    <a href="javascript:;" title="生成助教账号" class="opt-gen-ass">助</a>
                                    <a href="javascript:;" title="用户操作日志" class="opt-log">log</a>
                                    <a href="javascript:;" title="刷新回访" class="opt-refresh_call_end">刷新回访</a>
                                    @if($var["account_role"]==5)
                                        <a href="javascript:;" title="同步老师入职时间" class="opt-set-train-through-time">同</a>
                                        <a href="javascript:;" title="修改老师等级" class="opt-set-teacher-level">等</a>
                                    @endif
                                    @if(in_array($account,["jim","jack","孙瞿"]))
                                        <a href="javascript:;" title="权限删除测试" class="opt-delete-permission">权限删除测</a>
                                    @endif
                                    @if(in_array($account,["michelle","jack","jim"]))
                                        <a href="javascript:;" title="权限备份互换" class="opt-change-permission-new">权限更换</a>
                                    @endif
                                    @if(in_array($account, ['jim', 'ricky',"孙瞿"]))
                                        <a href="javascript:;" title="个人拥有权限" class="opt-ower-permission">个人权限</a>
                                    @endif
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
