@extends('layouts.app')
@section('content')
<!-- page content -->
<?php $userid = Auth::user()->id; ?>
@if (getAccessStatusUser('Inventory',$userid)=='yes')
<div class="right_col" role="main">
   <div class="">
      <div class="page-title">
         <div class="nav_menu">
            <nav>
               <div class="nav toggle">
                  <a id="menu_toggle"><i class="fa fa-bars"></i><span class="titleup">&nbsp {{ trans('app.Supplier')}}</span></a>
               </div>
               @include('dashboard.profile')
            </nav>
         </div>
      </div>
   </div>
   <div class="x_content">
      <ul class="nav nav-tabs bar_tabs" role="tablist">
         <li role="presentation" class=""><a href="{!! url('/supplier/list')!!}"><span class="visible-xs"></span><i class="fa fa-list fa-lg">&nbsp;</i> {{ trans('app.Supplier List')}}</a></li>
         <li role="presentation" class="active"><a href="{!! url('/supplier/add')!!}"><span class="visible-xs"></span> <i class="fa fa-plus-circle fa-lg">&nbsp;</i><b>{{ trans('app.Add Supplier')}}</b></a></li>
      </ul>
   </div>
   <div class="clearfix"></div>
   <div class="row">
      <div class="col-md-12 col-sm-12 col-xs-12">
         <div class="x_panel">
            <div class="x_content">
               <form method="post" action="{{ url('/supplier/store') }}" enctype="multipart/form-data"  class="form-horizontal upperform">
                  <div class="col-md-12 col-xs-12 col-sm-12 space">
                     <h4><b>{{ trans('app.Personal Information')}}</b></h4>
                     <p class="col-md-12 col-xs-12 col-sm-12 ln_solid"></p>
                  </div>
				<div class="col-md-12 col-sm-6 col-xs-12">  
                  <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                     <label class="control-label col-md-4 col-sm-4 col-xs-12" for="first-name">{{ trans('app.First Name')}} 
                     </label>
                     <div class="col-md-8 col-sm-8 col-xs-12">
                        <input type="text" id="firstname" name="firstname" max="5" class="form-control" value="{{ old('firstname') }}"  placeholder="{{ trans('app.Enter First Name')}}" maxlength="25" >
                        @if ($errors->has('firstname'))
                        <span class="help-block">
                        <strong>{{ $errors->first('firstname') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                     <label class="control-label col-md-4 col-sm-4 col-xs-12" for="last-name">{{ trans('app.Last Name')}}
                     </label>
                     <div class="col-md-8 col-sm-8 col-xs-12">
                        <input type="text" id="lastname" name="lastname" class="form-control" value="{{ old('lastname') }}" placeholder="{{ trans('app.Enter Last Name')}}" maxlength="25" >
                        @if ($errors->has('lastname'))
                        <span class="help-block">
                        <strong>{{ $errors->first('lastname') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                </div>
				<div class="col-md-12 col-sm-6 col-xs-12">  
                  <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                     <label for="middle-name" class="control-label col-md-4 col-sm-4 col-xs-12">{{ trans('app.Company Name')}}<label class="text-danger">*</label></label>
                     <div class="col-md-8 col-sm-8 col-xs-12">
                        <input type="text" id="displayname" class="form-control"  name="displayname" value="{{ old('displayname') }}" placeholder="{{ trans('app.Enter Display Name')}}" maxlength="50" >
						@if ($errors->has('displayname'))
							<span class="help-block" style="color:#a94442">
							<strong>{{ $errors->first('displayname') }}</strong>
							</span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                     <label class="control-label col-md-4 col-sm-4 col-xs-12">{{ trans('app.Gender')}} <label class="text-danger">*</label></label>
                     <div class="col-md-8 col-sm-8 col-xs-12 gender">
                        <input type="radio"  name="gender" value="1" checked>{{ trans('app.Male')}} 
                        <input type="radio" name="gender" value="2" >{{ trans('app.Female')}}
                     </div>
                  </div>
				</div>
				<div class="col-md-12 col-sm-6 col-xs-12">  
                  <div class="col-md-6 col-sm-6 col-xs-12 form-group {{ $errors->has('dob') ? ' has-error' : '' }}">
                     <label class="control-label col-md-4 col-sm-4 col-xs-12">{{ trans('app.Date Of Birth')}} 
                     </label>
                     <div class="col-md-8 col-sm-8 col-xs-12 input-group date datepicker">
                        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
                        <input type="text" id="date_of_birth" class="form-control"  name="dob" value="{{ old('dob') }}"   placeholder="<?php echo getDatepicker();?>" readonly />
                     </div>
                     @if ($errors->has('dob'))
                     <span class="help-block">
                     <strong style="margin-left:27%;">{{ $errors->first('dob') }}</strong>
                     </span>
                     @endif
                  </div>
				   <div class="col-md-6 col-sm-6 col-xs-12 form-group {{ $errors->has('email') ? ' has-error' : '' }}">
                     <label class="control-label col-md-4 col-sm-4 col-xs-12" for="Email">{{ trans('app.Email')}} <label class="text-danger">*</label>
                     </label>
                     <div class="col-md-8 col-sm-8 col-xs-12">
                        <input type="text" id="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="{{ trans('app.Enter Email')}}"  maxlength="50" required>
                        @if ($errors->has('email'))
                        <span class="help-block">
                        <strong>{{ $errors->first('email') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                </div>
                <div class="col-md-12 col-sm-6 col-xs-12">  
                  <div class="col-md-6 col-sm-6 col-xs-12 form-group {{ $errors->has('mobile') ? ' has-error' : '' }}">
                     <label class="control-label col-md-4 col-sm-4 col-xs-12" for="mobile">{{ trans('app.Mobile No')}} <label class="text-danger">*</label>
                     </label>
                     <div class="col-md-8 col-sm-8 col-xs-12">
                        <input type="text" id="mobile" name="mobile" class="form-control" value="{{ old('mobile') }}" maxlength="15" placeholder="{{ trans('app.Enter Mobile No')}}" required>
                        @if ($errors->has('mobile'))
                        <span class="help-block">
                        <strong>{{ $errors->first('mobile') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
				   <div class="col-md-6 col-sm-6 col-xs-12 form-group {{ $errors->has('contact_person') ? ' has-error' : '' }}">
                     <label class="control-label col-md-4 col-sm-4 col-xs-12">{{ trans('app.Contact Person')}}<label class="text-danger">*</label> 
                     </label>
                     <div class="col-md-8 col-sm-8 col-xs-12">
                        <input type="text" id="" class="form-control"  name="contact_person" value="{{ old('contact_person') }}" placeholder="{{ trans('app.Enter Contact Person Name')}}" maxlength="25">
						@if ($errors->has('contact_person'))
							<span class="help-block">
							<strong>{{ $errors->first('contact_person') }}</strong>
							</span>
                        @endif
                     </div>
					</div>
                  </div>
				<div class="col-md-12 col-sm-6 col-xs-12">  
                  <div class="col-md-6 col-sm-6 col-xs-12 form-group {{ $errors->has('landlineno') ? ' has-error' : '' }}">
                     <label class="control-label col-md-4 col-sm-4 col-xs-12" for="landline-no">{{ trans('app.Landline No')}}<label class="text-danger">*</label>
                     </label>
                     <div class="col-md-8 col-sm-8 col-xs-12">
                        <input type="text" id="landlineno" name="landlineno" class="form-control" value="{{ old('landlineno') }}" maxlength="15" placeholder="{{ trans('app.Enter LandLine No')}}">
                        @if ($errors->has('landlineno'))
                        <span class="help-block">
                        <strong>{{ $errors->first('landlineno') }}</strong>
                        </span>
                        @endif
                     </div>
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-12 form-group {{ $errors->has('image') ? ' has-error' : '' }}">
                     <label class="control-label col-md-4 col-sm-4 col-xs-12" for="image">{{ trans('app.Image')}} 
                     </label>
                     <div class="col-md-8 col-sm-8 col-xs-12">
                        <input type="file" id="input-file-max-fs"  name="image"  class="form-control dropify" data-max-file-size="5M">
                        @if ($errors->has('image'))
                        <span class="help-block">
                        <strong>{{ $errors->first('image') }}</strong>
                        </span>
                        @endif
                        <div class="dropify-preview">
                           <span class="dropify-render"></span>
                           <div class="dropify-infos">
                              <div class="dropify-infos-inner">
                                 <p class="dropify-filename">
                                    <span class="file-icon"></span> 
                                    <span class="dropify-filename-inner"></span>
                                 </p>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                </div>
                  <div class="col-md-12 col-xs-12 col-sm-12 space">
                     <h4><b>{{ trans('app.Address')}}</b></h4>
                     <p class="col-md-12 col-xs-12 col-sm-12 ln_solid"></p>
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                     <label class="control-label col-md-4 col-sm-4 col-xs-12" for="Country">{{ trans('app.Country')}} <label class="text-danger">*</label>
                     </label>
                     <div class="col-md-8 col-sm-8 col-xs-12">
                        <select class="form-control select_country" name="country_id" countryurl="{!! url('/getstatefromcountry') !!}" required>
                           <option value="">{{ trans('app.Select Country')}}</option>
                           @foreach ($country as $countrys)
                           <option value="{{ $countrys->id }}">{{$countrys->name }}</option>
                           @endforeach
                        </select>
                     </div>
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                     <label class="control-label col-md-4 col-sm-4 col-xs-12" for="State ">{{ trans('app.State')}} </label>
                     <div class="col-md-8 col-sm-8 col-xs-12">
                        <select class="form-control state_of_country" name="state" stateurl="{!! url('/getcityfromstate') !!}">
                        </select>
                     </div>
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                     <label class="control-label col-md-4 col-sm-4 col-xs-12" for="Town/City">{{ trans('app.Town/City')}}</label>
                     <div class="col-md-8 col-sm-8 col-xs-12">
                        <select class="form-control city_of_state" name="city" value="{{ old('firstname') }}">
                        </select>
                     </div>
                  </div>
                  <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                     <label class="control-label col-md-4 col-sm-4 col-xs-12" for="Address">{{ trans('app.Address')}} <label class="text-danger">*</label>
                     </label>
                     <div class="col-md-8 col-sm-8 col-xs-12">
					 <textarea id="address" name="address" class="form-control" maxlength="100" required>{{ old('address') }}</textarea>
                        
                     </div>
                  </div>
                  @if(!empty($tbl_custom_fields))
                  <div class="col-md-12 col-xs-12 col-sm-12 space">
                     <h4><b>{{ trans('app.Custom Fields')}}</b></h4>
                     <p class="col-md-12 col-xs-12 col-sm-12 ln_solid"></p>
                  </div>
                  @foreach($tbl_custom_fields as $tbl_custom_field)
                  <?php 
                     if($tbl_custom_field->required == 'yes')
                     {
                     	$required="required";
                     	$red="*";
                     }else{
                     	$required="";
                     	$red="";
                     }
                      ?>
                  <div class="col-md-6 col-sm-6 col-xs-12 form-group">
                     <label class="control-label col-md-4 col-sm-4 col-xs-12" for="account-no">{{$tbl_custom_field->label}} <label class="text-danger">{{$red}}</label></label>
                     <div class="col-md-8 col-sm-8 col-xs-12">
                        @if($tbl_custom_field->type == 'textarea')
                        <textarea  name="custom[{{$tbl_custom_field->id}}]" class="form-control" placeholder="Enter {{$tbl_custom_field->label}}" maxlength="100" {{$required}}></textarea>
                        @else
                        <input type="{{$tbl_custom_field->type}}"  name="custom[{{$tbl_custom_field->id}}]"  class="form-control" placeholder="Enter {{$tbl_custom_field->label}}" maxlength="30" {{$required}}>
                        @endif
                     </div>
                  </div>
                  @endforeach	
                  @endif
                  <input type="hidden" name="_token" value="{{csrf_token()}}">
                  <div class="form-group col-md-12 col-sm-12 col-xs-12">
                     <div class="col-md-12 col-sm-12 col-xs-12 text-center">
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
<script>
   $(document).ready(function(){
   	
   	$('.select_country').change(function(){
   		countryid = $(this).val();
   		var url = $(this).attr('countryurl');
   		$.ajax({
   			type:'GET',
   			url: url,
   			data:{ countryid:countryid },
   			success:function(response){
   				$('.state_of_country').html(response);
   			}
   		});
   	});
   	
   	$('body').on('change','.state_of_country',function(){
   		stateid = $(this).val();
   		
   		var url = $(this).attr('stateurl');
   		$.ajax({
   			type:'GET',
   			url: url,
   			data:{ stateid:stateid },
   			success:function(response){
   				$('.city_of_state').html(response);
   			}
   		});
   	});
   });
</script>
<script>
   $(document).ready(function(){
       // Basic
       $('.dropify').dropify();
   
       // Translated
       $('.dropify-fr').dropify({
           messages: {
               default: 'Glissez-déposez un fichier ici ou cliquez',
               replace: 'Glissez-déposez un fichier ou cliquez pour remplacer',
               remove:  'Supprimer',
               error:   'Désolé, le fichier trop volumineux'
           }
       });
   
       // Used events
       var drEvent = $('#input-file-events').dropify();
   
       drEvent.on('dropify.beforeClear', function(event, element){
           return confirm("Do you really want to delete \"" + element.file.name + "\" ?");
       });
   
       drEvent.on('dropify.afterClear', function(event, element){
           alert('File deleted');
       });
   
       drEvent.on('dropify.errors', function(event, element){
           console.log('Has Errors');
       });
   
       var drDestroy = $('#input-file-to-destroy').dropify();
       drDestroy = drDestroy.data('dropify')
       $('#toggleDropify').on('click', function(e){
           e.preventDefault();
           if (drDestroy.isDropified()) {
               drDestroy.destroy();
           } else {
               drDestroy.init();
           }
       })
   });
   
</script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="{{ URL::asset('vendors/moment/min/moment.min.js') }}"></script>
<script src="{{ URL::asset('vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ URL::asset('vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
<script>
   $('.datepicker').datetimepicker({
      format: "<?php echo getDatepicker(); ?>",
   autoclose: 1,
   minView: 2,
   endDate: new Date(),
   });
</script>
@endsection