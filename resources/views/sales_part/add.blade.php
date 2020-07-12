@extends('layouts.app')
@section('content')
<style>
.first_width,.second_width{width:82%;}
.table{margin-bottom:0px;}
.all{width:42%;}
</style>
<!-- page content -->
<?php $userid = Auth::user()->id; ?>
@if (getAccessStatusUser('Sales Part',$userid)=='yes')
    <div class="right_col" role="main">
        <div>
            <div class="page-title">
				<div class="nav_menu">
					<nav>
						<div class="nav toggle">
							<a id="menu_toggle"><i class="fa fa-bars"></i><span class="titleup">&nbsp {{ trans('app.Sales')}}</span></a>
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
                <ul class="nav nav-tabs bar_tabs" role="tablist">
					<li role="presentation" class=""><a href="{!! url('/sales_part/list')!!}"><span class="visible-xs"></span> <i class="fa fa-plus-circle fa-lg">&nbsp;</i>{{ trans('app.Sale Part')}}</span></a></li>
					<li role="presentation" class=""><a href="{!! url('/sales_part/add')!!}"><span class="visible-xs"></span> <i class="fa fa-plus-circle fa-lg">&nbsp;</i>{{ trans('app.Add Sale Part')}}</span></a></li>
				</ul>
			</div>
            <div class="row">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="x_panel">
						<div class="x_content">
							<form method="post" action="{!! url('/sales_part/store') !!}" enctype="multipart/form-data"  class="form-horizontal upperform">

								<div class="form-group">
									<div class="">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" for="last-name">{{ trans('app.Bill No')}} <label class="text-danger">*</label></label>
										<div class="col-md-4 col-sm-4 col-xs-12">
											<input type="text" id="bill_no" name="bill_no" class="form-control" value="{{ $code }}" readonly>
										</div>
									</div>
									<div class="">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" for="last-name">{{ trans('app.Sales Date')}} <label class="text-danger">*</label></label>
										<div class="col-md-4 col-sm-4 col-xs-12 input-group date datepicker">
										 
											<span class="input-group-addon"><i class="glyphicon glyphicon-calendar fa fa-calendar"></i></span>
											<input type="text" id="date" name="date" class="form-control" placeholder="" value="" onkeypress="return false;" required>
										</div>
									</div>
								</div>
								<div class="form-group">
									<div class="">
										<label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">{{ trans('app.Customer Name')}} <label class="text-danger">*</label></label>
										<div class="col-md-4 col-sm-4 col-xs-12">
											<select class="form-control" name="cus_name" required>
												<option value="">{{ trans('app.Select Customer')}}</option>
												@if(!empty($customer))
													@foreach($customer as $customers)
														<option value="{{ $customers->id }}" >{{ $customers->name.' '.$customers->lastname }}</option>
													@endforeach
												@endif
											</select>
										</div>
									</div>
									<div>
										<label class="control-label col-md-2 col-sm-2 col-xs-12" for="first-name">{{ trans('app.Salesman')}} <label class="text-danger">*</label></label>
										<div class="col-md-4 col-sm-4 col-xs-12">
											<select class="form-control" name="salesmanname" id="" required>
												<option value="">{{ trans('app.Select Name')}}</option>
												@if(!empty($employee))
													@foreach($employee as $employees)
														<option value="{{ $employees->id }}" >{{ $employees->name.' '.$employees->lastname }}</option>
													@endforeach
												@endif
											</select>
										</div>
									</div>
									
								</div>
								
								<div class="col-md-12 col-xs-12 col-sm-12 form-group" style="margin-top:20px;">
								<div class="col-md-10 col-sm-8 col-xs-8 header">
									<h4><b>{{ trans('app.Sale Part')}}</b></h4>
								</div>
								<div class="col-md-2 col-sm-4 col-xs-4">
									<button type="button" id="add_new_product" class="btn btn-default" url="{!! url('sales_part/add/getproductname')!!}" style="margin:5px 0px;">{{ trans('app.Add New')}} </button>
								</div>
							</div>
							<div class="col-md-12 col-xs-12 col-sm-12 form-group">
								<table class="table table-bordered adddatatable" id="tab_taxes_detail" align="center">
									<thead>
										<tr>
											
											<th class="actionre">{{ trans('app.Product Name')}}</th>
											<th class="actionre">{{ trans('app.Quantity')}}</th>
											<th class="actionre" style="width:10%;">{{ trans('app.Price')}} (<?php echo getCurrencySymbols(); ?>)</th>
											<th class="actionre" style="width:13%;">{{ trans('app.Amount')}} (<?php echo getCurrencySymbols(); ?>)</th>
											<th class="actionre">{{ trans('app.Action')}}</th>
										</tr>
									</thead>
									<tbody>
										<tr id="row_id_1">
											
											<td>
												<select name="product[product_id][]" class="form-control  productid select_productname_1"  url="{!! url('purchase/add/getproduct')!!}" row_did="1" data-id="1" style="width:100%;" required="required">
													<option value="">{{ trans('app.--Select Product--')}}</option>
													  @if(!empty($brand))
													  @foreach($brand as $brands)
												<option value="{{ $brands->id }}" >{{ $brands->name }}</option>
												@endforeach
													  @endif		
												</select>
											</td>
											<td>
												<input type="text" name="product[qty][]" url="{!! url('purchase/add/getqty')!!}" class="quantity form-control qty qty_1" id="qty_1" row_id="1" value="1" maxlength="8" style="width: 50%;">
												<span class="qty_1"></span>
											</td>
											<td>
												<input type="text" name="product[price][]" class="product form-control prices price_1" value="" id="price_1" style="width:100%;" readonly="true">
											</td>
											<td>
												<input type="text" name="product[total_price][]" class="product form-control total_price total_price_1"  value="" style="width:100%;" id="total_price_1" readonly="true">
											</td>
											<td align="center">
												<span class="product_delete" style="width:100%;" data-id="0"><i class="fa fa-trash"></i> </span>
											</td>
										</tr>
									</tbody>
								</table>
								<input type="hidden" name="_token" value="{{csrf_token()}}">
							</div>
								<div class="form-group">
									<div class="col-md-12 col-sm-12 col-xs-12 text-center">
									  <a class="btn btn-primary" href="{{ URL::previous() }}">{{ trans('app.Cancel')}}</a>
									  <button type="submit" class="btn btn-success">{{ trans('app.Add')}}</button>
									</div>
								</div>
							</form>
						</div>
						<!-- Color Add or Remove Model-->
						<div class="col-md-6">
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
												
												<button type="button" colorid="{{ $colors->id }}" deletecolor="{!! url('sales/colortypedelete') !!}" class="btn btn-danger btn-xs colordelete">X</button>
												</td>
												</tr>
												@endforeach
											</tbody>
										</table>
											
											<div class="col-md-8 form-group data_popup">
												<label>{{ trans('app.Color Name')}}: <span class="text-danger">*</span></label>
												<input type="text" class="form-control c_name" name="c_name"  placeholder="{{ trans('app.Enter color name')}}" />
											</div>
											
											<div class="col-md-4 form-group data_popup" style="margin-top:24px;">
												<button type="button" class="btn btn-success addcolor" colorurl="{!! url('sales/color_name_add') !!}">{{ trans('app.Submit')}}</button>
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
<script type="text/javascript">
	$(function(){
		$('#vehi_bra_name').change(function(){
			var vehicale_id = $(this).val();
			var url = $(this).attr('bran_url');
			var qty= $('#qty').val();
			$.ajax({
				type: 'GET',
				url: url,
				data : {vehicale_id:vehicale_id},
			
				success: function (response)
					 {	
						//var res_cust = jQuery.parseJSON(response);
						
						/* var price_dta = res_cust.price;
						
						$('#price').attr('value',res_cust.price);
						
						total_price =  price_dta * qty;
						 $('#total_price').val(total_price); */
						//$('#chassis_num').attr('value',res_cust.chassisno);
						var price_dta = $('#price').val(response.price);
						//$('#price').attr('value',res_cust.price);
						
						var total_price =  response.price * qty;
						
						if(response.qty == "not available")
						{
							$('#qty').attr('max',0);
						}
						else
						{
							$('#qty').attr('max',response.qty);
						}
						$('#total_price').val(total_price); 
						
					},

				beforeSend:function()
					{
						$('#price').attr('value','Loading...');
					},

				error: function(e) 
					{
					 alert("An error occurred: " + e.responseText);
						console.log(e);
					}
				});
			});
			
			$('#vehicale_select').change(function(){
				var url = $(this).attr('chasisurl');
				var	modelname = $('option:selected', this).attr('modelname');
				var	vehicle_id = $('option:selected', this).val();
					$.ajax({
						type: 'GET',
						url: url,
						data : {modelname:modelname,vehicle_id:vehicle_id},
					
						success: function (response)
							 {	
								//alert(response);
								$('#chassis_num').html(response);
							 },

					    beforeSend:function()
							{
								$('#price').attr('value','Loading...');
							},

					    error: function(e) 
							{
							 alert("An error occurred: " + e.responseText);
								console.log(e);
							}
						});
			});
	});
