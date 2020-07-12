<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\tbl_payments;
use App\Http\Requests;
use DB;
use Illuminate\Support\Facades\Input;

class PaymentControler extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }

	 // payment add form
	public function index()
	{   
         return view('payment.add');  
	}

	// payment store
	public function paymentstore()
	{
		$paymenttype=Input::get('payment');
		$count =DB::table('tbl_payments')->where('payment','=',$paymenttype)->count();
		if($count == 0)
		{
			$payment= new tbl_payments;
			$payment->payment=$paymenttype;
			$payment->save();
			return redirect('payment/list')->with('message','Successfully Submitted');
		}
		else
		{
			return redirect('payment/add')->with('message','Duplicate Data');
		}
	}
	
	// payment list
	public function paymentlist()
	{
		$vehical=DB::table('tbl_payments')->orderBy('id','DESC')->get()->toArray();

		return view('payment.list',compact('vehical'));
	}
    
	// payment delete
    public function destory($id)
    {
    	$vehical=DB::table('tbl_payments')->where('id','=',$id)->delete();
    	
    	return redirect('/payment/list')->with('message','Successfully Deleted');
    }

	// payment edit
    public function editpayment($id)
    {   
    	$editid=$id;
    	$vehicals=DB::table('tbl_payments')->where('id','=',$id)->first();
    	return view('payment.edit',compact('vehicals','editid'));
    }

	// payment update
    public function updatepayment($id)
    {
    	$paymenttype=Input::get('payment');
		$count =DB::table('tbl_payments')->where([['payment','=',$paymenttype],['id','!=',$id]])->count();
		if($count == 0)
		{
			$payment=tbl_payments::find($id);
			$payment->payment=$paymenttype;
			$payment->save();
			return redirect('payment/list')->with('message','Successfully Updated');
		}
		else
		{
			return redirect('payment/list/edit/'.$id)->with('message','Duplicate Data');
		}
    }
}