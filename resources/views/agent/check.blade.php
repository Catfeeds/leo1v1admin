@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/page_js/set_lesson_time.js"></script>
<script type="text/javascript" src="/js/qiniu/plupload/plupload.full.min.js"></script>
<script type="text/javascript" src="/js/qiniu/plupload/i18n/zh_CN.js"></script>
<script type="text/javascript" src="/js/qiniu/ui.js"></script>
<script type="text/javascript" src="/js/qiniu/qiniu.js"></script>
<script type="text/javascript" src="/js/qiniu/highlight/highlight.js"></script>
<script type="text/javascript" src="/js/jquery.md5.js"></script>
<script type="text/javascript" src="/js/svg.js"></script>
<script type="text/javascript" src="/js/wb-reply/audio.js"></script>
<script type="text/javascript" src="/page_js/select_user.js"></script>
<script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <section class="content ">
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >渠道</span>
                        <input type="text" value=""  class="opt-change"  id="id_origin_ex"  placeholder=""  />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <a id="id_add_lesson_by_excel" class="btn btn-warning"><li class="fa fa-plus">xls一键添加</li></a>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>userid </td>
                    <td>渠道 </td>
                    <td>进入时间 </td>
                    <td>最后联系cc所在部门 </td>
                    <td>是否接通 </td>
                    <td>是否试听成功 </td>
                    <td>是否签单 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["userid"]}} </td>
                        <td>{{@$var["origin"]}} </td>
                        <td>{{@$var["add_time"]}} </td>
                        <td>{{@$var["account"]}}/{{@$var["group_name"]}} </td>
                        <td>{!! $var["is_called_str"] !!} </td>
                        <td>{!! $var["is_suc_test_str"] !!} </td>
                        <td>{!! $var["is_order_str"] !!} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit" style="display:none;" title="编辑"> </a>
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

