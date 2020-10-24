<?php $__env->startSection('content'); ?>
<section id="middle">
	
	<div class="site-login change-pwd">
	<div class="login-inner-box">
	
	<div class="user-create">	
		<?php if(\Session::has('success')): ?>
			<div class="alert alert-success">
			<?php echo e(\Session::get('success')); ?>

			</div>
		<?php endif; ?>
	</div>
	<div id="error-data"> 
	<?php if(\Session::has('error')): ?>
			<div class="alert alert-danger">
			<?php echo e(\Session::get('error')); ?>

			</div>
		<?php endif; ?>
		<?php if($errors->any()): ?>
		<div class="alert alert-danger">
            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php echo e($error); ?> <br/>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
		</div>
		<?php endif; ?>
	</div>
        <h1>Change Password</h1>
        <div class="login-fields">
			<form id="w0" action="/reset-password" method="post">
			<?php echo e(csrf_field()); ?>

				<div class="animated form-group field-admin-oldpassword required">
						<label class='control-label bmd-label-static' for='email-addr'><strong class='mandatory'>*</strong> Old Password</label><input type="password" id="current-password" class="form-control" name="current-password" aria-required="true">
						<div class='error invite-via-email-response-error' style='width:100%'><p class="help-block help-block-error"></p></div>
				</div>	

				<div class="animated form-group field-admin-newpassword required">
					<label class='control-label bmd-label-static' for='email-addr'><strong class='mandatory'>*</strong> New Password</label><input type="password" id="new-password" class="form-control" name="new-password" aria-required="true">
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
<?php $__env->stopSection(); ?>



<?php echo $__env->make('layouts.common-layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ISPCalling/resources/views/reset-password.blade.php ENDPATH**/ ?>