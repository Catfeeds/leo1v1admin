@extends('layouts.app')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

                <div class="col-xs-6 col-md-2">
                    <div class="input-group " >
                        <span >xx</span>
                        <input type="text" value=""  class="opt-change"  id="id_"  placeholder=""  />
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>userid </td>
                    <td>例子进入时间 </td>
                    <td>分配人 </td>
                    <td>被分配人 </td>
                    <td>分配时间 </td>
                    <td>首次拨打时间 </td>
                    <td>首次拨通时间 </td>
                    <td>签单金额 </td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["userid"]}} </td>
                        <td>{{@$var["add_time"]}} </td>
                        <td>{{@$var["give_account"]}} </td>
                        <td>{{@$var["get_account"]}} </td>
                        <td>{{@$var["create_time"]}} </td>
                        <td>{{@$var["first_revisit_time"]}} </td>
                        <td>{{@$var["first_contact_time"]}} </td>
                        <td>{{@$var["price"]}} </td>
                        <td>
                            <div
                                {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                            >
                                <a class="fa fa-edit opt-edit"  title="编辑"> </a>
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

