@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <link rel='stylesheet' href='/css/fullcalendar.css' />
    <script src='/js/moment.js'></script>
    <script src='/js/fullcalendar.js'></script>
    <script src='/js/lang-all.js'></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time.js"></script>
    <script type="text/javascript" src="/page_js/select_teacher_free_time_new.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/seller_student/common.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_record.js?v={{@$_publish_version}}"></script>
    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="//g.alicdn.com/sj/aliphone-sdk/aliphone.min.js" charset="utf-8"></script>
    <script type="text/javascript" src="/js/svg.js"></script>
    <script type="text/javascript" src="/js/wb-reply/audio.js"></script>
    <script type="text/javascript" src="/page_js/lib/flow.js"></script>


    <section class="content ">
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-4"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span >是否展示正负值</span>
                        <select id="id_show_flag" class ="opt-change" >
                            <option value="0">不展示</option>
                            <option value="1">展示</option>
                        </select>
                    </div>
                </div>


                <div class="col-xs-6 col-md-3">
                    <div class="input-group ">
                        <span >展示角色</span>
                        <select id="id_seller_flag" class ="opt-change" >
                            <option value="0">销售</option>
                            <option value="1">老师</option>
                        </select>
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span >例子成本</span>
                        <input type="text" value="" class=" form-control click_on put_name opt-change"  data-field="lesson_money" id="id_lesson_money"  placeholder="请输入例子成本 回车确认" />
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <table class="common-table">
            <thead >
                <tr class="show_body">
                    <td>编号</td>
                    <td>销售</td>
                    {!!\App\Helper\Utils::th_order_gen([
                        ["入职时长","work_day" ],
                        ["排课总数","lesson_count" ],
                        ["试听成功数","suc_count" ],
                        ["到课率","lesson_per"],
                        ["签单数","order_count"],
                        ["签单率","order_per"],
                        ["签单金额","all_price"],
                        ["投入产出比","money_per"],
                        ["老师签单率","tea_per"],
                        ["正负值","range"]
                       ])  !!}
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $k=>$var )
                    <tr>
                        <td>{{$k+1}}</td>
                        <td>{{@$var["account"]}}</td>
                        <td>{{@$var["work_day"]}}天</td>
                        <td>{{@$var["lesson_count"]}}</td>
                        <td class="success_lesson" data-adminid="{{@$var["cur_require_adminid"]}}">
                            <a href="javascript:;" > {{@$var["suc_count"]}}</a>
                        </td>
                        <td>{{@$var["lesson_per"]}}%</td>
                        <td>{{@$var["order_count"]}}</td>
                        <td>{{@$var["order_per"]}}%</td>
                        <td>{{@$var["all_price"]/100}}</td>
                        <td>{{@$var["money_per"]}}</td>
                        <td>
                            @if(@$var["tea_per"]!="")
                                {{@$var["tea_per"]}}%
                            @endif
                        </td>
                        <td>{{@$var["range"]}}</td>


                        <td>
                            <div class="data"
                                {!! \App\Helper\Utils::gen_jquery_data($var) !!}
                            >
                                <a class="opt-teacher-lesson-per">查看老师转化率</a>

                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
@endsection
