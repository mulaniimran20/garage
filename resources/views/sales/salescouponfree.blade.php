<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
 <!-- Bootstrap -->
    <link href= "{{ URL::asset('vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
<body>
<h2></h2>

<div>
   <p> {!! $email_content1 !!}.</p>
   
	<!-- <table width="100%">
			
				<tr>
					<th align="left">Coupon_Number </th>
					 
				</tr>
			    @if(!empty($tbl_services))
				   @foreach($tbl_services as $tbl_servicess)
				<tr>
				  
					<td align="left"><?php echo $tbl_servicess->job_no; ?></td>
				</tr>
				@endforeach
				@endif
		</table>
		-->
</div>
</body>
</html>