@extends('layouts.app')
@section('content')

    <section class="content ">
        <div>
            <div class="row  ">
                <div class="col-xs-12 col-md-5">
                    <div id="id_date_range"> </div>
                </div>
               <div class="col-xs-6 col-md-2">
                   <div class="input-group ">
                       <span class="input-group-addon">角色</span>
                       <select class="opt-change form-control" id="id_account_role" >
                       </select>
                   </div>
               </div>
               <div class="col-xs-6 col-md-2">
                   <div class="input-group ">
                       <span class="input-group-addon">拨打者</span>
                       <input class="opt-change form-control" id="id_callerid" />
                   </div>
               </div>

            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>账号</td>
                    <td>uid </td>
                    <td>拨打总次数</td>
                    <td>拨通总次数</td>
                    <td>拨通总时长</td>
                    <td>拨打人数</td>
                    <td>拨通人数</td>
                    <td> 操作  </td> </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                            <td>{{@$var["account"]}} </td>
                            <td>{{@$var["uid"]}} </td>
                            <td>{{@$var["all_count"]}} </td>
                            <td>{{@$var["is_called_phone_count"]}} </td>
                            <td>{{@$var["duration_count"]}} </td>
                            <td>{{@$var["phone_count"]}} </td>
                            <td>{{@$var["called_phone_count"]}} </td>
                            <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a  class="opt-show" > 明细 </a>

                            </div>
                            </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

