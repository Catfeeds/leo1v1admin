@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <script type="text/javascript" src="/page_js/dlg_return_back.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>

    <section class="content ">
        
        <div>
            <div class="row  " >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">助教</span>
                        <input class="opt-change form-control" id="id_ass_adminid" />
                    </div>
                </div>

                <div class="col-xs-6 col-md-1">
                    <button class="btn btn-primary" id="id_add">新增</button>
                </div>



            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>申请时间</td>
                    <td>电话</td>
                    <td>昵称</td>
                    <td>助教</td>
                    <td>年级</td>
                    <td>科目</td>
                    <td>销售</td>
                    <td>状态</td>
                    <td>转介绍学生</td>
                    <td style=" min-width:400px ;" >备注</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["add_time"]}} </td>
                        <td>{{@$var["phone"]}} </td>
                        <td>{{@$var["nick"]}} </td>
                        <td>{{@$var["ass_admin_nick"]}} </td>
                        <td>{{@$var["grade_str"]}} </td>
                        <td>{{@$var["subject_str"]}} </td>
                        <td>{{@$var["admin_revisiterid_nick"]}} </td>
                        <td>{{@$var["status_str"]}} </td>
                        <td>{{@$var["origin_user_nick"]}} </td>
                        <td>{{@$var["user_desc"]}} </td>
                        <td>
                            <div class="opt-div"
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="编辑"> </a>
                                <a class="fa-comments opt-return-back-list " title="回访列表" ></a>
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

