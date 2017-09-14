@extends('layouts.app')
@section('content')

    <section class="content ">

        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  >
                    邮件组: {{$title}} / {{$email}} 
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <span class="input-group-addon">adminid</span>
                        <input class="opt-change form-control" id="id_adminid" />
                    </div>
                </div>


                <div class="col-xs-6 col-md-2"  >
                    <button id="id_add" class="btn btn-primary" >增加成员  </button>
                </div>

                <div class="col-xs-6 col-md-2"  >
                    <button id="id_sync" class="btn btn-warning" >同步到邮箱服务器</button>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  >
            <thead>
                <tr>
                    <td>adminid</td>
                    <td>账号 </td>
                    <td>姓名</td>
                    <td>邮箱 </td>
                    <td>是否已创建 </td>
                    <td>同步到邮箱服务器</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["adminid"]}} </td>
                        <td>{{@$var["account"]}} </td>
                        <td>{{@$var["name"]}} </td>
                        <td>{{@$var["email"]}} </td>
                        <td>{!!  @$var["email_create_flag_str"] !!} </td>
                        <td>{!!  @$var["create_flag_str"] !!} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit" title="修改"> </a>
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
