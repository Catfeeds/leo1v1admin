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

        <div>

            <div class="row" >               


                <div class="col-xs-12 col-md-4"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-2" >
                    <div class="input-group ">
                        <span >助教</span>
                        <input id="id_assistantid"  /> 
                    </div>
                </div>

                <div class="col-xs-3 col-md-1" >
                    <button  class="btn btn-info" id="id_add">新增例子</button>
                </div>


            </div>
          
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                  
                    <td>学生</td>
                    <td>手机号</td>
                    <td>资源添加时间</td>
                    <td >地区</td>
                    <td >来源</td>
                    <td>分配助教</td>
                    <td>分配时间</td>
                    <td>分配人</td>
                   

                    <td style="min-width:130px" >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{$var["nick"]}} </td>
                        <td>{{$var["phone"]}} </td>
                        <td>{{$var["add_time_str"]}} </td>
                        <td>{{$var["phone_location"]}} </td>

                        <td>
                            @if  ($var["origin_assistantid"]==0)
                                {{$var["origin"]}} <br/>
                            @else
                                转介绍: {{$var["origin_assistant_nick"]}} <br/>
                            @endif
                        </td>
                        <td>{{$var["ass_nick"]}}</td>
                        <td>{{$var["ass_assign_time_str"]}} </td>
                        <td>{{$var["admin_assignerid_nick"]}} </td>
                       

                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}


                            >
                                <a href="javascript:;" title="用户信息" class="fa-user opt-user"></a>

                                <a class="fa fa-times opt-del" title="删除"> </a>
                               
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
