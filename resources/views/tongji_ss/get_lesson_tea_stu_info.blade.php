@extends('layouts.app')
@section('content')
    
    <script type="text/javascript" src="/page_js/lib/select_dlg_record.js?v={{@$_publish_version}}"></script>
    <section class="content ">
        
        <div>
            <div class="row " >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>
   
            </div>
        </div>
        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>科目 </td>
                    <td>试听课 </td>
                    <td>签单数 </td>
                    <td>签单率</td>
                    <td>试听课(去除CC) </td>
                    <td>签单数(去除CC)</td>
                    <td>签单率(去除CC)</td>
                    <td>操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["subject_str"]}}</td>
                        <td>{{@$var["lesson_count"]}} </td>
                        <td>{{@$var["order_count"]}} </td>
                        <td>{{@$var["order_per"]}}% </td>
                        <td>{{@$var["no_cc_lesson_count"]}} </td>
                        <td>{{@$var["no_cc_order_count"]}} </td>
                        <td>{{@$var["no_cc_order_per"]}}% </td>
                       
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

