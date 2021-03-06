 @extends('layouts.app')
@section('content')
<?php $userid = Auth::user()->id; ?>
@if (getAccessStatusUser('Job Card',$userid)=='yes')
	@if(getActiveCustomer($userid)=='no')
        <div class="right_col" role="main">
			<div class="page-title">
				<div class="nav_menu">
					<nav>
						<div class="nav toggle titleup">
							<span>&nbsp {{ trans('app.You are not authorize this page.')}}</span>
						</div>
					</nav>
				</div>
			</div>
		</div>
    @else	
	<div class="right_col" role="main">
		<div class="page-title">
			<div class="nav_menu">
				<nav>
					<div class="nav toggle">
						<a id="menu_toggle"><i class="fa fa-bars"> </i><span class="titleup">&nbsp {{ trans('app.Gate Pass')}}</span></a>
					</div>
					  @include('dashboard.profile')
				</nav>
			</div>
		</div>
		<div class="x_content">
			<ul class="nav nav-tabs bar_tabs" role="tablist">
				<li role="presentation" class=""><a href="{!! url('/gatepass/list')!!}"><span class="visible-xs"></span><i class="fa fa-list fa-lg">&nbsp;</i>{{ trans('app.Gatepass List')}}</span></a></li>
				
				<li role="presentation" class="active"><a href="{!! url('/gatepass/list/edit/{$gatepass->id}') !!}"><span class="visible-xs"></span><i class="fa fa-plus-circle fa-lg">&nbsp;</i><b>{{ trans('app.Edit Gatepass')}}</b></span></a></li>
			</ul>
		</div>
		<div class="row">
			<div class="col-md-12 col-sm-12 col-xs-12">
				<div class="x_panel">
					<div class="x_content">
						<form id="demo-form2" action="upadte/{{$gatepass->id}}" method="post" 
						enctype="multipart/form-data" data-parsley-validate 
									 class="form-horizontal form-label-left input_mask">
									 
							<input type="hidden" name="_token" value="{{csrf_token()}}">
							
							<div class="col-md-12 col-xs-12 col-sm-12 space">
								<h4><b>{{ trans('app.Customer Information')}}</b></h4>
								<p class="col-md-12 col-xs-12 col-sm-12 ln_solid"></p>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback {{ $errors->has('jobcard') ? ' has-error' : '' }}">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" for="jobcard">{{ trans('app.JobCard No. ') }} <label class="text-danger">*</label> 
							
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<select name="jobcard"  class="form-control" id="selectjobcard" url="{!!url('/gatepass/gatedata')!!}"required >
										
										@if(!empty($jobno))
											@foreach($jobno as $jobnos)
											<option value="{{$jobnos->job_card}}" <?php if($jobnos->job_card == $gatepass->jobcard_id){echo"selected";};?>>{{$jobnos->job_card }}</option>
											@endforeach
											@endif
									</select>
								</div>
							</div>

							<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback {{ $errors->has('gatepass_no') ? ' has-error' : '' }}">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" for="gatepass_no">{{ trans('app.Gate_no') }} <label class="text-danger">*</label>
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<input type="text" id="gatepass_no" name="gatepass_no"  class="form-control"  
									value="{{$gatepass->gatepass_no}}" placeholder="{{ trans('app.Auto Generated Gate Pass Number')}}"  readonly/>
								</div>
							</div>

							<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback {{ $errors->has('firstname') ? ' has-error' : '' }}">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" for="firstname">{{ trans('app.First Name') }} <label class="text-danger">*</label> 
								</label>
							
								<div class="col-md-9 col-sm-9 col-xs-12">
								  <input type="text"  id="customer" name="Customername" value="{{$gatepass->name}}" placeholder="{{ trans('app.Enter First Name')}}"  class="form-control" readonly >
								</div>
							</div>
						 
							<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback {{ $errors->has('lastname') ? ' has-error' : '' }}">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" for="lastname">{{ trans('app.Last Name') }} <label class="text-danger">*</label>
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<input type="text" id="lastname" name="lastname" value="{{$gatepass->lastname}}" placeholder="{{ trans('app.Enter Last Name')}}" class="form-control" readonly>
								</div>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback {{ $errors->has('email') ? ' has-error' : '' }}">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" for="email">{{ trans('app.Email') }} <label class="text-danger">*</label></label>
								<div class="col-md-9 col-sm-9 col-xs-12">
									<input type="text"  id="email" name="email" value="{{$gatepass->email}}" placeholder="{{ trans('app.Enter Email')}}" class="form-control " readonly>
								</div>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback {{ $errors->has('mobile') ? ' has-error' : '' }}">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" for="mobile">{{ trans('app.Mobile No') }} <label class="text-danger" >*</label>
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
								  <input type="text"  id="mobile" name="mobile" value="{{$gatepass->mobile_no}}" placeholder="{{ trans('app.Enter Mobile No')}}"  class="form-control" readonly >
								</div>
							</div>
						 
							<div class="col-md-12 col-xs-12 col-sm-12 space">
								<h4><b>{{ trans('app.Vehicle Information')}}</b></h4>
								<p class="col-md-12 col-xs-12 col-sm-12 ln_solid"></p>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback {{ $errors->has('model_name') ? ' has-error' : '' }}">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" for="model_name">{{ trans('app.Vehicle Name') }} <label class="text-danger" >*</label>
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
								  <input type="text"  id="vehicle" name="vehiclename" value="{{$gatepass->modelname}}"placeholder="{{ trans('app.Enter Vehicle Name')}}" class="form-control" readonly >
								</div>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback {{ $errors->has('veh_type') ? ' has-error' : '' }}">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" for="veh_type">{{ trans('app.Vehicle Type') }} <label class="text-danger" >*</label>
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
								  <input type="text"  id="veh_type" name="veh_type" value="{{$gatepass->vehicle_type}}"placeholder="{{ trans('app.Enter Vehicle Type')}}" class="form-control" readonly >
								</div>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback {{ $errors->has('chassis') ? ' has-error' : '' }}">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" for="chassis">{{ trans('app.Chassis') }} <label class="text-danger" >*</label>
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
								  <input type="text" id="chassis" name="chassis" value="{{$gatepass->chassisno}}" placeholder="{{ trans('app.Enter Chassis No.')}}"  class="form-control" readonly >
								</div>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback {{ $errors->has('kms') ? ' has-error' : '' }}">
								<label class="control-label col-md-3 col-sm-3 col-xs-12" for="kms">{{ trans('app.KMs.Run') }} <label class="text-danger" >*</label>
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12">
								  <input type="text" id="kms" name="kms" value="{{$gatepass->kms_run}}" placeholder="{{ trans('app.Enter Kms. Run')}}" class="form-control jobcard" readonly >
								</div>
							</div>
							<div class="col-md-12 col-xs-12 col-sm-12 space">
								<h4><b>{{ trans('app.Other Information')}}</b></h4>
								<p class="col-md-12 col-xs-12 col-sm-12 ln_solid"></p>
							</div>
							<div class="col-md-6 col-sm-6 col-xs-12 form-group {{ $errors->has('out_date') ? ' has-error' : '' }}">
								<label class="control-label col-md-3 col-sm-3 col-xs-12 currency" for="out_date">{{ trans('app.Vehicle Out Date') }} <label class="text-danger" >*</label>
								</label>
								<div class="col-md-9 col-sm-9 col-xs-12 input-group date datepicker">
									<span class="input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
									<input type="text"  id="" name="out_date" value="<?php  echo date( getDateFormat().' H:i:s',strtotime($gatepass->service_out_date));?>" placeholder="{{ trans('app.Enter Vehicle Out Date')}}" class="form-control" onkeypress="return false;" required >
								</div>
								<!-- @if ($errors->has('out_date'))
								   <span class="help-block">
									   <strong style="margin-left: 27%;">{{ $errors->first('out_date') }}</strong>
								   </span>
								 @endif -->
							</div>
							<div class="form-group col-md-12 col-sm-12 col-xs-12">
								<div class="col-md-12 col-sm-12 col-xs-12 text-center">
									<a class="btn btn-primary" href="{{ URL::previous() }}">{{ trans('app.Cancel')}}</a>
									<button type="submit" class="btn btn-success">{{ trans('app.Update')}}</button>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
