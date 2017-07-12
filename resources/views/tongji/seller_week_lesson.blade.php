@extends('layouts.app')
@section('content')

    <section class="content ">
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >销售</span>
                        <input type="text" value=""  class="opt-change"  id="id_cc_name"  placeholder=""  />
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>销售 </td>
                    @foreach ( $week_info as $var )
                        <td>{{@$var}} </td>
                    @endforeach
                    <!-- <td> 操作  </td> -->
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["cc_name"]}} </td>
                        <td>{{@$var['1']}} </td>
                        <td>{{@$var['2']}} </td>
                        <td>{{@$var['3']}} </td>
                        <td>{{@$var['4']}} </td>
                        <td>{{@$var['5']}} </td>
                        <td>{{@$var['6']}} </td>
                        <td>{{@$var['0']}} </td>
                        <td>{{@$var["sum"]}} </td>
                        <!-- <td>
                             <div
                             {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                             >
                             <a class="fa fa-edit opt-edit"  title="编辑"> </a>
                             <a class="fa fa-times opt-del" title="删除"> </a>

                             </div>
                             </td> -->
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>
    
@endsection

