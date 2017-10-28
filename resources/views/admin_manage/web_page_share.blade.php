@extends('layouts.app')
@section('content')
    <section class="content">

        <div class="row   "  >
            <div class="col-xs-12 col-md-12 " data-always_show="1"  >
            处理:
            {{ $web_page_info["title"] }} |
            {{ $web_page_info["url"] }}
            </div>
        </div>
        <div class="row  row-query-list "  >
            <div class="col-xs-6 col-md-2 " data-always_show="1"  >
                    <div class="input-group ">
                        <span class="input-group-addon">账号</span>
                        <input class="opt-change form-control" id="id_adminid" />
                    </div>
            </div>
            <div class="col-md-2 col-xs-0">
                <div class="input-group ">
                    <span>角色</span>
                    <select class="opt-change" id="id_account_role">
                    </select>
                </div>
            </div>

            <div class="col-md-2 col-xs-6">
                <button id="id_send" class="btn btn-primary"  > 发到cc 微信 </button>
            </div>
        </div>
        <hr/>
        <table class="common-table" >
            <thead>
                <tr>
                    <td style="width:10px">
                        <a href="javascript:;" id="id_select_all" title="全选">全</a>
                        <a href="javascript:;" id="id_select_other" title="反选">反</a>
                    </td>
                    <td>id</td>
                    <td>用户名</td>
                    <td>真实姓名</td>
                    <td style="display:none;">电子邮箱</td>
                    <td>手机号</td>
                    <td>角色</td>
                    <td>是否转正</td>
                    <td style="display:none;">微信openid</td>
                    <td >微信号/姓名</td>
                    <td > 咨询师等级</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
                      <tr>
                        <td> <input type="checkbox" class="opt-select-item" data-userid="{{$var["uid"]}}"/>    </td>
                            <td>{{$var["uid"]}}</td>
                            <td>{{$var["account"]}}</td>
                            <td>{{$var["name"]}}</td>
                            <td >{{$var["email"]}}</td>
                            <td>{{$var["phone"]}}</td>
                            <td>{{$var["account_role_str"]}}</td>
                            <td>{{$var["become_full_member_flag_str"]}}</td>
                            <td>{{$var["wx_openid"]}}</td>
                            <td> {{$var["wx_id"]}} /{{$var["nickname"]}}</td>
                            <td>{{$var["seller_level_str"]}}</td>
                            <td  >
                                <div class="div-row-data"
                                     {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >
                                </div>
                            </td>
                        </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
