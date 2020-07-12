@extends('layouts.app')
@section('content')
<script src="{{ URL::asset('build/js/jquery.min.js') }}"></script>
<?php $userid = Auth::user()->id; ?>
@if (getAccessStatusUser('Observation library',$userid)=='yes')
 <div class="right_col" role="main">
          <div class="">
            <div class="page-title">
           <div class="nav_menu">
            <nav>
              <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i><span class="titleup">&nbsp {{ trans('app.Observation')}}</span></a>
              </div>
                  @include('dashboard.profile')
            </nav>
          </div>
            </div>
			
		@if(session('message'))
			<div class="row massage">
				<div class="col-md-12 col-sm-12 col-xs-12">
					<div class="checkbox checkbox-success checkbox-circle">
					  <label for="checkbox-10 colo_success">  {{session('message')}} </label>
					</div>
				</div>
			</div>
		@endif
          <div class="clearfix"></div>
            <div class="row">
              <div class="col-md-12 col-sm-12 col-xs-12">
               <div class="x_content">
                  <div class="">
                     <ul class="nav nav-tabs bar_tabs tabconatent" role="tablist">
						<li role="presentation" class="suppo_llng_li floattab"><a href="{!! url('/observation/list')!!}"><span class="visible-xs"></span><i class="fa fa-list fa-lg">&nbsp;</i> {{ trans('app.Observation List')}}</a></li>
						<li role="presentation" class="active suppo_llng_li_add floattab"><a href="{!! url('/observation/add')!!}"><span class="visible-xs"></span> <i class="fa fa-plus-circle fa-lg">&nbsp;</i><b>{{ trans('app.Add Observation')}}</b></a></li>
					</ul>
                    
                    <div class="clearfix"></div>
                  </div>
                  
				   <div class="x_panel">
                    <br />
                    <form id="form_add" action="{{ url('/observation/store') }}"  method="post"  enctype="multipart/form-data" data-parsley-validate class="form-horizontal form-label-left">

                      <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="veh_name">{{ trans('app.Vehicle Name')}} <label class="text-danger">*</label>
                        </label>
                        <div class="col-md-5 col-sm-5 col-xs-12">
                          
                          <select  required name="veh_name[]" class="form-control col-md-7 col-xs-12 vehical_name" id="chkveg" multiple="multiple" id="veh_name" >

                            @foreach ($vehicle_name as $datas) 

                                <option value="{{ $datas->id }}">{{ $datas->modelname }}</option>
                              
                            @endforeach
						                    <option value="0">General</option>   
                          </select>

                          <script>
                          $(document).ready(function() {
                          $('#veh_name').multiselect({
                          maxHeight: 80
                          });
                          });

                          </script> 
                        </div>
                      </div>

                      
					  <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="checkpoint_name">
                        {{ trans('app.Checkpoint Category')}} <label class="text-danger">*</label>
                        </label>
						<div class="col-md-5 col-sm-5 col-xs-12">
							<select required="required" name="checkpoint_name1" class="form-control col-md-7 col-xs-12 category check_cat" id="chkveg" id="veh_name">
								<option value="">Select Category</option>
								@foreach ($cat_name as $cat_names) 

									<option value="{{ $cat_names->checkout_point }}">{{ $cat_names->checkout_point }}</option>
									
							   @endforeach
							   
							</select>
							<input type="hidden" value="" name="checkpoint_name" id="val_app" />
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12 addremove">
							<button type="button" class="btn btn-default" data-toggle="modal" data-target="#add_category">{{ trans('app.Add Category')}}</button>
						</div>
					  </div>
					  
                     <input type="hidden" name="_token" value="{{csrf_token()}}">
					 
                      <div class="items">

                      <div class="form-group">
                      <label class="control-label col-md-3 col-sm-3 col-xs-12">{{ trans('app.Check Point') }}<label class="text-danger">&nbsp;&nbsp;*</label></label>
					<div class="col-md-5 col-sm-5 col-xs-12">
                      <input id="author_email" placeholder="{{ trans('app.Enter Checkpoint Name')}}" name="checkpoint[]" maxlength="30" type="text" class="form-control" required />
					</div>
					<div class="col-md-3 col-sm-3 col-xs-12 addremove">
					  <button type="button" id="add_new_check" class="btn btn-default add_field_button" url="">{{ trans('app.Add Checkpoint')}}</button>
					 </div>
                      </div>
                      </div>
                      <div class="form-group">
                        <div class="col-md-10 col-sm-10 col-xs-12 text-center">
                            
							<a class="btn btn-primary" href="{{ URL::previous() }}">{{ trans('app.Cancel')}}</a>						
							<button type="submit" class="btn btn-success v_check">{{ trans('app.Submit')}}</button>
						  
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
        </div>
    </div> 
	<div class="modal fade" id="add_category" role="dialog">
		<div class="modal-dialog">
		  <!-- Modal content-->
		  <div class="modal-content">
			<div class="modal-header">
			  <button type="button" class="close" data-dismiss="modal">&times;</button>
			  <h4 class="modal-title">{{ trans('app.Add New Category')}}</h4>
			</div>
			<div class="modal-body">
					<div class="col-md-12 col-sm-12 col-xs-12 form-group">
						<div class="col-md-12 col-sm-12 col-xs-12 form-group">
						  <label class="control-label col-md-3 col-sm-3 col-xs-12" style="padding-top:8px;" for="cat">{{ trans('app.Check Point')}}<label class="text-danger">&nbsp;&nbsp;*</label></label>
						  <div class="col-md-6 col-sm-6 col-xs-12">
							<input id="cat" placeholder="{{ trans('app.Enter Checkpoint Name')}}" name="category" type="text" class="form-control add_newcat" value="" maxlength="25" required />
						  </div>
						</div>
					</div>
					<div class="col-md-12 col-sm-12 col-xs-12 form-group">
						<div class="col-md-9 col-sm-9 col-xs-12 text-center">
							<button type="button" class="btn btn-success add_cat" data-dismiss="modal">{{ trans('app.Submit')}}</button>
						</div>
					</div>
			</div>
			<div class="modal-footer" style="border-top: none;">
			</div>
		  </div>
		</div>
   </div>
