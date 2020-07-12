<?php

use App\POST;
namespace App\Http\Controllers;
use Auth;
use Illuminate\Http\Request;
use App\Http\Requests;
use Illuminate\Contracts\Validation\Validator;
use App\tbl_checkout_categories;
use Illuminate\Support\Facades\Input;
use App\tbl_vehicles;
use App\tbl_points;
use DB;


class CheckpointController extends Controller
{
    
	 public function __construct()
    {
        $this->middleware('auth');
    }

	//observation addform
    public function index()
    {
        $vehicle_name = DB::table('tbl_vehicles')->get()->toArray();
		$cat_name = DB::table('tbl_checkout_categories')->distinct()->select('checkout_point')->get()->toArray();
		
        return view("observation.add",compact('vehicle_name','cat_name'));

    }
    
	//observation add category
    public function add_category(Request $request)
    {
        $vehical_name = Input::get('vehical_name');
		$category = Input::get('category');
			foreach($vehical_name as $data)
			{
				$tbl_checkout_categories = new tbl_checkout_categories;
				$tbl_checkout_categories->vehicle_id =  $data;
				$tbl_checkout_categories->checkout_point =  $category;
				$tbl_checkout_categories->create_by =  Auth::user()->id; 
				$tbl_checkout_categories->save();
			}
			return $tbl_checkout_categories->id; 			
    }

	//observation store
    public function store(Request $request)
    {
		$vehical = Input::get('veh_name');
		foreach($vehical as $vhi)
		{	
			$v[] = $vhi;	
		}
		$chkpoin =  Input::get('checkpoint_name');	
		$chek_sub_pt = Input::get('checkpoint');
	
		$data = DB::table('tbl_checkout_categories')->whereIn('vehicle_id',$v)->where('checkout_point','=',$chkpoin)->count();

		if($data == 0 )
		{
			foreach ($vehical as $data1)
				{
						$tbl_checkout_categories = new tbl_checkout_categories;
						$tbl_checkout_categories->vehicle_id = $data1;
						$tbl_checkout_categories->checkout_point = $chkpoin;
						$tbl_checkout_categories->create_by = Auth::user()->id; 
						$tbl_checkout_categories->save();
						
						foreach($chek_sub_pt as $data)
						{ 					
								$tbl_points = new tbl_points;
								$tbl_points->checkout_subpoints = $tbl_checkout_categories->checkout_point;
								$tbl_points->vehicle_id = $tbl_checkout_categories->vehicle_id;
								$tbl_points->checkout_point = $data;
								$tbl_points->create_by =  Auth::user()->id; 
								$tbl_points->save();
						} 
				} 
		  		
		}
		else
		{ 
		foreach($chek_sub_pt as $data)
				{ 
					foreach ($vehical as $data1)
					{
					$tbl_points = new tbl_points;
					$tbl_points->checkout_subpoints = $chkpoin;
					$tbl_points->vehicle_id = $data1;
					$tbl_points->checkout_point = $data;
					$tbl_points->create_by =  Auth::user()->id; 
					$tbl_points->save();
					}		
				} 
		}			  
	 	return redirect('observation/list')->with('message','Successfully Submitted'); 		      
    }

	//observation list
    public function showall()
    { 
        $check_data = DB::table('tbl_checkout_categories')->groupBy('vehicle_id')->orderBy('id','DESC')->get()->toArray();
        return view("/observation/list",compact('check_data'));
    }

   //observation edit
    public function edit()
    {
		$id = Input::get('id');
		$sub_data = DB::table("tbl_points")->where('id',$id)->get()->toArray();
		
		$html = view('observation.editmodel')->with(compact('id','sub_data'))->render();
		return response()->json(['success' => true, 'html' => $html]);	
    }

	//observation update
    public function updatedata()
    {

        $id = Input::get('id');
        $subpoint = Input::get('subpoints');
		
		$data = DB::table('tbl_points')->where('id','=',$id)->get()->toArray();
		
		foreach($subpoint as $subpoints)
		{
		
			foreach ($data as $datas)
			{
				
				$ids = $datas->id;
				$tbl_points =  tbl_points::find($ids);
				$tbl_points->checkout_point = $subpoints;
				$tbl_points->save();
			}
		}
		return 1;
		
    }
    
	//observation delete
    public function destroy()
    { 
        $id = Input::get('id');
	    $data = tbl_points::find($id);
        $data->delete();
        echo $id;
		return 1;
    }	
}