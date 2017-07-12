@extends('layouts.app')
@section('content')
	<script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.min.js"></script>
	<script language="javascript" type="text/javascript" src="/js/flot/jquery.flot.pie.min.js"></script>
    <script type="text/javascript" >
     
     var g_data_list= <?php  echo json_encode ($ret_info); ?> ;
    </script>
    <section class="content">

        <div class="row">
            <div class="col-md-2">
                <div class="input-group ">
                    <span class="input-group-addon">类型</span>
                    <select  id="id_praise_type" class="opt-change">
                        <option value="-1">[全部]</option>
                    </select>
                </div>
            </div> 
            <div class="col-xs-6 col-md-3">
                <div class="input-group ">
                    <span >日期</span>
                    <input type="text" id="id_start_date" class="opt-change"/>
                    <span >-</span>
                    <input type="text" id="id_end_date" class="opt-change"/>
                </div>
            </div>
        </div>

        <hr/>

        <div class="row">
            <div class="col-xs-6 col-md-6">
		        <div id="id_add_placeholder" class="demo-placeholder" style="height:400;"></div>
            </div>
            <div class="col-xs-6 col-md-6">
            </div>
        </div>



        <table class="common-table">
            <thead>
                <tr>
                    <td >赞类型</td>
                    <td >获赞数目</td>
                    <td >赞类型次数</td>
                    <td >操作</td>
                </tr>
            </thead>
            <tbody>
                @foreach ($ret_info as $var)
                    <tr>
                        <td>{{$var["type_str"]}}</td>
                        <td>{{$var["praise_num"]}}</td>
                        <td>{{$var["type_num"]}}</td>
                        <td class=" remove-for-xs " >
                            <div class="opt" data-type="{{$var["type"]}}">
                                <a class="fa-magic opt-type " title="类型查询"> </a>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@endsection
