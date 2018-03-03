@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/js/jquery.contextify.js"></script>
    <script>
    </script>
    <style>
     .up_file,.down_file,.dele_file{ padding: 4px;margin-left: 6px;margin-bottom:5px };
     .hide{ display:none}
     .power_table{ width:90%}
     .power_table thead tr th,.power_table tbody tr td{ border:1px solid #999;padding:10px 10px}
     .power_table thead tr{ background:#d2d6de}
     .power_table thead{}
     .edit_name input{ width:100%;height:100%;border:0px;}
     .power_choose label{ margin-right:10px}
    </style>
    <section class="content">      
        <div class="row" style="margin-bottom:10px">
            <div class="col-xs-2 col-md-2 ">
                <button class="btn btn-primary opt_add_resource">添加资源分类</button>
            </div>
        </div>

        <table class="power_table">
            <thead>
                <tr>                   
                    <th>资源分类</th>
                    <th>细分类型</th>
                    <th>咨询部</th>
                    <th>助教部</th>
                    <th>市场部</th>
                    <th>操作</th>
                </tr>
            </thead>
            <tbody>
                @foreach ( $table_data_list as $var )
                    <tr class="right-menu" {!!  \App\Helper\Utils::gen_jquery_data($var )  !!} >
                        <td resource="{{@$var["resource_id"]}}" class="edit_name">
                            <input type="text" value="{{@$var["resource_name"]}}" />
                        </td>
                        <td type="{{@$var["type_id"]}}" class="edit_name">
                            <input type="text" value="{{@$var["type_name"]}}" />
                        </td>
                        <td power="{{@$var["consult_power"]}}" class="power_choose">
                            <label>
                                <input type ="radio" name ="consult_power" value ="0"
                                       @if(@$var["consult_power"] == 0) checked @endif
                                />无
                            </label>

                            <label>
                                <input type ="radio" name ="consult_power" value ="1"
                                       @if(@$var["consult_power"] == 1) checked @endif
                                />仅查看
                            </label>

                            <label>
                                <input type ="radio" name ="consult_power" value ="2"
                                       @if(@$var["consult_power"] == 2) checked @endif
                                />可下载
                            </label>
                        </td>
                        <td power="{{@$var["assistant_power"]}}" class="power_choose">
                            <label>
                                <input type ="radio" name ="assistant_power" value ="0"
                                       @if(@$var["assistant_power"] == 0) checked @endif
                               />无
                            </label>

                            <label>
                                <input type ="radio" name ="assistant_power" value ="1"
                                       @if(@$var["assistant_power"] == 1) checked @endif
                                >仅查看
                            </label>

                            <label>
                                <input type ="radio" name ="assistant_power" value ="2"
                                       @if(@$var["assistant_power"] == 2) checked @endif
                                >可下载
                            </label>

                        </td>
                        <td power="{{@$var["market_power"]}}" class="power_choose">
                            <label>
                                <input type ="radio" name ="market_power" value ="0"
                                       @if(@$var["market_power"] == 0) checked @endif
                                >无
                            </label>

                            <label>
                                <input type ="radio" name ="market_power" value ="1"
                                       @if(@$var["market_power"] == 1) checked @endif
                                >仅查看
                            </label>

                            <label>
                                <input type ="radio" name ="market_power" value ="2"
                                       @if(@$var["market_power"] == 2) checked @endif
                                >可下载
                            </label>

                        </td>
                  
                        <td>
                            <a class="opt_save btn color-blue"  title="保存">保存</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        @include("layouts.page")
    </section>

@endsection
