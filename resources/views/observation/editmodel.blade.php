
        			
	<?php foreach ($sub_data as $sub_datas) { ?>
   <div class="items">

	  <div class="col-md-12 col-sm-12 col-xs-12 form-group">
			<label class="control-label col-md-3 col-sm-3 col-xs-12" style="padding-top:5px;">{{ trans('app.Check Point')}}<label class="text-danger">&nbsp;&nbsp;*</label></label>
	  
			<input type="hidden" name="check_point_id" class="check_point_id" value="<?php echo $id;?>" />
			<div class="col-md-8 col-sm-8 col-xs-12">
				<input id="sub_ch" placeholder="{{ trans('app.Enter Checkpoint Name')}}" name="checkpoint[]" type="text" maxlength="30" class="form-control chekpoint_sub" value="<?php echo $sub_datas->checkout_point;?>" />
			</div>
	  </div>
  </div>
	<?php } ?>
