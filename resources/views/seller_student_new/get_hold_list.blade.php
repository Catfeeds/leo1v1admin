@extends('layouts.app')

@section('content')
<script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
<script type="text/javascript" src="/js/qiniu/ui.js"></script>
<script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
<script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
<script type="text/javascript" src="/js/jquery.md5.js"></script>
<script type="text/javascript" src="/page_js/dlg_return_back.js"></script>

    <section class="content ">
        <div>
            <div class="row  row-query-list" >

                <div class="col-xs-6 col-md-2" data-always_show="1" >
                    <div class="input-group ">
                        <span class="input-group-addon">保留</span>
                        <select class="opt-change form-control" id="id_hold_flag" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-3" data-always_show="1"   >
                    <div class="input-group ">
                        <input class="opt-change form-control" id="id_phone_name" placeholder="电话,姓名,回车搜索"/>
                    </div>
                </div>




                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">年级</span>
                        <select class="opt-change form-control" id="id_grade" >
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">科目</span>
                        <select class="opt-change form-control" id="id_subject" >
                        </select>
                    </div>
                </div>


                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">状态</span>
                        <select class="opt-change form-control" id="id_seller_student_status" >
                        </select>
                    </div>
                </div>
                <div class="col-md-2 col-xs-6 ">
                    <div class="input-group">
                        <span>每页个数</span>
                        <select id="id_page_count" class="opt-change  ">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                            <option value="500">500</option>
                        </select>
                    </div>
                </div>







            </div>

            <div class="row  " >
                <div class="col-xs-3 col-md-1">
                    <button class="btn btn-primary" id="id_set_hold">批量保留</button>
                </div>
                <div class="col-xs-3 col-md-1">
                    <button class="btn btn-info" id="id_set_no_hold">批量不保留</button>
                </div>
                <div class="col-xs-3 col-md-1">
                </div>


                <div class="col-xs-3 col-md-2">

                    <button class="btn btn-primary" id="id_set_no_hold_free">不保留->回流公海</button>
                </div>



                <div class="col-xs-6 col-md-2">

                    <button class="btn" id="id_hold_define_count" data-value="{{$hold_define_count}}" > </button>
                    <button class="btn" id="id_hold_cur_count" data-value="{{$hold_cur_count}}" > </button>
                </div>

                <div class="col-xs-3 col-md-2">
                    <button class="btn btn-primary" id="id_set_all_hold">全保留</button>
                </div>



            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td style="width:10px">
                        <a href="javascript:;" id="id_select_all" title="全选">全</a>
                        <a href="javascript:;" id="id_select_other" title="反选">反</a>
                    </td>
                    <td >是否保留</td>
                    <td style="width:60px">资源时间</td>
                    <td style="width:60px">不可回流原因</td>
                    <td >手机号</td>
                    <td >姓名</td>
                    <td style="width:60px">下次回访时间</td>
                    <td style="width:60px">试听时间</td>
                    <!-- <td >来源</td> -->
                    <td style="width:70px">回访状态</td>
                    <td >用户备注</td>
                    <td >年级</td>
                    <td >科目</td>
                    <td style="min-width:130px" >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td> <input type="checkbox" class="opt-select-item" data-userid="{{$var["userid"]}}"/>   {{$var["index"]}} </td>
                            <td>{!!   $var["hold_flag_str"]!!} </td>
                        <td>{{$var["add_time"]}} </td>
                        <td>{{$var["set_not_hold_err_msg"]}} </td>
                        <td>{{$var["phone"]}} </td>
                        <td> {{$var["nick"]}} </td>
                        <td> {{$var["next_revisit_time"]}} </td>
                        <td> {{$var["lesson_start"]}} </td>
                        <!-- <td>
                             @if  ($var["origin_assistantid"]==0)
                             {{$var["origin"]}}
                             @else
                             转介绍:  <br/>
                             @endif
                             </td> -->
                        <td>
                            {{$var["seller_student_status_str"]}} <br/>
                        </td>

                        <td>
                            {{$var["user_desc"]}} <br/>
                        </td>

                        <td>
                            {{$var["grade_str"]}} <br/>
                        </td>

                        <td>
                            {{$var["subject_str"]}} <br/>
                        </td>

                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}


                            >
                                <a href="javascript:;" title="用户信息" class="fa-user opt-user"></a>
                                <a title="查看回访" class=" show-in-select  fa-comments  opt-return-back-list "></a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
