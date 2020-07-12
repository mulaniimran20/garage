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
							<a id="menu_toggle"><i class="fa fa-bars"></i><span class="titleup">&nbsp {{ trans('app.Product')}}</span></a>
						</div>
						@include('dashboard.profile')
					</nav>
				</div>
            </div>
			@if(session('message'))
				<div class="row massage">
					<div class="col-md-12 col-sm-12 col-xs-12">
						<div class="checkbox checkbox-success checkbox-circle">
							@if(session('message') == 'Successfully Submitted')
								<label for="checkbox-10 colo_success"> {{trans('app.Successfully Submitted')}}  </label>
							@elseif(session('message')=='Successfully Updated')
								<label for="checkbox-10 colo_success"> {{ trans('app.Successfully Updated')}}  </label>
							@elseif(session('message')=='Successfully Deleted')
								<label for="checkbox-10 colo_success"> {{ trans('app.Successfully Deleted')}}  </label>
							@endif
						</div>
					</div>
				</div>
			@endif
            <div class="row" >
				<div class="col-md-12 col-sm-12 col-xs-12" >
					<div class="x_content">
						<ul class="nav nav-tabs bar_tabs" role="tablist">
							<li role="presentation" class="active"><a href="{!! url('/product/list')!!}"><span class="visible-xs"></span> <i class="fa fa-list fa-lg">&nbsp;</i><b>{{ trans('app.Product List')}}</b></a></li>
							<li role="presentation" class=""><a href="{!! url('/product/add')!!}"><span class="visible-xs"></span> <i class="fa fa-plus-circle fa-lg">&nbsp;</i>{{ trans('app.Add Product')}} </a></li>
						</ul>
					</div>
					<div class="x_panel table_up_div">
						<table id="datatable" class="table table-striped jambo_table" style="margin-top:20px; width:100%;">
							<thead>
								<tr>
									<th>#</th>
									<th>{{ trans('app.Product Number')}}</th>
									<th>{{ trans('app.Manufacturer Name')}}</th>
									<th>{{ trans('app.Product Name')}}</th>
									<th>{{ trans('app.Price')}} (<?php echo getCurrencySymbols(); ?>)</th>
									<th>{{ trans('app.Supplier Name')}}</th>
									<th>{{ trans('app.Company Name')}}</th>
									<th>{{ trans('app.Action')}}</th>
								</tr>
							</thead>
							<tbody>
							<?php $i = 1; ?>  
							@foreach ($product as $products)
								<tr>
									<td>{{ $i }}</td>
									<td>{{ $products->product_no }}</td>
									<td>{{	getProductName($products->product_type_id) }}</td>
									<td>{{ $products->name }}</td>
									<td>{{ $products->price }}</td>
									<td>{{ getSupplierName($products->supplier_id) }}</td>
									<td>{{ getCompanyName($products->supplier_id) }}</td>
									<td>
										<a href="{!! url('/product/list/edit/'.$products->id) !!}" ><button type="button" class="btn btn-round btn-success">{{ trans('app.Edit')}}</button></a>
										<a url="{!! url('/product/list/delete/'.$products->id) !!}" class="sa-warning"><button type="button" class="btn btn-round btn-danger">{{ trans('app.Delete')}}</button></a>
									</td>
								</tr>
								<?php $i++; ?>
							@endforeach	
							</tbody>
						</table>
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
 <!-- /page content --> 
<script src="{{ URL::asset('vendors/jquery/dist/jquery.min.js') }}"></script>
<script>
$(document).ready(function() {
    $('#datatable').DataTable( {
		responsive: true,
        "language": {
			
			 "url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/<?php echo getLanguageChange(); 
			?>.json"
        }
    } );
} );
</script>
       
<script>
$('body').on('click', '.sa-warning', function() {
	  var url =$(this).attr('url');
	    
        swal({   
            title: "Are You Sure?",
			text: "You will not be able to recover this data afterwards!",   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#297FCA",   
            confirmButtonText: "Yes, delete!",   
            closeOnConfirm: false 
        }, function(){
			window.location.href = url;
             
        });
    }); 
 
</script>

@endsection