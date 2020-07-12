@extends('layouts.app')
@section('content')
<style>
.checkbox-success{
	background-color: #cad0cc!important;
	 color:red;
}
</style>
<?php $userid = Auth::user()->id; ?>
@if (getAccessStatusUser('Accounts & Tax Rates',$userid)=='yes')
	<div class="right_col" role="main">
        <div class="">
			<div class="page-title">
				<div class="nav_menu">
					<nav>
						<div class="nav toggle">
							<a id="menu_toggle"><i class="fa fa-bars"></i><span class="titleup">&nbsp {{ trans('app.Account Tax')}}</span></a>
						</div>
						@include('dashboard.profile')
					</nav>
				</div>
				@if(session('message'))
				<div class="row massage">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="checkbox checkbox-success checkbox-circle">
							<label for="checkbox-10 colo_success"> {{ trans('app.Duplicate Data')}} </label>
						</div>
					</div>
				</div>
				@endif
            </div>
          <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
               <div class="x_content">
                  <div class="">
                     <ul class="nav nav-tabs bar_tabs tabconatent" role="tablist">
							<li role="presentation" class="suppo_llng_li floattab"><a href="{!! url('/taxrates/list')!!}"><span class="visible-xs"></span><i class="fa fa-list fa-lg">&nbsp;</i> {{ trans('app.List Account Tax')}}</a></li>
							<li role="presentation" class="active suppo_llng_li_add floattab"><a href="{!! url('/taxrates/add')!!}"><span class="visible-xs"></span> <i class="fa fa-plus-circle fa-lg">&nbsp;</i><b>{{ trans('app.Add Account Tax')}}</b></a></li>
					</ul> 
                    <div class="clearfix"></div>
                  </div>
				   <div class="x_panel">
                    <br />
                    <form action="{{ url('/taxrates/store') }}" method="post"  enctype="multipart/form-data" data-parsley-validate class="form-horizontal form-label-left">

                      <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">{{ trans('app.Tax name')}} <label class="text-danger">*</label>
                        </label>
                        <div class="col-md-5 col-sm-5 col-xs-12">
                          <input type="text"  required="required" name="taxrate" placeholder="{{ trans('app.Enter Tax Rate Name')}}" value="{{ old('taxrate') }}" class="form-control col-md-7 col-xs-12" maxlength="20">
                        </div>
                      </div>
                       <div class="form-group col-md-12 col-sm-12 col-xs-12 {{ $errors->has('tax') ? ' has-error' : '' }}">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="first-name">
                        {{ trans('app.Tax Rates')}} (%) <label class="text-danger">*</label>
                        </label>
                        <div class="col-md-5 col-sm-5 col-xs-12">
                          <input type="text"  required="required" name="tax" placeholder="{{ trans('app.Enater Tax')}}" maxlength="3" class="form-control col-md-7 col-xs-12">
                           @if ($errors->has('tax'))
               <span class="help-block">
                 <strong>{{ $errors->first('tax') }}</strong>
               </span>
             @endif
                        </div>
                      </div>
                     <input type="hidden" name="_token" value="{{csrf_token()}}">
                      <div class="form-group col-md-12 col-sm-12 col-xs-12">
                        <div class="col-md-9 col-sm-9 col-xs-12 text-center">
                         <a class="btn btn-primary" href="{{ URL::previous() }}">{{ trans('app.Cancel')}}</a>
              
                          <button type="submit" class="btn btn-success">{{ trans('app.Submit')}}</button>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
         </div> 
@else
	<div class="right_col" role="main">
		<div class="nav_menu main_title" style="margin-top:4px;margin-bottom:15px;">
           
              <div class="nav toggle" style="padding-bottom:16px;">
               <span class="titleup">&nbsp {{ trans('app.You are not authorize this page.')}}</span>
              </div>
          </div>
	</div>
@endif   
@endsection