</script>

<script>

$(document).ready(function(){
	
	/* $('.veh_brand').change(function(){
		
		var url = $(this).attr('bran_url');
		var brand_name = $(this).val();
		
		$.ajax({
			type : 'GET',
			url : url,
			data : {brand_name:brand_name},
			
			success:function(response)
			{
				
				$('.modelnm').remove();
				$('.selectmodel').append(response);
			},
			error:function(e)
			{
				alert("Somthing went wrong... :" + e.responseText);
				console.log(e);
			},
			
		});
		
	}); */
	
});
</script>

<script>
 $('body').on('click','#qty',function(){
            var qty= $(this).val();
			var price= $('#price').val();
			var url = $(this).attr('url');
			$.ajax({
				type: 'GET',
				url: url,
				data : {qty:qty,price:price},
				success: function (response)
					{	
					
						total_price =  price * qty;
						$('#total_price').val(total_price);
						
					},

					beforeSend:function()
					{
						
					},

				error: function(e) 
					{
					 alert("An error occurred: " + e.responseText);
						console.log(e);
					}
				});
        });
</script>

<script>
	$(document).ready(function(){
		
		
		$("#no_of_service").change(function(){
			
			var interval=$("#interval").val();
			
			var date_gape=$("#date_gape").val();
			var no_service=$("#no_of_service").val();
			var url = $(this).attr('url');
			
			if(interval!='' || date_gape!='' || no_service!='')
			{
				if($("#interval").val() == ''){
				  swal({   
							title: "Interval",
							text: "Please select Interval!"   

						});
				  $('#no_of_service').html('<option value="0">No of service </option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>');
				  return false;
				}
			 // if($("#date_gape").val() == 0){
				  // swal({   
							// title: "Date Gape",
							// text: "Please select Date Gape!"   

						// });
				  // $('#no_of_service').html('<option value="0">No of service </option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>');
				  // return false;
				// }
				if(interval!='' && date_gape!='' && no_service!='') {
			 
					$("#date_gape").change(function(){
						$("#load_service_data").css("display", "none");
						$('#no_of_service').html('<option value="0">No of service </option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>');
					});
					
					$("#interval").change(function(){
						$("#load_service_data").css("display", "none");
						$('#no_of_service').html('<option value="0">No of service </option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option>');
					});

					$("#no_of_service").change(function(){
						$("#load_service_data").css("display", "block");
					});
			 
					$.ajax({
						   type: 'GET',
						  url: url,
						 data : {interval:interval,date_gape:date_gape,no_service:no_service},
						 success: function (response)
							{
								$("#load_service_data").html(response);
							},
						error: function(e) {
					   alert("An error occurred: " + e.responseText);
						console.log(e);
					},
					beforeSend:function(){
						$("#load_service_data").html("<center><h3>Loading...</h3></center>");
					}
					});
				}
			}
		});
	});
