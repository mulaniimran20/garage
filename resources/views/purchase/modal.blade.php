<html>
<head>
<style>

</style>

		<script language="javascript">
			function printdiv(el) {
			var restorepage = $('body').html();
			var printcontent = $('#' + el).clone();
			$('body').empty().html(printcontent);
			window.print();
			$('body').html(restorepage);

			}
		</script>
		
</head>
<body>	
		
<script>
		 $(document).ready(function() {
		$('.adddatatable').DataTable({
			responsive: true,
			paging: false,
			lengthChange: false,
			ordering: false,
			searching: false,
			info: false,
			autoWidth: true,
			sDom: 'lfrtip'
		
		});
	});
</script>
	
		<div id="div_print" style="width:100%;" >
			
			<table width="100%" border="0">
				<tbody>
					<tr>
						<td align="left">
						<?php $nowdate = date("Y-m-d");?>
							{{ trans('app.Date')}} :<?php echo  date(getDateFormat(),strtotime($nowdate)); ?> </td>
					</tr>
				</tbody>
			</table> <br/><br/>
			<table width="100%" border="0" class="adddatatable">
				<thead>
					<tr>
						<td></td>
						<td></td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td width="70%">
						
							<span style="float:left;">
								<h4>{{$logo->system_name}}</h4>
								<img src="{{ URL('public/vehicle/service.png')}}" style="width: 235px; height: 90px;">
								
								<img src="{{ URL('public/general_setting/'.$logo->logo_image)}}" width="230px" height="70px" class="purchaseimg" >
								
							</span>
						</td>
						<td width="30%">
							{{ trans('app.Purchase Number :')}} <?php echo $purchas->purchase_no; ?><br>							
							{{ trans('app.Date :')}}<?php echo  date(getDateFormat(),strtotime( $purchas->date)); ?> <br>				
						</td>
					</tr>
				</tbody>
			</table>
			<br/>
			</hr>
			<table width="100%" border="0" class="adddatatable">
				<thead>
					<tr>
						<td align="left" width="70%" style="float:left;">
							<h4>{{ trans('app.Other information')}}</h4>
						</td>
						<td align="left" style="" width="30%">
							<h4>{{ trans('app.Supplier Detail')}}</h4>
						</td>
					</tr>
					
				</thead>
				<tbody>
					<tr>
						<td valign="top" align="left" width="70%">
						
							{{ trans('app.Billing Address:')}} <?php echo $purchas->address; ?><br>	</td>				
						<td valign="top" align="left" width="30%">
							<span style="width:100%; float:left;">{{ trans('app.Name :')}} <?php echo getSupplierName($purchas->supplier_id); ?> </span>
							<span style="width:100%; float:left;">{{ trans('app.Email :')}}<?php echo $purchas->email; ?>	</span></td>
					</tr>
				</tbody>
			</table>
			</hr>
			<table width="100%" border="0">
				<tbody>
					<tr>
						<td align="left">
							<h4>{{ trans('app.Product Information')}}</h4>
						</td>					
					</tr>			
				</tbody>
			</table>
			<br/>
			<table class="table table-bordered" width="100%" border="1" style="border-collapse:collapse;">			
				<thead>
					<tr>
						<th class="text-center">{{ trans('app.Category')}}</th><th class="text-center">{{ trans('app.Product Number')}}</th>
						<th class="text-center">{{ trans('app.Manufacturer Name')}}</th>
						<th class="text-center">{{ trans('app.Product Name')}}</th>
						<th class="text-center">{{ trans('app.Qty')}}</th>
						<th class="text-center">{{ trans('app.Price')}} ( <?php echo getCurrencySymbols(); ?> )</ </th>
						<th class="text-center">{{ trans('app.Total Amount')}} ( <?php echo getCurrencySymbols(); ?> )</ </th>
					</tr>
				</thead>
				<tbody>
			   
					<?php 
					$total = 0;
					if(!empty($purchasdetails))
					{
					foreach($purchasdetails as $purchasdetail)
					{ ?>
						<tr>
							<td class="text-center"><?php echo getCategory($purchasdetail->category); ?></td>
							<td class="text-center"><?php echo getProductcode($purchasdetail->product_id); ?></td>
							
							<td class="text-center"><?php echo getProductName(getproducttyid($purchasdetail->product_id)); ?></td>
							<td class="text-center"><?php echo getProduct($purchasdetail->product_id); ?></td>
							<td class="text-center"><?php echo $purchasdetail->qty; ?></td>
							<td class="text-center"><?php echo $purchasdetail->price; ?></td>
							<td class="text-center"><?php echo $purchasdetail->total_amount; ?></td>
							<?php $total += $purchasdetail->total_amount; ?>
											
						</tr>
					<?php } } ?>
				</tbody>
			
			</table>
		
			<table class="table" style="border:1px solid #ddd" width="100%">
				<tbody>
					<tr>
						<td colspan="2" class="text-right" align="right">{{ trans('app.Grand Total')}} ( <?php echo getCurrencySymbols(); ?> ): &nbsp; &nbsp;<?php echo $total; ?> </td>
					</tr>
				</tbody>
			</table>
		</div>
		
		<div class="modal-footer">
			<!-- <input type="submit" class="btn btn-default"  onClick="printdiv('div_print');" value=" Print "> -->
			
			<button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('app.Close')}}</button>
	
		</div>
		
</body>
</html>
		