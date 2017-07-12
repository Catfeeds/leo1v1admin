@extends('layouts.app')
@section('content')

    <section class="content ">
        
        <div>
            <div class="row">
                <div class="col-xs-6 col-md-4">
                    <div class="input-group ">
                        <span >手机</span>
                        <input type="text" value=""  class="opt-change"  id="id_phone"  placeholder="" />
                    </div>
                </div>
                <div class="col-xs-6 col-md-2">
                    <div class="input-group ">
                        <a  id="id_notify_phone" class="btn btn-primary"> 拨出电话 </a>
                    </div>
                </div>

            </div>

        </div>
        <hr/>

    </section>
    
@endsection

