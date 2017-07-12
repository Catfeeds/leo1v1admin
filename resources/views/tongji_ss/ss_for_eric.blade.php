@extends('layouts.app')
@section('content')
	<script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.min.js"></script>

	<script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.categories.js"></script>
	<script language="javascript" type="text/javascript" src="/page_js/lib/select_date_range.js"></script>
    
    <section class="content ">
      
        <hr/>
        <div id="id_pic_user_count" > </div>

            <hr/>
            <table     class="common-table"  > 
                <thead>
                    <tr>
                        <td>老师姓名</td>
                        <td>排课时间</td>
                        <td>排课人</td>                     
                        <td> 操作  </td> </tr>
                </thead>
                <tbody>
                    @foreach ( $table_data_list as $var )
                        <tr>
                            <td>{{@$var["realname"]}} </td>
                            <td>{{@$var["time"]}} </td>
                            <td>{{@$var["account"]}} </td>
                           
                            <td>
                                <div class="row-data"
                                    {!!  \App\Helper\Utils::gen_jquery_data($var )  !!}
                                >
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

    </section>
        
@endsection

