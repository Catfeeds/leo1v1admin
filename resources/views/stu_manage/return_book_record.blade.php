@extends('layouts.stu_header')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <div  id="id_date_range" >
                    </div>
                </div>

            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>回访时间</td>
                    <td>回访记录 </td>
                    <td>回访语音</td>
                    <td>回访人</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["revisit_time"]}} </td>
                        <td>{!! @$var["operator_note"]  !!} </td>

                        <td>{{@$var["operator_audio"]}} </td>
                        <td>{{@$var["sys_operator"]}} </td>
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

