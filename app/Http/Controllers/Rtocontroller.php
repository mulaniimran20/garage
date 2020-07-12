<?php

namespace App\Http\Controllers;
use App\User;
use Auth;
use App\tbl_rto_taxes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use DB;

class Rtocontroller extends Controller
{
	public function __construct()
    {
        $this->middleware('auth');
    }
	
	//rto list
    public function index()
	{	
		$rto = DB::table('tbl_rto_taxes')->orderBy('id','DESC')->get()->toArray();
		return view('rto.list',compact('rto')); 
	}
	
	//rto add form
	public function addrto()
	{	
		$vehicle = DB::table("tbl_vehicles")->select('*')
				->whereNOTIn('tbl_vehicles.id',function($query){
				$query->select('tbl_rto_taxes.vehicle_id')->from('tbl_rto_taxes');
				})
				->get()->toArray();
		return view('rto.add',compact('vehicle')); 
	}
	
	//rto store
	public function store(Request $request)
	{	
		$this->validate($request, [  
         'rto_tax' => 'numeric',
         'num_plate_tax' => 'numeric',
         'mun_tax' => 'numeric',
         ],
		 [
		 'rto_tax.numeric' => 'RTO tax must be digits only',
		 'num_plate_tax.numeric' => 'Number Plate must be digits only',
		 'mun_tax.numeric' => 'Municipal road tax must be digits only',
		 ]);
		$rto = new tbl_rto_taxes;
		$rto->vehicle_id = Input::get('v_id');
		$rto->registration_tax = Input::get('rto_tax');
		$rto->number_plate_charge = Input::get('num_plate_tax');
		$rto->muncipal_road_tax = Input::get('mun_tax');
		$rto->save();
		
		return redirect('/rto/list')->with('message','Successfully Submitted');
	}
	
	//rto delete
	public function destroy($id)
	{	
		$rto = DB::table('tbl_rto_taxes')->where('id','=',$id)->delete();
		return redirect('/rto/list')->with('message','Successfully Deleted');
	}
	
	//rto editform
	public function edit($id)
	{	
		$editid = $id;
		$vehicle = DB::table('tbl_vehicles')->get()->toArray();
		$rto = DB::table('tbl_rto_taxes')->where('id','=',$id)->first();
		return view('rto.edit',compact('rto','editid','vehicle'));
	}
	
	//rto update
	public function update(Request $request ,$id)
	{
		$this->validate($request, [  
         'rto_tax' => 'numeric',
         'num_plate_tax' => 'numeric',
         'mun_tax' => 'numeric',
         ],
		 [
		 'rto_tax.numeric' => 'RTO tax must be digits only',
		 'num_plate_tax.numeric' => 'Number Plate must be digits only',
		 'mun_tax.numeric' => 'Municipal road tax must be digits only',
		 ]);
		$rto = tbl_rto_taxes::find($id);
		$rto->vehicle_id = Input::get('v_id');
		$rto->registration_tax = Input::get('rto_tax');
		$rto->number_plate_charge = Input::get('num_plate_tax');
		$rto->muncipal_road_tax = Input::get('mun_tax');
		$rto->save();		
		return redirect('/rto/list')->with('message','Successfully Updated');
	}
}	
