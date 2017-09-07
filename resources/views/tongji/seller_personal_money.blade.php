@extends('layouts.app')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-md-3 col-xs-0" data-always_show="1">
                    <div class="input-group col-sm-12"  >
                        <input  id="id_user_info" type="text" value="" class="form-control opt-change"  placeholder="输入用户名/电话，回车查找" />
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>序号 </td>
                    <td>销售 </td>
                    @foreach ( $date_list as $var )
                        <td>{{@$var["month"]}}月 </td>
                    @endforeach
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["id"]}} </td>
                        <td>{{@$var["account"]}} </td>
                        <td>{{@$var["money1"]}} </td>
                        <td>{{@$var["money2"]}} </td>
                        <td>{{@$var["money3"]}} </td>
                        <td>{{@$var["money4"]}} </td>
                        <td>{{@$var["money5"]}} </td>
                        <td>{{@$var["money6"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

