@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="/js/wb-reply/audio.js"></script>
    <section class="content ">


        <div class="row">

            <div class="col-xs-12 col-md-5">
                <div id="id_date_range"> </div>
            </div>



            <div class="col-xs-6 col-md-3">
                <div class="input-group ">
                    <span class="input-group-addon">电话</span>
                    <input class="opt-change form-control" id="id_phone" />
                </div>
            </div>

            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">是否打通</span>
                    <select class="opt-change form-control" id="id_is_called_phone" >
                    </select>
                </div>
            </div>

            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">uid</span>
                    <input class="opt-change form-control" id="id_uid" />
                </div>
            </div>


            <div class="col-xs-6 col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">用户id</span>
                    <input class="opt-change form-control" id="id_userid" />
                </div>
            </div>


            <div class="col-xs-12 col-md-4">
                <div class="input-group ">
                    <span class="input-group-addon">状态</span>
                    <input class="opt-change form-control" id="id_seller_student_status" />
                </div>
            </div>
             <div class="col-md-3 col-xs-0" data-always_show="1">
                <div class="input-group col-sm-12"  >
                    <input  id="id_user_info" type="text" value="" class="form-control opt-change"  placeholder="输入拨打者名字，回车查找" />
                </div>
            </div>
        </div>

        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>时间</td>
                    <td>接通时间</td>
                    <td>结束时间</td>
                    <td>拨打者</td>
                    <td>角色</td>
                    <td>拨打者id</td>
                    <td>电话</td>
                    <td>状态</td>
                    <td>是否打通</td>
                    <td>挂机人</td>
                    <td>通话时长(秒)</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["start_time"]}} </td>
                        <td>{{$var["obj_start_time"]}} </td>
                        <td>{{$var["end_time"]}} </td>
                        <td>{{$var["admin_nick"]}} </td>
                        <td>{{$var["admin_role_str"]}} </td>
                        <td>{{$var["uid"]}} </td>
                        <td>{{$var["phone"]}} </td>
                        <td>{{$var["seller_student_status_str"]}} </td>
                        <td> <font color="{{ $var["is_called_phone"]?"green":"red"   }}"> {{$var["is_called_phone_str"]}} </font> </td>
                        <td>{{$var["end_reason_str"]}} </td>
                        <td>{{$var["duration"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa-volume-up  opt-audio "> </a>


                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
