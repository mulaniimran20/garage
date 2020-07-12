<?php

namespace App\Http\Controllers;

use Auth;
use App\tbl_purchases;
use App\users;
use App\tbl_stock_records;
use App\tbl_purchase_history_records;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use DB;

class Purchasecontroller extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
	
	//purchase list
	public function listview()
	{	    
	   
		$purchase=DB::table('tbl_purchases')->orderBy('id','DESC')->get()->toArray();
				
		return view('purchase.list',compact('purchase')); 
	}
	//purchase list
	public function listview1($id)
	{	    
	   
		$purchase=DB::table('tbl_purchases')->where('id','=',$id)->get()->toArray();
				
		return view('purchase.list',compact('purchase')); 
	}
	//purchase addform
    public function index()
	{
		$characters = '0123456789';
		$code =  'P'.''.substr(str_shuffle($characters),0,6);
		$supplier=DB::table('users')->where('role','=','supplier')->get()->toArray();
		$product=DB::table('tbl_products')->get()->toArray();
		$Select_product=DB::table('tbl_product_types')->get()->toArray();
		
		return view('purchase.add',compact('supplier','product','code','Select_product'));
	}
	
	//get supplier record
	public function getrecord()
	{
		$s_id=Input::get('supplier_id');
		
		$supplier_record=DB::table('users')->where([['id','=',$s_id],['role','=','supplier']])->first();
		$record = json_encode($supplier_record);
		
		echo $record;
	}
	//productitem
	public function productitem()
	{
		$id = Input::get('m_id');
		
		$tbl_products = DB::table('tbl_products')->where('product_type_id','=',$id)->get()->toArray();
		
		
		if(!empty($tbl_products))
		{   ?>
			<option value="">--Select Product--</option>
			<?php
			foreach($tbl_products as $tbl_productss)
			{ ?>
				<option value="<?php echo  $tbl_productss->id; ?>"><?php echo $tbl_productss->name; ?></option>
			<?php 
			} 
		}
		else
		{
			?>
			<option value="">--Select Product--</option>
			<?php
		}
	}
	
	//product data
	public function getproduct()
	{
		$p_id=Input::get('p_id');
		if(!empty($p_id))
		{
		$t_record = DB::table('tbl_products')->where('id','=',$p_id)->first();
			echo json_encode($t_record);
		}
		else
		{
			echo 0;
		}
	}
	
	//delete product
	public function deleteproduct()
	{
		$productid=Input::get('procuctid');
		
		$product1=DB::table('tbl_purchase_history_records')->where('id','=',$productid)->first();	
		$pid=$product1->product_id;
		$qty=$product1->qty;
		$stock=DB::table('tbl_stock_records')->where('product_id','=',$pid)->first();
		$sid=$stock->no_of_stoke;
		$total=$sid - $qty;
		DB::update("update tbl_stock_records set no_of_stoke='$total' where product_id='$pid'");
		$product=DB::table('tbl_purchase_history_records')->where('id','=',$productid)->delete();	
	}
	
	//product total
	public function getqty()
	{	
		$qty = Input::get('qty');
		$price = Input::get('price');
		$total_price = $qty * $price;  
		echo $total_price;
	}
	
	//product store
	public function store()
	{
		if(getDateFormat()== 'm-d-Y')
		{
			$dates=date('Y-m-d',strtotime(str_replace('-','/',Input::get('p_date'))));
		}
		else
		{
			$dates=date('Y-m-d',strtotime(Input::get('p_date')));
		}
		$purchase= new tbl_purchases;
		$purchase->purchase_no=Input::get('p_no');
		$purchase->date=$dates;
		
		$purchase->supplier_id=Input::get('s_name');
		$purchase->mobile=Input::get('mobile');
		$purchase->email=Input::get('email');
		$purchase->address=Input::get('address');
		$purchase->save();
		
		$lat_record = DB::table('tbl_purchases')->orderBy('id','=','desc')->first();
		$purchase_id =$lat_record->id;
		
	    $products = Input::get('product');
		if(!empty($products)){
			foreach($products['product_id'] as $key => $value)
			{		
			    // $Manufacturer_id = $products['Manufacturer_id'][$key];
			    $Product_id = $products['product_id'][$key];
				$qty = $products['qty'][$key];
				$price = $products['price'][$key];
				$total_price = $products['total_price'][$key];
				$category = $products['category_id'][$key];
				
				$purchas=new tbl_purchase_history_records;
				// $purchas->purchase_id=$Manufacturer_id;
				$purchas->purchase_id=$purchase_id;
				$purchas->product_id=$Product_id;
				$purchas->qty=$qty;
				$purchas->price=$price;
				$purchas->category=$category;
				$purchas->total_amount=$total_price;
				$purchas->save();
				
				$stock=DB::table('tbl_stock_records')->where('product_id','=',$Product_id)->first();		
				if( !empty($stock))
				{			   
				   $old_stock=$stock->no_of_stoke;
				  
				  $qty = $products['qty'][$key] + $old_stock;
				  
				  DB::update("update tbl_stock_records set no_of_stoke='$qty' where product_id='$Product_id'");
				}
				else
				{	 
				$product= new tbl_stock_records();				
				$product->product_id=$Product_id;				
				$product->supplier_id=Input::get('s_name');
				$product->no_of_stoke=$qty;				
				$product->save();
				}
			}
		}			
		return redirect('purchase/list')->with('message','Successfully Submitted');
	}
    
	//product edit
	public function editview($id)
	{   
		$purchase=DB::table('tbl_purchases')->where('id','=',$id)->first();	   
	    $supplier=DB::table('users')->where('role','=','supplier')->get()->toArray();		
		$product=DB::table('tbl_products')->get()->toArray();
		$stock=DB::table('tbl_purchase_history_records')->where('purchase_id','=',$id)->get()->toArray();
		$Select_product=DB::table('tbl_product_types')->get()->toArray();
	
		return view('purchase.edit',compact('supplier','product','purchase','stock','Select_product'));
	}
	
	//product delete
	public function destory($id)
	{ 
		$stock=DB::table('tbl_purchase_history_records')->where('purchase_id','=',$id)->get()->toArray();
		foreach($stock as $stock)
		{
			$product_id=$stock->product_id;
			
	
			$getqty=DB::table('tbl_purchase_history_records')->where([['product_id','=',$product_id],['purchase_id','=',$id]])->first();
			$total=$getqty->qty;
			
			$stock1=DB::table('tbl_stock_records')->where('product_id','=',$product_id)->first();
			
				if( !empty($stock1))
				{			   
				   $old_stock=$stock1->no_of_stoke;
				 
				  $qty = $old_stock - $total;
				  
				  DB::update("update tbl_stock_records set no_of_stoke='$qty' where product_id='$product_id'");
				}
			
		}
			
		$purchase=DB::table('tbl_purchases')->where('id','=',$id)->delete();
		$purchase=DB::table('tbl_purchase_history_records')->where('purchase_id','=',$id)->delete();
		
		
		return redirect('purchase/list')->with('message','Successfully Deleted');
	}
	
	//product update
	public function update($id)
	{   
	    if(getDateFormat()== 'm-d-Y')
		{
			$dates=date('Y-m-d',strtotime(str_replace('-','/',Input::get('p_date'))));
		}
		else
		{
			$dates=date('Y-m-d',strtotime(Input::get('p_date')));
		}
		$purchase=tbl_purchases::find($id);
		$purchase->purchase_no=Input::get('p_no');
		$purchase->date=$dates;
		$purchase->supplier_id=Input::get('s_name');
		$purchase->mobile=Input::get('mobile');
		$purchase->email=Input::get('email');
		$purchase->address=Input::get('address');
		$purchase->save();		
	    $products = Input::get('product');
	   
	   $stock_no=DB::table('tbl_purchase_history_records')->where('purchase_id','=',$id)->get()->toArray();
				
				if(!empty($stock_no))
				{
					
				foreach($stock_no as $stock_nos)
				{
				
					$productids=$stock_nos->product_id;
					
					if(!empty($productids))
					{
						$stocknos=DB::table('tbl_purchase_history_records')->where([['purchase_id','=',$id],['product_id','=',$productids]])->first();
						
						$pr_id=$stocknos->product_id;
						$qtyold=$stocknos->qty;
						$stock=DB::table('tbl_stock_records')->where('product_id','=',$pr_id)->first();
				
						$stock_id=$stock->id;
						$qtyolds=$stock->no_of_stoke;
						
						$newqty=$qtyolds - $qtyold;
				
						$stcoksnew = tbl_stock_records::find($stock_id);
						$stcoksnew->product_id=$productids;
						$stcoksnew->no_of_stoke=$newqty;
						$stcoksnew->save();
					}
					}
				
				}
	
	if(!empty($products))
	{
			foreach($products['product_id'] as $key => $value)
			{	
			    
				$purchase_hiatory_id = $products['tr_id'][$key];
			
				
				
				$Product_id = $products['product_id'][$key];
				
					$qty = $products['qty'][$key];
					$price = $products['price'][$key];
					$total_price = $products['total_price'][$key];
					$category = $products['category_id'][$key];
			
				
				
				
				$stockno=DB::table('tbl_purchase_history_records')->where('purchase_id','=',$id)->get()->toArray();
				
				
					
					if($purchase_hiatory_id != '')
						{	
							  $history = tbl_purchase_history_records::find($purchase_hiatory_id);
							  $history->product_id = $Product_id;
							  $history->qty = $qty;
							  $history->price = $price; 
							  $history->total_amount = $total_price;
							  $history->category = $category;
							  $history->save();
						}else
						{
							$history = new tbl_purchase_history_records;
							$history->product_id = $Product_id;
							$history->purchase_id = $id;
							$history->qty = $qty;
							$history->price = $price; 
							$history->total_amount = $total_price;
							$history->category = $category;
							$history->save();
						}

				
	             $stocks=DB::table('tbl_purchase_history_records')->where('product_id','=',$Product_id)->get()->toArray();
				
			     $qtytotal=0;
				 foreach($stocks as $stockss)
				{
					$pur_stock=$stockss->qty;
					$qtytotal += $pur_stock;	
						
				 }
				
				
				
				
					
					$stock=DB::table('tbl_stock_records')->where('product_id','=',$Product_id)->first();
					//$pid = $stock->product_id;
					if(!empty($stock))
					{
						$sid = $stock->id;
						$stockes=tbl_stock_records::find($sid);
						   $stockes->product_id=$Product_id;
						   $stockes->supplier_id=Input::get('s_name');
						   $stockes->no_of_stoke=$qtytotal;
						   $stockes->save();
					}
				else
					{
						
					$stocks= new tbl_stock_records;
					$stocks->product_id=$Product_id;
				   $stocks->supplier_id=Input::get('s_name');
				   $stocks->no_of_stoke=$qty;
				   $stocks->save();
				}				
			
				
			} 
			
		}
		return redirect('purchase/list')->with('message','Successfully Updated');
	}
	
	//modal view for product
	public function purchaseview()
	{	
		$purchaseid=Input::get('purchaseid');
		
		$logo = DB::table('tbl_settings')->first();
		$purchas=DB::table('tbl_purchases')->where('id','=',$purchaseid)->first();
		$purchasdetails= DB::table('tbl_purchase_history_records')->where('purchase_id','=',$purchaseid)->get()->toArray();	

        $html = view('purchase.modal')->with(compact('purchasdetails','purchas','logo','purchaseid'))->render();
		return response()->json(['success' => true, 'html' => $html]);
		
		
	}
	
	public function getproductname()
	{
		$id = Input::get('row_id');	
		$ids = $id+1;	    
		$rowid = 'row_id_'.$ids;      
		$product = DB::table('tbl_products')->get()->toArray();	
		$Select_product=DB::table('tbl_product_types')->get()->toArray();
		$html = view('purchase.newproduct')->with(compact('id','ids','rowid','product','Select_product'))->render();
		return response()->json(['success' => true, 'html' => $html]);
	}
	
	public function Categoryitem()
	{
		$id = Input::get('m_id');
		
		$tbl_products = DB::table('tbl_products')->where('product_type_id','=',$id)->get()->toArray();
		
		
		if(!empty($tbl_products))
		{   ?>
			<option value="">--Select Product--</option>
			<?php
			foreach($tbl_products as $tbl_productss)
			{ ?>
				<option value="<?php echo  $tbl_productss->id; ?>"><?php echo $tbl_productss->name; ?></option>
			<?php 
			} 
		}
		else
		{
			?>
			<option value="">--Select Product--</option>
			<?php
		}
	}
	
}	
