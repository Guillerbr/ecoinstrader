@extends('admin.layouts.master')
@section('content')	
{{ HTML::script('assets/js/bootstrap-paginator.js') }}
<h2>Deposit </h2>

@if ( is_array(Session::get('error')) )
        <div class="alert alert-error">{{ head(Session::get('error')) }}</div>
	@elseif ( Session::get('error') )
      <div class="alert alert-error">{{{ Session::get('error') }}}</div>
	@endif
	@if ( Session::get('success') )
      <div class="alert alert-success">{{{ Session::get('success') }}}</div>
	@endif

	@if ( Session::get('notice') )
	      <div class="alert">{{{ Session::get('notice') }}}</div>
	@endif
<a href="javascript:void()" id="add_wallet_link">{{Lang::get('admin_texts.add_new_method')}}</a>

<form class="form-horizontal" role="form" id="add_new_wallet" method="POST" action="{{{URL::to('/admin/add-method-deposit')}}}">
	<input type="hidden" name="_token" value="{{{ Session::getToken() }}}">
	<div class="form-group">
	    <label for="inputEmail3" class="col-sm-2 control-label">{{Lang::get('admin_texts.method_name')}}</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			  <input type="text" minlength="2" class="form-control" required="" name="dname" id="dname" value="">
			</div>	      	      
	    </div>
	</div>	
	<div class="form-group">
	    <label for="inputPassword3" class="col-sm-2 control-label">{{Lang::get('admin_texts.method_fee')}} (%)</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			  <input type="text" name="dfee" id="dfee"  class="form-control" value="">
			</div>	      
	    </div> 
	</div>
	<div class="form-group">
	    <label for="inputPassword3" class="col-sm-2 control-label">{{Lang::get('admin_texts.method_min_value')}}</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			  <input type="text"  class="form-control" name="dmin" id="dmin" value="">			  
			</div>
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputPassword3" class="col-sm-2 control-label">{{Lang::get('admin_texts.method_description')}}</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			 <textarea type="text" cols="28" rows="5" name="ddes" id="ddes" class="form-control"  value=""></textarea>	  		  
			</div>
	    </div>
	</div>
	<div class="form-group">
	    <label for="inputPassword3" class="col-sm-2 control-label">{{Lang::get('admin_texts.method_min_fee')}}</label>
	    <div class="col-sm-10">
	    	<div class="input-append">
			 <input type="text" name="dminfee" id="dminfee" class="form-control"  value="">  		  
			</div>
	    </div>
	</div>
	
	<div class="form-group">		
	    <div class="col-sm-offset-2 col-sm-10">
	      <button type="submit" class="btn btn-primary" id="do_add">{{Lang::get('admin_texts.add')}}</button>
	    </div>
	</div>
</form>
<table class="table table-striped" id="list-fees">
	<tr>	 	
	 	<th>{{Lang::get('admin_texts.method_name')}}</th>
	 	<th>{{Lang::get('admin_texts.method_fee')}} (%)</th>	 	
	 	<th>{{Lang::get('admin_texts.method_min_value')}}</th>
	 	<th>{{Lang::get('admin_texts.method_description')}}</th>	 	
	 	<th>{{Lang::get('admin_texts.method_min_fee')}}</th>	 	
	 	<th>Action</th>	 	
	</tr> 	
	@foreach($MethodDepositCurrency as $method)
	<tr><td>{{$method->dname}}</td><td>{{$method->dfee}}</td><td>{{$method->dmin}}</td><td>{{$method->ddes}}</td><td>{{$method->dminfee}}</td><td><a href="{{URL::to('admin/edit-method-deposit')}}/{{$method->id}}" class="edit_wallet">{{trans('admin_texts.edit')}}</a>  | <a href="javascript:void()" onclick="deleteMethodDeposit({{$method->id}})" class="delete_wallet">{{trans('admin_texts.delete')}}</a></td></tr>
	@endforeach	
</table>
<div id="pager"></div>
<div id="messageModal" class="modal hide fade" tabindex="-1" role="dialog">		
	<div class="modal-body">		
	</div>
	<div class="modal-footer">
		<button class="btn close-popup" data-dismiss="modal">{{{ trans('texts.close')}}}</button>
	</div>
</div>
{{ HTML::script('assets/js/jquery.validate.min.js') }}
<script type="text/javascript">
function deleteMethodDeposit(wallet_id){
	$.post('<?php echo action('Admin_SettingController@deleteMethodDeposit')?>', {isAjax: 1, wallet_id: wallet_id }, function(response){
       	var obj = $.parseJSON(response);
	    console.log('obj: ',obj);
	    if(obj.status == 'success'){
            $('#messageModal .modal-body').html('<p style="color:#008B5D; font-weight:bold;text-align:center;">'+obj.message+'</p>');            
            $('#messageModal').on('hidden.bs.modal', function (e) {              
              location.reload();
            });
        }else{
            $('#messageModal .modal-body').html('<p style="color:red; font-weight:bold;text-align:center;">'+obj.message+'</p>');
        }
        $('#messageModal').modal({show:true, keyboard:false}); 
    });
    return false;
}

    $(document).ready(function() {
    	$('#add_new_wallet').hide();
        $('#add_wallet_link').click(function(event) {
        	$('#add_new_wallet').toggle("slow");
        });
        $("#add_new_wallet").validate({
            rules: {
                dname: "required",             
                dfee: {
                    required: true,
                    number: true
                },
                dmin: {
                    required: true,
                    number: true
                },
                dminfee: {
                    required: true,
                    number: true
                }
            },
            messages: {
                dname: "Please provide a name of method.",
                dfee: {
                    required: "Please provide a fee.",
                    number: "Fee must be a number."
                },              
                dmin: {
                    required: "Please provide a minimum value.",
                    number: "Please enter a number."
                },  
                dminfee: {
                    required: "Please provide a minimum fee.",
                    number: "Please enter a number."
                },  
            }
	});

   });
</script>
<script type='text/javascript'>
    var options = {
        currentPage: <?php echo $cur_page ?>,
        totalPages: <?php echo $total_pages ?>,
        alignment:'right',
        pageUrl: function(type, page, current){
        	return "<?php echo URL::to('admin/manage/method-deposit'); ?>"+'/'+page; 
        }
    }
    $('#pager').bootstrapPaginator(options);
</script>
@stop