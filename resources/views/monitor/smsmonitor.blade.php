@extends('layouts.app')
@section('content')
    <section class="content">
        <div class="right">
            <div class="upload_list">
                <div class="row">
                    <div class="col-md-5 col-xs-12">
                        <div id="id_date_range"> </div>
                    </div>
                    <div class="col-md-2">
                        <div class="input-group">
                            <input type="text" value="" class="form-control opt-change"
                                   placeholder="联系电话 回车查找" data-field="phone"   id="id_phone"  />

                            <input type="text" value="" class="form-control opt-change"
                                   placeholder=" 错误原因 查找" id="id_search_info"  />
                        </div>
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
            </div>
            <hr>
            <table class="common-table">
                <thead>
                    <tr>
                        <td >记录ID</td>
                        <td >类型</td>
                        <td >电话号码</td>
                        <td >发送时间</td>
                        <td >是否成功</td>
                        <td >内容</td>
                        <td width="300px" style="display:none;" >短信系统返回</td>
                        <td >操作</td>
                    </tr>
                </thead>
                <tbody>
                    @foreach ( $table_data_list as  $var )
                        <tr>
                            <td>{{$var["recordid"]}}</td>
                            <td>{{$var["type"]}}</td>
                            <td>{{$var["phone"]}}</td>
                            <td>{{$var["send_time"]}}</td>
                            <td>{{$var["is_success"]}}</td>
                            <td style="padding:0 10px;">{{$var["message"]}}</td>
                            <td>{{$var["receive_content"]}}</td>
                            <td  >
                                <div   
                                >
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include("layouts.page")
        </div>
    </section>
@endsection
