@extends('layouts.app')
@section('content')
<script type="text/javascript" src="/page_js/select_user.js"></script>
 <script type="text/javascript" src="//g.alicdn.com/sj/aliphone-sdk/aliphone.min.js" charset="utf-8"></script>
    <section class="content">
        <div class="book_filter">

            <div class="row">
                <div class="col-xs-12 col-md-4">
                    <div class="input-group ">
                        <span >时间:</span>
                        <input type="text" id="id_start_date" class="opt-change"/>
                        <span >-</span>
                        <input type="text" id="id_end_date" class="opt-change"/>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">手机</span>
                        <input type="text" id="id_phone" class="opt-change"/>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="input-group">
                        <span class="input-group-addon">来源</span>
                        <input type="text" id="id_origin" class="opt-change"/>
                    </div>
                </div>
            </div>

        </div>
        <hr />
        <div class="body">
            <table class="common-table ">
                <thead>
                    <tr>
                        <td >手机号</td>
                        <td >归属地</td>
                        <td >时间</td>
                        <td >来源</td>
                        <td >姓名</td>
                        <td >年级</td>
                        <td >科目</td>
                        <td >是否有pad</td>
                        <td >操作</td>

                    </tr>
                </thead>
                <tbody>
                    @foreach ($table_data_list as $var)
                        <tr>
                            <td > {{$var["phone"]}} </td>
                            <td > {{$var["phone_location"]}} </td>
                            <td >{{$var["add_time"]}}</td>
                            <td class="">{{$var["origin"]}}</td>
                            <td class="">{{$var["nick"]}}</td>
                            <td class="">{{$var["grade_str"]}}</td>
                            <td >{{$var["subject_str"]}}</td>
                            <td class="">{{$var["has_pad_str"]}}</td>
                            <td>
                                <div 
                                     data-id="{{$var["id"]}}"
                                     data-phone="{{$var["phone"]}}"
                                >
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @include("layouts.page")
        </div>
        <div class="dlg-set-status" style="display:none">
            <div class="row">
                <div class="input-group">
                    <span class="input-group-addon">用户手机</span>
                    <label class="show-user-phone form-control" ></label>
                </div>
            </div>
            <div>
			    <td style="text-align:right; width:30%;">设置状态</td>
			    <td>
                <select class="update_user_status">
                </select>
                </td>
	        </div>
	    </div>

        <div class="dlg-add-revisit" style="display:none">
            <div class="row">
                <div class="input-group">
                    <span class="input-group-addon">用户手机</span>
                    <label class="show-user-phone form-control" ></label>
                </div>
            </div>
            <div class="row">
                <div class="input-group">
                    <span class="input-group-addon">回访记录</span>
                    <textarea class="opt-add-record form-control" style="height:200px;"></textarea>
                </div>
            </div>
        </div>

        <div class="dlg-show-revisit" style="display:none">
            <table class="table table-bordered table-striped ">
            </table>
        </div>

        <div class="dlg-add_book_time_next" style="display:none">
            <div class="row">
                <div class="input-group">
                    <span class="input-group-addon">添加下次回访时间</span>
                    <input class="update_book_time_next" type="text"/>
                </div>
            </div>
        </div>

        <div class="dlg-update_user_info" style="display:none">
            <table class="table table-bordered table-striped">
	            <tbody>
		            <tr>
			            <td style="text-align:right; width:30%;">用户手机</td>
			            <td><input value="" class="update_user_phone" type="text"/></td>
		            </tr>
                    <tr>
			            <td style="text-align:right; width:30%;">用户状态</td>
			            <td>
                            <select class="update_user_status">
                            </select>
                        </td>
		            </tr>
                    <tr>
			            <td style="text-align:right; width:30%;">用户备注</td>
			            <td><textarea value="" style="height:150px;width:100%;" class="update_user_note" type="text"></textarea></td>
		            </tr>
                    <tr>
			            <td style="text-align:right; width:30%;">回访记录</td>
			            <td><textarea value="" style="height:150px;width:100%" class="update_user_record" type="text"></textarea></td>
		            </tr>

                 </tbody>
	        </table>
        </div>
    </section>
    <script src="/js/qiniu/plupload/plupload.full.min.js"></script>

@endsection

