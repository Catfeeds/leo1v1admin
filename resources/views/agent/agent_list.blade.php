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
                        <input type="text" class=" form-control click_on put_name opt-change"  data-field="p_phone" id="id_p_phone"  placeholder="上级手机号 回车查找" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2" data-always_show="1">
                    <div class="input-group ">
                        <input type="text" class=" form-control click_on put_name opt-change"  data-field="phone" id="id_phone"  placeholder="手机号 回车查找" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" data-always_show="1">
                    <div class="input-group ">
                        <span class="input-group-addon">绑定类型</span>
                        <select class="opt-change form-control" id="id_agent_type" >
                        </select>
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
                    <td>上级id</td>
                    <td>微信昵称</td>
                    <td>上级微信昵称</td>
                    <td>上上级微信昵称</td>
                    <td>手机号</td>
                    <td>会员等级</td>
                    <td>是否成功试听</td>
                    <td>试听时间</td>
                    <td>类型</td>
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
                        <td>{{@$var["nickname"]}} </td>
                        <td>{{@$var["p_nickname"]}} </td>
                        <td>{{@$var["pp_nickname"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["agent_level_str"]}} </td>
                        <td>{!! @$var["lesson_user_online_status_str"] !!} </td>
                        <td>{{@$var["lesson_start"]}} </td>
                        <td>{{@$var["agent_type_str"]}} </td>
                        <td>{{@$var["origin"]}} </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-wechat opt-wechat-desc"  title="微信数据"> </a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
