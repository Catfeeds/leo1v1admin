@extends('layouts.app')
@section('content')
    <section class="content">
    <div class="row">
        <div class="col-xs-1 col-md-2">
            <div class="input-group ">
                <span >学生</span>
                <input id="id_studentid"  />
            </div>
        </div>
        <div class="col-xs-1 col-md-2">
            <div class="input-group ">
                <span >家长</span>
                <input id="id_parentid"  />
            </div>
        </div>
        <div class="col-xs-1 col-md-2">
            <div class="input-group ">
                <div class=" input-group-btn ">
                    <button id="id_add_contract" type="submit"  class="btn  btn-warning" >
                     <i class="fa fa-plus"></i>添加活动
                    </button>
                </div>
            </div>
        </div>
        
    </div>

    <hr/>

        <table   class="common-table"   >
            <thead>
                <tr>
                    <td >家长id</td>
                    <td >手机号</td>
                    <td >家长</td>
                    <td >家长类型</td>
                    <td >学生id</td>
                    <td >学生</td>
                    <td >账户角色</td>
                    <td >账户</td>
                    <td >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
            <tr>
                        <td >{{$var["parentid"]}}</td>
                        <td >{{$var["phone"]}}</td>
                        <td >{{$var["parent_nick"]}}</td>
                        <td >{{$var["parent_type_str"]}}</td>
                        <td >{{$var["userid"]}}</td>
                        <td >{{$var["user_nick"]}}</td>
                        <td >{{$var["role_str"]}}</td>
                        <td >{{$var["login_phone"]}}</td>
                        <td >
                            <div 
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class=" fa-edit opt-set-parentid" title="修改 家长"> </a>
                                <a class="fa fa-times opt-del" title="删除"> </a>
                            </div>
                        </td>
            </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")

    </section>

    <script type="text/javascript" src="/page_js/select_course.js"></script>
    <script type="text/javascript" src="/page_js/select_user.js"></script>
    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>

@endsection

