@extends('layouts.app')
@section('content')
    <script type="text/javascript" >
     var g_data_ex_list= <?php  echo json_encode($table_data_list); ?> ;
    </script>


    <section class="content ">
	      <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.min.js"></script>
	      <script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.categories.js"></script>
    <script type="text/javascript" >
     var g_data_ex_list= <?php  echo json_encode($table_data_list); ?> ;
    </script>


        <div>
            <div class="row  row-query-list" >
                <div class="col-xs-12 col-md-5"  data-title="时间段">
                    <div  id="id_date_range" >
                    </div>
                </div>

            </div>
        </div>
        <hr/>

        <div id="id_pic_user_count" > </div>

        <hr/>
        <table     class="common-table"  > 
            <thead>
                <tr>
                    <td>日期 </td>
                    <td>学员数</td>
                    <td>上课人数</td>
                    <td> 操作  </td>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr>
                        <td>{{@$var["title"]}} </td>
                        <td>{{@$var["user_count"]*1}} </td>
                        <td>{{@$var["lesson_user_count"]*1}} </td>
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
