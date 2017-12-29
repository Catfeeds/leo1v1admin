@extends('layouts.app')
@section('content')
<section class="content">

    <div class="row">
        <div class="col-xs-6 col-md-8">
            <div class="input-group ">
                <span class="input-group-addon">数据库</span>
                <select class="input-change form-control" id="id_db_name" >
                    <option value="db_weiyi">db_weiyi</option>
                    <option value="db_weiyi_admin">db_weiyi_admin</option>
                    <option value="db_tool">db_tool</option>
                    <option value="db_account">db_account</option>
                </select>
                <span class="input-group-addon">表</span>
                <select class="input-change form-control" id="id_table_name" >
                    @foreach($table_list as $item )
                        <option value="{{$item["TABLE_NAME"] }}">{{$item["TABLE_NAME"] }}-{{$item["TABLE_COMMENT"] }} </option>
                    @endforeach
                </select>

            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <button class="btn btn-primary" id="id_change_table_comment"> 修改表注释 </button>
            </div>
        </div>
        <div class="col-xs-6 col-md-2">
            <div class="input-group ">
                <button class="btn btn-primary" id="id_edit"> 编辑数据</button>
            </div>
        </div>
    </div>
    <hr/>
    <table class=" common-table "   >
        <thead>
            <tr>
                <td style="width:100px;" >Field</td>
                <td style="width:180px;" >Type</td>
                <td style="display:none;" >Collation</td>
                <td style="display:none;" >Null</td>
                <td class="remove-for-xs" >Key</td>
                <td style="display:none;"  >Default</td>
                <td style="display:none;"  >Extra</td>
                <td style="display:none;"  >Privileges</td>
                <td >Comment</td>
                <td style="min-width:100px">操作</td>
            </tr>
        </thead>
        <tbody>
            @foreach ($table_data_list as $var)
                <tr>
                    <td  >{{$var["Field"]}} </td>
                    <td >{{$var["Type"]}}</td>
                    <td >{{$var["Collation"]}}</td>
                    <td >{{$var["Null"]}}</td>
                    <td >{{$var["Key"]}}</td>
                    <td >{{$var["Default"]}}</td>
                    <td >{{$var["Extra"]}}</td>
                    <td >{{$var["Privileges"]}}</td>
                    <td >{{$var["Comment"]}}</td>
                    <td >
                        <div
                            data-field="{{$var["Field"]}}"
                          data-comment="{{$var["Comment"]}}"
                        >
                            <a class="fa-gavel opt-field-comment " title="修改注释"></a>
                            <a class="opt-set-none" title="无用设置">设为无用</a>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    @include("layouts.page")
    <pre >
{{$create_table_str}}
    </pre>
@endsection

