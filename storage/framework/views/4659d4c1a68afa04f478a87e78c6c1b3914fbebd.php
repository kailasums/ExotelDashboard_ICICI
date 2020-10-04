<?php $__env->startSection('content'); ?>
    <section id="middle">
        <div class="site-login">
            <div class="login-inner-box">
                <div class="login-screen-logo text-center">
                    <img src="<?php echo e(URL::asset('images/logo.png')); ?>" />
                </div>
                <h1>Admin Login</h1>
                <div class="login-fields">
                    <form method="POST" action="<?php echo e(route('admin-login')); ?>">
                        <?php echo csrf_field(); ?>
                        <div class="animated form-group field-loginform-email required">
                            <label class='control-label bmd-label-static' for='email-addr'><strong
                                    class='mandatory'>*</strong> Email</label><input type="text"
                                id="loginform-email" class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="email" autofocus
                                aria-required="true">
                            <div class='error invite-via-email-response-error' style='width:100%'>
                                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="help-block help-block-error"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="animated form-group field-loginform-password required">
                            <label class='control-label bmd-label-static' for='email-addr'><strong
                                    class='mandatory'>*</strong> Password</label><input type="password"
                                id="loginform-password" class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" name="password" value=""
                                aria-required="true">
                            <div class='error invite-via-email-response-error' style='width:100%'>
                                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <p class="help-block help-block-error"><?php echo e($message); ?></p>
                                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            </div>
                        </div>
                        <div class="remember-me form-group field-loginform-rememberme">
                            <div class="checkbox">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>

                            <label class="form-check-label" for="remember">
                                <?php echo e(__('Remember Me')); ?>

                            </label>
                                <p class="help-block help-block-error"></p>

                            </div>
                        </div>
                        <div class="form-group btn-group text-center">
                            <button type="submit" class="btn" name="login-button">Login</button> </div>
                            <!-- <?php if(Route::has('password.request')): ?>
                                <a class="btn-link" href="<?php echo e(route('password.request')); ?>">
                                    <?php echo e(__('Forgot Your Password?')); ?>

                                </a>
                            <?php endif; ?> -->

                    </form>
                </div>
            </div>
        </div>

        <!-- <p class="text-center">© 2020 Tata Sky. All Rights Reserved.</p> -->
    </section>
    <?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.common-auth', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/html/ISPCalling/resources/views/auth/admin-login.blade.php ENDPATH**/ ?>