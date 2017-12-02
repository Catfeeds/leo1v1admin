@extends('layouts.app')
@section('content')

    <section class="content ">
        <div>
            <div class="row  row-query-list" >

                <div class="col-xs-12 col-md-5">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">grabid</span>
                        <input class="opt-change form-control" id="id_grabid" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">抢课链接</span>
                        <input class="opt-change form-control" id="id_grab_lesson_link" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">有效时间(分)</span>
                        <input class="opt-change form-control" id="id_live_time" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">adminid</span>
                        <input class="opt-change form-control" id="id_adminid" />
                    </div>
                </div>


            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td> grabid </td>
                    <td> 抢课链接  </td>
                    <td> 生成时间  </td>
                    <td> 有效时间(分)  </td>
                    <td> 排课人 </td>
                    <td> 排课数 </td>
                    <td style="display:none;"> requireids </td>
                    <td> 访问次数 </td>
                    <td> 抢课次数 </td>
                    <td> 成功次数 </td>
                    <td> 失败次数 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["grabid"]}} </td>
                        <td>{{@$var["grab_lesson_link"]}} </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>{{@$var["live_time"]}} </td>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["lesson_count"]}} </td>
                        <td>{{@$var["requireids"]}} </td>
                        <td>{{@$var["visit_count"]}} </td>
                        <td>{{@$var["grab_count"]}} </td>
                        <td>{{@$var["succ_count"]}} </td>
                        <td>{{@$var["fail_count"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-list opt-visit-list btn"  title="访问详情"> </a>
                                <!-- <a class="fa fa-times opt-del" title="删除"> </a>
                                   -->
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