</script>

<!-- color add  model -->
<script>
$(document).ready(function(){
	$('.addcolor').click(function(){
		
		var c_name = $('.c_name').val();
		
		var url = $(this).attr('colorurl');

		if(c_name == ""){
            swal('Please Enter Color Name!');
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
									swal("Duplicate Data !!! Please try Another... ");
								}else
								{
									$('.colornametype').append('<tr class="'+classname+' data_color_name"><td class="text-center">'+c_name+'</td><td class="text-center"><button type="button" colorid='+data+' deletecolor="{!! url('sales/colortypedelete') !!}" class="btn btn-danger btn-xs colordelete">X</button></a></td><tr>');
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
	
	$('body').on('click','.colordelete',function(){
		
	var colorid = $(this).attr('colorid');
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

$("#add_new_product").click(function(){
		var row_id = $("#tab_taxes_detail > tbody > tr").length;
		var url = $(this).attr('url');
		$.ajax({
			   type: 'GET',
			  url: url,
			 data : {row_id:row_id},
			 success: function (response)
				{
				   // $("#tab_taxes_detail > tbody").append(response.html);
					$('.adddatatable').DataTable().row.add($(response.html)).draw();
					return false;
				},
			error: function(e) {
		 alert("An error occurred: " + e.responseText);
			console.log(e);
		}
       });
	});

	$('body').on('click','.product_delete',function(){
		
		var row_id = $(this).attr('data-id');
		
		$('table#tab_taxes_detail tr#row_id_'+row_id).fadeOut();
		$('table#tab_taxes_detail tr#row_id_'+row_id).html('<option value="">Select product</option>');
		$('table#tab_taxes_detail tr#row_id_'+row_id).html('<input type="text" name="" class="form-control qty" value="" id="tax_1" readonly="true">');
		$('table#tab_taxes_detail tr#row_id_'+row_id).html('<input type="text" name="" class="form-control price" value="" id="tax_1" readonly="true">');
		$('table#tab_taxes_detail tr#row_id_'+row_id).html('<input type="text" name="" class="form-control total_price" value="" id="tax_1" readonly="true">');
		$('table#tab_taxes_detail tr#row_id_'+row_id).html('<span class="product_delete" data-id="0"><i class="fa fa-trash"></i> Delete</span>');
		return false;
	});
	
	
	$('body').on('change','.productid','.qty',function(){
		
		var row_id = $(this).attr('row_did');
		var p_id = $(this).val();
		
		var qty= $('.qty_'+row_id).val();
		var price= $('.price_'+row_id).val();
		var url = $(this).attr('url');
		
		$.ajax({
			type: 'GET',
			url: url,
			data : {p_id:p_id},
			success: function (response)
				{	
					var json_obj = jQuery.parseJSON(response);
					var price = json_obj['price'];
					var total_price =  price * qty;
					$('.price_'+row_id).val(price);
					$('.total_price_'+row_id).val(total_price);
					var product_no = json_obj['product_no'];
					$('.qty_'+row_id).html(product_no);
				},
			error: function(e) {
				alert("An error occurred: " + e.responseText);
				console.log(e);
			}
		});
	});
</script>

<script type="text/javascript">

 $('body').on('keyup','.qty',function(){
	 
			var row_id = $(this).attr('row_id');
			
            var p_id = $('.select_productname_'+row_id).val();
			
			if(p_id == '')
			{
				 alert('first select product name');
				 $('.qty_'+row_id).val('1');
			}
			else
			{
				if (/\D/g.test(this.value))
				{
				    $('.qty_'+row_id).val('1');
				}
				else
				{
					var qty= $('.qty_'+row_id).val();
					
					var price= $('.price_'+row_id).val();
					
					var url = $(this).attr('url');
					$.ajax({
								type: 'GET',
								url: url,
								data : {qty:qty,price:price},
								success: function (response)
									 {	
										total_price =  price * qty;
										$('.total_price_'+row_id).val(total_price);
									},
									beforeSend:function()
									{
									},
								error: function(e) 
									{
									 alert("An error occurred: " + e.responseText);
										console.log(e);
									}
								});
				}
			}
        });
</script>
 
<script>
	$(function(){
			$('#supplier_select').change(function(){
				var supplier_id = $(this).val();
				var url = $(this).attr('url');
				
					$.ajax({
						type: 'GET',
						url: url,
						data : {supplier_id:supplier_id},
						success: function (response)
							 {	
								 var res_supplier = jQuery.parseJSON(response);
								
								$('#mobile').attr('value',res_supplier.mobile_no);
								$('#email').attr('value',res_supplier.email);
								$('#address').text(res_supplier.address);
							},

							beforeSend:function()
							{
								$('#mobile').attr('value','Loading..');
								$('#email').attr('value','Loading..');
								$('#address').attr('value','Loading..');
							},

					    error: function(e) 
							{
							 alert("An error occurred: " + e.responseText);
								console.log(e);
							}
						});
			});
	});
</script>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="{{ URL::asset('vendors/moment/min/moment.min.js') }}"></script>
    <script src="{{ URL::asset('vendors/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ URL::asset('vendors/bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js') }}"></script>
<!-- datetimepicker-->
	<script>
    $('.datepicker').datetimepicker({
       format: "<?php echo getDatepicker(); ?>",
		autoclose: 1,
		minView: 2,
    });
</script>

@endsection