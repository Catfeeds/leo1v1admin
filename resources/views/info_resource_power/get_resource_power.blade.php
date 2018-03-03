@extends('layouts.app')
@section('content')
    <script type="text/javascript" src="/js/jquery.md5.js"></script>
    <script type="text/javascript" src="/js/jquery.contextify.js"></script>
    <script>
    </script>
    <style>
     .up_file,.down_file,.dele_file{ padding: 4px;margin-left: 6px;margin-bottom:5px };
     .hide{ display:none}
     .power_table{ width:1100px}
     .power_table thead tr th,.power_table tbody tr td{ border:1px solid #999;padding:10px 10px}
     .power_table tbody tr td.edit_name{ padding:0px }
     .power_table thead tr{ background:#d2d6de}
     .power_table thead{}
     .edit_name input{ width:100%;height:51px;border:0px;text-indent:5px}
     .power_choose label{ margin-right:10px}
     
     .power_choose input[type='radio']:checked + span { color: red; }
     .right-menu a{ cursor:pointer}
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
                    <th width="5%">资源分类ID</th>
                    <th width="11%">资源分类</th>
                    <th width="11%">细分类型</th>
                    <th width="20%">咨询部</th>
                    <th width="20%">助教部</th>
                    <th width="21%">市场部</th>
                    <th width="12%">操作</th>
                </tr>
            </thead>
            <tbody>
                <tr class="right-menu tr_case hide" power_id="0">
                    <td class="resource_id"></td>
                    <td class="edit_name">
                        <input class="resource_name" type="text" value="" resource_id="0" readonly />
                    </td>
                    <td class="edit_name">
                        <input class="type_name" type="text" type_id="0" value=""/>
                    </td>
                    <td class="power_choose consult_power">
                        <label>
                            <input type ="radio" name ="consult_power" value ="1" />
                            <span>无</span>
                        </label>
                        <label>
                            <input type ="radio" name ="consult_power" value ="2" />
                            <span>仅查看</span>
                        </label>
                        <label>
                            <input type ="radio" name ="consult_power" value ="3" />
                            <span>可下载</span>
                        </label>
                    </td>
                    <td class="power_choose assistant_power">
                        <label>
                            <input type ="radio" name ="assistant_power" value ="1" />
                            <span>无</span>
                        </label>
                        <label>
                            <input type ="radio" name ="assistant_power" value ="2" />
                            <span>仅查看</span>
                        </label>
                        <label>
                            <input type ="radio" name ="assistant_power" value ="3" />
                            <span>可下载</span>
                        </label>
                    </td>

                    <td class="power_choose market_power">
                        <label>
                            <input type ="radio" name ="market_power" value ="1" />
                            <span>无</span>
                        </label>
                        <label>
                            <input type ="radio" name ="market_power" value ="2" />
                            <span>仅查看</span>
                        </label>
                        <label>
                            <input type ="radio" name ="market_power" value ="3" />
                            <span>可下载</span>
                        </label>
                    </td>
                    
                    <td>
                        <a class="opt_save color-blue" onclick="save_type(event)" title="保存">保存</a>
                        <a class="opt_dele color-blue" onclick="dele_type(event)" title="删除">删除</a>
                    </td>
                </tr>

                @foreach ( $ret as $var )
                    <tr class="right-menu" {!!  \App\Helper\Utils::gen_jquery_data($var )  !!} power_id='{{@$var["id"]}}'>
                        <td class="resource_id">{{@$var["resource_id"]}}</td>
                        <td class="edit_name">
                            <input class="resource_name" type="text" resource_id="{{@$var["resource_id"]}}"  value="{{@$var["resource_name"]}}" readonly/>
                        </td>
                        <td class="edit_name">
                            <input class="type_name" type="text" type_id="{{@$var["type_id"]}}" value="{{@$var["type_name"]}}"/>
                        </td>
                        <td power="{{@$var["consult"]}}" class="power_choose consult_power">
                            <label>
                                <input type ="radio" name ="consult_{{@$var['id']}}" value ="1"
                                       @if(@$var["consult"] == 1) checked @endif />
                                <span>无</span>
                            </label>

                            <label>
                                <input type ="radio" name ="consult_{{@$var['id']}}" value ="2"
                                       @if(@$var["consult"] == 2) checked @endif />
                                <span>仅查看</span>
                            </label>

                            <label>
                                <input type ="radio" name ="consult_{{@$var['id']}}" value ="3"
                                       @if(@$var["consult"] == 3) checked @endif/>
                                <span>可下载</span>
                            </label>
                        </td>
                        <td power="{{@$var["assistant"]}}" class="power_choose assistant_power">
                            <label>
                                <input type ="radio" name ="assistant_{{@$var['id']}}" value ="1"
                                       @if(@$var["assistant"] == 1) checked @endif/>
                                <span>无</span>
                            </label>

                            <label>
                                <input type ="radio" name ="assistant_{{@$var['id']}}" value ="2"
                                       @if(@$var["assistant"] == 2) checked @endif>
                                <span>仅查看</span>
                            </label>

                            <label>
                                <input type ="radio" name ="assistant_{{@$var['id']}}" value ="3"
                                       @if(@$var["assistant"] == 3) checked @endif />
                                <span>可下载</span>
                            </label>

                        </td>
                        <td power="{{@$var["market"]}}" class="power_choose market_power">
                            <label>
                                <input type ="radio" name ="market_{{@$var['id']}}" value ="1"
                                       @if(@$var["market_power"] == 1) checked @endif />
                                <span>无</span>
                            </label>

                            <label>
                                <input type ="radio" name ="market_{{@$var['id']}}" value ="2"
                                       @if(@$var["market"] == 2) checked @endif />
                                <span>仅查看</span>
                            </label>

                            <label>
                                <input type ="radio" name ="market_{{@$var['id']}}" value ="3"
                                       @if(@$var["market"] == 3) checked @endif />
                                <span>可下载</span>
                            </label>

                        </td>
                        
                        <td>
                            <a class="opt_save color-blue" onclick="save_type(event)"  title="保存">保存</a>
                            <a class="opt_dele color-blue" onclick="dele_type(event)" title="删除">删除</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </section>

@endsection
