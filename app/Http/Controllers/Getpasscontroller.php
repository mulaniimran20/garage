<?php

namespace App\Http\Controllers;
use App\User;
use Auth;
use App\tbl_gatepasses;
use App\tbl_settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use DB;

class Getpasscontroller extends Controller
{
	
	public function __construct()
    {
        $this->middleware('auth');
    }
	
	//gatepass list
    public function index()
	{
	
		$userid=Auth::User()->id;
		if(!empty(getActiveCustomer($userid)=='yes'))
		{
			$gatepass=DB::table('tbl_gatepasses')->orderby('id','DESC')->get()->toArray();
		}
		elseif(!empty(getActiveEmployee($userid)=='yes'))
		{
			$gatepass=DB::table('tbl_services')
											->join('tbl_gatepasses','tbl_services.job_no','=','tbl_gatepasses.jobcard_id')
											->where('tbl_services.assign_to','=',$userid)
											->orderby('tbl_gatepasses.id','DESC')->get()->toArray();
		}
		else
		{
			$gatepass=DB::table('tbl_gatepasses')->where('customer_id','=',$userid)->orderby('id','DESC')->get()->toArray();	
			
		}
		return view('gatepass.list',compact('gatepass')); 
	}
	
	//gatepass add form
	public function addgatepass()
	{
		$characters = '0123456789';
		$code =  'G'.''.substr(str_shuffle($characters),0,6);
    	
		$customer=DB::table('users')->where('role','=','Customer')->get()->toArray();
		$vehicle=DB::table('tbl_vehicles')->get()->toArray();
		
		$getpass=DB::table('tbl_gatepasses')->get()->toArray();
		
		$job_no=array();
		
			foreach($getpass as $getpas)
			{
				$job_no[]=$getpas->jobcard_id;
				
			}	
			
			$jobno=DB::table('tbl_invoices')->where('job_card','like','J%')->whereNotIn('job_card',$job_no)->get()->toArray();
		return view('gatepass.gatepass',compact('customer','vehicle','code','jobno'));
	}
	
	//gatepass data to show for customer
	public function gatedata()
	{
		$jobcard=Input::get('jobcard');
		
		$gatepass=DB::select("SELECT * FROM `tbl_services` 
        		INNER JOIN users ON tbl_services.customer_id = users.id 
        		INNER JOIN tbl_vehicles ON tbl_services.vehicle_id = tbl_vehicles.id 
				INNER JOIN tbl_jobcard_details ON tbl_services.id = tbl_jobcard_details.service_id 
				INNER JOIN tbl_vehicle_types ON tbl_vehicles.vehicletype_id = tbl_vehicle_types.id where tbl_services.job_no='$jobcard'");
         
		$getdata = str_replace(array('[', ']'), '', htmlspecialchars(json_encode($gatepass), ENT_NOQUOTES));
		echo $getdata;
	}
	
	//gatepass store
	public function store(Request $request){
		$this->validate($request, [  
        
		  // 'out_date'  => 'required|date|after:today',
	      ]);
		  
		 
		$jobcard=Input::get('jobcard');
		if(getDateFormat()== 'm-d-Y')
		{
			$out_date=date('Y-m-d H:i:s',strtotime(str_replace('-','/',Input::get('out_date'))));
		}
		else
		{
			$out_date=date('Y-m-d H:i:s',strtotime(Input::get('out_date')));
		}
		$jobservice=DB::table('tbl_services')->where('job_no','=',$jobcard)->first();
		$c_id=$jobservice->customer_id;
		$v_id=$jobservice->vehicle_id;
		
		$gatepass= new tbl_gatepasses;
		$gatepass->jobcard_id=$jobcard;
		$gatepass->gatepass_no=Input::get('gatepass_no');
		$gatepass->customer_id=$c_id;
		$gatepass->vehicle_id=$v_id;
		$gatepass->service_out_date=$out_date;
		$gatepass->ser_pro_status = 1;
        $gatepass->create_by = Auth::user()->id;
		$gatepass->save();
		return redirect('/gatepass/list')->with('message','Successfully Submitted');
	}
	
	//gatepass delete
	public function delete($id)
	{
		$gatepass=DB::table('tbl_gatepasses')->where('id','=',$id)->delete();
		return redirect('/gatepass/list')->with('message','Successfully Deleted');
	}
	
	//gatepass edit
	public function edit($id)
	 {	
		
			$jobno=DB::table('tbl_invoices')->where('job_card','like','J%')->get()->toArray();
			
	
			 $gatepass=DB::table('tbl_services')
											 ->join('users','tbl_services.customer_id','=','users.id')
											 ->join('tbl_vehicles','tbl_services.vehicle_id','=','tbl_vehicles.id')
										->join('tbl_jobcard_details','tbl_services.id','=','tbl_jobcard_details.service_id')
										->join('tbl_vehicle_types','tbl_vehicles.vehicletype_id','=','tbl_vehicle_types.id')
										->join('tbl_gatepasses','tbl_services.job_no','=','tbl_gatepasses.jobcard_id')
										->where('tbl_gatepasses.id','=',$id)->first();
			
		return view('gatepass.edit',compact('gatepass','jobno'));
	}
	
	//gatepass update
	public function upadte(Request $request,$id)
	{
		$this->validate($request, [
		  // 'out_date'  => 'required|date|after:today',
	      ]);
		$jobcard=Input::get('jobcard');
		if(getDateFormat()== 'm-d-Y')
		{
			$out_date=date('Y-m-d H:i:s',strtotime(str_replace('-','/',Input::get('out_date'))));
		}
		else
		{
			$out_date=date('Y-m-d H:i:s',strtotime(Input::get('out_date')));
		}
		$jobservice=DB::table('tbl_services')->where('job_no','=',$jobcard)->first();
		$c_id=$jobservice->customer_id;
		$v_id=$jobservice->vehicle_id;
		
		$gatepass=tbl_gatepasses::find($id);
		$gatepass->jobcard_id=$jobcard;
		$gatepass->gatepass_no=Input::get('gatepass_no');
		$gatepass->customer_id=$c_id;
		$gatepass->vehicle_id=$v_id;
	
		$gatepass->service_out_date=$out_date;
		$gatepass->ser_pro_status = 1;
        $gatepass->create_by = Auth::user()->id;
		$gatepass->save();
		return redirect('/gatepass/list')->with('message','Successfully Updated');
	}
	
	//gatepass modal 
	public function gatepassview()
	{
		$getpassid=Input::get('getpassid');
		
		$getpassdata = DB::table('tbl_gatepasses')
						->join('users','users.id','=','tbl_gatepasses.customer_id')
						->join('tbl_vehicles','tbl_gatepasses.vehicle_id','=','tbl_vehicles.id')
						->join('tbl_services','tbl_gatepasses.jobcard_id','=','tbl_services.job_no')
						->select('tbl_gatepasses.*','tbl_services.service_date','tbl_vehicles.modelname','users.name','users.lastname')
						->where('jobcard_id',$getpassid)->first();
		
		$setting = DB::table('tbl_settings')->first();				
						
		$html = view('gatepass.getpassmodel')->with(compact('getpassid','getpassdata','setting'))->render();
		return response()->json(['success' => true, 'html' => $html]);
	}
	
}	
