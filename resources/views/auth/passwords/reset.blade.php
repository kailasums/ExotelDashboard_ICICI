<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Forget Password</title>
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
                    <h1>{{__('Reset Password') }}
                        <div class="login-fields">
                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        <input type="hidden" name="token" value="{{ $token }}">

                        <div class="animated form-group field-loginform-email required">
                            <label for="email" class="control-label bmd-label-static">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ $email ?? old('email') }}" required autocomplete="email" autofocus>
                                <div class='error invite-via-email-response-error' style='width:100%'>
                                    @error('email')
                                        <p class="help-block help-block-error">{{$message}}</p>
                                    @enderror
                                </div>
                        </div>

                        <div class="animated form-group field-loginform-password required">
                                    <label class='control-label bmd-label-static' for='email-addr'><strong class='mandatory'>*</strong> Password</label><input type="password" id="loginform-password" class="form-control @error('password') is-invalid @enderror" name="password" value="" aria-required="true">
                                    <div class='error invite-via-email-response-error' style='width:100%'>
                                        @error('password')
                                        <p class="help-block help-block-error">{{$message}}</p>
                                        @enderror
                                    </div>
                        </div>

                        <div class="animated form-group field-loginform-password required">
                                    <label class='control-label bmd-label-static' for='confirm-password'><strong class='mandatory'>*</strong> {{ __('Confirm Password') }}</label><input type="password" id="confirm-password" class="form-control" name="password_confirmation" required autocomplete="new-password" aria-required="true">
                                    <div class='error invite-via-email-response-error' style='width:100%'>
                                        @error('password')
                                        <p class="help-block help-block-error">{{$message}}</p>
                                        @enderror
                                    </div>
                                </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Reset Password') }}
                                </button>
                            </div>
                        </div>
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
