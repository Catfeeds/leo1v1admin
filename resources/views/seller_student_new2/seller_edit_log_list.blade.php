@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/select_seller_month_thing.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <link rel='stylesheet' href='/css/fullcalendar.css' />
    <script src='/js/moment.js'></script>
    <script src='/js/fullcalendar.js'></script>
    <script src='/js/lang-all.js'></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time.js"></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time_new.js"></script>
    <script type="text/javascript" src="/page_js/seller_student/common.js"></script>
    <style>
     #cal_week th  {
         text-align:center;
     }

     #cal_week td  {
         text-align:center;
     }

     #cal_week .select_free_time {
         background-color : #17a6e8;
     }
    </style>

    <section class="content ">
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-4" >
                    <div class="input-group ">
                        <span class="input-group-addon">渠道选择</span>
                        <input class="opt-change form-control" id="id_origin_ex" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-4" data-always_show="1">
                    <div class="input-group ">
                        <input type="text" class=" form-control click_on put_name opt-change"  data-field="user_name" id="id_user_name"  placeholder="学生/家长姓名, 手机号,userid 回车查找" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">分配人</span>
                        <input class="opt-change form-control" id="id_adminid" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">被分配人</span>
                        <input class="opt-change form-control" id="id_uid" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">被分配类型</span>
                        <select class="opt-change form-control" id="id_hand_get_adminid" >
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>分配人 </td>
                    <td>被分配人 </td>
                    <td>学生 </td>
                    <td>分配类型 </td>
                    <td>渠道 </td>
                    <td>是否联系 </td>
                    <td>是否删除 </td>
                    <td>分配时间 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["adminid_nick"]}} </td>
                        <td>{{@$var["uid_nick"]}} </td>
                        <td>{{@$var["phone_hide"]}} </td>
                        <td>{{@$var["hand_get_adminid_str"]}} </td>
                        <td>{{@$var["origin"]}} </td>
                        <td>{!! @$var["global_tq_called_flag_str"] !!} </td>
                        <td>{!! @$var["del_flag_str"] !!} </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit" style="display:none;" title="编辑"> </a>
                                <a class="fa-comments opt-return-back-list " title="通话记录" ></a>
                                <a class="fa fa-times opt-del" style="display:none;" title="删除"> </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

