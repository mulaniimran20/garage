<?php

namespace App\Http\Controllers;
use App\User;
use Auth;
use App\tbl_services;
use App\tbl_sales_taxes;
use App\tbl_sales;
use App\tbl_sale_parts;
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
class SalesPartcontroller extends Controller
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
			$sales = DB::table('tbl_sale_parts')->where('product_id','!=','<>')->groupby('bill_no')->orderBy('id','DESC')->get()->toArray();
		}
		elseif(!empty(getActiveEmployee($userid)=='yes'))
		{
			$sales = DB::table('tbl_sale_parts')->where('salesmanname','=',Auth::User()->id)->groupby('bill_no')->where('product_id','!=','<>')->orderBy('id','DESC')->get()->toArray();
		}
		else
		{
			$sales = DB::table('tbl_sale_parts')->where('product_id','!=','<>')->groupby('bill_no')->where('customer_id','=',Auth::User()->id)->orderBy('id','DESC')->get()->toArray();
		}
		return view('sales_part.list',compact('sales','onlysales')); 
	}

	//sales add form
	public function addsales()
	{	
		$characters = '0123456789';
		$code =  'SP'.''.substr(str_shuffle($characters),0,6);
		$color = DB::table('tbl_colors')->get()->toArray();
		$employee = DB::table('users')->where('role','=','Employee')->get()->toArray();
		$customer = DB::table('users')->where('role','=','Customer')->get()->toArray();
		$taxes = DB::table('tbl_account_tax_rates')->get()->toArray();
		$payment = DB::table('tbl_payments')->get()->toArray();
		$brand = DB::table('tbl_products')->where('category',1)->get()->toArray();
		return view('sales_part.add',compact('customer','vehicale','employee','code','color','taxes','payment','brand')); 
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
		else
		{
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
		$brand_name = Input::get('vehicale_id');	
		$data = DB::table('tbl_products')->where('id','=',$brand_name)->first();
		$purchase = DB::table('tbl_purchase_history_records')->where('product_id',$brand_name)->where('category',1)->get();
		$s = [];
		$sp = [];
		foreach($purchase as $purchases)
		{
			$s[] = $purchases->qty;
		}
		$sums = array_sum($s);
		$purchase_p = DB::table('tbl_sale_parts')->where('product_id',$brand_name)->get();
		foreach($purchase_p as $purchasesd)
		{
			$sp[] = $purchasesd->quantity;
		}
		$sumsd = array_sum($sp);
		if($sums >= $sumsd || $sumsd == 0)
		{
			if($sumsd == 0)
			{
				$diff = $sums;
			}
			else
			{
				$diff = $sums - $sumsd;
			}
		}
		else
		{
			$diff ="not available";
		}
		return array('price'=>$data->price,'qty'=>$diff);
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

		if(getDateFormat()== 'm-d-Y')
		{
			$s_date=date('Y-m-d',strtotime(str_replace('-','/',Input::get('date'))));
		}
		else
		{
			$s_date=date('Y-m-d',strtotime(Input::get('date')));
		}
		/* $sales = new tbl_sale_parts;
		$sales->customer_id = Input::get('cus_name');
		$sales->bill_no = Input::get('bill_no');
		$sales->date =$s_date;
		$sales->quantity = Input::get('qty');
		$sales->price = Input::get('price');
		$sales->total_price = Input::get('total_price');
		$sales->salesmanname = Input::get('salesmanname');
		$sales->product_id = Input::get('product_id');
		$sales->save(); */
		
		$products = Input::get('product');
		if(!empty($products)){
			foreach($products['product_id'] as $key => $value)
			{		
			    // $Manufacturer_id = $products['Manufacturer_id'][$key];
			    $Product_id = $products['product_id'][$key];
				$qty = $products['qty'][$key];
				$price = $products['price'][$key];
				$total_price = $products['total_price'][$key];
				$sales = new tbl_sale_parts;
				$sales->customer_id = Input::get('cus_name');
				$sales->bill_no = Input::get('bill_no');
				$sales->date =$s_date;
				$sales->quantity = $qty;
				$sales->price = $price;
				$sales->total_price = $total_price;
				$sales->salesmanname = Input::get('salesmanname');
				$sales->product_id = $Product_id;
				$sales->save();
			}
		}
		return redirect('sales_part/list')->with('message','Successfully Submitted');
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
		$sales = DB::table('tbl_sale_parts')->where('id','=',$id)->first();
		$saless = DB::table('tbl_sale_parts')->where('bill_no','=',$sales->bill_no)->get();
		
		$salesp = DB::table('tbl_sale_parts')->select(DB::raw("SUM(total_price) AS total_price,bill_no,quantity,date,product_id,price ,customer_id,id,salesmanname"))->where('bill_no','=',$sales->bill_no)->get();
		
		$salesps = DB::table('tbl_sale_parts')->select(DB::raw("SUM(total_price) AS total_price,bill_no,quantity,date,product_id,price ,customer_id,id,salesmanname"))->where('bill_no','=',$sales->bill_no)->first();
		
		$v_id = $sales->product_id;
		$vehicale =  DB::table('tbl_products')->where('id','=',$v_id)->first();
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
		$html = view('invoice.sales_partinvoicemodel')->with(compact('viewid','vehicale','sales','logo','invioce','taxes','rto','p_key','saless','salesp','salesps'))->render();
		return response()->json(['success' => true, 'html' => $html]);
	}

	public function destroy($id)
	{	
		$salesp = DB::table('tbl_sale_parts')->find($id);
		$sales = DB::table('tbl_sale_parts')->where('bill_no','=',$salesp->bill_no)->delete();
		return redirect('sales_part/list')->with('message','Successfully Deleted');		
	}
	
	public function sale_part_destroy()
	{	
		$id=Input::get('procuctid');
		$sales = DB::table('tbl_sale_parts')->where('id','=',$id)->delete();
		//return redirect('sales_part/list')->with('message','Successfully Deleted');		
	}

	//sales edit form
	public function edit($id)
	{
		$editid = $id;	
		$color = DB::table('tbl_colors')->get()->toArray();
		$employee = DB::table('users')->where('role','=','Employee')->get()->toArray();
		$customer = DB::table('users')->where('role','=','Customer')->get()->toArray();
		$vehicale = DB::table('tbl_vehicles')->get()->toArray();
		$sales = DB::table('tbl_sale_parts')->where('id','=',$id)->first();

		$payment = DB::table('tbl_payments')->get()->toArray();
		$sales_services = DB::table('tbl_services')->where('sales_id','=',$id)->get()->toArray();
		//$brand = DB::table('tbl_vehicle_brands')->get()->toArray();
		$brand = DB::table('tbl_products')->where('category',1)->get()->toArray();
		$stock=DB::table('tbl_sale_parts')->where('bill_no','=',$sales->bill_no)->get();
		return view('sales_part.edit',compact('sales','taxes','editid','vehicale','customer','payment','color','employee','sales_services','tbl_sales_taxes','brand','stock'));
	}

	//sales update
	public function update(Request $request,$id)
	{ 
		$this->validate($request, [  
			'qty' => 'numeric',
			//'price' => 'numeric',
	    ]);
		/* $service_coupan = DB::table('tbl_services')->where('sales_id','=',$id)->get()->toArray();
		if(!empty($service_coupan))
		{
			foreach($service_coupan as $coupan)
			{
				tbl_services::Destroy($coupan->id);
			}
		}*/
		if(getDateFormat()== 'm-d-Y')
		{
			$s_date=date('Y-m-d',strtotime(str_replace('-','/',Input::get('date'))));
		}
		else
		{
			$s_date=date('Y-m-d',strtotime(Input::get('date')));
		}
		$products = Input::get('product');
		if(!empty($products)){
			foreach($products['product_id'] as $key => $value)
			{		
			    // $Manufacturer_id = $products['Manufacturer_id'][$key];
			    $Product_id = $products['product_id'][$key];
				$qty = $products['qty'][$key];
				$price = $products['price'][$key];
				$total_price = $products['total_price'][$key];
				$purchase_hiatory_id = $products['tr_id'][$key];
				$p_id = DB::table('tbl_sale_parts')->find($id);
				if($purchase_hiatory_id != '')
				{
					if(!empty($p_id))
					{
						$sales = tbl_sale_parts::find($id);
						$sales->customer_id = Input::get('cus_name');
						$sales->bill_no = Input::get('bill_no');
						$sales->date =$s_date;
						$sales->quantity = $qty;
						$sales->price = $price;
						$sales->total_price = $total_price;
						$sales->salesmanname = Input::get('salesmanname');
						$sales->product_id = $Product_id;
						$sales->save();
					}
				}
				else
				{
					$sales = new tbl_sale_parts;
					$sales->customer_id = Input::get('cus_name');
					$sales->bill_no = Input::get('bill_no');
					$sales->date =$s_date;
					$sales->quantity = $qty;
					$sales->price = $price;
					$sales->total_price = $total_price;
					$sales->salesmanname = Input::get('salesmanname');
					$sales->product_id = $Product_id;
					$sales->save();
				}
			}
		}
		return redirect('sales_part/list')->with('message','Successfully Updated');
	}

	public function getproductname()
	{
		$id = Input::get('row_id');	
		$ids = $id+1;	    
		$rowid = 'row_id_'.$ids;      
		$product = DB::table('tbl_products')->where('category',1)->get()->toArray();	
		$Select_product=DB::table('tbl_product_types')->get()->toArray();
		$html = view('sales_part.newproduct')->with(compact('id','ids','rowid','product','Select_product'))->render();
		return response()->json(['success' => true, 'html' => $html]);
	}
}