@endif
 @else
	<div class="right_col" role="main">
		<div class="nav_menu main_title" style="margin-top:4px;margin-bottom:15px;">
			<div class="nav toggle" style="padding-bottom:16px;">
               <span class="titleup">&nbsp {{ trans('app.You are not authorize this page.')}}</span>
              </div>
			</div>
	</div>
@endif   
<script src="{{ URL::asset('build/js/jquery.min.js') }}"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="{{ URL::asset('vendors/moment/min/moment.min.js') }}"></script>
    <script src="{{ URL::asset('vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ URL::asset('vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
<!-- datetimepicker -->	
	<script>
    $('.datepicker').datetimepicker({
        format: "<?php echo getDatetimepicker(); ?>",
		autoclose:1,
    });
</script>
<script>

$('body').on('change','#selectjobcard',function(){
	var jobcard = $(this).val();
	
	var url=$(this).attr('url');
	
	$.ajax({
				type: 'GET',
				url: url,
				data : {jobcard:jobcard},
				success: function (data)
				{	
					 var gaterecord = jQuery.parseJSON(data);
								
						$('#customer').attr('value',gaterecord.name);
						$('#lastname').attr('value',gaterecord.lastname);
						$('#email').attr('value',gaterecord.email);
						$('#mobile').attr('value',gaterecord.mobile_no);
						$('#vehicle').attr('value',gaterecord.modelname);
						$('#veh_type').attr('value',gaterecord.vehicle_type);
						$('#chassis').attr('value',gaterecord.chassisno);
						$('#kms').attr('value',gaterecord.kms_run);
				}
			});
});
</script>

@endsection