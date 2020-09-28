<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">


    <title>Login</title>
    <!--<link rel="shortcut icon" href="/images/favicon.png" type="image/ico"/>-->


    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom.css') }}" rel="stylesheet">
    <link href="{{ asset('css/main.css') }}" rel="stylesheet">
</head>

<body>
    <div class="wrap">


        <section id="middle">
            <div class="site-login">
                <div class="login-inner-box">
                    <div class="login-screen-logo text-center">
                        <img src="{{URL::asset('images/logo.png')}}" />
                    </div>
                    <h1>Login</h1>
                    <div class="login-fields">
                        <form method="POST" action="{{ route('admin-login') }}">
                            @csrf
                            <div class="animated form-group field-loginform-email required">
                                <label class='control-label bmd-label-static' for='email-addr'><strong
                                        class='mandatory'>*</strong> Email</label><input type="text"
                                    id="loginform-email" class="form-control @error('email') is-invalid @enderror" name="email" autofocus
                                    aria-required="true">
                                <div class='error invite-via-email-response-error' style='width:100%'>
                                    @error('email')
                                    <p class="help-block help-block-error">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="animated form-group field-loginform-password required">
                                <label class='control-label bmd-label-static' for='email-addr'><strong
                                        class='mandatory'>*</strong> Password</label><input type="password"
                                    id="loginform-password" class="form-control @error('password') is-invalid @enderror" name="password" value=""
                                    aria-required="true">
                                <div class='error invite-via-email-response-error' style='width:100%'>
                                    @error('password')
                                    <p class="help-block help-block-error">{{$message}}</p>
                                    @enderror
                                </div>
                            </div>
                            <div class="remember-me form-group field-loginform-rememberme">
                                <div class="checkbox">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                                    <p class="help-block help-block-error"></p>

                                </div>
                            </div>
                            <div class="form-group btn-group text-center">
                                <button type="submit" class="btn" name="login-button">Login</button> </div>
                                @if (Route::has('password.request'))
                                    <a class="btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif

                        </form>
                    </div>
                </div>
            </div>

            <!-- <p class="text-center">© 2020 Tata Sky. All Rights Reserved.</p> -->
        </section>
    </div>

    <script src="{{URL::asset('js/jquery.js')}}"></script>
    <script src="{{URL::asset('js/inline-script.js')}}"></script>
    <script src="{{URL::asset('js/tether.min.js')}}"></script>
    <script src="{{URL::asset('js/bootstrap-material-design.iife.min.js')}}"></script>
    <script src="{{URL::asset('js/jquery.slimscroll.js')}}"></script>
</body>

</html>
