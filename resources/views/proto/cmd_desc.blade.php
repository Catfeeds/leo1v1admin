<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>协议[{{$project}}]</title>
        <link rel="stylesheet" href="/css/jQueryUI/jquery-ui-1.10.3.custom.min.css">

        <style>
         li.xspace-loglist
         h4.xspace-entrytitle { background-color: #B0F3DF; }

         body { margin: 0; padding: 0; background: #DEE8F2 repeat-x; color: #111111;
             font: 12px Arial, Helvetica, sans-serif; text-align: center; }
         #wrap { margin: 1em auto; text-align: left; width: 770px; width: 760px; border: 5px solid #D1ECE9;
             background: #D3D5E8 ; overflow: hidden; }

         /* CSS Document */

         body {
             font: normal 12px auto "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
             color: #4f6b72;
             background:  #D3D5E8;
         }

         a.link_big{
             color: #4f6b72;
             font-size:14px;
         }


         table{
             width: 90%;
             padding: 0;
             margin: 0;
         }


         .caption_center {
             padding: 0 0 5px 0;
             width: 90%;
             font: bold  20px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif ;
             text-align: center;
             background:  #D3D5E8;
             color:green ;

         }



         caption {
             padding: 0 0 5px 0;
             font: bold  15px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif ;
             text-align: left;
             background:  #D3D5E8;
             color:blue;

         }

         th {
             font: bold 12px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
             color: #4f6b72;
             border-right: 1px solid #C1DAD7;
             border-bottom: 1px solid #C1DAD7;
             border-top: 1px solid #C1DAD7;
             letter-spacing: 2px;
             padding: 4px 4px 4px 4px;
             text-align: left;
         }

         th.title_def {
             font: bold 12px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
             color: #4f6b72;
             border-right: 1px solid #FFD8C0;
             border-bottom: 1px solid #FFD8C0;
             border-top: 1px solid #FFD8C0 ;
             letter-spacing: 2px;
             text-align: left;
             padding: 6px 6px 6px 12px;
             background: #FFD8C0 ;
         }
         table.title_def {
             width: 98%;
             padding: 0;
             margin: 0;
         }



         input{
             border-right: 1px solid #C1DAD7;
             border-bottom: 1px solid #C1DAD7;
             background: #fff;
             width:200;
             font-size:12px;
             color: #4f6b72;
             text-align: left;
         }

         select{
             border-right: 1px solid #C1DAD7;
             border-bottom: 1px solid #C1DAD7;
             background: #fff;
             width:200;
             font-size:12px;
             color: #4f6b72;
             text-align: left;
         }




         th.nobg {
             border-top: 0;
             border-left: 0;
             border-right: 1px solid #C1DAD7;
             background: none;
         }

         td {
             border-right: 1px solid #C1DAD7;
             border-bottom: 1px solid #C1DAD7;
             background: #fff;
             width:200;
             font-size:14px;
             padding: 2px 2px 2px 2px;
             color: #4f6b72;
             text-align: left;
         }


         td.alt {
             background: #F5FAFA;
             color: #797268;
         }
         td.spec {
             background: #fff  no-repeat;
             color:  #4f6b72;
         }


         th.spec {
             border-left: 1px solid #C1DAD7;
             border-top: 0;
             background: #fff url(images/bullet1.gif) no-repeat;
             font: bold 10px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
         }

         th.specalt {
             border-left: 1px solid #C1DAD7;
             border-top: 0;
             background: #f5fafa url(images/bullet2.gif) no-repeat;
             font: bold 10px "Trebuchet MS", Verdana, Arial, Helvetica, sans-serif;
             color: #797268;
         }

        </style>
    </head>



    <body>
        <div  style="max-width:1000px;margin:0 auto; "   >
            @if( count( $table_data_list )  >0 )
                <table      class="ui-widget ui-widget-content"  cellspacing="0"  style=" margin:0 auto; " >

                    <caption class="caption_center"> 命令列表 [{{$project}}]   </caption>
                    <tbody>
                        <tr  class="ui-widget-header">
                            <td width="50px">命令号</td>
                            <td width="200px">名称</td>
                            <td >说明</td>
                            <td >标签</td>
                        </tr>


                        @foreach ( $table_data_list as $var )
                            <tr>
                                <td>{{@$var["cmdid"]}} </td>
                                <td  > <a
                                           id="{{@$var["name"]}}_item"
                                           href="#{{@$var["name"]}}_desc"
                                       > {{@$var["name"]}} </a> </td>
                                <td>{{@$var["desc"]}} </td>
                                <td>{{@$var["tags"]}} </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
            <hr/>

            @foreach (  $cmd_desc_list as  $key=> $cmd_item )

                <table class="title_def" cellspacing="0"><tbody><tr><th class="title_def" width="50"> {{$key}} </th><th class="title_def">&nbsp;
                    <a
                        id="{{@$cmd_item["name"]}}_desc"
                        href="#{{@$cmd_item["name"]}}_item"  >
                        {{$cmd_item["name"]}}  </a>
                    <br> {{$cmd_item["desc"]}}
                </th><th class="title_def" width="100"> {{$cmd_item["cmdid"]}} </th></tr></tbody>
                </table>


                <table      class="ui-widget ui-widget-content"  cellspacing="0"  style=" margin:0 auto; " >
                    <thead>
                        <tr  class="ui-widget-header">
                            <td width="40%">  <font color="blue">请求包 </font>  字段名</td>
                            <td width="20%">类型</td>
                            <td width="30%">说明</td>
                            <td width="10%">字段id</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $cmd_item["in"] as $var )
                            <tr>
                                <td>{{@$var[2]}} </td>
                                <td>
                                    {{@$var["1"]}}
                                    {!!  $var[0]=="repeated"?"<font color=red>数组 </font>":"" !!}
                                </td>
                                <td>{{@$var["4"]}} </td>
                                <td>{{@$var["3"]}} </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <br/>
                <table      class="ui-widget ui-widget-content"  cellspacing="0"  style=" margin:0 auto; " >
                    <thead>
                        <tr  class="ui-widget-header">
                            <td width="40%">  返回包 字段名</td>
                            <td width="20%">类型</td>
                            <td width="30%">说明</td>
                            <td width="10%">字段id</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $cmd_item["out"] as $var )
                            <tr>
                                <td>{{@$var[2]}} </td>
                                <td>
                                    {{@$var["1"]}}
                                    {!!  $var[0]=="repeated"?"<font color=red>数组 </font>":"" !!}
                                </td>
                                <td>{{@$var["4"]}} </td>
                                <td>{{@$var["3"]}} </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <br/>

                <table      class="ui-widget ui-widget-content"  cellspacing="0"  style=" margin:0 auto; " >
                    <thead>
                        <tr  class="ui-widget-header">
                            <td width="10%"> 错误码值 </td>
                            <td width="10%"> 错误码 </td>
                            <td width="80%">说明</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $cmd_item["cmd_error_list"] as $var )
                            <tr>
                                <td>{{@$var["value"]}} </td>
                                <td>{{@$var["name"]}} </td>
                                <td>{{@$var["desc"]}} </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endforeach



            @if (count($error_list) >0 )
                <hr/>
                <table      class="ui-widget ui-widget-content"  cellspacing="0"  style=" margin:0 auto; " >
                    <thead>
                        <tr>
                            <td width="10%">  错误码值 </td>
                            <td width="10%"> 错误码 </td>
                            <td width="80%">说明
                                <font color= "red">全体错误码列表 </font>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ( $error_list as $var )
                            <tr>
                                <td>{{@$var["value"]}} </td>
                                <td>{{@$var["name"]}} </td>
                                <td>{{@$var["desc"]}} </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </body>
