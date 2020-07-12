<?php

namespace App\Http\Controllers;
use App\User;
use Auth;
use App\tbl_services;
use App\tbl_sales_taxes;
use App\tbl_sales;
use App\tbl_colors;
use App\tbl_rto_taxes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use DB;
use Mail;
use Illuminate\Mail\Mailer;
use PDF;
use App\tbl_mail_notifications;
class Salescontroller extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }
	
	//sales list
    public function index()
	{	
		$userid=Auth::User()->id;
		if(!empty(getActiveCustomer($userid)=='yes'))
		{
			$sales = DB::table('tbl_sales')->orderBy('id','DESC')->get()->toArray();
		}
		elseif(!empty(getActiveEmployee($userid)=='yes'))
		{
			$sales = DB::table('tbl_sales')->where('salesmanname','=',Auth::User()->id)->orderBy('id','DESC')->get()->toArray();
		}
		else
		{
			$sales = DB::table('tbl_sales')->where('customer_id','=',Auth::User()->id)->orderBy('id','DESC')->get()->toArray();
		}
		return view('sales.list',compact('sales','onlysales')); 
	}
	
	//sales add form
	public function addsales()
	{	
		$characters = '0123456789';
		$code =  'S'.''.substr(str_shuffle($characters),0,6);
		$color = DB::table('tbl_colors')->get()->toArray();
		$employee = DB::table('users')->where('role','=','Employee')->get()->toArray();
		$customer = DB::table('users')->where('role','=','Customer')->get()->toArray();
		$taxes = DB::table('tbl_account_tax_rates')->get()->toArray();
		$payment = DB::table('tbl_payments')->get()->toArray();
		$brand = DB::table('tbl_vehicle_brands')->get()->toArray();
		return view('sales.add',compact('customer','vehicale','employee','code','color','taxes','payment','brand')); 
	}
	
	//color add
	public function coloradd()
	{   
		    $color_name = Input::get('c_name');
			$colors = DB::table('tbl_colors')->where('color','=',$color_name)->count();
			if($colors == 0)
			{
			 $color = new tbl_colors;
			 $color->color=$color_name;
			 $color->save();
			 echo $color->id;
			}
			else{
				 return '01';
			}	
	}
	
	//color delete
	public function colordelete()
	{
		$id = Input::get('colorid');
		$color =DB::table('tbl_colors')->where('id','=',$id)->delete();
	}
	
	//get chassis
	public function getchasis()
	{	
		$modelname = Input::get('modelname');
		$vehicle_id = Input::get('vehicle_id');	
		$sales = DB::table('tbl_sales')->where('vehicle_id','!=',$vehicle_id)->get()->toArray();
		$count = DB::table('tbl_sales')->where('vehicle_id','!=',$vehicle_id)->count();	
		if($count > 0 )
		{		
			foreach ($sales as $sale)
			{
				$ve_id[] = $sale->vehicle_id;
				$csno[] = $sale->chassisno;				
			}
			$data = DB::table('tbl_vehicles')->whereNotIn('id',$ve_id)->where('modelname',$modelname)->get()->toArray();
		}
		else
		{
			$data = DB::table('tbl_vehicles')->where('modelname','=',$modelname)->get()->toArray();
		}
		?>
			<?php foreach ($data as $datas) { ?>
				<option value="<?php echo $datas->chassisno;?>" ><?php echo $datas->chassisno;?></option>
		<?php	} ?>		
		<?php
	}
	
	//get vehicle data
	public function getrecord()
	{	
		$vid = Input::get('vehicale_id');
		
		$v_record = DB::table('tbl_vehicles')->where('id','=',$vid)->first();
		
		$record = json_encode($v_record);
		
		echo $record;
	}
	
	//get model name
	public function getmodel_name()
	{	
		$brand_name = Input::get('brand_name');
		$data = DB::table('tbl_sales')->where('vehicle_brand','=',$brand_name)->get()->toArray();
		$count = DB::table('tbl_sales')->where('vehicle_brand','=',$brand_name)->count();
		
		if($count>0)
		{	
			foreach ($data as $datas)
			{
				$vehical_id[] = $datas->vehicle_id;	
			}
			$vehicale = DB::table('tbl_vehicles')->whereNotIn('id',$vehical_id)->where('vehiclebrand_id','=',$brand_name)->get()->toArray();
		}
		else
		{
			$vehicale = DB::table('tbl_vehicles')->where('vehiclebrand_id','=',$brand_name)->get()->toArray();
		}		
		?>
			<?php foreach ($vehicale as $vehicales) { ?>
				<option  class="modelnm" value="<?php echo $vehicales->id;?>" modelname="<?php echo $vehicales->modelname; ?>" brand="<?php echo $vehicales->vehiclebrand_id;?>" vhi_type="<?php $vehicales->vehicletype_id;?>" ><?php echo $vehicales->modelname;?></option>
		<?php	} ?>		
		<?php	
	}
	
	//get tax per
	public function gettaxespercentage()
	{	
		$t_name = Input::get('t_name');
		if(!empty($t_name)){
		$t_record = DB::table('tbl_account_tax_rates')->where('taxname','=',$t_name)->first();
		$tax = $t_record->tax;
		echo $tax;
		}
		else{
			echo 0;
		}
	}
	
	// free services
	public function getservices()
	{	
		$interval = Input::get('interval');
		$date_gape = Input::get('date_gape');
		$no_service = Input::get('no_service');		
		$characters = '0123456789';
		$code =  'C'.''.substr(str_shuffle($characters),0,6);	
		$new_interval=$interval;
			
			$new_interval_array=array();
			$no_service_arry=array();
			$get_service_data=date('Y-m-d');
		
				$addmonth=(int)$interval;
				$addday = (int)$date_gape;
				for($j=1;$j<=$no_service;$j++){
					
					$no_service_date = date('Y-m-d', strtotime("+".$addmonth." months", strtotime($get_service_data)));
					$no_service_date_gap = date('Y-m-d', strtotime("+".$addday." days", strtotime($no_service_date)));
					
					$get_service_data=$no_service_date;
					$codes = $code.$j;
					$no_service_arry[$get_service_data]=("$j Service");
					
					?>
					<table class="table" align="center" style="width:80%;">
					<tr class="data_of_type">
						<td class="text-center"><?php echo $j; ?></td>
						<td class="text-center"><input type="text" class="form-control first_width" value="<?php echo $no_service_date.'  To  '.$no_service_date_gap; ?>" name="service[service_date][]"></td>
						<td class="text-center"><input type="text" class="form-control second_width" name="service[service_text][]" value="<?php echo $no_service_arry[$get_service_data];?>" ></td>
						<td class="text-center"><input type="text" class="form-control second_width" name="service[service_job][]" value="<?php echo $codes;?>" readonly></td>
					</tr>
					</table>
					<?php
				}			
	}
	
	//get taxes
	public function gettaxes()
	{
		$id = Input::get('row_id');
		$ids = $id+1;
		$rowid = 'row_id_'.$ids;
	
		$taxes = DB::table('tbl_account_tax_rates')->get()->toArray();		
		?>		
		<tr id="<?php echo $rowid;?>">
		<input type="hidden" value="<?php echo $ids;?>" name="account[tr_id][]"/>
		<td><select name="account[tax_name][]" url="<?php  echo url('sales/add/gettaxespercentage'); ?>" class="form-control tax_name" row_did="<?php echo $ids;?>" data-id="<?php echo $ids;?>" required="">
		<option value="0">Select Tax</option><?php  foreach($taxes as $tax) { ?><option value="<?php echo $tax->taxname;?>"><?php echo $tax->taxname;?></option> <?php } ?> </select>
		</td>
		<td>
			<input type="text" name="account[tax][]" class="form-control tax" value="" id="tax_<?php echo $ids;?>" readonly="true">
		</td>
		<td>
			<span class="trash_account" data-id="<?php echo $ids; ?>"><i class="fa fa-trash"></i> Delete</span>
		</td>
		</tr>
		<?php
	}
	
	//get qty
	public function getqty()
	{	
		$qty = Input::get('qty');
		$price = Input::get('price');
		echo $qty;
		echo $price;
	}
	
	//sales store
	public function store(Request $request)
	{	
		$this->validate($request, [  
         'qty' => 'numeric',
         // 'price' => 'numeric',
	     ]);
			
		$c_id=Input::get('cus_name');
		if(getDateFormat()== 'm-d-Y')
		{
			$s_date=date('Y-m-d',strtotime(str_replace('-','/',Input::get('date'))));
		}
		else
		{
			$s_date=date('Y-m-d',strtotime(Input::get('date')));
		}
		$totalamount=Input::get('total_price');
		$bill_no=Input::get('bill_no');
		$sales = new tbl_sales;
		$sales->customer_id =$c_id;
		// $sales->status = Input::get('status');
		$sales->bill_no = $bill_no;
		// $sales->payment_type_id = 0;
		$sales->date =$s_date; 
		$sales->vehicle_brand = Input::get('vehi_bra_name');
		$sales->chassisno = Input::get('chassis');
		$sales->vehicle_id = Input::get('vehicale_name');
		$sales->color_id = Input::get('color');
		$sales->quantity = Input::get('qty');
		$sales->price = Input::get('price');
		$sales->total_price = $totalamount;
		$sales->no_of_services = Input::get('no_of_services');
		$sales->interval = Input::get('interval');
		$sales->date_gap = Input::get('date_gape');
		$sales->assigne_to = Input::get('assigne_to');
		$sales->salesmanname = Input::get('salesmanname');
		$sales->save();
				
		$sales_record = DB::table('tbl_sales')->orderBy('id','desc')->first();
		$id = $sales_record->id;
		
		//Services Code Code  
		$service = Input::get('service');
		if(!empty($service)){
			foreach($service['service_date'] as $key => $value)
			{	
				$date = $service['service_date'][$key];
				$new_date = strtok($date, " ");
				$title = $service['service_text'][$key];
				$job = $service['service_job'][$key];
				
				$services = new tbl_services;
				$services->job_no = $job;
				$services->service_type = 'free';
				$services->sales_id = $id;
				$services->service_date = $new_date;
				$services->full_date = $date;
				$services->title = $title;
				$services->done_status =2;
				$services->assign_to = Input::get('assigne_to');
				$services->customer_id = Input::get('cus_name');
				$services->vehicle_id = Input::get('vehicale_name');
				$services->save();
				
			}
		}
			
	
		return redirect('sales/list')->with('message','Successfully Submitted');
	}
	
	//modal view for sales
	public function view()
	{
		if(!empty(Input::get('saleid')))
		{
			$id = Input::get('saleid');
			$invoice_number = Input::get('invoice_number');			
		}
		else
		{
			$id = Input::get('serviceid');
			$auto_id = Input::get('auto_id');
		}
				
		$viewid = $id;
		$sales = DB::table('tbl_sales')->where('id','=',$id)->first();
		$v_id = $sales->vehicle_id;
		$vehicale =  DB::table('tbl_vehicles')->where('id','=',$v_id)->first();
		if(Input::get('saleid'))
		{
			$invioce = DB::table('tbl_invoices')->where([['sales_service_id',$id],['invoice_number',$invoice_number]])->first();
		}
		else
		{
			$invioce = DB::table('tbl_invoices')->where('id',$auto_id)->first();
		}
		if(!empty($invioce->tax_name))
		{
			$taxes = explode(', ',$invioce->tax_name);	
		}
		else
		{
			$taxes='';	
		}		
		$rto = DB::table('tbl_rto_taxes')->where('vehicle_id','=',$v_id)->first();
		$logo = DB::table('tbl_settings')->first();		
		$updatekey = DB::table('updatekey')->first();
		$s_key = $updatekey->secret_key;
		$p_key = $updatekey->publish_key;
		
		$html = view('invoice.salesinvoicemodel')->with(compact('viewid','vehicale','sales','logo','invioce','taxes','rto','p_key'))->render();
		return response()->json(['success' => true, 'html' => $html]);
	}
	
	public function destroy($id)
	{	
		$sales = DB::table('tbl_sales')->where('id','=',$id)->delete();
		$services = DB::table('tbl_services')->where('sales_id','=',$id)->delete();
		return redirect('sales/list')->with('message','Successfully Deleted');		
	}
	
	//sales edit form
	public function edit($id)
	{	
		$editid = $id;	
		$color = DB::table('tbl_colors')->get()->toArray();
		$employee = DB::table('users')->where('role','=','Employee')->get()->toArray();
		$customer = DB::table('users')->where('role','=','Customer')->get()->toArray();
		$vehicale = DB::table('tbl_vehicles')->get()->toArray();
		$sales = DB::table('tbl_sales')->where('id','=',$id)->first();
		$brand_id = $sales->vehicle_brand;
		$payment = DB::table('tbl_payments')->get()->toArray();
		$sales_services = DB::table('tbl_services')->where('sales_id','=',$id)->get()->toArray();
		$brand = DB::table('tbl_vehicle_brands')->get()->toArray();
		
		return view('sales.edit',compact('sales','taxes','editid','vehicale','customer','payment','color','employee','sales_services','tbl_sales_taxes','brand'));
	}
	
	//sales update
	public function update(Request $request,$id)
	{ 
		$this->validate($request, [  
         'qty' => 'numeric',
         // 'price' => 'numeric',
	     ]);
		$service_coupan = DB::table('tbl_services')->where('sales_id','=',$id)->get()->toArray();
		if(!empty($service_coupan))
		{
			foreach($service_coupan as $coupan)
			{
				tbl_services::Destroy($coupan->id);
			}
		}
		if(getDateFormat()== 'm-d-Y')
		{
			$s_date=date('Y-m-d',strtotime(str_replace('-','/',Input::get('date'))));
		}
		else
		{
			$s_date=date('Y-m-d',strtotime(Input::get('date')));
		}
		$sales = tbl_sales::find($id);
		$sales->customer_id = Input::get('cus_name');
		// $sales->status = Input::get('status');
		$sales->bill_no = Input::get('bill_no');
		// $sales->payment_type_id = Input::get('payment');
		$sales->date =$s_date;
		$sales->vehicle_brand = Input::get('vehi_bra_name');
		$sales->chassisno = Input::get('chassis');
		$sales->vehicle_id = Input::get('vehicale_name');
		$sales->color_id = Input::get('color');
		$sales->quantity = Input::get('qty');
		$sales->price = Input::get('price');
		$sales->total_price = Input::get('total_price');
		$sales->no_of_services = Input::get('no_of_services');
		$sales->interval = Input::get('interval');
		$sales->date_gap = Input::get('date_gape');
		$sales->assigne_to = Input::get('assigne_to');
		$sales->salesmanname = Input::get('salesmanname');
		$sales->save();
		
		$service = Input::get('service');
		if(!empty($service)){
			foreach($service['service_date'] as $key => $value)
			{	
				$date = $service['service_date'][$key];
				$new_date = strtok($date, " ");
				$title = $service['service_text'][$key];
				$job = $service['service_job'][$key];
				
				$services = new tbl_services;
				$services->job_no = $job;
				$services->service_type = 'free';
				$services->sales_id = $id;
				$services->service_date = $new_date;
				$services->full_date = $date;
				$services->title = $title;
				$services->done_status = 2;
				$services->assign_to = Input::get('assigne_to');
				$services->customer_id = Input::get('cus_name');
				$services->vehicle_id = Input::get('vehicale_name');
				$services->save();	
			}
		}				
		return redirect('sales/list')->with('message','Successfully Updated');
	}
}	