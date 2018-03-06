@extends('layouts.app')

@section('content')
<script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
<script type="text/javascript" src="/js/qiniu/ui.js"></script>
<script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
<script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
<script type="text/javascript" src="/js/jquery.md5.js"></script>
<script type="text/javascript" src="/page_js/dlg_return_back.js"></script>

    <script type="text/javascript" src="/page_js/seller_student_new/common.js?{{@$_publish_version}}"></script>
    <section class="content ">

        <!-- 此处为模态框-->



        <div>

            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-6"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <!-- <div class="col-xs-6 col-md-2">
                     <div class="input-group ">
                     <span class="input-group-addon">状态</span>
                     <select class="opt-change form-control" id="id_seller_student_status" >
                     </select>
                     </div>
                     </div>

                     <div class="col-xs-6 col-md-3">
                     <div class="input-group ">
                     <span class="input-group-addon">TQ状态</span>
                     <input class="opt-change form-control" id="id_global_tq_called_flag" />
                     </div>
                     </div>


                     <div class="col-xs-6 col-md-2">
                     <div class="input-group ">
                     <span class="input-group-addon">年级</span>
                     <input class="opt-change form-control" id="id_grade" />
                     </div>
                     </div>


                     <div class="col-xs-6 col-md-2">
                     <div class="input-group ">
                     <span class="input-group-addon">科目</span>
                     <input class="opt-change form-control" id="id_subject" />
                     </div>
                     </div>
                -->
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td style="width:60px">时间</td>
                    <td >基本信息</td>
                    <td >来源</td>
                    <td >CC标注</td>
                    <td >TMK标注</td>
                    <td style="width:70px">回访状态</td>
                    <td style="width:70px">子状态</td>
                    <td >全局TQ状态</td>
                        {!!\App\Helper\Utils::th_order_gen([
                            ["回公海次数","return_publish_count" ],
                           ])!!}


                    <td >cc备注</td>
                    <td >tmk备注</td>
                    <td >年级</td>
                    <td >科目</td>
                    <td >是否有pad</td>
                    <td >负责人</td>
                    <td >联系负责人</td>
                    <td style="min-width:130px" >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["add_time"]}} </td>
                        <td>
                            <a href="javascript:;" class="show_phone" data-phone="{{$var["phone"]}}" >
                                {{@$var["phone_hide"]}}
                            </a>
                            {{$var["phone_location"]}} <br/>
                            {{$var["nick"]}}
                        </td>

                        <td>
                            @if  ($var["origin_assistantid"]==0)
                                {{$var["origin"]}} ({{$var["origin_level_str"]}}) <br/>
                            @else
                                转介绍: {{$var["origin_assistant_nick"]}} <br/>
                            @endif
                        </td>
                        <td>
                            @if($var['cc_mark'])
                                <?php foreach(explode('|',$var['cc_mark']) as $v){
                                echo $v.'<br>';
                                } ?>
                            @else

                            @endif
                        </td>
                        <td>
                            @if($var['tmk_mark'])
                                <?php
                                  foreach(explode('|',$var['tmk_mark']) as $v){
                                    echo $v.'<br>';
                                  }
                                ?>
                            @else

                            @endif
                        </td>
                        <td>
                            {{$var["seller_student_status_str"]}} <br/>
                        </td>
                        <td>
                            {{$var["seller_student_sub_status_str"]}}
                        </td>

                        <td>
                            {{$var["global_tq_called_flag_str"]}} <br/>
                        </td>

                        <td>
                            {{$var["return_publish_count"]}} <br/>
                        </td>



                        <td>
                            {{$var["user_desc"]}} <br/>
                        </td>


                        <td>
                            {{@$var["tmk_desc"]}} <br/>
                        </td>

                        <td>
                        <td>
                            {{$var["grade_str"]}} <br/>
                        </td>

                        <td>
                            {{$var["subject_str"]}} <br/>
                        </td>

                        <td>
                            {{$var["has_pad_str"]}} <br/>
                        </td>

                        <td>
                            {{$var["sub_assign_admin_2_nick"]}} / {{$var["admin_revisiter_nick"]}}
                            <br/>
                        </td>


                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa-volume-up opt-audio_all btn fa"> </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
        <div style="display:none;" >
            <div id="id_assign_log">
                <table class="table table-bordered ">
                    <tbody><tr>  <th> 角色 </th><th>姓名 </th><th>录音 </th></tr>
                    </tbody><tbody class="data-body">
                    </tbody>
                </table>
            </div>
        </div>
    </section>

@endsection
