@extends('layouts.app')
@section('content')
	<script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.min.js"></script>
	<script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.categories.js"></script>
	<script language="javascript" type="text/javascript" src="/page_js/lib/select_date_range.js"></script>
    <section class="content ">
        <div class="row">
            <div class="col-xs-12 col-md-5">
                <div id="id_date_range"> </div>
            </div>
            <div class="col-md-2">
                <div class="input-group">
                    <select id="id_is_succ" class="opt-change">
                        <option value="-1">全部</option>
                        <option value="0">失败</option>
                        <option value="1">成功</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="input-group">
                    <span >类型</span>
                    <select id="id_type" class="opt-change">
                    </select>
                </div>
            </div>

        </div>
        <div id="id_pic_user_count" > </div>
            <hr/>
            <table     class="common-table"  > 
                <thead>
                    <tr> <td>日期 </td>  <td >个数</td> 
                        <td> 操作  </td> </tr>
                </thead>
                <tbody>
                    @foreach ( $table_data_list as $var )
                        <tr>
                            <td>{{@$var["title"]}} </td>
                            <td>{{@$var["count"]}} </td>
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

    </section>
@endsection

