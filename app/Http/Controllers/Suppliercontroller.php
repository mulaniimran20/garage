<?php

namespace App\Http\Controllers;
use App\User;
use App\tbl_custom_fields;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use App\Http\Requests;
use DB;
use timezone;
class Suppliercontroller extends Controller
{	
	public function __construct()
    {
        $this->middleware('auth');
    }
	
	//supplier list
    public function supplierlist()
	{	
		$user = DB::table('users')->where('role','=','Supplier')->orderBy('id','DESC')->get()->toArray();
		
		$server = "http://".$_SERVER['SERVER_NAME']."/garrage";
		// $product = DB::table('tbl_products')->where('supplier_id','=',$id)->first();

		return view('supplier.list',compact('user','server','supplier','product'));
	}
	
	//supplier add in user_tbl
	public function adddata()
	{	
		$supllier = new User;
		$supllier->name = Input::get('name');
		$supplier->save();
	}
	
	//supplier add form
	public function supplieradd()
	{	
		$country = DB::table('tbl_countries')->get()->toArray();
		$tbl_custom_fields=DB::table('tbl_custom_fields')->where([['form_name','=','supplier'],['always_visable','=','yes']])->get()->toArray();
	   return view('supplier.add',compact('country','tbl_custom_fields'));
	}
	
	//supplier store
	public function storesupplier(Request $request)
	{	
		$this->validate($request, [
			'contact_person' =>'regex:/^[(a-zA-Z\s)]+$/u',
			'displayname' =>'required|regex:/^[(a-zA-Z\s)]+$/u',
			'email' => 'unique:users|email',
			'mobile' =>	'required|max:15|min:10|regex:/^[- +()]*[0-9][- +()0-9]*$/',
			'landlineno' => 'max:15|regex:/^[- +()]*[0-9][- +()0-9]*$/',
			'image' => 'image|mimes:jpg,png,jpeg',
			],[
				'landlineno.regex' => 'Enter valid landline no',
				'contact_person.regex' => 'Enter valid contact person name',
				'displayname.regex' => 'Enter valid company name',
				'displayname.required' => 'Company name is required',
			]);		
			
		$displayname = Input::get('displayname');
		$firstname = Input::get('firstname');
		$lastname = Input::get('lastname');
		$gender = Input::get('gender');
		
		$dd=Input::get('dob');
		if($dd == '')
		{
			$dob=$dd;
		}
		else
		{	
			if(getDateFormat()== 'm-d-Y')
			{
				$dob=date('Y-m-d',strtotime(str_replace('-','/',Input::get('dob'))));
			}
			else
			{
				$dob=date('Y-m-d',strtotime(Input::get('dob')));
			}
		}
		
		$contact_person = Input::get('contact_person');
		$email = Input::get('email');
		$mobile = Input::get('mobile');
		$landlineno = Input::get('landlineno');
		$address = Input::get('address');
		$country_id = Input::get('country_id');
		$state = Input::get('state');
		$city = Input::get('city');
				
		$user = new User;
		$user->name = $firstname;
		$user->lastname = $lastname;
		$user->display_name = $displayname;
		$user->gender = $gender;
		$user->birth_date = $dob;
		$user->contact_person = $contact_person;
		$user->email = $email;
		$user->mobile_no = $mobile;
		$user->landline_no = $landlineno;
		$user->address = $address;
		if(!empty(Input::hasFile('image')))
			{
				$file= Input::file('image');
				$filename=$file->getClientOriginalName();
				$file->move(public_path().'/supplier/', $file->getClientOriginalName());
				$user->image = $filename;
			}
		else
			{
					$user->image='avtar.png';
			}
		
		$user->country_id = $country_id;
		$user->state_id = $state;
		$user->city_id = $city;
		$user->role = 'Supplier';
		$user->language="en";
		$user->timezone="UTC";
	//custom field	
		$custom=Input::get('custom');
		$custom_fileld_value=array();	
		$custom_fileld_value_jason_array=array();	
		if(!empty($custom))
		{
			foreach($custom as $key=>$value)
			{
			$custom_fileld_value[]=array("id" => "$key", "value" => "$value");	
			}	
       
			$custom_fileld_value_jason_array['custom_fileld_value']=json_encode($custom_fileld_value); 

			foreach($custom_fileld_value_jason_array as $key1=>$val1)
			{
			$casedata=$val1;
			}	
			$user->custom_field = $casedata;
		}
		$user->save();		
		return redirect('/supplier/list')->with('message','Successfully Submitted');
	}
	
