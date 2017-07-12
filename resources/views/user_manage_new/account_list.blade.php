@extends('layouts.app')
@section('content')

    <script type="text/javascript" src="/page_js/lib/select_dlg_ajax.js"></script>
    <section class="content">
        <div class="row">

            <div class="col-xs-6 col-md-3">
                <div class="input-group ">
                    <span class="input-group-addon">账号</span>
                    <input type="text" class=" " id="id_phone" />
                </div>
            </div>

            <div class="col-xs-1 col-md-2" >
                <div class="input-group ">
                    <span >userid</span>
                    <input id="id_userid"  /> 
                </div>
            </div>


        </div>

        <hr/>



        <table   class="common-table"   >
            <thead>
                <tr>

                    <td  >账号</td>
                    <td  >角色</td>
                    <td  >userid</td>
                    <td  >昵称</td>
                    <td  >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($table_data_list as $var)
				    <tr>
                        <td >{{$var["phone"]}}</td>
                        <td >{{$var["role_str"]}}</td>
                        <td >{{$var["userid"]}}</td>
                        <td >{{$var["nick"]}}</td>
                        <td >
                            <div class="btn-group"
                                 data-phone="{{$var["phone"]}}"
                                 data-role="{{$var["role"]}}"
                            >
                                <a class=" fa-edit opt-set-userid" title="修改 userid"> </a>
                                
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