@else
	<div class="right_col" role="main">
		<div class="nav_menu main_title" style="margin-top:4px;margin-bottom:15px;">
            <div class="nav toggle" style="padding-bottom:16px;">
               <span class="titleup">&nbsp {{ trans('app.You are not authorize this page.')}}</span>
            </div>
        </div>
	</div>
@endif   

		
<script>

 
	 $('.v_check').click(function(){
		var vehical_name = $('.vehical_name').val();
		if( vehical_name == null )
		{
			swal({   
					title: "Select Vehicles",
					text: 'Please Select Vehicles Name..'  
					});	
		}
		
	});
		
$(document).ready(function() {
	$('body').on('change','.check_cat',function(){
		var check_cat = $('.check_cat option:selected').text();
		$('#val_app').val(check_cat);
	});
var max_fields = 20; //maximum input boxes allowed
var wrapper = $(".items"); //Fields wrapper
var add_button = $(".add_field_button"); //Add button ID
 
 
var x = 1; //initlal text box count
$(add_button).click(function(e){ //on add input button click
e.preventDefault();
if(x < max_fields){ //max input box allowed
x++; 
$(wrapper).append('<div class="form-group"><label class="control-label col-md-3 col-sm-3 col-xs-12">{{ trans('app.Check Point') }}<label class="text-danger">&nbsp;&nbsp;*</label></label>' +
'<input class="form-control col-md-7 col-xs-12 addcheckpoint" maxlength="30" id="chek_pt" type="text" placeholder="{{ trans('app.Enter Checkpoint Name')}}" name="checkpoint[]"/>' + '&nbsp;&nbsp;'+'<a href="#" class="remove_field"><i class="fa fa-times" style="margin-top:10px !important;"></a></div>'); 
}
});
 
$(wrapper).on("click",".remove_field", function(e){ 
e.preventDefault(); $(this).parent('div').remove(); x--;
})
});

$(function() {

$('#chkveg').multiselect({

includeSelectAllOption: true

});

$('#btnget').click(function() {

alert($('#chkveg').val());

})

});


</script>
<script>

$(document).ready(function(){
	$('.add_cat').click(function(){
		
		var url = "<?php echo url('/newcategory'); ?>"
		var category = $('.add_newcat').val();
		
		var vehical_name = $('.vehical_name').val();
		
		if( vehical_name == null || category == '')
		{
		   if(vehical_name == null)
		   {
		    swal({   
					title: "Select Vehicles",
					text: 'Please Select Vehicles Name..'  
					});	
			}
			else if(category == '')
			{
				swal({   
					title: "Enter Category Name",
					text: 'Please enter category name..'  
					});	
			}
					
		}
		else
		{
			$.ajax({
				type:'GET',
				url:url,
				data:{ category:category, vehical_name:vehical_name },
				success:function(response)
				{	
					
						$('.category').append("<option value="+category+" selected>"+category+"</option>");
						$('#val_app').val(category);
						$('.add_newcat').val('');
					
				},
				error:function()
				{
					alert("Somthing went wrong..!");
				}
			}); 
		}
		
	}); 
	
});

</script>  



  
@endsection