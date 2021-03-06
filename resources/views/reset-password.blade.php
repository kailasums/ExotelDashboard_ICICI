@extends('layouts.common-layout')

@section('content')
<section id="middle">
	
	<div class="site-login change-pwd">
	<div class="login-inner-box">
	
	<div class="user-create">	
		@if (\Session::has('success'))
			<div class="alert alert-success">
			{{ \Session::get('success') }}
			</div>
		@endif
	</div>
	<div id="error-data"> 
	@if (\Session::has('error'))
			<div class="alert alert-danger">
			{{ \Session::get('error') }}
			</div>
		@endif
		@if ($errors->any())
		<div class="alert alert-danger">
            @foreach ($errors->all() as $error)
                {{$error}} <br/>
            @endforeach
		</div>
		@endif
	</div>
        <h1>Change Password</h1>
        <div class="login-fields">
			<form id="w0" action="/reset-password" method="post">
			{{ csrf_field() }}
				<div class="animated form-group field-admin-oldpassword required">
						<label class='control-label bmd-label-static' for='email-addr'><strong class='mandatory'>*</strong> Old Password</label><input type="password" id="current-password" class="form-control" name="current-password" aria-required="true">
						<div class='error invite-via-email-response-error' style='width:100%'><p class="help-block help-block-error"></p></div>
				</div>	

				<div class="animated form-group field-admin-newpassword required">
					<label class='control-label bmd-label-static' for='email-addr'><strong class='mandatory'>*</strong> New Password</label><input type="password" id="new-password" class="form-control" name="new-password" aria-required="true"> <!-- <a href="#" data-toggle="tooltip" data-placement="top" title="The password should be 8-15 characters long, must contain 1 number, 1 capital letter,1 special character"><i class="fa fa-info" aria-hidden="true"></i></a> --> 
					<div class='error invite-via-email-response-error' style='width:100%'><p class="help-block help-block-error"></p></div>
				</div>

				<div class="animated form-group field-admin-confirmpassword required">
					<label class='control-label bmd-label-static' for='email-addr'><strong class='mandatory'>*</strong> Confirm New Password</label><input type="password" id="new-password-confirm" class="form-control" name="new-password-confirm" aria-required="true">
					<div class='error invite-via-email-response-error' style='width:100%'><p class="help-block help-block-error"></p></div>
				</div>		
			
				<div class="form-group btn-group text-center">
					<button type="submit" class="btn">Submit</button> 
				</div>
		
			</form>        
		</div>
    </div>
</div>




</div>
    </section>
@endsection

<script>
$(document).ready(function(){
  $('[data-toggle="tooltip"]').tooltip();
});
</script>

