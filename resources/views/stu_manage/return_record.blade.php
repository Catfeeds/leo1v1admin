@extends('layouts.stu_header')
@section('content')

    <script type="text/javascript" src="/js/wb-reply/audio.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>

    <section class="content ">

        <div>
            <div class="row">
                <div class="col-xs-5 col-md-2">
                    <button class="btn btn-danger " title="" id="id_reload_ytx">刷新电话记录 </button>

                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">是否预警</span>
                        <select id="id_is_warning_flag" class="opt-change">
                        </select>
                    </div>
                </div>


            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>回访时间</td>
                    <td>类型 </td>
                    <td>回访对象 </td>
                    <td>回访记录</td>
                    <td> 语音记录  </td>
                    <td> 回访人 </td>

                    <td>其他预警问题</td>
                    <td>预警情况</td>
                    <td>预警处理方案</td>
                    <td> 操作 </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["revisit_time_str"]}} </td>
                        <td>{{$var["revisit_type_str"]}} </td>
                        <td>{{@$var["revisit_person"]}} </td>
                        <td>{!! @$var["operator_note"]  !!} </td>
                        <td>{{@$var["duration"]}} </td>
                        <td>{{@$var["sys_operator"]}} </td>

                        <td>{{@$var["other_warning_info"]}} </td>
                        <td>{{@$var["is_warning_flag_str"]}} </td>
                        <td>
                            {{@$var["warning_deal_info"]}}<br>
                            @if($var["warning_deal_url"])
                                <a class="show_pic" href="javascript:;" data-url="{{@$var["warning_deal_url"]}}" >查看图片</a>
                            @endif

                        </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa-volume-up  opt-audio "> </a>
                                @if(in_array($adminid,[$var["uid"],$var["master_adminid"],74,540,349]))
                                    <a class="fa fa-edit opt-edit"  title="编辑" style="display:none"> </a>
                                    <a class="fa fa-edit opt-edit-new" title="编辑"  > </a>
                                    @if($var["is_warning_flag"]>0)
                                        <a class="fa opt-warning-record" >预警处置 </a>
                                    @endif
                                @endif
                                <a class = "opt_detail" data-userid="{{$var['userid']}}" data-revisit_time="{{$var['revisit_time']}}">详情</a>
                                @if(in_array($account,["jack","jim"]))
                                    <a class=" opt-edit-test1"  title="编辑-test"> 编辑普通回访-test</a>
                                    <a class=" opt-edit-test"  title="编辑-test"> 编辑电话回访-test</a>
                                    
                                @endif

                            </div>
                        </td>


                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
