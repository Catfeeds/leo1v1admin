@extends('layouts.app')
@section('content')

<script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">渠道</span>
                        <input class="opt-change form-control" id="id_origin_ex" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">提交次数</span>
                        <input class="opt-change form-control" id="id_need_count" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">状态</span>
                        <select class="opt-change form-control" id="id_seller_student_status" >
                        </select>
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>渠道 </td>
                    <td> 提交时间 </td>
                    <td>  最后一次回访时间</td>
                    <td>电话 </td>
                    <td>昵称</td>
                    <td> 年级 </td>
                    <td> 设备 </td>
                    <td>  销售  </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["origin"]}} </td>
                        <td>{{$var["add_time"]}} </td>
                        <td>{{$var["last_revisit_time"]}} </td>
                        <td>{{$var["phone"]}} </td>
                        <td>{{$var["nick"]}} </td>
                        <td>{{$var["grade_str"]}} </td>
                        <td>{{$var["has_pad_str"]}} </td>
                        <td>{{$var["admin_revisiter_nick"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >


                                <a href="javascript:;" title="用户信息" class="fa-user opt-user"></a>
                                <a title="查看回访" class=" show-in-select  fa-comments  opt-return-back-list "></a>
                                <a title="查看报名信息" class="  fa-list  opt-post-info  "></a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
