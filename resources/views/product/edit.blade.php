@extends('layouts.app')
@section('content')
<!-- page content -->
<?php $userid = Auth::user()->id; ?>
@if (getAccessStatusUser('Inventory',$userid)=='yes')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i><span class="titleup">&nbsp {{ trans('app.Product')}}</span></a>
              </div>
                  @include('dashboard.profile')
            </nav>
          </div>
    </div>
	<div class="x_content">
        <ul class="nav nav-tabs bar_tabs" role="tablist">
			<li role="presentation" class=""><a href="{!! url('/product/list')!!}"><span class="visible-xs"></span> <i class="fa fa-list fa-lg">&nbsp;</i>{{ trans('app.Product List')}}</a></li>
			<li role="presentation" class="active"><a href="{!! url('/product/list/edit/'.$editid)!!}"><span class="visible-xs"></span><i class="fa fa-pencil-square-o" aria-hidden="true">&nbsp;</i><b>{{ trans('app.Edit Product')}}</b></a></li>
		</ul>
	</div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_content">
                   <form method="post" action="update/{{ $product->id }}" enctype="multipart/form-data"  class="form-horizontal upperform">
						<div class="form-group">
							<div class="">
								<label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">{{ trans('app.Product Number')}} <label class="text-danger">*</label></label>
								<div class="col-md-4 col-sm-4 col-xs-12">
								
									<input type="text" id="p_no" name="p_no"  class="form-control" value="{{ $product->product_no }}" placeholder="{{ trans('app.Enter Product No')}}" readonly>
								</div>
							</div>
							<div class="">
								<label class="control-label col-md-2 col-sm-2 col-xs-12" for="last-name">{{ trans('app.Product Date')}} <label class="text-danger">*</label></label>
								<div class="col-md-4 col-sm-4 col-xs-12 input-group date datepicker">
									<span class="input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
									<input type="text" id="p_date" name="p_date" class="form-control" placeholder="<?php echo getDateFormat();?>" value="{{ date(getDateFormat(),strtotime($product->product_date)) }}" onkeypress="return false;" required />
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="">
								<label class="control-label col-md-2 col-sm-2 col-xs-12" for="last-name">{{ trans('app.Name')}} <label class="text-danger">*</label></label>
								<div class="col-md-4 col-sm-4 col-xs-12">
									<input type="text" id="name" name="name" class="form-control" maxlength="30" value="{{ $product->name }}" placeholder="{{ trans('app.Enter Product Name')}}" required>
								</div>
							</div>
							<div class="">
								<label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">{{ trans('app.Product Image')}}</label>
								<div class="col-md-4 col-sm-4 col-xs-12">
									<input type="file" id="input-file-max-fs"  name="image"  class="form-control dropify" data-max-file-size="5M" >
									 @if(!empty($product->product_image))
						
									 <img src="{{ URL::asset('public/product/'.$product->product_image) }}"  width="60px" height="60px" class="img-circle" style="margin-top:10px;">
									 @else
										 <img src="{{ URL::asset('public/product/avtar.png') }}"  width="60px" height="60px" class="img-circle" style="margin-top:10px;">
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
						<div class="form-group">
							<div class="">
								<label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">{{ trans('app.Manufacturer Name')}} </label>
								<div class="col-md-2 col-sm-2 col-xs-12">
									<select id="p_type" name="p_type"  class="form-control product_type_data">
										<option value="">--{{ trans('app.Select Manufacturing Name')}}--</option>
											@if(!empty($product_type))
												@foreach($product_type as $product_types)
													<option value="{{ $product_types->id }}" <?php if($product_types->id == $product->product_type_id) {echo 'selected';} ?>>{{ $product_types->type }}</option>
												@endforeach
											@endif
									</select>
								</div>
								<div class="col-md-2 col-sm-2 col-xs-12 addremove">
									<button type="button" data-target="#responsive-modal" data-toggle="modal" class="btn btn-default">{{ trans('app.Add Or Remove')}}</button>
								</div>
							</div>
							<div class="{{ $errors->has('price') ? ' has-error' : '' }}">
								<label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">{{ trans('app.Price')}} (<?php echo getCurrencySymbols(); ?>) <label class="text-danger">*</label></label>
								<div class="col-md-4 col-sm-4 col-xs-12">
									<input type="text" id="price" name="price"  class="form-control" value="{{ $product->price }}" placeholder="{{ trans('app.Enter Product Price')}}" maxlength="10" required>
									 @if ($errors->has('price'))
								   <span class="help-block">
									   <strong>{{ $errors->first('price') }}</strong>
								   </span>
								 @endif
								</div>
							</div>
						</div>
						<div class="form-group">
							 <div class="">
								<label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">{{ trans('app.Color Name')}}</label>
								<div class="col-md-2 col-sm-2 col-xs-12">
									<select id="color_type" name="color"  class="form-control color_name_data">
										<option value="">{{ trans('app.-- Select Color --')}}</option>
											@if(!empty($color))
												@foreach($color as $colors)
													<option value="{{ $colors->id }}" <?php if($colors->id == $product->color_id) { echo 'selected'; } ?> >{{ $colors->color }}</option>
												@endforeach
											@endif
									</select>
								</div>
								<div class="col-md-2 col-sm-2 col-xs-12 addremove">
									<button type="button" data-target="#responsive-modal-color" data-toggle="modal" class="btn btn-default">{{ trans('app.Add Or Remove')}}</button>
								</div>
							</div>
							<div class="">
								<label class="control-label col-md-2 col-sm-2 col-xs-12" for="last-name">{{ trans('app.Warranty')}} </label>
								<div class="col-md-4 col-sm-4 col-xs-12">
									<input type="text" id="warranty" name="warranty" class="form-control" value="{{ $product->warranty }}" placeholder="{{ trans('app.Enter Product Warranty')}}" maxlength="20">
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="">
								<label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">{{ trans('app.Unit Of Measurement')}} <label class="text-danger">*</label></label>
								<div class="col-md-2 col-sm-2 col-xs-12">
									<select  name="unit"  class="form-control unit_product_data" required>
										<option value="">{{ trans('app.-- Select Unit --')}}</option>
										@foreach($unitproduct as $tbl_product_unit)
											<option value="{{$tbl_product_unit->id}}"<?php if($tbl_product_unit->id == $product->unit) { echo 'selected'; } ?>>{{$tbl_product_unit->name}}
										@endforeach
										</option>
									</select>
								</div>
								<div class="col-md-2 col-sm-2 col-xs-12 addremove">
									<button type="button" data-target="#responsive-modal-unit" data-toggle="modal" class="btn btn-default">{{ trans('app.Add Or Remove')}}</button>
								</div>
							</div>
							<div class="">
								<label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">{{ trans('app.Supplier')}}</label>
								<div class="col-md-4 col-sm-4 col-xs-12">
									<select  id="sup_id" name="sup_id"  class="form-control">
									<option value="">{{ trans('app.-- Select Supplier --')}}</option>
									@if(!empty($supplier))
										@foreach ($supplier as $suppliers)
											<option value="{{ $suppliers->id }}" <?php if($suppliers->id == $product->supplier_id) { echo 'selected'; } ?> >{{ $suppliers->name.' '.$suppliers->lastname }}</option>
										@endforeach
									@endif
									</select>
								</div>
							</div>
						</div>
						<div class="form-group">
							<div class="col-md-6 col-sm-6 col-xs-12 form-group has-feedback">
								<label class="control-label col-md-4 col-sm-4 col-xs-12"> {{ trans('app.Category') }} <label class="text-danger">*</label></label>
								<div class="col-md-8 col-sm-8 col-xs-12 gender">
									<input type="radio" name="category" value="0" required @if($product->category == '0') Checked @endif>Vehicle
									<input type="radio" name="category" value="1" @if($product->category == '1') Checked @endif> Part
								</div>
							</div>
						</div>
					  <input type="hidden" name="_token" value="{{csrf_token()}}">
                      <div class="form-group">
                        <div class="col-md-12 col-sm-12 col-xs-12 text-center">
                          <a class="btn btn-primary" href="{{ URL::previous() }}">{{ trans('app.Cancel')}}</a>
                          <button type="submit" class="btn btn-success">{{ trans('app.Update')}}</button>
                        </div>
                      </div>
                    </form>
                </div>
			<!-- product type Add or Remove Model-->	
				 <div class="col-md-6 col-sm-12 col-xs-12">
							<div id="responsive-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
								<div class="modal-dialog">
								<div class="modal-content">
								  <div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
									<h4 class="modal-title">{{ trans('app.Manufacturer Name')}}</h4>
								  </div>
								  <div class="modal-body">
								   <form class="form-horizontal" action="" method="">
										
										<table class="table producttype"  align="center" style="width:40em">
										<thead>
										<tr>
											<td class="text-center"><strong>{{ trans('app.Manufacturer Name')}}</strong></td>
											<td class="text-center"><strong>{{ trans('app.Action')}}</strong></td>
										</tr>
										</thead>
										<tbody>
										@if(!empty($product_type))
											@foreach ($product_type as $product_types)
											<tr class="del-{{$product_types->id }} data_of_type" >
											<td class="text-center ">{{ $product_types->type }}</td>
											<td class="text-center">
											
											<button type="button" productid="{{ $product_types->id }}" deleteproduct="{!! url('prodcttypedelete') !!}" class="btn btn-danger btn-xs deleteproducted">X</button>
											</td>
											</tr>
											@endforeach
										@endif
										</tbody>
										</table>
										 <div class="col-md-8 form-group data_popup">
											<label>{{ trans('app.Manufacturer Name')}}: <span class="text-danger">*</span></label>
												<input type="text" class="form-control product_type" name="product_type"  placeholder="{{ trans('app.Manufacturer Name')}}" maxlength="30" />
										</div>
										<div class="col-md-4 form-group data_popup" style="margin-top:24px;">
											<button type="button" class="btn btn-success addtype" producturl="{!! url('/product_type_add') !!}">{{ trans('app.Submit')}}</button>
										</div>
									</form>
								</div>
							  </div>
							</div>	
						</div>
				</div>
			<!-- Color Add or Remove Model -->
				<div class="col-md-6 col-sm-12 col-xs-12">
							<div id="responsive-modal-color" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
								<div class="modal-dialog">
								<div class="modal-content">
								  <div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
									<h4 class="modal-title">{{ trans('app.Color')}}</h4>
								  </div>
								   <div class="modal-body">
								   <form class="form-horizontal" action="" method="">
										
										<table class="table colornametype"  align="center" style="width:40em">
										<thead>
										<tr>
											<td class="text-center"><strong>{{ trans('app.Color Name')}}</strong></td>
											<td class="text-center"><strong>{{ trans('app.Action')}}</strong></td>
										</tr>
										</thead>
										<tbody>
										@foreach ($color as $colors)
										<tr class="del-{{$colors->id }} data_color_name" >
										<td class="text-center ">{{ $colors->color }}</td>
										<td class="text-center">
										
										<button type="button" id="{{ $colors->id }}" deletecolor="{!! url('colortypedelete') !!}" class="btn btn-danger btn-xs deletecolors">X</button>
										</td>
										</tr>
										@endforeach
										
										</tbody>
										</table>
										
										 <div class="col-md-8 form-group data_popup">
											<label>{{ trans('app.Color Name')}}: <span class="text-danger">*</span></label>
												<input type="text" class="form-control c_name" name="c_name"  placeholder="{{ trans('app.Enter color name')}}" maxlength="20" />
										</div>
										
										<div class="col-md-4 form-group data_popup" style="margin-top:24px;">
												<button type="button" class="btn btn-success addcolor" colorurl="{!! url('/color_name_add') !!}">{{ trans('app.Submit')}}</button>
										</div>
									</form>
								</div>
								</div>
							 </div>
		                    </div>
					</div>
					<!-- Unit Add or Remove Model-->
					<div class="col-md-6 col-sm-12 col-xs-12">
							<div id="responsive-modal-unit" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
								<div class="modal-dialog">
								<div class="modal-content">
								  <div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
									<h4 class="modal-title">{{ trans('app.Unit Of Measurement')}}</h4>
								  </div>
								   <div class="modal-body">
								   <form class="form-horizontal" action="" method="">
								   
										<table class="table unitproductname"  align="center" style="width:40em">
										<thead>
										<tr>
											<td class="text-center"><strong>{{ trans('app.Unit Name')}}</strong></td>
											<td class="text-center"><strong>{{ trans('app.Action')}}</strong></td>
										</tr>
										</thead>
										<tbody>
										@foreach ($unitproduct as $unitproducts)
										<tr class="delete-{{$unitproducts->id }} data_unit_name" >
										<td class="text-center ">{{ $unitproducts->name }}</td>
										<td class="text-center">
										
										<button type="button" unitid="{{ $unitproducts->id }}" u_url="{!! url('product/unitdelete') !!}" class="btn btn-danger btn-xs unitdelete">X</button>
										</td>
										</tr>
										@endforeach
										
										</tbody>
										</table>
										
										<div class="form-group" style="margin-top:20px;">
											<div class="col-md-10 form-group data_popup">
												<label>{{ trans('app.Unit Of Measurement')}}: <span class="text-danger">*</span></label>
												<input type="text" class="form-control u_name" name="unit_measurement"  placeholder="{{ trans('app.Enter Unit Of Measurement')}}" maxlength="30" />
											</div>
										
											<div class="col-md-2 form-group data_popup" style="margin-top:24px;">
												<button type="button" class="btn btn-success addunit" uniturl="{!! url('product/unit') !!}">{{ trans('app.Submit')}}</button>
											</div>
										</div>
									</form>
								</div>
								</div>
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
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="{{ URL::asset('vendors/moment/min/moment.min.js') }}"></script>
<script src="{{ URL::asset('vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ URL::asset('vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
<script>
	$('.datepicker').datetimepicker({
		format: "<?php echo getDatepicker(); ?>",
		autoclose: 1,
		minView: 2,
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

<!-- color add  model -->
<script>
$(document).ready(function(){
	$('.addcolor').click(function(){
		
		var c_name = $('.c_name').val();
		
		var url = $(this).attr('colorurl');
		if(c_name == ""){
			swal("Please Enter Color Name!")
        }else{
			$.ajax({
			    type: 'GET',
						url: url,
						data : {c_name:c_name},
						success:function(data)
					  {
						  var newd = $.trim(data);
				          var classname = 'del-'+newd;
						  
						if(data == '01')
								{
									swal('This Record is Duplicate');
								}else
								{
									$('.colornametype').append('<tr class="'+classname+' data_color_name"><td class="text-center">'+c_name+'</td><td class="text-center"><button type="button" id='+data+' deletecolor="{!! url('colortypedelete') !!}" class="btn btn-danger btn-xs">X</button></a></td><tr>');
									$('.color_name_data').append('<option value='+data+'>'+c_name+'</option>');
									$('.c_name').val('');
								}
						},
						 error: function(e) {
                 alert("An error occurred: " + e.responseText);
                    console.log(e);
                }
						  
	              });
		}
		    
        });
  });
</script>
<!-- color Delete  model -->
<script>
$(document).ready(function(){
	
	$('body').on('click','.btn-xs',function(){
	var colorid = $(this).attr('id');
	var url = $(this).attr('deletecolor');
	swal({
            title: "Are you sure?",
            text: "You will not be able to recover this imaginary file!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        },
         function(isConfirm){
		if (isConfirm) {
		$.ajax({
				type:'GET',
				url:url,
				data:{colorid:colorid},
				success:function(data){
					$('.del-'+colorid).remove();
					$(".color_name_data option[value="+colorid+"]").remove();
					swal("Done!","It was succesfully deleted!","success");
				}
		});
		}else{
				swal("Cancelled", "Your imaginary file is safe :)", "error");
			} 
		})
});
});
</script>

<!-- Product type add add  model -->
<script>
$(document).ready(function(){
	$('.addtype').click(function(){
		var product_type = $('.product_type').val();
		var url = $(this).attr('producturl');
		if(product_type == ""){
            swal('Please Enter Product Type!');
        }else{
               $.ajax({
                       type: 'GET',
						url: url,
						data : {product_type:product_type},
						success:function(data)
                        {
							 var newd = $.trim(data);
				             var classname = 'del-'+newd;
								if(data == '01')
								{
									swal('This Record is Duplicate');
								}else
								{
									$('.producttype').append('<tr class="'+classname+' data_of_type"><td class="text-center">'+product_type+'</td><td class="text-center"><button type="button" id='+data+' deleteproduct="{!! url('prodcttypedelete') !!}" class="btn btn-danger btn-xs">X</button></a></td><tr>');
									$('.product_type_data').append('<option value='+data+'>'+product_type+'</option>');
									$('.product_type').val('');
								}
						},
                    error: function(e) {
                 alert("An error occurred: " + e.responseText);
                    console.log(e);
                }
       });
            
        }
	});
});
</script>
<script>
$(document).ready(function(){
	
	$('body').on('click','.deleteproducted',function(){
		var ptypeid = $(this).attr('productid');
		var url = $(this).attr('deleteproduct');
	swal({
            title: "Are you sure?",
            text: "You will not be able to recover this imaginary file!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        },
         function(isConfirm){
			if (isConfirm) {
				
				$.ajax({
				type:'GET',
				url:url,
				data:{ptypeid:ptypeid},
				success:function(data){
						$('.del-'+ptypeid).remove();
						$(".product_type_data option[value="+ptypeid+"]").remove();
					swal("Done!","It was succesfully deleted!","success");
							}
						});
				}else{
						swal("Cancelled", "Your imaginary file is safe :)", "error");
					} 
				})
		});
	});
</script>

<!-- Unit add  model -->
<script>
$(document).ready(function(){
	$('.addunit').click(function(){
		
		var unit_measurement = $('.u_name').val();
		var url = $(this).attr('uniturl');
		if(unit_measurement == ""){
            swal('Please Enter Unit of Measurement!');
        }else{
			$.ajax({
			    type: 'GET',
						url: url,
						data : {unit_measurement:unit_measurement},
						success:function(data)
						{
						   var newd = $.trim(data);
						  
				           var deleteclass = 'delete-'+newd;
				           
						if(data == '01')
								{
									swal('This Record is Duplicate');
								}else
								{
								$('.unitproductname').append('<tr class="'+deleteclass+' data_unit_name"><td class="text-center">'+unit_measurement+'</td><td class="text-center"><button type="button" unitid='+data+' u_url="{!! url('product/unitdelete') !!}" class="btn btn-danger btn-xs unitdelete">X</button></a></td></tr>');
								$('.unit_product_data').append('<option value='+data+'>'+unit_measurement+'</option>');
								$('.u_name').val('');
								}
						},
						 error: function(e) {
                 alert("An error occurred: " + e.responseText);
                    console.log(e);
                }	  
	              });
		}
        });
   $('body').on('click','.unitdelete',function(){     
	
		var unitid = $(this).attr('unitid');
	   
		var url = $(this).attr('u_url');
		swal({
            title: "Are you sure?",
            text: "You will not be able to recover this imaginary file!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Yes, delete it!",
            closeOnConfirm: false
        },
        function(isConfirm){
				if (isConfirm) {
					$.ajax({
					type:'GET',
					url:url,
					data:{unitid:unitid},
					success:function(data){
						$('.delete-'+unitid).remove();
						$(".unit_product_data option[value="+unitid+"]").remove();
						swal("Done!","It was succesfully deleted!","success");
					}
					});
				}
				else
				{
					swal("Cancelled", "Your imaginary file is safe :)", "error");
				} 
				})
		});
	
	});
 
</script>


@endsection