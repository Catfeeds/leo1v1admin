@extends('layouts.app')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row" >
                <div class="col-xs-4 col-md-4" >

                </div>

                <div class="col-xs-4 col-md-4" >
                    <form role="form" action="{{url('aliyun_oss/upload_add')}}" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="exampleInputEmail1">文件名</label>
                            <input type="text" class="form-control" id="file_name" name="file_name" placeholder="输入文件名" >
                        </div>
                        <div class="form-group">
                            <label for="name">文件类型</label>
                            <select class="form-control" id="file_type" name="file_type">
                                <option value="1">学生端</option>
                                <option value="2">家长端</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="exampleInputFile">文件上传</label>
                            <input type="file" id="file" name="file">
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                    </form>

                </div>

                <div class="col-xs-4 col-md-4" >

                </div>

           </div>
        </div>
        <hr/>
        @include("layouts.page")
    </section>
    
@endsection