	//supplier show
	public function showsupplier($id)
	{	
		$viewid = $id;		
		$user = DB::table('users')->where([['role','=','Supplier'],['id','=',$id]])->first();
		$tbl_custom_fields=DB::table('tbl_custom_fields')->where([['form_name','=','supplier'],['always_visable','=','yes']])->get()->toArray();
		return view('supplier.show',compact('user','viewid','tbl_custom_fields'));
	}
	
	//supplier delete
	public function destroy($id)
	{	
		$user = DB::table('users')->where('id','=',$id)->delete();
		return redirect('/supplier/list')->with('message','Successfully Deleted');
	}
	
	//supplier edit
	public function edit($id)
	{	
		$editid = $id;
		$country = DB::table('tbl_countries')->get()->toArray();
		$state = DB::table('tbl_states')->get()->toArray();
		$city = DB::table('tbl_cities')->get()->toArray();
		$user = DB::table('users')->where('id','=',$id)->first();
			
		$tbl_custom_fields=DB::table('tbl_custom_fields')->where([['form_name','=','supplier'],['always_visable','=','yes']])->get()->toArray();		
		return view('supplier.edit',compact('country','state','city','user','editid','tbl_custom_fields'));
	}
	
	//supplier update
	public function update(Request $request, $id)
	{	
		$this->validate($request, [
			/* 'firstname' => 'regex:/^[(a-zA-Z\s)]+$/u',
			'lastname' =>'regex:/^[(a-zA-Z\s)]+$/u', */
			'contact_person' =>'regex:/^[(a-zA-Z\s)]+$/u',
			'displayname' =>'required|regex:/^[(a-zA-Z\s)]+$/u',
			'mobile' =>	'required|max:15|min:10|regex:/^[- +()]*[0-9][- +()0-9]*$/',
			'landlineno' => 'max:15|regex:/^[- +()]*[0-9][- +()0-9]*$/',
			'image' => 'image|mimes:jpg,png,jpeg',
			
			],[
				/* 'firstname.regex' => 'Enter valid first name',
				'lastname.regex' => 'Enter valid last name', */
				'landlineno.regex' => 'Enter valid landline no',
				'contact_person.regex' => 'Enter valid contact person name',
				'displayname.regex' => 'Enter valid company name',
				'displayname.required' => 'Company name is required',
			]);
		
		$usimgdtaa = DB::table('users')->where('id','=',$id)->first();
			 $email = $usimgdtaa->email;

				if($email != Input::get('email'))
				{
				$this->validate($request, [
					'email' => 'email|unique:users'
				   
				]);
				}
				
		$firstname = Input::get('firstname');
		$lastname = Input::get('lastname');
		$displayname = Input::get('displayname');
		$gender = Input::get('gender');
		$dd=Input::get('dob');
		if($dd == '')
		{
			$dob=$dd;
		}
		else
		{
			if(getDateFormat()== 'm-d-Y')
			{
				$dob=date('Y-m-d',strtotime(str_replace('-','/',Input::get('dob'))));
			}
			else
			{
				$dob=date('Y-m-d',strtotime(Input::get('dob')));
			}
		}
		$email = Input::get('email');
		$contact_person = Input::get('contact_person');
		$password = Input::get('password');
		$mobile = Input::get('mobile');
		$landlineno = Input::get('landlineno');
		$address = Input::get('address');
		$country_id = Input::get('country_id');
		$state = Input::get('state');
		$city = Input::get('city');
			
		$user = User::find($id);
		$user->name = $firstname;
		$user->lastname = $lastname;
		$user->display_name = $displayname;
		$user->gender = $gender;
		$user->birth_date = $dob;
		$user->email = $email;
		$user->mobile_no = $mobile;
		$user->landline_no = $landlineno;
		$user->address = $address;
		
		if(!empty(Input::hasFile('image')))
			{
				$file= Input::file('image');
				$filename=$file->getClientOriginalName();
				$file->move(public_path().'/supplier/', $file->getClientOriginalName());
				$user->image = $filename;
			}
		
		$user->contact_person = $contact_person;
		$user->country_id = $country_id;
		$user->state_id = $state;
		$user->city_id = $city;
		$user->role = 'Supplier';	
		$user->language="en";
		$user->timezone="UTC";
		$custom=Input::get('custom');
		$custom_fileld_value=array();	
		$custom_fileld_value_jason_array=array();
		if(!empty($custom))
		{
			foreach($custom as $key=>$value)
			{
				$custom_fileld_value[]=array("id" => "$key", "value" => "$value");	
			}	      
			$custom_fileld_value_jason_array['custom_fileld_value']=json_encode($custom_fileld_value); 

			foreach($custom_fileld_value_jason_array as $key1=>$val1)
			{
				$customdata=$val1;
			}
			$user->custom_field = $customdata;
		}			
		$user->save();		
		return redirect('/supplier/list')->with('message','Successfully Updated');
	}
}	
