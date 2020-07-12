@extends('layouts.app')
@section('content')

<!-- page content -->
<?php $userid = Auth::user()->id; ?>
@if (getAccessStatusUser('Compliance',$userid)=='yes')
    <div class="right_col" role="main">
        <div class="">
            <div class="page-title">
				<div class="nav_menu">
					<nav>
						<div class="nav toggle">
							<a id="menu_toggle"><i class="fa fa-bars"></i><span class="titleup">&nbsp {{ trans('app.Compliance Management')}}</span></a> 
						</div>
						@include('dashboard.profile')
					</nav>
                </div>
				@if(session('message'))
				<div class="row massage">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="checkbox checkbox-success checkbox-circle">
							<input id="checkbox-10" type="checkbox" checked="">
							<label for="checkbox-10 colo_success">  {{session('message')}} </label>
						</div>
					</div>
				</div>
				@endif
            </div>
			<div class="x_content">
                <ul class="nav nav-tabs bar_tabs tabconatent" role="tablist">
					<li role="presentation" class="suppo_llng_li floattab"><a href="{!! url('/rto/list')!!}"><span class="visible-xs"></span> <i class="fa fa-list fa-lg">&nbsp;</i>{{ trans('app.List Of RTO Taxes')}}</span></a></li>
					<li role="presentation" class="active suppo_llng_li_add floattab"><a href="{!! url('/rto/list/edit/'.$editid)!!}"><span class="visible-xs"></span> <i class="fa fa-pencil-square-o" aria-hidden="true">&nbsp;</i><b>{{ trans('app.Edit RTO Taxes')}}</b></span></a></li>
				</ul>
			</div>
            <div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_content">
							<form method="post" action="update/{{ $rto->id }}" enctype="multipart/form-data"  class="form-horizontal upperform">
								<div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="v_id">{{ trans('app.Vehicle Name')}}  <label class="text-danger">*</label></label>
									<div class="col-md-5 col-sm-5 col-xs-12">
										<select class="form-control" name="v_id" required>
											<option value="">{{ trans('app.-- Select Vehicle --')}}</option>
											@if(!empty($vehicle))
												@foreach ($vehicle as $vehicles)
													<option value="{{ $vehicles->id }}" <?php if($vehicles->id == $rto->vehicle_id) {echo 'selected';}?>>{{ $vehicles->modelname }}</option>
												@endforeach
											@endif
										</select>
									</div>
								</div>
					  
								<div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback {{ $errors->has('rto_tax') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="rto_tax">{{ trans('app.RTO / Registration C.R. Temp Tax')}} <label class="text-danger">*</label></label>
									<div class="col-md-5 col-sm-5 col-xs-12">
										<input type="text" id="rto_tax" name="rto_tax" value="{{ $rto->registration_tax }}" maxlength="10" class="form-control" required>
										@if ($errors->has('rto_tax'))
										   <span class="help-block">
											   <strong>{{ $errors->first('rto_tax') }}</strong>
										   </span>
										 @endif
									</div>
								</div>
					  
								<div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback {{ $errors->has('num_plate_tax') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="num_plate_tax">{{ trans('app.Number Plate charge')}} <label class="text-danger">*</label>
									</label>
									<div class="col-md-5 col-sm-5 col-xs-12">
										<input type="text" id="num_plate_tax" name="num_plate_tax"  value="{{ $rto->number_plate_charge }}" class="form-control" maxlength="10" required>
										@if ($errors->has('num_plate_tax'))
										   <span class="help-block">
											   <strong>{{ $errors->first('num_plate_tax') }}</strong>
										   </span>
										 @endif
									</div>
								</div>
					  
								<div class="col-md-12 col-sm-12 col-xs-12 form-group has-feedback {{ $errors->has('mun_tax') ? ' has-error' : '' }}">
									<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mun_tax">{{ trans('app.Municipal Road Tax')}} <label class="text-danger">*</label></label>
									<div class="col-md-5 col-sm-5 col-xs-12">
									  
									 <input type="text" id="mun_tax" name="mun_tax"  value="{{ $rto->muncipal_road_tax }}" maxlength="10" class="form-control" required>
									 @if ($errors->has('mun_tax'))
									   <span class="help-block">
										   <strong>{{ $errors->first('mun_tax') }}</strong>
									   </span>
									 @endif
									</div>
								</div>
									<input type="hidden" name="_token" value="{{csrf_token()}}">
                     
								<div class="form-group col-md-12 col-sm-12 col-xs-12 ">
									<div class="col-md-9 col-sm-9 col-xs-12 text-center">
									  <a class="btn btn-primary" href="{{ URL::previous() }}">{{ trans('app.Cancel')}}</a>
									  <button type="submit" class="btn btn-success">{{ trans('app.Update')}}</button>
									</div>
								</div>
							</form>
							<div class="col-md-12 col-sm-12 col-xs-12 form-group">
								<p>* {{ trans('app.RTO')}} = {{ trans('app.Regional Transport Office')}}</p>
							</div>
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
		 <script type="text/javascript" src="https://code.jquery.com/jquery-2.1.4.min.js"></script>  


@endsection