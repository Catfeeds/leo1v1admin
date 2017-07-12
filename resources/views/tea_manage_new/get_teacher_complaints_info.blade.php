@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
    <script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
    <script type="text/javascript" src="/js/qiniu/ui.js"></script>
    <script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
    <script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
    <script type="text/javascript" src="/js/jquery.md5.js"></script>

    <section class="content ">
        
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">投诉人</span>
                        <input class="opt-change form-control" id="id_require_adminid" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">处理人</span>
                        <input class="opt-change form-control" id="id_accept_adminid" />
                    </div>
                </div>


               
                          

            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>编号</td>
                    <td>投诉时间</td>
                    <td>投诉人</td>
                    <td>投诉内容</td>
                    <td> 老师 </td>
                    <td> 科目 </td>
                    <td> 年级 </td>
                    <td> 处理方案</td>
                    <td> 处理时间</td>
                    <td> 处理人</td>
                    <td> 处理时长</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["id_index"]}}</td>
                        <td>{{@$var["add_time_str"]}} </td>
                        <td>{{@$var["account"]}} </td>
                        <td>
                            {{@$var["complaints_info"]}}<br>
                            @if($var["complaints_info_url"])
                                <a class="show_pic" href="javascript:;" data-url="{{@$var["complaints_info_url"]}}" >查看图片</a>
                            @endif
                        </td>

                        <td>
                            <a  href="/human_resource/index?teacherid={{$var["teacherid"]}}"
                                target="_blank" title="老师信息">{{@$var["realname"]}} </a>
                        </td>
                        
                        
                        <td>{{@$var["subject_str"]}} </td>
                        <td>
                            @if($var["grade_start"]>0)
                                {{@$var["grade_start_str"]}}至{{@$var["grade_end_str"]}}
                            @else
                                {{@$var["grade_part_ex_str"]}}
                            @endif
                        </td>
                       
                        <td>
                            @if($var["accept_time"]>0)
                                {{@$var["record_scheme"]}}<br>
                                @if($var["record_scheme_url"])
                                    <a class="show_pic" href="javascript:;" data-url="{{@$var["record_scheme_url"]}}" >查看图片</a>
                                @endif
                            @endif
                        </td>
                        <td>{{@$var["accept_time_str"]}}<br></td>
                        <td>{{@$var["accept_account"]}}</td>
                        <td>
                            @if($var["accept_time"]>0)
                                {{@$var["deal_time"]}}小时 
                            @endif
                        </td>
                        
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                               

                                <a class="fa-edit opt-edit"  title="处理方案"> </a>
                                <a class="fa-edit opt-edit-new"  title="修改申请"> </a>
                                <a class="fa-trash-o opt-del"  title="删除申请"> </a>
                                @if($acc=="jack")
                                    <a class=" opt-del-new"  title="删除申请">删除 </a>
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

