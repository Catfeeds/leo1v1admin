@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
<section class="content">

    <div class="row">
        <div class="col-xs-6 col-md-3">
            <div class="input-group ">
                <input type="text" value="" class=" form-control click_on put_name for_input"  data-field="user_name" id="id_user_name"  placeholder="家长姓名, 回车查找" />
            </div>
        </div>
        <div class="col-xs-6 col-md-3" >
            <div class="input-group ">
                <input type="text" value="" class="form-control click_on put_phone for_input"   placeholder="联系电话 回车查找" data-field="phone"   id="id_phone"  />
            </div>
        </div>
    </div>

    <hr/>

    <table   class=" common-table " style=" border-top: 3px solid #d2d6de; " >
        <thead>
            <tr>
                <td class="remove-for-xs"  >id</td>
                <td style="width:80px;" >姓名</td>
                <td class="remove-for-xs" >联系电话</td>
                <td class="remove-for-xs" >性别</td>
                <td class="remove-for-xs" >邮箱</td>
                <td class="remove-for-xs" >最后修改时间</td>
                <td class="remove-for-xs" >登陆情况</td>
                <td  style="width:220px" >操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
                <tr>
                    <td >{{$var["parentid"]}}</td>
                    <td >{{$var["nick"]}}</td>
                    <td >
                        <a href="javascript:;" class="show_phone" data-phone="{{$var["phone"]}}" >
                            {{@$var["phone_hide"]}}
                        </a>
                    </td>
                    <td >{{$var["gender"]}}</td>
                    <td >{{$var["email"]}}</td>
                    <td >{{$var["time"]}}</td>
                    <td >{{$var["has_login"]}}</td>
                    <td >
                        <div  data-parentid="{{$var["parentid"]}}"
                              data-nick="{{$var["nick"]}}"
                              data-phone="{{$var["phone"]}}"
                        >
                            <a class="fa-edit opt-edit" title="编辑信息" ></a>
                            <a class="fa-gavel opt-modify " title="设置密码"></a>
                            <a class="fa-user opt-user " title="个人信息" ></a>
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
    <div style="display:none;" class="cl_dlg_change_type">
        <div class="mesg_alertCont">
            <table border="0" cellspacing="0" width="100%" style="border-collapse:collapse;" class="stu_tab02">
                <tr>
                    <td width="30%">设置测试学员：</td>
                    <td width="70%" class="align_l"><select id="id_set_channel"><option value="-1">请选择</option></select></td>

                    </tr>
                </table>
        </div>
    </div>
    <div style="display:none;" >
        <select id="id_gender">
            <option value="1">男</option>
            <option value="2">女</option>
        </select>
        <select id="id_has_login">
            <option value="0">未登录</option>
            <option value="1">曾登陆</option>
        </select>

    </div>

    <div style="display:none;" class="cl_dlg_stu_origin">
        <div class="mesg_alertCont">
              <table border="0" cellspacing="0" width="100%" style="border-collapse:collapse;" class="stu_tab02">
                    <tr>
                        <td width="30%">设置渠道：</td>
                        <td width="70%" class="align_l">
                            <select id="id_stu_origin">
                                <option value="-1">请选择</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td width="30%">userid：</td>
                        <td width="70%" class="align_l"><input id="id_origin_userid"></input></td>
                    </tr>

                </table>
        </div>
    </div>

@endsection
